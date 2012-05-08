<?php

class AdminCriteriasController extends AppController {
    var $name = 'AdminCriterias';
    var $uses = array('Criteria');
    var $helpers = array('Text', 'Number');
    var $paginate = array(
     'Criteria' => array(
         'limit' => 5,
         'order' => array(
              'name' => 'desc'
            )
        )
    );

    function beforeFilter() {
    	
    	$this->requireRepository();
    	
    	if(!$this->Session->check('Experto.isExperto')) {
    		$this->Session->setFlash('You don\'t have permission to access this page');
    		$this->redirect('/');
    	}
    }
    
    function index() {
        $this->redirect(array('action'=>'listCriteriasUser'));
    }


    function listCriteriasUser() {

        $user = $this->getConnectedUser();
        $repo = $this->getCurrentRepository();

        $criterias = $this->Criteria->findCriteriasUserinRepo($user, $repo);
        
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
        $this->set(compact('criterias'));	

        $this->render('listar');

    }

    function edit($id = null) {
    	$params = array(
    			'menu' => 'menu_expert',
    			'current' => 'criteria',
    			'title' => 'Edit criteria',
    	);
    	$this->set($params);
    	if(!empty($this->data)){
	
	        $this->Criteria->id = $id;
	        
	        if ($this->Criteria->save($this->data)) {
	            $this->Session->setFlash('Criteria '.$this->data['Criteria']['name'].' was successfully modified');
	            CakeLog::write('activity', 'Criteria '.$this->data['Criteria']['name'].' was modified');
	            $this->redirect(array('controller' => 'admin_criterias', 'action' => 'listCriteriasUser'));
	        }
	        else{
	        	$this->Session->setFlash('There was an error creating the criteria, please blame the developer');
	        	CakeLog::write('activity', 'There was an error creating the criteria, please blame the developer');
	        	$this->redirect(array('controller' => 'admin_criterias', 'action' => 'listCriteriasUser'));
	        }
    	}
    	if(is_null($id))
    		$this->redirect('index');
    	$crit = $this->Criteria->read(null, $id);
    		
    	if(empty($crit))
    		$this->e404();
    		
    	$this->data = $crit;

    }
    
    
    function remove($id = null) {
    	if(is_null($id))
    		$this->e404();
    
    	if($this->Criteria->delete($id)) {
    		$this->Session->setFlash('Criteria deleted successfuly');
    		CakeLog::write('activity', 'Criteria [id='.$id.'] deleted');
    	} else {
    		$this->Session->setFlash('An error ocurred deleting the criteria', 'flash_errors');
    	}
    
    	if(Configure::read('App.subdomains')) {
    		$dom = Configure::read('App.domain');
    		$this->redirect("http://www.{$dom}/admin_repositories");
    	} else {
    		$this->redirect('index');
    	}
    
    }

}
?>