<?php
/**
 * 
 * This code NEEEEDS to be refactored (someday)
 * @author mquezada
 *
 */

class RepositoriesController extends AppController {
	/**
	 * SessionComponent
	 * @var SessionComponent
	 */
	var $Session;
	
	/**
	 * Repository Model
	 * @var Repository
	 */
	var $Repository;
	
	/**
	 * 
	 * RepositoriesUser Model
	 * @var RepositoriesUser
	 */
	var $RepositoriesUser;
	
	var $name = 'Repositories';
	
	var $uses = array('Repository', 'RepositoriesUser', 'User', 'Document', 'Criteria', 'RepositoryRestriction');
	
	/*@rmeruane*/
	private function make_seed()
	{
	  list($usec, $sec) = explode(' ', microtime());
	  return (float) $sec + ((float) $usec * 100000);
	}
	private function color_aleatorio(){
		mt_srand($this->make_seed());
		$retorno = '';
		for($i=0; $i<3; $i++)
			$retorno .= sprintf("%02X", mt_rand(100, 180));
		return $retorno;
	}
	
	function index($repo_url = null) {	
		if(is_null($repo_url)) {
			$this->redirect('/');
		}
		$this->Session->delete('Experto');
		
		$result = $this->_set_repo($data = compact('repo_url'));
		if($result[0]) {		
			$repository = $result[1];
			$watching = null;
			
			// watching repo
			if($this->isLoggedIn()) {
				$user = $this->getConnectedUser();
				$r = $this->RepositoriesUser->find('first', array(
					'conditions' => array(
						'RepositoriesUser.repository_id' => $repository['Repository']['id'],
						'RepositoriesUser.user_id' => $user['User']['id'],
						'RepositoriesUser.user_type_id <>' => 1,
						'RepositoriesUser.activation_id' => 'A'),
	 					'recursive' => -1 
				));				
				
				$watching = $r;
			}
			
			// stats
			$creator = $this->User->find('first', array(
				'conditions' => array(
					'User.id' => $repository['Repository']['user_id']
				),
				'fields' => array('User.id', 'User.name'),
				'recursive' => -1,
			));
			
			$documents = $this->Document->find('count', array(
				'conditions' => array(
					'Document.repository_id' => $repository['Repository']['id']
				),
				'recursive' => -1,
			));
			
			$cloud_data = "<tags>";
			$tag_factor_crecimiento = 1.5;
			$tag_min_size = 10; //tamano minimo
			$tag_max_size = 40; //tamano maximo

		  	$options['joins'] = array(
		  			array('table' => 'criterias_documents',
		  					'alias' => 'CriteriasDocument',
		  					'type' => 'inner',
		  					'conditions' => array(
		  							'CriteriasDocument.criteria_id = Criteria.id')
		  			),
		  			array('table' => 'documents',
		  					'alias' => 'Document',
		  					'type' => 'inner',
		  					'conditions' => array(
		  							'CriteriasDocument.document_id = Document.id'
		  					)
		  			),
		  			array('table' => 'repositories',
		  					'alias' => 'Repository',
		  					'type' => 'inner',
		  					'conditions' => array(
		  							'Repository.id = Document.repository_id')
		  			)
		  	);
		  	$options['conditions'] = array(
		  			'Repository.id' => $repository['Repository']['id']);
		  
		  	$options['fields'] = array(
		  			'DISTINCT Criteria.id', 'Criteria.name');
		  
		  	$criterias = $this->Criteria->find('all', $options);

			$criteria_size = 30;
			foreach($criterias as $criteria){
				$cloud_data .= "<a href='' style='".$criteria_size."' color='0x".$this->color_aleatorio()."' hicolor='0x".$this->color_aleatorio()."'>".$criteria['Criteria']['name']."</a>";
			}

			$cloud_data .= "</tags>";

			if(isset($user)) {
				$criteriasuser = $this->Criteria->findCriteriasUserinRepo($user, $repository);
			}
			
			if(!empty($criteriasuser) && isset($criteriasuser)) {
				$this->Session->write('Experto.isExperto', true);	
			}			
			
			$this->set(compact('repository','watching', 'user', 'creator', 'documents', 'cloud_data'));
		} else {
			$this->e404();
		}		
	}
	
	/**
	 * 
	 * set repository data in session, by id or url, if exists
	 * @param array $data
	 * @return array array( boolean: status, array: data | null)
	 */
	function _set_repo($data = array()) {
		if(empty($data)) {
			return array(false, null);
		}
		
		if(isset($data['repo_url'])) {
			$repo = $this->Repository->find('first', array(
				'conditions' => array(
					'Repository.internal_name' => $data['repo_url']
				),
				'recursive' => -1,
			));
		} elseif(isset($data['repo_id'])) {
			$repo = $this->Repository->find('first', array(
				'conditions' => array(
					'Repository.id' => $data['repo_id']
				),
				'recursive' => -1,
			));
		}
		
		if(!empty($repo)) {
			$this->setRepositorySession($repo);			
			return array(true, $repo);
		} else {
			return array(false, null);
		}
					
	}
	
	function set_repository_by_url($repo_url = null) {
		if(is_null($repo_url)) {		
			$this->e404();
		}
		
		$result = $this->_set_repo($data = compact('repo_url'));		
		if($result[0]) {
			$this->redirect(array('action' => 'index', $result[1]['Repository']['internal_name']));
		} else {
			$this->e404();
		}		
	}
	
	function set_repository_by_id($repo_id = null) {
		if(is_null($repo_id)) {
			$this->e404();
		}
		$result = $this->_set_repo(compact('repo_id'));		
		if($result[0]) {
			$this->redirect(array('action' => 'index', $result[1]['Repository']['internal_name']));
		} else {
			$this->e404();
		}		
	}
	
	function create() {
		
		if($this->getConnectedUser() == $this->anonymous)
			$this->redirect(array('controller' => 'login'));
		
		if(!empty($this->data)) {
			$user = $this->getConnectedUser();
			$this->data['Repository']['user_id'] = $user['User']['id'];
			
			// restrictions

			$restrictions = array();

			if(isset($this->data['Repository']['restrictions'])) {
				if(empty($this->data['Repository']['max_documents']) && 
					empty($this->data['Repository']['max_size']) && 			
					empty($this->data['Repository']['max_extension'])) {
					$this->Session->setFlash("You must set at least one option: max documents, max size, extension");
					$this->redirect($this->referer());
				}

				if(!empty($this->data['Repository']['max_documents'])) {
					$restrictions['amount'] = $this->data['Repository']['max_documents'];
					if(!is_numeric($restrictions['amount'])) {
						$this->Session->setFlash("Maximum of documents must be a number", flash_errors);
						$this->redirect($this->referer());		
					}
				} else {
					$restrictions['amount'] = 0;
				}
				if(!empty($this->data['Repository']['max_size'])) {
					$restrictions['size'] = $this->data['Repository']['max_size'];
					if(!is_numeric($restrictions['size'])) {
						$this->Session->setFlash("Maximum size must be a number", flash_errors);
						$this->redirect($this->referer());		
					}
				} else {
					$restrictions['size'] = 0;
				}
				if(!empty($this->data['Repository']['extension'])) {
					$restrictions['extension'] = $this->data['Repository']['extension'];
					$restrictions['extension'] = trim($restrictions['extension']);

				} else {
					$restrictions['extension'] = "*";
				}
				
			}

			
			$this->data['Repository']['activation_id'] = 'A';
			$this->data['Repository']['internalstate_id'] = 'A';
			
			$this->Repository->set($this->data);
			
			if($this->Repository->validates()) {
				$repository = $this->Repository->createNewRepository($this->data, $user);
				CakeLog::write('activity', "Repository [name=\"{$repository['Repository']['name']}\"] created");
				if(is_null($repository)) {
					$this->Session->setFlash('An error occurred creating the repository. Please, blame the developer');
					$this->redirect('/');
				}

				if(!empty($restrictions)){
					if(!$this->RepositoryRestriction->saveRestriction($restrictions, $user['User']['id'], $repository['Repository']['id'])) {
						$this->Session->setFlash('An error occurred creating the repository. Please, blame the developer');
						$this->redirect('/');
					}
				}

				if(Configure::read('App.subdomains')) {
					$dom = Configure::read('App.domain');
					$this->redirect("http://{$repository['Repository']['internal_name']}.{$dom}");
				} else {
					$this->redirect(array('action' => 'index', $repository['Repository']['internal_name']));
				}

			} else {
				$this->Session->setFlash($this->Repository->invalidFields(), 'flash_errors');
			}	
		}

	}

	
	function join($id = null) {
		if(is_null($id)) {
			$this->Session->setFlash('Repository not found');
			$this->redirect('/');
		}
		if(!$this->isLoggedIn()) {
			$this->redirect(array('controller' => 'login', 'action' => 'index'));
		}
		
		$user = $this->getConnectedUser(); 
		$repo = $this->RepositoriesUser->find('first', array('conditions' => array('user_id' => $user['User']['id'], 'repository_id' => $id), 'recursive' => -1));
		
		/*if(empty($repo)) {
			$this->Session->setFlash('Repository not found');
			$this->redirect('/');
		}*/
		
		$watching = false;

		if(empty($repo)) {

			if(!$this->RepositoriesUser->saveRepositoryUser($user['User']['id'], $id)) {
				$this->Session->setFlash('Joining failed','flash_errors');
				$this->redirect('/');
			}

		}
		elseif ($repo['RepositoriesUser']['activation_id'] === 'A') {
			$repo['RepositoriesUser']['activation_id'] = 'N';

			$this->create();
			$this->RepositoriesUser->save($repo);				
			$watching = true;
		}
		else {
			$repo['RepositoriesUser']['activation_id'] = 'A';

			$this->create();
			$this->RepositoriesUser->save($repo);
		}


//		$watching = $repo['RepositoriesUser']['watching'];
		
//		$this->RepositoriesUser->read(null, $repo['RepositoriesUser']['id']);
//		$this->RepositoriesUser->set('watching', !$watching);
//		$this->RepositoriesUser->save();
		
		if($watching) $msg = "You have removed this repository from your watchlist";
		else $msg = "You have added this repository to your watchlist";
		
		$this->Session->setFlash($msg);
		$this->redirect(array('action' => 'set_repository_by_id', $id));
	}

	function _make_user_expert() {
		$this->Session->write('User.esExperto', true);
	}
}