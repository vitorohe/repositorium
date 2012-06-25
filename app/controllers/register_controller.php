<?php

class RegisterController extends AppController{
  var $name = 'Register';
  var $uses = array();
  
  function beforeFilter() {
	if($this->getConnectedUser() != $this->anonymous)
		$this->redirect('/');
  }
  
  /*Controller in charge of the registration of new users*/
  function index() {
	if(!empty($this->data)) {
	  $this->User->set($this->data);		

	  if(!$this->User->validates()) {
		$errors = $this->User->invalidFields();
		$this->Session->setFlash($errors, 'flash_errors');

	  } else {
	  		  	
		$p1 = $this->data['User']['_password'];
		$p2 = $this->data['User']['_password2'];
		$this->data['User']['password'] = $p1;
		
		if(strlen($p1) < 6) {
		  $this->Session->setFlash('Password must have at least 6 characters');
		  
		} else if(strcmp($p1,$p2) != 0) {
		  $this->Session->setFlash('Passwords dont match', 'flash_errors');
		  
		} elseif($user = $this->User->register($this->data)) {
			$this->login($this->data);			
			$this->redirect('/');		  		
		}  		
	  }
	}
  }
}

?>