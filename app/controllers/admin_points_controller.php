<?php

class AdminPointsController extends AppController {
  
  var $uses = array('User', 'Criteria', 'Document', 'CriteriasDocument', /*'Tag',*/  /*'Expert',*/ 'Attachfile'/*, 'ConstituentsKit'*/);
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
  	$repo = $this->requireRepository();

	if(is_null($repo)) {
		$this->Session->setFlash("Must be in a repository");
		$this->redirect('/');
	}
  	
  	if($this->isAnonymous() || !$this->Session->check('Experto.isExperto')) {
  		$this->Session->setFlash('You do not have permission to access this page');
  		$this->redirect('/');
  	}
  }


  function index() {
	$this->redirect(array('action'=>'listUserPoints'));
  }
  
  function set_paginate($criteria){
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
  	
  	$this->paginate['User']['joins'] = array(
  			array('table' => 'criterias_users',
  					'alias' => 'CriteriasUser',
  					'type' => 'LEFT',
  					'conditions' => array(
  							'CriteriasUser.user_id = User.id',
  							'CriteriasUser.criteria_id = '.$criteria
  					)
  			));
  	
  	$this->paginate['User']['fields'] = array('User.id', 'User.name', 'User.username', 'User.email', 'CriteriasUser.criteria_id', 'CriteriasUser.score_obtained');
  	
  	$this->paginate['User']['limit'] = $this->Session->check('User.limit') ? $this->Session->read('User.limit') : 5;
  	$this->paginate['User']['recursive'] = -1;
  	
  }
  
  function listUsersPoints(){
  	$user = $this->getConnectedUser();
  	$rep = $this->getCurrentRepository();
  	
  	$crit_list = $this->Criteria->getCriteriasForExpert($user['User']['id']);
	$criteria_id = array_keys($crit_list);
	$criteria_id = $criteria_id[0]; 
  	
  	$current = 'points';
  	$menu = 'menu_expert';
  	$criteria_n = $this->Session->check('User.criteria') && $this->Session->read('User.criteria') != 0 ?
  	$this->Session->read('User.criteria') : $criteria_id;
  	$criteria_list = $crit_list;
  	$limit = $this->Session->read('User.limit') ? $this->Session->read('User.limit') : $this->paginate['User']['limit'];
  	//$ordering = $this->Session->read('CriteriasDocument.order') ? $this->Session->read('CriteriasDocument.order') : $this->_arrayToStr($this->paginate['Criteria']['order']);
  	
  	$this->set_paginate($criteria_n);
  	$users = $this->paginate();
  	
  	$title = 'points';
  	
  	print_r($users);
  	die();
  	
	$this->set(compact('current', 'rep', 'menu', 'limit', 'criteria_n', 'criteria_list', 'title', 'users'));
  }
  
}