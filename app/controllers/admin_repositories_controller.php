<?php
/**
 * 
 * Repositories Administration
 * @author mquezada
 *
 */

class AdminRepositoriesController extends AppController {
	var $name = 'AdminRepositories';
	var $uses = array('Repository', 'RepositoriesUser', 'User');
	var $paginate = array(
		'Repository' => array(
		  'limit' => 5,
		  'recursive' => -1,
		  'order' => array('Repository.created' => 'desc')
		),
		'User' => array(
			'limit' => 5,
			'recursive' => -1,
			'order' => array('User.id' => 'asc')
		)
	);
	
	/**
	 * SessionComponent
	 * @var SessionComponent
	 */
	var $Session;
	
	/**
	 * 
	 * Repository Model
	 * @var Repository
	 */
	var $Repository;
	
	var $helpers = array('Text', 'Repo');
	
	function beforeFilter() {
		if(!$this->isAdmin()) {
			$this->Session->setFlash('You don\'t have permission to access this page');
			$this->redirect('/');
		}		
	}
	
	function index() {
		$current = 'repositories';
		$menu = 'menu_admin';
		$this->data = $this->paginate();
		$this->set(compact('current', 'menu'));
	}
	
	function edit($id = null) {
		if(empty($this->data)) {
			if(is_null($id)) 
				$this->redirect('index');
			$repo = $this->Repository->read(null, $id);
			
			if(empty($repo))
				$this->e404();
			
			$this->data = $repo;
			$current = 'repositories';
			$menu = 'menu_admin';
			$this->set(compact('current', 'menu'));
		} else {
			$this->Repository->set($this->data);
			if(!$this->Repository->validates()) {
				$this->Session->setFlash($this->Repository->validationErrors, 'flash_errors');
			} elseif(!$this->Repository->save()) {
				$this->Session->setFlash('An error ocurred saving the repository. Please, blame the developer', 'flash_errors');
			} else {
				$this->Session->setFlash('Repository saved', 'flash_green');
				CakeLog::write('activity', 'Repository [id='.$id.'] edited');
				$this->redirect('index');
			}
		}
	}
	
	function remove($id = null) {
		if(is_null($id))
			$this->e404();
		
		if($this->Repository->delete($id)) {
			$this->Session->setFlash('Repository deleted successfuly', 'flash_green');
			CakeLog::write('activity', 'Repository [id='.$id.'] deleted');
		} else {
			$this->Session->setFlash('An error ocurred deleting the repository', 'flash_errors');
		}
		
		if(Configure::read('App.subdomains')) {
			$dom = Configure::read('App.domain');
			$this->redirect("http://www.{$dom}/admin_repositories");
		} else {
			$this->redirect($this->referer());		
		}
		
	}
	
	function users($id = null) {
		if(is_null($id))
			$this->e404();
		
		$this->paginate['User']['joins'] = array(
				array('table' => 'repositories_users',
						'alias' => 'RepositoriesUser',
						'type' => 'inner',
						'conditions' => array(
								'User.id = RepositoriesUser.user_id')
						)
		);
		
		$this->paginate['User']['conditions'] = array('RepositoriesUser.repository_id' => $id, 'RepositoriesUser.activation_id' => 'A');
		$this->paginate['User']['fields'] = array('User.id', 'User.email', 'User.name', 'User.username', 'RepositoriesUser.user_type_id', 'RepositoriesUser.activation_id');
		
		$this->data = $this->paginate('User');
		

		$repo = $this->Repository->find('first', array('conditions' => compact('id'), 'recursive' => -1));
		$data = array(
			'current' => 'repositories',
			'repo' => $repo,
			'menu' => 'menu_admin',
			'title' => "Collaborators of '{$repo['Repository']['name']}' Repository",
			'cond' => 'owner',
			'footnotes' => array('Repository administrator'), 
		);
		
		$this->set($data);
		
		$this->render('../admin_usuarios/listar');		
	}
}