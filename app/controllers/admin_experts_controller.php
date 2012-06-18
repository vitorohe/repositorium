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

    function beforeFilter() {
    	if(!$this->Session->check('Experto.isExperto')) {
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
	    		$this->redirect($this->referer());
	    	}
    	}
    }
    
    function index() {

        $this->redirect(array('action'=>'list_notexperts', $this->data['User']['limit']));
    }

    
    function set_paginate($user = null, $id = null, $non_experts = true){
    	if(is_null($user) || is_null($id)){
    		$this->Session->setFlash('An error has occurred', flash_errors);
    		$this->redirect($this->referer());
    	}
    	
		$subquery = $this->CriteriasUser->getExpertsSubquery($id, $non_experts);
		
		if(is_null($subquery)){
			$this->Session->setFlash('An error has occurred in the DB', flash_errors);
			$this->redirect($this->referer());
		}
		
		$this->paginate['User']['recursive'] = -1;
		$this->paginate['User']['conditions'] = array($subquery, 'User.id <>' => $user['User']['id']);
    	
    }
    
    function list_notexperts($id = null) {
    
    	$user = $this->getConnectedUser();
    	$repo = $this->getCurrentRepository();
    	
    	$this->set_paginate($user, $id);
    	
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
    
    function list_experts($id = null) {
    
    	$user = $this->getConnectedUser();
    	$repo = $this->getCurrentRepository();
    	 
    	$this->set_paginate($user, $id, false);
    	 
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
    
    function setUserExpert($criteriaid = null, $userid = null, $qu = 1) {

        if($this->referer() === '/')
            $this->redirect('/');

    	if(is_null($userid) || is_null($criteriaid)){
    		$this->Session->setFlash('Invalid criteria and/or user', flash_errors);
    		$this->redirect($this->referer());
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