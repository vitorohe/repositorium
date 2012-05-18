<?php
App::import('Sanitize');
class CriteriasController extends AppController {

  var $uses = array('Document', 'Criteria', 'CriteriasDocument', 'Attachfile', 'CriteriasUser');
  var $paginate = array(
	'Criteria' => array(
	  'limit' => 5,
	  'order' => array(
		'Criteria.question' => 'desc'
	  )
	)
  );
  

  
//   var $helpers = array('Session', 'Form');

  /*function beforeFilter() {
	if(!$this->isExpert()) {	  
	  	$this->Session->setFlash('You do not have permission to access this page');
	  	$this->redirect('/');		
	}
	
	$this->requireRepository();
	
	if($this->Session->check('Criteria.limit'))
		$this->paginate['Criteria']['limit'] = $this->Session->read('Criteria.limit'); 
	if(!isset($this->paginate['Criteria']['conditions'])) {
		$repo = $this->requireRepository();
				
		$this->paginate['Criteria']['conditions'] = array(
			'Criteria.repository_id' => $repo['Repository']['id']
		);
	}
  }*/

  function index() {
  	if(!empty($this->data)) {  		
  		if(!empty($this->data['Criteria']['limit'])) {
  			$this->paginate['Criteria']['limit'] = $this->data['Criteria']['limit'];
  			$this->Session->write('Criteria.limit', $this->data['Criteria']['limit']);
  		} 
  	} 

  	$this->data = $this->paginate();
  	$params = array(
  		'limit' => $this->Session->read('Criteria.limit') ? $this->Session->read('Criteria.limit') : $this->paginate['Criteria']['limit'],
  		'repo' => $this->requireRepository(),
  		'menu' => 'menu_expert',
  		'current' => 'criteria',
  		'title' => 'Criteria'
  	); 
  
  	$this->set($params);	
  }
  
  function search() {
  	$repo = $this->requireRepository();
  
  	$criterias = $this->Criteria->findRepoCriterias($repo['Repository']['id']);
  
  	$this->set(compact('criterias'));
  }

  function add() {
  	$params = array(
  	  	'repo' => $this->requireRepository(),
  	  	'menu' => 'menu_expert',
  	  	'current' => 'criteria',
  	  	'title' => 'Add new criteria'
  	);
  	$this->set($params);
  	
	if(!empty($this->data)) {
	  $this->Criteria->set($this->data);	  
	  if($this->Criteria->validates()) {
	  	$repo = $this->getCurrentRepository();
	  	
	  	if(is_null($repo)) {
	  		$this->Session->setFlash('Please set a current repository first');
	  		$this->redirect('index');
	  	}
	  	
		$this->data['Criteria']['repository_id'] = $repo['Repository']['id'];
		
		if($this->Criteria->save($this->data)) {
			$this->Session->setFlash('Criteria added successfully');
			CakeLog::write('activity', 'Criteria "'.$this->data['Criteria']['question'].'" was added');
		} else {
			$this->Session->setFlash('An error occurred saving the criteria', 'flash_errors');
			CakeLog::write('error', 'Criteria "'.$this->data['Criteria']['question'].'" was not added');			
		}
		$this->redirect('index');
	  } else {
		$this->Session->setFlash($this->Criteria->invalidFields(),'flash_errors');
	  }
	}
  }

  function view($id = null) {  }


  function edit($id = null) {
  	$repo = $this->requireRepository();
  	$params = array(
  	  	'menu' => 'menu_expert',
  	  	'current' => 'criteria',
  	  	'title' => 'Edit criteria',
  	  	'repo' => $repo
  	);
  	$this->set($params);
  	
	$this->Criteria->id = $id;
	if (empty($this->data)) {
	  $this->data = $this->Criteria->read();
	  if($this->data['Criteria']['repository_id'] != $repo['Repository']['id']) {
	  	$this->Session->setFlash('This criteria does not correspond to current repository', 'flash_errors');
	  	$this->redirect('index');
	  }
	} else {
	  if ($this->Criteria->save($this->data)) {
		$this->Session->setFlash('Criteria '.$id.' was successfully modified');
		CakeLog::write('activity', 'Criteria '.$id.' was modified');
		$this->redirect(array('controller' => 'criterias', 'action' => 'index'));
	  }
	}
	$this->render('add');
  }

  function remove($id = null) {
  	if(!is_null($id)) {
  		if($this->Criteria->delete($id)) {
  			$this->Session->setFlash('Criteria '.$id.' removed');
  			CakeLog::write('activity', 'Criteria '.$id.' was removed');
  		} else {
  			$this->Session->setFlash('There was an error deleting that criteria you specified');
  		}
  	}
  	$this->redirect($this->referer());  	
  }
  
  function create(){
  	if($this->getConnectedUser() == $this->anonymous)
  		$this->redirect(array('controller' => 'login'));
  	
  	if(!empty($this->data)) {
  		$user = $this->getConnectedUser();

  		$cu = array('CriteriasUser' => array(
  					'successful_evaluation' => 0,
  					'negative_evaluation' => 0,
  					'score_obtained' => 0,
  					'activation_id' => 'A',
  					'internalstate_id' => 'A',
  					'user_id' => $user['User']['id'],
  					'quality_user_id' => 1));
  		
  		$this->data['Criteria']['user_id'] = $user['User']['id'];
  		$this->data['Criteria']['activation_id'] = 'A';
  		$this->data['Criteria']['internalstate_id'] = 'A';
  		/*$this->data['Criteria']['upload_score'] = 5;
  		$this->data['Criteria']['download_score'] = 10;
  		$this->data['Criteria']['collaboration_score'] = 5;*/
  		
  		
  			
  		$this->Criteria->set($this->data);
  			
  		if($this->Criteria->validates()) {
  			$criteria = $this->Criteria->createNewCriteria($this->data, $cu);
  			CakeLog::write('activity', "Criteria [name=\"{$criteria['Criteria']['name']}\"] created");
  			if(is_null($criteria)) {
  				$this->Session->setFlash('An error occurred creating the criteria. Please, blame the developer');
  				$this->redirect('/');
  			}
  			
  			$this->Session->setFlash('Criteria successfully created');
  			$this->redirect('/');
  	
  		} else {
  			$this->Session->setFlash($this->Criteria->invalidFields(), 'flash_errors');
  		}
  	}
  }

  function process(){
  	if(!empty($this->data) && isset($this->data['Criteria']) && isset($this->data['Criteria']['criterias']) && !empty($this->data['Criteria']['criterias'])){
	  	$data = $this->data;
	  	
      $repo = $this->requireRepository();

	  	$there_are_criterias = false;
	  	if(isset($data['Criteria']['criterias'])) {
	  		$there_are_criterias = true;
	  	}
	  	
	  	$criterias = explode('&', $data['Criteria']['criterias']);
	  	$criterias = array_map("trim", $criterias);
	  	unset($data['Criteria']['criterias']);
	  	
	  	$criteria_ids = array();
	  	foreach($criterias as $criteria) {
	  		$criteria_ids[] = substr($criteria, strpos($criteria, '=')+1);
	  	}

      $conditions['conditions'] = array('Document.repository_id' => $repo['Repository']['id']);

      $attached_files = $this->Attachfile->find('all',$conditions);
     
      $attached_document_ids = array();
      foreach($attached_files as $attachf) {
        $attachedf_document_ids[] = $attachf['Attachfile']['document_id'];
      }
	  	
	  	$options['joins'] = array(
	  			array('table' => 'documents',
	  					'alias' => 'Document',
	  					'type' => 'inner',
	  					'conditions' => array(
	  							'CriteriasDocument.document_id = Document.id'
	  					)
	  			)
	  	);
	  	if(!isset($attachedf_document_ids)){
        $options['conditions'] = array(
          'CriteriasDocument.criteria_id' => $criteria_ids,
          'Document.repository_id' => $repo['Repository']['id']);
      } else {
  	  	$options['conditions'] = array(
  	  		'CriteriasDocument.criteria_id' => $criteria_ids,
          'Document.repository_id' => $repo['Repository']['id'],
          array ("NOT" => array ('CriteriasDocument.document_id' => $attachedf_document_ids)));
  	  }

	  	$options['fields'] = array(
	  			'DISTINCT Document.id', 'Document.name', 'Document.description');
	  	
	  	$options['group'] = 'Document.id HAVING COUNT(CriteriasDocument.criteria_id) >='.count($criteria_ids);
	  	
	  	$options['recursive'] = -1;
	  	
	  	$documents = $this->CriteriasDocument->find('all', $options);

      /*options to find documents with files and theses criterias*/
      if(isset($attachedf_document_ids)) {
        $options['joins'] = array(
          array('table' => 'documents',
                'alias' => 'Document',
                'type' => 'inner',
                'conditions' => array(
                  'CriteriasDocument.document_id = Document.id'
                  )
              ),
          array('table' =>'attachfiles',
                'alias' => 'Attachfile',
                'type' => 'inner',
                'conditions' => array(
                  'Attachfile.document_id = Document.id'
                  )
              )
        );
      
        $options['conditions'] = array(
          'CriteriasDocument.criteria_id' => $criteria_ids, 
          'CriteriasDocument.document_id' => $attachedf_document_ids);
       
        $options['fields'] = array(
          'DISTINCT Document.id', 'Document.name', 'Document.description', 'Attachfile.name', 'Attachfile.location', 'Attachfile.extension');
       
        $document_with_files = $this->CriteriasDocument->find('all', $options);
      } else {
        $document_with_files = null;
      }
	  	
	  	$criterias_name = $this->Criteria->find('all', array('field' => array('Criteria.name'), 'conditions' => array('Criteria.id' => $criteria_ids)));
	  
	  	$this->set(compact('documents','document_with_files','criterias_name'));
  	}
  	else if(!empty($this->data) && isset($this->data['Criteria']) && isset($this->data['Criteria']['criterias']) && empty($this->data['Criteria']['criterias'])){
  		$this->Session->setFlash('You must select at least a criteria');
  		$this->redirect($this->referer());
  	}
  	else{
  		$this->Session->setFlash('Invalid Action', flash_errors);
  		$this->redirect($this->referer());
  	}
  }
}

?>