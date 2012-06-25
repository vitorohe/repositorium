<?php
/**
 * 
 * Repositories Administration
 * @author mquezada
 *
 */

class AdminRepositoriesController extends AppController {
	var $name = 'AdminRepositories';
	var $uses = array('Repository', 'RepositoriesUser', 'User', 'RepositoryRestriction');
	var $paginate = array(
		'Repository' => array(
		  'limit' => 30,
		  'recursive' => -1,
		  'order' => array('Repository.created' => 'desc')
		),
		'User' => array(
			'limit' => 30,
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
	
	/*Perform permission comprobation for admin*/
	function beforeFilter() {
		if(!$this->isAdmin()) {
			$this->Session->setFlash('You don\'t have permission to access this page');
			$this->redirect('/');
		}		
	}
	
	/*Lists all repositories*/
	function index() {
		$current = 'repositories';
		$menu = 'menu_admin';
		$this->data = $this->paginate();
		$this->set(compact('current', 'menu'));
	}
	
	/*Action for the edition of a repository*/
	function edit($id = null) {
		if(empty($this->data)) {
			if(is_null($id)) 
				$this->redirect('index');
			$repo = $this->Repository->read(null, $id);
			
			if(empty($repo))
				$this->e404();

			$repo_restrictions = $this->RepositoryRestriction->find('first', array(
  	  				'conditions' => array(
  	  					'RepositoryRestriction.repository_id' => $id, 
  	  					), 
  	   				'recursive' => -1));
			
			if($repo_restrictions['RepositoryRestriction']['activation_id'] == 'N')
				$restrictions = array();
			else
				$restrictions = $repo_restrictions;

			$this->Session->write('restrictions', $repo_restrictions);
			$this->Session->write('repo', $repo);

			$this->data = $repo;
			$current = 'repositories';
			$menu = 'menu_admin';
			$this->set(compact('current', 'menu', 'restrictions'));
		} else {
			$this->Repository->set($this->data);

			$repo_restrictions = $this->Session->read('restrictions');
			$repo = $this->Session->read('repo');

			$new_restrictions = array();
		
			if(isset($this->data['Repository']['restrictions'])) {
				if(empty($this->data['Repository']['max_documents']) && 
					empty($this->data['Repository']['max_size']) && 			
					empty($this->data['Repository']['max_extension'])) {
					$this->Session->setFlash("You must set at least one option: max documents, max size, extension");
					$this->redirect($this->referer());
				}

				if(!empty($this->data['Repository']['max_documents'])) {
					$new_restrictions['amount'] = $this->data['Repository']['max_documents'];
					if(!is_numeric($new_restrictions['amount'])) {
						$this->Session->setFlash("Maximum of documents must be a number", flash_errors);
						$this->redirect($this->referer());		
					}
				} else {
					$new_restrictions['amount'] = 0;
				}
				if(!empty($this->data['Repository']['max_size'])) {
					$new_restrictions['size'] = $this->data['Repository']['max_size'];
					if(!is_numeric($new_restrictions['size'])) {
						$this->Session->setFlash("Maximum size must be a number", flash_errors);
						$this->redirect($this->referer());		
					}
				} else {
					$new_restrictions['size'] = 0;
				}
				if(!empty($this->data['Repository']['extension'])) {
					$new_restrictions['extension'] = $this->data['Repository']['extension'];
					$new_restrictions['extension'] = trim($new_restrictions['extension']);

				} else {
					$new_restrictions['extension'] = "*";
				}
				
			}
			
			if(!$this->Repository->validates()) {
				$this->Session->setFlash($this->Repository->validationErrors, 'flash_errors');
			} elseif(!$this->Repository->save()) {
				$this->Session->setFlash('An error ocurred saving the repository. Please, blame the developer', 'flash_errors');
			} else {

				if(!empty($new_restrictions)){

					if(empty($repo_restrictions)){
						if(!$this->RepositoryRestriction->saveRestriction($new_restrictions, $repo['Repository']['user_id'], $repo['Repository']['id'])) {
							$this->Session->setFlash('An error occurred creating the repository. Please, blame the developer', 'flash_errors');
							$this->redirect('/admin_repositories/');
						} 
					} else {
						$repo_restrictions['RepositoryRestriction']['activation_id'] = 'A';
						$repo_restrictions['RepositoryRestriction']['amount'] = $new_restrictions['amount'];
						$repo_restrictions['RepositoryRestriction']['size'] = $new_restrictions['size'];;
						$repo_restrictions['RepositoryRestriction']['extension'] = $new_restrictions['extension'];

						$this->RepositoryRestriction->save($repo_restrictions);								
					}
	
				} else {

					if(!empty($repo_restrictions)){

						$repo_restrictions['RepositoryRestriction']['activation_id'] = 'N';

						$this->RepositoryRestriction->save($repo_restrictions);		
					}

				}

				$this->Session->setFlash('Repository saved', 'flash_green');
				CakeLog::write('activity', 'Repository [id='.$id.'] edited');
				$this->redirect('index');
			}
		}
	}
	
	/*Removes a repository, given its id */
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
	
	/*List collaboratos of a repository*/ 
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