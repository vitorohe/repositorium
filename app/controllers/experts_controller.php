<?php
/**
 * 
 * Collaborators controller
 * Used by repositories owners and other collaborators
 * Uses only admin_usuarios views
 * @author mquezada
 *
 */
class ExpertsController extends AppController {
	var $name = 'Experts';
	
	var $paginate = array(
		'Expert' => array(
			'limit' => '15',
			'conditions' => array('Expert.user_id <>' => 1),
			'order' => array('Expert.created' => 'desc'),
		)
	);
	
	function beforeFilter() {
		if(!$this->isExpert()) {
			$this->Session->setFlash('You don\'t have permission to access this page');
			$this->redirect('/');
		}
	}
	
	function index() {
		$repo = $this->requireRepository();
		$this->paginate['Expert']['conditions'] = array(
			'Expert.repository_id' => $repo['Repository']['id']
		);
		$this->data = $this->paginate('Expert');
		
		$params = array(
			'current' => 'experts',
			'title' => 'Repository Collaborators',
			'menu' => 'menu_expert',
			'repo' => $repo,
			'cond' => 'owner',
			'footnotes' => array('Repository owner'),
			'add_button' => false,
			'collaborator_button' => true,
		); 
		
		$this->set($params);
		$this->render('../admin_usuarios/listar');
	}
}