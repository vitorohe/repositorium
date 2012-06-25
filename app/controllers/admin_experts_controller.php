<?php

class AdminExpertsController extends AppController {
    var $name = 'AdminExperts';
    var $uses = array('User', 'Criteria', 'CriteriasUser');
    var $helpers = array('Text', 'Number');
    var $paginate = array(
      'User' => array(
      	 'limit' => 100,
      	 'order' => array(
      		   'name' => 'desc'
      	  )
      )
    );
    
    /*Executes before an action of a controller. Perform basic permission tests*/
    function beforeFilter() {
    	if(!$this->Session->check('Expert.isExpert')) {
    		$this->Session->setFlash('You don\'t have permission to access this page', 'flash_errors');
    		$this->redirect('/');
    	}
    	
    	$user = $this->getConnectedUser();
    	if(isset($this->params['pass'][0])){
	    	$criteria = $this->CriteriasUser->find('first', 
	    			array('conditions' => 
	    					array('CriteriasUser.criteria_id' => $this->params['pass'][0], 
	    							'CriteriasUser.user_id' => $user['User']['id'],
	    							'CriteriasUser.quality_user_id' => 1),
	    					'recursive' => -1));
	    	if(is_null($criteria) || empty($criteria)){
	    		$this->Session->setFlash('You don\'t have permission to access this page', 'flash_errors');
	    		$this->redirect('/');
	    	}
    	}
    }
    
    function index() {

        $this->redirect(array('action'=>'list_notexperts', $this->data['User']['limit']));
    }

    /*Sets paginate for this controller*/
    function set_paginate($user = null, $id = null, $non_experts = true){
    	if(is_null($user) || is_null($id)){
    		return false;
    	}
    	
    	/*Obtain a subquery, for obtaining experts/not experts users*/
		$subquery = $this->CriteriasUser->getExpertsSubquery($id, $non_experts);
		
		if(is_null($subquery)){
			$this->Session->setFlash('An error has occurred in the DB', 'flash_errors');
			$this->redirect('/');
		}
		
		$this->paginate['User']['recursive'] = -1;
		$this->paginate['User']['conditions'] = array($subquery, 'User.id <>' => $user['User']['id']);
		
		return true;
    	
    }
    
    /*Controller for listing not experts users*/
    function list_notexperts($id = null) {
    
    	$user = $this->getConnectedUser();
    	$repo = $this->getCurrentRepository();
    	
    	if(!$this->set_paginate($user, $id)){
    		$this->Session->setFlash('An error has occurred', 'flash_errors');
			$this->redirect(array('controller' => 'admin_criterias', 'action' => 'listCriteriasUser'));
    	}
    	
    	$users = $this->paginate();
    	$criteria = $this->Criteria->find('first', array('conditions' => array('Criteria.id' => $id)));
    
    	$creator = false;
    	 
    	if($user['User']['id'] == $criteria['Criteria']['user_id']){
    		$creator = true;
    	}
    	
    	$params = array(
    			'limit' => 100,
    			'rep' => $repo,
    			'menu' => 'menu_experts',
    			'current' => 'not_experts',
    			'title' => 'Criteria'
    	);

    	$this->set($params);
    	$this->set(compact('users', 'criteria', 'creator'));
    
    }
    
    /*Controller for listing experts users*/
    function list_experts($id = null) {
    
    	$user = $this->getConnectedUser();
    	$repo = $this->getCurrentRepository();
    	 
    	if(!$this->set_paginate($user, $id, false)){
    		$this->Session->setFlash('An error has occurred', flash_errors);
    		$this->redirect(array('controller' => 'admin_criterias', 'action' => 'listCriteriasUser'));
    	}
    	 
    	$users = $this->paginate();
    	$criteria = $this->Criteria->find('first', array('conditions' => array('Criteria.id' => $id)));
    	
    	$creator = false;
    	$creator_id = $criteria['Criteria']['user_id'];
    	
    	if($user['User']['id'] == $criteria['Criteria']['user_id']){
    		$creator = true;
    	}
    	
    	$params = array(
    			'limit' => 100,
    			'rep' => $repo,
    			'menu' => 'menu_experts',
    			'current' => 'experts',
    			'title' => 'Criteria'
    	);

    	$this->set($params);
    	$this->set(compact('users', 'criteria', 'creator', 'creator_id'));
    
    }
    
    /*Sets/unsets an user to/from expert*/
    function setUserExpert($criteriaid = null, $userid = null, $qu = 1) {

        if($this->referer() === '/')
            $this->redirect('/');

    	if(is_null($userid) || is_null($criteriaid)){
    		$this->Session->setFlash('Invalid criteria and/or user', flash_errors);
    		$this->redirect('/');
    	}
    	
    	$user = $this->getConnectedUser();
    	$type = $qu == 2 ? '' : 'not';
    	 
    	$criteria = $this->Criteria->find('first', array('conditions' => array('Criteria.id' => $criteriaid),
    				'recursive' => -1));
    	if($user['User']['id'] != $criteria['Criteria']['user_id']){
    		$this->Session->setFlash('You don\'t have permission to access this page', 'flash_errors');
    		$this->redirect('/');
    	}
    	
    	if(!$this->CriteriasUser->setExpert($userid, $criteriaid, $qu)){
    		$this->Session->setFlash('An error occurred promoting the user', flash_errors);
    		$this->redirect(array('controller' => 'admin_experts', 'action' => 'list_'.$type.'experts', $criteriaid));
    	}
    	else{
    		if($qu == 1)
	    		$this->Session->setFlash('The user has been promoted to an Expert of the Criteria', 'flash_green');
    		else 
    			$this->Session->setFlash('The user has been droped out from the Expertise of the Criteria', 'flash_green');
	    	$this->redirect(array('controller' => 'admin_experts', 'action' => 'list_'.$type.'experts', $criteriaid));
    	}
    	
    }

}
?>