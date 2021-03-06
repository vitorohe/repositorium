<?php
App::import('Sanitize');
class CriteriasController extends AppController {

  var $helpers = array('Mime');

  var $uses = array('Document', 'Criteria', 'CriteriasDocument', 'Attachfile', 'CriteriasUser', 'CategoryCriteria');
  var $paginate = array(
    'Criteria' => array(
      'limit' => 5,
      'order' => array(
      'Criteria.question' => 'desc'
      )
    )
  );

  /* ??? */
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
  
  /* Controller of the search controller, sets the criterias and categories of the repository */
  function search() {
    $repo = $this->requireRepository();
  
    $criterias = $this->Criteria->findRepoCriterias($repo['Repository']['id']);

    if(empty($criterias)){
    	$this->Session->setFlash('There aren\'t documents in the repository');
    	if(Configure::read('App.subdomains')) {
    		$dom = Configure::read('App.domain');
    		$this->redirect("http://{$repo['Repository']['internal_name']}.{$dom}");
    	} else {
    		$this->redirect(array('controller' => 'repositories', 'action' => 'index', $repo['Repository']['internal_name']));
    	}
    }
    
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
    
    $options['joins'] = array(
    		array('table' => 'categories',
    				'alias' => 'Category',
    				'type' => 'inner',
    				'conditions' => array(
    						'CategoryCriteria.category_id = Category.id'
    				)
    		)
    );
    
    $options['fields'] = array('Category.id', 'Category.name', 'CategoryCriteria.criteria_id');
    
    $options['recursive'] = -1;
    
    $options['group'] = 'Category.id HAVING COUNT(*) = 
    			(SELECT COUNT(*) FROM category_criterias
    			WHERE category_criterias.category_id = Category.id 
    				  AND category_criterias.criteria_id IN ('.implode(', ',$criterias_ids).'))';
    
    /*obtain the categories of the search*/
    $categories = $this->CategoryCriteria->find('all', $options);
    
    $categories_ids = array();
    foreach($categories as $category){
    	$categories_ids[] = $category['Category']['id'];
    }
    
    unset($options['group']);
    
    $options['joins'][] = array('table' => 'criterias',
    				'alias' => 'Criteria',
    				'type' => 'inner',
    				'conditions' => array(
    						'CategoryCriteria.criteria_id = Criteria.id'
    				)
    		);
    
    $options['fields'][] = 'Criteria.name';
    $options['fields'][] = 'Criteria.download_score';
    
    $options['conditions'] = array('Category.id' => $categories_ids);
    
    /*obtain categories_criterias*/
    $categories = $this->CategoryCriteria->find('all', $options);
    
    $categories_names = array();
    $categories_points = array();
    $criterias_categories = array();
    /*create two dictionaries, in one the key is a category, an the value an array with the criterias of the categories
     * in the other, the key is a category, and the value too
     */
    foreach($categories as $category){
    	$categories_names[$category['Category']['name']][] = $category['Criteria']['name'];
    	$criterias_categories[$category['Criteria']['name']] = $category['Category']['name'];
    
    	if(!isset($categories_points[$category['Category']['name']])){
    		$categories_points[$category['Category']['name']] = $category['Criteria']['download_score'];
    	}
    	else {
    		$categories_points[$category['Category']['name']] += $category['Criteria']['download_score'];
    	}
    }

    /*sets session variables for the dynamic display of criterias and categories*/
    $this->Session->write('criterias_names',$criterias_names);
    $this->Session->write('criterias_ids',$criterias_ids);
    $this->Session->write('criterias_points',$criterias_points);
    $this->Session->write('categories_ids', $categories_ids);
    $this->Session->write('categories_names', $categories_names);
    $this->Session->write('categories_points', $categories_points);
    $this->Session->write('criterias_categories', $criterias_categories);

    $this->set(compact('criterias'));

  }
  /*IT IS NOT USED NOW 
   * For adding a criteria*/
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
      $this->Session->setFlash('Criteria added successfully', 'flash_green');
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

  /* Edit a criteria*/
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
    $this->Session->setFlash('Criteria '.$id.' was successfully modified', 'flash_green');
    CakeLog::write('activity', 'Criteria '.$id.' was modified');
    $this->redirect(array('controller' => 'criterias', 'action' => 'index'));
    }
  }
  $this->render('add');
  }

  /*Remove a criteria*/
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
  
  
  /* Controller for the creation of criterias */
  function create(){
  	if($this->isAnonymous()){
		$this->Session->setFlash('You must log in first', 'flash_errors');
		$this->redirect(array('controller' => 'login', 'action' => 'index'));
	}
    
	/*if data is not empty, set de data for the criteria, and save it, otherwise load the view for this controller*/ 
    if(!empty($this->data)) {
      $user = $this->getConnectedUser();
      $repo = $this->getCurrentRepository();

      $cu = array('CriteriasUser' => array(
            'successful_evaluation' => 0,
            'negative_evaluation' => 0,
            'score_obtained' => 3*$this->data['Criteria']['upload_score'],
            'activation_id' => 'A',
            'internalstate_id' => 'A',
            'user_id' => $user['User']['id'],
            'quality_user_id' => 1));
      
      $this->data['Criteria']['user_id'] = $user['User']['id'];
      $this->data['Criteria']['activation_id'] = 'A';
      $this->data['Criteria']['internalstate_id'] = 'A';
      $this->data['Criteria']['register_date'] = date('Y-m-d H:i:s');     
      
        
      $this->Criteria->set($this->data);
      
      /* validates view's fields and creates the criteria in bd*/
      if($this->Criteria->validates()) {
        $criteria = $this->Criteria->createNewCriteria($this->data, $cu);
        CakeLog::write('activity', "Criteria [name=\"{$criteria['Criteria']['name']}\"] created");
        if(is_null($criteria)) {
          $this->Session->setFlash('An error occurred creating the criteria. Please, blame the developer');
          $this->redirect('/');
        }
        
        $this->Session->setFlash('Criteria successfully created', 'flash_green');
        
        if(!is_null($repo)){
	        if(Configure::read('App.subdomains')) {
	        	$dom = Configure::read('App.domain');
	        	$this->redirect("http://{$repo['Repository']['internal_name']}.{$dom}");
	        } else {
	        	$this->redirect(array('controller' => 'repositories','action' => 'index', $repo['Repository']['internal_name']));
	        }
        }
	    else
       		$this->redirect('/');
    
      } else {
        $this->Session->setFlash($this->Criteria->invalidFields(), 'flash_errors');
      }
    }
  }

  /*Process the search of documents, given a list of criterias, and discount the points of the search, if necessary*/
  function process(){
    if(!empty($this->data) && isset($this->data['Criteria']) && (isset($this->data['Criteria']['criterias']) || isset($this->data['Criteria']['categories'])) && (!empty($this->data['Criteria']['criterias']) || !empty($this->data['Criteria']['categories']))){
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

      /*Get criterias selected*/
      $criterias = explode('&', $data['Criteria']['criterias']);
      $criterias = array_map("trim", $criterias);

      $criteria_ids = array();
      if(!empty($this->data['Criteria']['criterias'])){
	      foreach($criterias as $criteria) {
	        $criteria_ids[] = substr($criteria, strpos($criteria, '=')+1);
	      }
      }
      
      $categories = explode('&', $data['Criteria']['categories']);
      $categories = array_map("trim", $categories);
      
      
      $categories_ids = array();
      if(!empty($this->data['Criteria']['categories'])){
	      foreach($categories as $category) {
	      	$categories_ids[] = substr($category, strpos($category, '=')+1);
	      }
      }
      
      $criterias_category = $this->CategoryCriteria->find('all',
      			array('conditions' => array('CategoryCriteria.category_id' => $categories_ids),
      					'recursive' => -1)
      		);
      
      foreach($criterias_category as $cc){
      	$criteria_ids[] = $cc['CategoryCriteria']['criteria_id'];
      }
      
      unset($data['Criteria']['criterias']);
      unset($data['Criteria']['categories']);
      
      /*Get criterias_users, for getting the user's points*/
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
          'Document.repository_id' => $repo['Repository']['id'],
      	  'CriteriasDocument.answer' => 1);

      $options['fields'] = array(
          'DISTINCT Document.id', 'Document.name', 'Document.description');

      $options['group'] = 'Document.id HAVING COUNT(CriteriasDocument.criteria_id) >='.count($criteria_ids);

      $options['recursive'] = -1;

      /*Find the documents that achieve the search terms*/
      $documents = $this->CriteriasDocument->find('all', $options);
      
      $documents_with_files = array();
      foreach ($documents as $document) {
        $document['files'] = array();
        $document['files'] = $this->Attachfile->find('all' , 
          array('conditions' => 
            array('Attachfile.document_id' => $document['Document']['id']), 
            'recursive' => -1, 
            'fields' => array("Attachfile.id", "Attachfile.name", "Attachfile.extension", "Attachfile.location")));
        $documents_with_files[] = $document;
      }

      if(count($documents_with_files) > $documents_amount) {
        shuffle($documents_with_files);
        $docs_ids = array_rand($documents_with_files, $documents_amount);
        $docs_ids_array = (is_array($docs_ids) ? $docs_ids : array($docs_ids));
        $documents_with_files = array_intersect_key($documents_with_files, array_flip($docs_ids_array));
        $documents_amount = count($documents_with_files);
      }
      
      /*Verify that the user has enough points to spend*/
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


  /**
   * This function allows to delete the element that is
   * in $arrayFrom and in $arrayAgainst at the same time.
   * The result is the difference, i.e., it the array 
   * $arrayFrom without elements from $arrayAgainst 
   */

  function arrayDiffEmulation($arrayFrom, $arrayAgainst) {
    foreach ($arrayFrom as $key => $value) {
      if($this->in_array_($value,$arrayAgainst)) {
        unset($arrayFrom[$key]);
      }
    }
         
    return $arrayFrom;
  }

  function in_array_($value,$arrayAgainst) {

    foreach ($arrayAgainst as $val) {
      if(trim($value) === trim($val)) {
        return true;
      }
    }

    return false;
  }

  /*Function that JQuery uses for the dynamic display of criterias*/
  function autocomplete() {

    $category_create = false;
    $referer = $this->referer();
    if(strpos($referer, 'categories/create') === false)
      $category_create = false;
    else
      $category_create = true;
    
    $search_data = $this->params['url']['searchData'];

    $i = 0;
    $criterias = preg_split('/ - [0-9]+ points/', $this->params['url']['criterias']);

    unset($criterias[count($criterias)-1]);
     
    $criterias_selected = $criterias;
    
    $categories_names = $this->Session->read('categories_names');

    $categories = preg_split('/ - [0-9]+ points/', $this->params['url']['categories']);
    unset($categories[count($categories)-1]);

    /*See categories selected, and add the respective criterias to selected*/
    $categories_selected = array();
    foreach($categories as $category) {
      $categories_selected[] = $category;
      
      foreach ($categories_names as $key => $value) {
        if(trim($key) === trim($category))
          foreach($value as $c)
            $criterias_selected[] = $c;
      }    	
    }

    if($search_data != '')
    	$criterias_autocomplete0 = preg_grep("/^".$search_data."/i", $this->Session->read('criterias_names'));
    else
    	$criterias_autocomplete0 = $this->Session->read('criterias_names');
    
    /*Criterias that aren't selected*/
    $criterias_autocomplete = $this->arrayDiffEmulation($criterias_autocomplete0, $criterias_selected);
    
    $keys = array();

    foreach (array_keys($criterias_autocomplete) as $key) {
      $keys[] = $key;
    }

    $this->set(compact('search_data','criterias_autocomplete', 'keys', 'category_create'));

    $this->render('/elements/criterias_autocomplete','ajax'); 
  }
  
  function prueba(){
  	print_r($this->Session->read('criterias_selected'));
  	$this->render('/elements/prueba','ajax'); 
  }

}

?>