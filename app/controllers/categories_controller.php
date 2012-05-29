<?php
App::import('Sanitize');
class CategoriesController extends AppController {

	var $helpers = array('Html', 'Javascript', 'Ajax');
	var $uses = array('Category','Criteria');
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

	function view($id = null) {
	}


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
	  	$repo = $this->getCurrentRepository();
	  	$user = $this->getConnectedUser();
	    if($this->isAnonymous()){
	      $this->Session->setFlash('You must log in first', 'flash_errors');
	      $this->redirect(array('controller' => 'login', 'action' => 'index'));
	    }
	  	
	    $criterias = $this->Criteria->find('all');
	
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
	        $criterias_points[] = $criteria['Criteria']['upload_score'];
	    }
	
	
	    $this->Session->write('criterias_names',$criterias_names);
	    $this->Session->write('criterias_ids',$criterias_ids);
	    $this->Session->write('criterias_points',$criterias_points);
	
	  	//$constituents = $this->ConstituentsKit->find('list', array(
	  		//  				'conditions' => array('ConstituentsKit.kit_id' => $repo['Repository']['kit_id'], 'ConstituentsKit.constituent_id' != '0'), 
	  		  //				'recursive' => 1,
	  		  	//			'fields'=>array('Constituent.sysname')));
	  	
	  	if(!empty($this->data)) {
	  		
	  		$criterias = explode('&', $this->data['Criteria']['criterias']);
	  		$criterias = array_map("trim", $criterias);
	  		 
	  		$criteria_ids = array();
	  		foreach($criterias as $criteria) {
	  			$criteria_ids[] = substr($criteria, strpos($criteria, '=')+1);
	  		}
	  		if(count($criteria_ids) < 2){
	  			$this->Session->setFlash('You must select at least 2 criterias');
	  			$this->redirect($this->referer());
	  		}
	  		
	  		unset($this->data['Criteria']['criterias']);
	  		
	  		$this->data['Category']['user_id'] = $user['User']['id'];
	  		$this->data['Category']['activation_id'] = 'A';
	  		$this->data['Category']['internalstate_id'] = 'A';
	  		$this->data['CategoryCriteria']['activation_id'] = 'A';
	  		$this->data['CategoryCriteria']['internalstate_id'] = 'A';
	  		
	  		$this->Category->set($this->data);
	  		
	  		if(!$this->Category->validates()) {
	  			$errors = $this->Category->invalidFields();
	  			$this->Session->setFlash($errors, 'flash_errors');
	  			$this->redirect($this->referer());
	  		}
	  		if(!$this->Category->createNewCategory($this->data, $criteria_ids)){
	  			$this->Session->setFlash('An error has occurred, please blame the developer', 'flash_errors');
	  			$this->redirect($this->referer());
	  		}
	  		$this->Session->setFlash('Category succesfully created');
	  		if(is_null($repo)){
	  			$this->redirect('index.php');
	  		}
	  		else{
	  			$this->redirect(array('controller' => 'repositories', 'action' => 'index', $repo['Repository']['internal_name']));
	  		}
	  		
	  	}
	  	
	  	$this->set(compact('criterias'));    
		//$this->set(compact('constituents'));
	}
	
	function autocomplete() {
		$search_data = $this->params['url']['searchData'];
	
		$i = 0;
		$categories = explode(' ', $this->params['url']['categories']);
		$categories = array_map("trim", $categories);
		 
		$categories_selected = array();
		foreach($categories as $category) {
			if($i%4 == 0 && $i >= 24)
				$categories_selected[] = $category;
	
			$i++;
		}
		
		$criterias_categories = $this->Session->read('criterias_categories');
		$categories_names = $this->Session->read('categories_names');
		$cr_catcount = array();
		
		$i = 0;
		$criterias = explode(' ', $this->params['url']['criterias']);
		$criterias = array_map("trim", $criterias);
		
		$criterias_selected = array();
		foreach($criterias as $criteria) {
			if($i%4 == 0 && $i >= 24){
				if(!isset($criterias_categories[$criteria]))
					continue;
				if(!isset($cr_catcount[$criterias_categories[$criteria]]))
					$cr_catcount[$criterias_categories[$criteria]] = 1;
				else
					$cr_catcount[$criterias_categories[$criteria]]++;
			}
		
			$i++;
		}
		
		foreach(array_keys($categories_names) as $cn){
			if(isset($cr_catcount[$cn]) && count($categories_names[$cn]) == $cr_catcount[$cn]){
				$categories_selected[] = $cn;
			}
		}
		
	
		$categories_autocomplete0 = preg_grep("/^".$search_data."/i", array_keys($categories_names));
	
		$categories_autocomplete = array_diff($categories_autocomplete0, $categories_selected);
	
		$keys = array();
	
		foreach (array_keys($categories_autocomplete) as $key) {
			$keys[] = $key;
		}
	
		$this->set(compact('search_data','categories_autocomplete', 'keys'));
	
		$this->render('/elements/categories_autocomplete','ajax');
	}

}