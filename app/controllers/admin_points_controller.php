<?php

class AdminPointsController extends AppController {
  
  var $uses = array('User', 'Criteria', 'Document', 'CriteriasUser', /*'Tag',*/  /*'Expert',*/ 'Attachfile'/*, 'ConstituentsKit'*/);
  var $helpers = array('Text', 'Number','Mime');
  var $paginate = array(
	'User' => array(
	  'limit' => 5,
	  'order' => array(
		'name' => 'desc'
	  )
	)
  );

  function beforeFilter() {
  	$user = $this->getConnectedUser();
  	
  	if($this->isAnonymous() || !$this->Session->check('Experto.isExperto')) {
  		$this->Session->setFlash('You do not have permission to access this page');
  		$this->redirect('/');
  	}
  }


  function index() {
	$this->redirect(array('action'=>'listUserPoints'));
  }
  
  function set_paginate(){
  	if(!empty($this->data)) {
  		if(!empty($this->data['User']['limit'])) {
  			$this->Session->write('User.limit', $this->data['User']['limit']);
  		}
  	
  		/*if(!empty($this->data['CriteriasDocument']['order'])) {
  			$this->paginate['Criteria']['order'] = $this->_strToArray($this->data['CriteriasDocument']['order']);
  			$this->Session->write('CriteriasDocument.order', $this->_arrayToStr($this->paginate['Criteria']['order']));
  		}*/

  		if(isset($this->data['Criteria']['id'])) {
  			$this->Session->write('User.criteria', $this->data['Criteria']['id']);
  		}
  	}

  	$this->paginate['User']['limit'] = $this->Session->check('User.limit') ? $this->Session->read('User.limit') : 5;
  	$this->paginate['User']['recursive'] = -1;
  	
  }
  
  /* List users and their points */
  function listUsersPoints(){
  	$user = $this->getConnectedUser();
  	$rep = $this->getCurrentRepository();
  	
  	$this->set_paginate();
  	$users = $this->paginate();
  	
  	$crit_list = $this->Criteria->getCriteriasForExpert($user['User']['id']);
	$criteria_id = array_keys($crit_list);
	$criteria_id = $criteria_id[0]; 
  	
  	$current = 'points';
  	$menu = 'menu_expert';
  	$criteria_n = $this->Session->check('User.criteria') && $this->Session->read('User.criteria') != 0 ?
  					$this->Session->read('User.criteria') : $criteria_id;
  	$criteria_list = $crit_list;
  	$limit = $this->Session->check('User.limit') ? $this->Session->read('User.limit') : $this->paginate['User']['limit'];
  	//$ordering = $this->Session->read('CriteriasDocument.order') ? $this->Session->read('CriteriasDocument.order') : $this->_arrayToStr($this->paginate['Criteria']['order']);
  	
  	$crit_user = $this->CriteriasUser->getUsersPoints($criteria_n);
  	
  	$title = 'points';
  	
	$this->set(compact('current', 'rep', 'menu', 'limit', 'criteria_n', 'criteria_list', 'title', 'users', 'crit_user'));
  }
  
  
  /*Add points to the user in a criteria*/
  function add_points(){
  	if(empty($this->data['User']['points'])){
  		$this->Session->setFlash('The quantity of points cannot be empty');
  		$this->redirect($this->referer());
  	}
  	if(!is_numeric($this->data['User']['points'])){
  		$this->Session->setFlash('The quantity of points must be numbers');
  		$this->redirect($this->referer());
  	}
  	
  	$user = $this->getConnectedUser();
  	
  	$crit = $this->Criteria->getCriteriabyUser($this->data['Criteria']['id'], $user['User']['id']);
  	 
  	if(empty($crit)){
  		$this->Session->setFlash('Permission Denied', 'flash_errors');
  		$this->redirect($this->referer());
  	}
  	
  	$crit_user = $this->Criteria->getCriteriabyUser($this->data['Criteria']['id'], $this->data['User']['id'], array(1, 2));
  	
  	if(empty($crit_user)){
  		$row['criteria_id'] = $this->data['Criteria']['id'];
  		$row['user_id'] = $this->data['User']['id'];
  		$row['score_obtained'] = $this->data['User']['points'] >= 0 ? $this->data['User']['points'] : 0;
  		$row['quality_user_id'] = 2;
  		$row['successful_evaluation'] = 0;
  		$row['negative_evaluation'] = 0;
  		$row['activation_id'] = 'A';
  		$row['internalstate_id'] = 'A';
  	}
  	else{
  		$this->CriteriasUser->id = $crit_user[0]['CriteriasUser']['id'];
  		$row['score_obtained'] = $crit_user[0]['CriteriasUser']['score_obtained'] + $this->data['User']['points'];
  		if($row['score_obtained'] < 0)
  			$row['score_obtained'] = 0;
  	}
  	
  	if($this->CriteriasUser->save($row)){
  		$this->Session->setFlash('Points successfully added to user', 'flash_green');
  	}
  	else{
  		$this->Session->setFlash('There was an error, please blame the developer', 'flash_errors');
  	}
  	
  	$this->redirect($this->referer());
  }
  
}