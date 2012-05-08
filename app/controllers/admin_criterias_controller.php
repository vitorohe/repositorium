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

    function index() {
        $this->redirect(array('action'=>'listCriteriasUser'));
    }


    function listCriteriasUser() {

        $user = $this->getConnectedUser();

        $options['joins'] = array(
            array('table' => 'criterias_users',
                  'alias' => 'CriteriasUser',
                  'type' => 'inner',
                  'conditions' => array(
                    'CriteriasUser.criteria_id = Criteria.id'
                    )
                )
            );

        $options['conditions'] = array(
            'CriteriasUser.user_id' => $user['User']['id'],
            'CriteriasUser.quality_user_id' => 1);

        $options['fields'] = array('Criteria.id', 'Criteria.name', 'Criteria.question', 'Criteria.upload_score',
        		 'Criteria.download_score', 'Criteria.collaboration_score', 'CriteriasUser.score_obtained');
        
        
        $options['recursive'] = -1;

        $criterias = $this->Criteria->find('all',$options);

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

}
?>