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

    $criterias_names = array();
    foreach ($criterias as $criteria) {
        $criterias_names[] = $criteria['Criteria']['name'];
    }

    $criterias_ids = array();
    foreach ($criterias as $criteria) {
        $criterias_ids[] = $criteria['Criteria']['id'];
    }

    $criterias_points = array();
    foreach ($criterias as $criteria) {
        $criterias_points[] = $criteria['Criteria']['download_score'];
    }

    $this->Session->write('criterias_names',$criterias_names);
    $this->Session->write('criterias_ids',$criterias_ids);
    $this->Session->write('criterias_points',$criterias_points);
    $this->Session->write('criterias_selected', array());

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
      $user = $this->getConnectedUser();

      $there_are_criterias = false;
      if(isset($data['Criteria']['criterias'])) {
        $there_are_criterias = true;
      }

      if(!empty($data['Payed_search']['documents_amount'])) {
        $documents_amount = $data['Payed_search']['documents_amount'];
      }
      else {
        $this->Session->setFlash('You must search at least 1 document.');
        $this->redirect('/criterias/search');
      }

      $criterias = explode('&', $data['Criteria']['criterias']);
      $criterias = array_map("trim", $criterias);
      unset($data['Criteria']['criterias']);

      $criteria_ids = array();
      foreach($criterias as $criteria) {
        $criteria_ids[] = substr($criteria, strpos($criteria, '=')+1);
      }
	
      
      $criterias_users = $this->CriteriasUser->find('all',
      		array('joins' => array(
      			    array('table' => 'criterias',
         		     	'alias' => 'Criteria',
          			    'type' => 'inner',
         	   			'conditions' => array(
                		 'CriteriasUser.criteria_id = Criteria.id'
              			)
            		)),      
      				'conditions' => array('CriteriasUser.user_id' => $user['User']['id'], 'CriteriasUser.criteria_id' => $criteria_ids),
      				'recursive' => -1,
      				'fields' => array('DISTINCT CriteriasUser.id', 'Criteria.name','Criteria.download_score', 'CriteriasUser.score_obtained')));
      
      

      if(count($criterias_users) < count($criteria_ids)){
      	$this->Session->setFlash('You haven\'t done enough challenges yet');
      	$this->redirect($this->referer());
      }
      
      $conditions['conditions'] = array('Document.repository_id' => $repo['Repository']['id']);

      $options['joins'] = array(
          array('table' => 'documents',
              'alias' => 'Document',
              'type' => 'inner',
              'conditions' => array(
                  'CriteriasDocument.document_id = Document.id'
              )
            )          
      );

      $options['conditions'] = array(
          'CriteriasDocument.criteria_id' => $criteria_ids,
          'Document.repository_id' => $repo['Repository']['id']);


      $options['fields'] = array(
          'DISTINCT Document.id', 'Document.name', 'Document.description');

      $options['group'] = 'Document.id HAVING COUNT(CriteriasDocument.criteria_id) >='.count($criteria_ids);

      $options['recursive'] = -1;

      $documents = $this->CriteriasDocument->find('all', $options);
      $documents_with_files = array();
      foreach ($documents as $document) {
        $document['files'] = array();
        $document['files'] = $this->Attachfile->find('all' , 
          array('conditions' => 
            array('Attachfile.document_id' => $document['Document']['id']), 
            'recursive' => -1, 
            'fields' => array("Attachfile.id","Attachfile.name","Attachfile.extension","Attachfile.location")));
        $documents_with_files[] = $document;
      }

      if(count($documents_with_files) > $documents_amount) {
        shuffle($documents_with_files);
        $docs_ids = array_rand($documents_with_files, $documents_amount);
        $docs_ids_array = (is_array($docs_ids) ? $docs_ids : array($docs_ids));
        $documents_with_files = array_intersect_key($documents_with_files, array_flip($docs_ids_array));
        $documents_amount = count($documents_with_files);
      }
      
      
      if(($str = $this->CriteriasUser->saveAndVerify($criterias_users, 0, $documents_amount)) != 'success'){
      	$this->Session->setFlash($str, flash_errors);
      	$this->redirect($this->referer());
      }

      $criterias_name = $this->Criteria->find('all', array('field' => array('Criteria.name'), 'conditions' => array('Criteria.id' => $criteria_ids)));

      $this->set(compact('criterias_name','documents_with_files'));
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

  function autocomplete() {
    $search_data = $this->params['url']['searchData'];

    $i = 0;
    $criterias = explode(' ', $this->params['url']['criterias']);
    $criterias = array_map("trim", $criterias);
     
    $criterias_selected = array();
    foreach($criterias as $criteria) {
    	if($i%4 == 0 && $i >= 24)
    		$criterias_selected[] = $criteria;
    
    	$i++;
    }
    
    $criterias_autocomplete0 = preg_grep("/^".$search_data."/i", $this->Session->read('criterias_names'));
    
    $criterias_autocomplete = array_diff($criterias_autocomplete0, $criterias_selected);
    
    $keys = array();

    foreach (array_keys($criterias_autocomplete) as $key) {
      $keys[] = $key;
    }  

    $this->set(compact('search_data','criterias_autocomplete', 'keys'));

    $this->render('/elements/criterias_autocomplete','ajax'); 
  }
  
  function prueba(){
  	print_r($this->Session->read('criterias_selected'));
  	$this->render('/elements/prueba','ajax'); 
  }

}

?>