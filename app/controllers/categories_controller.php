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


	function beforeFilter() {
	 	//...
	}

	function index() {
		$this->redirect(array('controller' => 'categories', 'action' => 'create'));
	}



	/**
	 * This function allows you to create a category
	 * based in the selection of some criterias.
	 */
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
	
	    $criterias_points_upload = array();
	    foreach ($criterias as $criteria) {
	        $criterias_points_upload[] = $criteria['Criteria']['upload_score'];
	    }

	    $criterias_points_download = array();
	    foreach ($criterias as $criteria) {
	        $criterias_points_download[] = $criteria['Criteria']['download_score'];
	    }

	    $criterias_points_collaboration = array();
	    foreach ($criterias as $criteria) {
	        $criterias_points_collaboration[] = $criteria['Criteria']['collaboration_score'];
	    }
	
	
	    /*Set variables for the use of Jquery*/
	    $this->Session->write('criterias_names',$criterias_names);
	    $this->Session->write('criterias_ids',$criterias_ids);
	    $this->Session->write('criterias_points_upload',$criterias_points_upload);
	    $this->Session->write('criterias_points_download',$criterias_points_download);
	    $this->Session->write('criterias_points_collaboration',$criterias_points_collaboration);
		  
	    /*if data is not empty, we confirm that the quantity of criterias of the category is greater than 1, then we save*/
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
	  		$this->Session->setFlash('Category succesfully created','flash_green');
	  		if(is_null($repo)){
	  			$this->redirect('index.php');
	  		}
	  		else{
	  			$this->redirect(array('controller' => 'repositories', 'action' => 'index', $repo['Repository']['internal_name']));
	  		}
	  		
	  	}
	  	
	  	$this->set(compact('criterias'));    
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

	function getKey($val, $arrayFrom) {
		foreach ($arrayFrom as $key => $value) {
			if(trim($key) === trim($val))
				return $key;
		}
		return "";

	}
	
	/*Function that JQuery uses for the dynamic display of categories*/
	function autocomplete() {
		$search_data = $this->params['url']['searchData'];
	
		$i = 0;
		$categories = preg_split('/ - [0-9]+ points/', $this->params['url']['categories']);
		unset($categories[count($categories)-1]);

		$categories_selected = $categories;

		$criterias_categories = $this->Session->read('criterias_categories');
		$categories_names = $this->Session->read('categories_names');
		$cr_catcount = array();
		
		$i = 0;
		$criterias = preg_split('/ - [0-9]+ points/', $this->params['url']['criterias']);
		unset($criterias[count($criterias)-1]);

		//print_r($criterias_categories);

		/*Counts criterias selected, that match a category*/
		$criterias_selected = array();
		foreach($criterias as $criteria) {
			$key = $this->getKey($criteria, $criterias_categories);

			if($key === "")
				continue;
			if(!isset($cr_catcount[$criterias_categories[$key]]))
				$cr_catcount[$criterias_categories[$key]] = 1;
			else
				$cr_catcount[$criterias_categories[$key]]++;
			
		
			$i++;
		}

		/*Add a category to categories_selected if all its criterias, was selected*/
		foreach(array_keys($categories_names) as $cn){
			if(isset($cr_catcount[$cn]) && count($categories_names[$cn]) == $cr_catcount[$cn]){
				$categories_selected[] = $cn;
			}
		}
		
		if($search_data != '')
			$categories_autocomplete0 = preg_grep("/^".$search_data."/i", array_keys($categories_names));
		else
			$categories_autocomplete0 = array_keys($categories_names);
	
		/*Categories that aren't selected*/
		$categories_autocomplete = $this->arrayDiffEmulation($categories_autocomplete0, $categories_selected);
	
		$keys = array();
	
		foreach (array_keys($categories_autocomplete) as $key) {
			$keys[] = $key;
		}
	
		$this->set(compact('search_data','categories_autocomplete', 'keys'));
	
		$this->render('/elements/categories_autocomplete','ajax');
	}

}