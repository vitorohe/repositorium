<?php

class AdminCriteriasController extends AppController {
    var $name = 'AdminCriterias';
    var $uses = array('Criteria', 'CriteriasUser');
    var $helpers = array('Text', 'Number', 'Paginator');
    var $paginate = array(
     'Criteria' => array(
         'limit' => 5,
         'order' => array(
              'name' => 'desc'
          )
      ),
      'User' => array(
      	 'limit' => 5,
      	 'order' => array(
      		   'name' => 'desc'
      	  )
      )
    );

    function beforeFilter() {
    	
    	if(!$this->Session->check('Experto.isExperto')) {
    		$this->Session->setFlash('You don\'t have permission to access this page');
    		$this->redirect('/');
    	}
    }
    
    function index() {

        $this->redirect(array('action'=>'listCriteriasUser', $this->data['Criteria']['limit']));
    }


    function listCriteriasUser() {

        if(!empty($this->params['pass']))
            $this->data['Criteria']['limit'] = $this->params['pass'][0];

        if(!empty($this->data)) { 
            if(!empty($this->data['Criteria']['limit'])) {
                $this->paginate['Criteria']['limit'] = $this->data['Criteria']['limit'];
                $this->Session->write('Criteria.limit', $this->data['Criteria']['limit']);
            } 
        }

        $user = $this->getConnectedUser();
        $repo = $this->getCurrentRepository();
		
        $criterias = $this->findCriteriasUserinRepo($user, $repo);
        
        $this->data = $criterias;

        $params = array(
           'limit' => $this->Session->read('Criteria.limit') ? $this->Session->read('Criteria.limit') : $this->paginate['Criteria']['limit'],
           'rep' => $repo,
           'menu' => 'menu_expert',
           'current' => 'criteria',
           'title' => 'Criteria'
        ); 

        $this->set($params);
        $this->set(compact('criterias'));	

        $this->render('listar');

    }

    /* Edit a criteria, given its id */
    function edit($id = null) {
    	if(is_null($id))
    		$this->redirect('index');
    	
    	$params = array(
    			'menu' => 'menu_expert',
    			'current' => 'criteria',
    			'title' => 'Edit criteria',
    	);
    	$this->set($params);
    	
    	/*Confirm that the user is an expert of the criteria */
    	$user = $this->getConnectedUser();
    	
    	$crit = $this->Criteria->getCriteriabyUser($id, $user['User']['id']);
    	
    	if(empty($crit)){
    		$this->Session->setFlash('Permission Denied: you are NOT expert in this criteria', 'flash_errors');
    		$this->redirect($this->referer());
    	}
    	
    	/* Save data */
    	if(!empty($this->data)){
	
	        $this->Criteria->id = $id;
	        $this->CriteriasUser->id = $crit[0]['CriteriasUser']['id'];
	        
	        $this->data['CriteriasUser']['score_obtained'] = $this->data['Criteria']['score_obtained'];
	        
	        if ($this->Criteria->save($this->data) && $this->CriteriasUser->save($this->data)) {
	            $this->Session->setFlash('Criteria '.$this->data['Criteria']['name'].' was successfully modified', 'flash_green');
	            CakeLog::write('activity', 'Criteria '.$this->data['Criteria']['name'].' was modified');
	            $this->redirect(array('controller' => 'admin_criterias', 'action' => 'listCriteriasUser'));
	        }
	        else{
	        	$this->Session->setFlash('There was an error modifying the criteria, please blame the developer');
	        	CakeLog::write('activity', 'There was an error creating the criteria, please blame the developer');
	        	$this->redirect(array('controller' => 'admin_criterias', 'action' => 'listCriteriasUser'));
	        }
    	}

    	if(empty($crit))
    		$this->e404();

    	$crit[0]['Criteria']['score_obtained'] = $crit[0]['CriteriasUser']['score_obtained'];	
    	
    	$this->data = $crit[0];
		
    }
    
    
    function remove($id = null) {
        if($this->referer() === '/')
            $this->redirect('/');
    	if(is_null($id))
    		$this->e404();
    
    	if($this->Criteria->delete($id)) {
    		$this->Session->setFlash('Criteria deleted successfuly', 'flash_green');
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
    
    function findCriteriasUserinRepo($user = array(), $repo = null){
    	if(empty($user))
    		return $user;    
    
    	$this->paginate['Criteria']['conditions'] = array(
    			'CriteriasUser.user_id' => $user['User']['id'],
    			'CriteriasUser.quality_user_id' => 1);
    	
    	if(!is_null($repo)){
    		$this->paginate['Criteria']['conditions']['Document.repository_id'] =  $repo['Repository']['id'];

            $this->paginate['Criteria']['joins'] = array(
                array('table' => 'criterias_users',
                        'alias' => 'CriteriasUser',
                        'type' => 'inner',
                        'conditions' => array(
                                'CriteriasUser.criteria_id = Criteria.id'
                        )
                ),
                array(
                        'table' => 'criterias_documents',
                        'alias' => 'CriteriasDocument',
                        'type' => 'inner',
                        'conditions' => array(
                                'CriteriasDocument.criteria_id = Criteria.id')
                ),
                array(
                        'table' => 'documents',
                        'alias' => 'Document',
                        'type' => 'inner',
                        'conditions' => array(
                                'Document.id = CriteriasDocument.document_id')
                )
            );
    	}else{

            $this->paginate['Criteria']['joins'] = array(
                array('table' => 'criterias_users',
                        'alias' => 'CriteriasUser',
                        'type' => 'inner',
                        'conditions' => array(
                                'CriteriasUser.criteria_id = Criteria.id'
                        )
                )
            );
        }
    
    	$this->paginate['Criteria']['fields'] = array('DISTINCT Criteria.id', 'Criteria.name', 'Criteria.question', 'Criteria.upload_score',
    			'Criteria.download_score', 'Criteria.collaboration_score', 'CriteriasUser.score_obtained');
    
    
    	$this->paginate['Criteria']['recursive'] = -1;
    
    	return $this->paginate();
    }


}
?>