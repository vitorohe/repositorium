<?php

class AdminCriteriasController extends AppController {
    var $name = 'AdminCriterias';
    var $uses = array('Criteria');
    var $helpers = array('Text', 'Number');
    var $paginate = array(
     'Criterias' => array(
         'limit' => 5,
         'order' => array(
              'total_respuestas' => 'desc'
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

        $this->Criteria->id = $id;
        
        if ($this->Criteria->save($this->data)) {
            $this->Session->setFlash('Criteria '.$id.' was successfully modified');
            CakeLog::write('activity', 'Criteria '.$id.' was modified');
            $this->redirect(array('controller' => 'criterias', 'action' => 'index'));
        }
        
        $this->render('add');
    }

}
?>