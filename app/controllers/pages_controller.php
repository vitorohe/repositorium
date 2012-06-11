<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @link http://book.cakephp.org/view/958/The-Pages-Controller
 */
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 * @access public
 */
	var $name = 'Pages';

/**
 * Default helper
 *
 * @var array
 * @access public
 */
	var $helpers = array('Html', 'Session');

/**
 * This controller does use a model
 *
 * @var array
 * @access public
 */
	var $uses = array('Repository', 'RepositoriesUser', 'CriteriasUser');
	
/**
 * @var string 
 * @access public
 */
	var $layout = 'non_repository_context';

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @access public
 */
	function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}

	/**
	 * 
	 * Home page
	 * @access public
	 */
	function home() {		
		if(!$this->isLoggedIn()) {
			$this->_anon();
		} else {
			$this->_user();
		}		
	}
	
	function _user() {

		$this->Session->delete('Repository');
		
		$user = $this->getConnectedUser();
		
		$yours = array(
			'Repository.user_id' => $user['User']['id']
		);
		
		$collaborator = array(
			'Expert.user_id' => $user['User']['id'],
			'NOT' => $yours
		);

		$watching = array(
			'RepositoriesUser.user_id' => $user['User']['id'],
			'RepositoriesUser.user_type_id <>' => 1,
			'RepositoriesUser.activation_id' => 'A'
			);
		
		$latest = array(
// 			'NOT' => $watched,
// 			'NOT' => $collaborator,
// 			'NOT' => $yours
		);

		$your_repos = $this->Repository->find('all', array(
			'conditions' => $yours,
			'recursive' => -1
		));

		$your_criterias = $this->CriteriasUser->find('all', array(
			'conditions' => array(
				'CriteriasUser.user_id' => $user['User']['id'],
				'CriteriasUser.quality_user_id' => 1
				)
		));

		if(!$this->Session->read('Experto.isExperto') && !empty($your_criterias))
			$this->Session->write('Experto.isExperto', true);	

		
		$this->RepositoriesUser->unbindModel(array('belongsTo' => array('User')));
		
		$watching_repos = $this->RepositoriesUser->find('all', array(
			'conditions' => $watching
			)
		);

		$this->Repository->unbindModel(array('belongsTo' => array('User')), array('hasMany' => array('Criteria', 'Document')));
		$latest_repos = $this->Repository->find('all', array(
			'conditions' => $latest,
			'order' => 'Repository.created desc'
		));
		
		$this->set(compact('your_repos', 'your_criterias','collaborator_repos', 'watching_repos', 'latest_repos'));
		$this->render('home_user');
	}
	
	function _anon() {
		$this->Repository->unbindModel(array('belongsTo' => array('User')), array('hasMany' => array('Criteria', 'Document')));
		$latest_repos = $this->Repository->find('all', array(
// 			'conditions' => $latest,
			'order' => 'Repository.created desc'
		));
		
		$this->set(compact('latest_repos'));
		$this->render('home_anon');
	}
}
