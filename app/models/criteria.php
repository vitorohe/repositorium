<?php
class Criteria extends AppModel {
	var $name = 'Criteria';
	var $displayField = 'question';
	var $validate = array(
		'name' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => 'The name cannot be empty',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
				'max_length' => array(
					'rule' => array('maxLength', 40),
				),
				'forty' => array(
						'rule' => array('forty', 'name'),
						'message' => 'Name of the criteria must be of at most 40 characters',
				),
				'unique' => array(
						'rule' => 'isUnique',
						'message' => 'A criteria with that name already exists.'
				),
		),
		'question' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'The question cannot be empty',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'questions_quantity' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'The Question quantity cannot be empty',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Questions quantity must be a number',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'upload_score' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Document Upload cost must be a number',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'positive' => array(
				'rule' => array('positive', 'upload_score'),
				'message' => 'Document Upload cost must be a positive number',
			)
		),
		'download_score' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Document Download cost must be a number',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'positive' => array(
				'rule' => array('positive', 'download_score'),
				'message' => 'Document Download cost must be a positive number',
			)
		),
		'collaboration_score' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Challenge reward must be a number',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'positive' => array(
				'rule' => array('positive', 'collaboration_score'),
				'message' => 'Challenge reward must be a positive number',
			),
			'positive' => array(
					'rule' => array('gtupload', 'collaboration_score'),
					'message' => 'Challenge reward must be greater than upload score',
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed


	var $hasMany = array(
		'CriteriasDocument' => array(
			'className' => 'CriteriasDocument',
			'foreignKey' => 'criteria_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'CriteriasUser' => array(
			'className' => 'CriteriasUser',
			'foreignKey' => 'criteria_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);
	
	function positive($value, $key) {
		return $value[$key] >= 0;
	}
	
	function gtupload($value, $key){
		return $value[$key] >= $this->data['Criteria']['upload_score'];
	}
	
	function forty($value, $key){
		return strlen($value[$key]) <=40;
	}
	
	// actualiza los documentos agregando el nuevo criterio a InfoDesafio
	// y los usuarios, con TamanoDesafio
	function afterSave($created) {
		/*if($created) {
			$cr = $this->read(null, $this->id);
			
			$ds = $this->getDataSource();
				
			$ds->begin($this);
			if(
				$this->CriteriasDocument->massCreateAfterCriteria($this->id, $cr['Criteria']['repository_id']) &&
				$this->CriteriasUser->massCreateAfterCriteria($this->id, $this->field('minchallenge_size', array('id' => $this->id))))
				$ds->commit($this);
			else
				$ds->rollback($this);
		}*/
	}
	
	/*Obtain the criterias that have documents in the repository*/
	function findRepoCriterias($repository_id){
		$options['joins'] = array(
				array('table' => 'criterias_documents',
						'alias' => 'CriteriasDocument',
						'type' => 'inner',
						'conditions' => array(
								'CriteriasDocument.criteria_id = Criteria.id')
				),
				array('table' => 'documents',
						'alias' => 'Document',
						'type' => 'inner',
						'conditions' => array(
								'CriteriasDocument.document_id = Document.id'
						)
				),
				array('table' => 'repositories',
						'alias' => 'Repository',
						'type' => 'inner',
						'conditions' => array(
								'Repository.id = Document.repository_id')
				)
		);
		$options['conditions'] = array(
				'Repository.id' => $repository_id);
		
		$options['fields'] = array(
				'DISTINCT Criteria.id', 'Criteria.name', 'Criteria.question', 'Criteria.download_score');
		
		$options['recursive'] = -1;
		
		return $this->find('all', $options);
	}
	
	/*Obtain a random criteria for the challenge*/
	function getRandomCriteria($repository_id) {
		$criterios = $this->findRepoCriterias($repository_id);
	
		if(empty($criterios))
			return null;
	
		return $criterios[array_rand($criterios)];
	}
	
	
	/*Obtain a list of documents from the database, that depends of the criteria
	 *Result is shuffled, and used in a challenge 
	 */
	function generateChallenge($user_id = null, $criterio = null, $repository_id = null, $proportion = 0.3) {
		if(is_null($user_id) || is_null($repository_id))
			return null;
	
		if(is_null($criterio) || !isset($criterio['Criteria']['id']))
			$criterio = $this->getRandomCriteria($repository_id);
	
		if(is_null($criterio))
			return null;
	
		$criterio_id = $criterio['Criteria']['id'];
		$c = $this->getQ($criterio_id);
	
		$qty_of_validated    = ceil($proportion * $c);
		$qty_of_nonvalidated = floor($proportion * $c);
		$qty_of_nonevaluated = $c - $qty_of_validated - $qty_of_nonvalidated;
	
		$v_params = array(
				'repository_id' => $repository_id,
				'criteria_id' => $criterio_id,
				'user_id' => $user_id,
				'confirmado' => 1,
				'quantity' => $qty_of_validated 
		);
	
		$n_params = array(
				'repository_id' => $repository_id,
				'criteria_id' => $criterio_id,
				'user_id' => $user_id,
				'confirmado' => 2,
				'quantity' => $qty_of_nonvalidated 
		);
		
		$nn_params = array(
				'repository_id' => $repository_id,
				'criteria_id' => $criterio_id,
				'user_id' => $user_id,
				'confirmado' => 3,
				'quantity' => $qty_of_nonevaluated
		);
	
		$validated = $this->CriteriasDocument->getRandomDocuments($v_params);
	
		if(count($validated) < $qty_of_validated)
			$n_params['quantity'] = $qty_of_nonvalidated = $qty_of_nonvalidated + ($qty_of_validated - count($validated));
	
		$nonvalidated = $this->CriteriasDocument->getRandomDocuments($n_params);
		
		if(count($nonvalidated) < $qty_of_nonvalidated)
			$nn_params['quantity'] = $qty_of_nonevaluated + ($qty_of_validated - count($validated)) + ($qty_of_nonvalidated - count($nonvalidated));
		
		$nonevaluated = $this->CriteriasDocument->getRandomDocuments($nn_params);
		
		$challenge = array_merge($validated, $nonvalidated, $nonevaluated);
		shuffle($challenge);
	
		return $challenge;
	}
	
	
	/*Creates a criteria, and its respective criterias_user, associated with the user that created the criteria*/
	function createNewCriteria($data, $cruser) {
		$ds = $this->getDataSource();
		$crds = $this->CriteriasUser->getDataSource();
		$ds->begin($this);
		$crds->begin($this->CriteriasUser);
		
		
		if(!$this->save($data)) {
			$ds->rollback($this);
			$crds->rollback($this->CriteriasUser);
			return null;
		}
		
		
		$cruser['CriteriasUser']['criteria_id'] = $this->id;
		
		if(!$this->save($data) || !$this->CriteriasUser->save($cruser)) {
			$ds->rollback($this);
			$crds->rollback($this->CriteriasUser);
			return null;
		}
			
		$ds->commit($this);
		$crds->commit($this->CriteriasUser);
		return $this->find('first', array('conditions' => array('id' => $this->getLastInsertID()), 'recursive' => -1));
	}
	
	/*Obtain the criterias and documents, in which the user is expert of the criteria, for evaluating a document*/
	function findCriteriasUserinRepo($user = array(), $repo = null, $answers = array(1,2,3)){
		if(empty($user) || is_null($repo))
			return $user;
		
		$options['joins'] = array(
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

		
		$options['conditions'] = array(
				'CriteriasUser.user_id' => $user['User']['id'],
				'CriteriasUser.quality_user_id' => 1,
				'Document.repository_id' => $repo['Repository']['id'],
				'CriteriasDocument.answer' => $answers);
		
		$options['fields'] = array('DISTINCT Criteria.id', 'Criteria.name', 'Criteria.question', 'Criteria.upload_score',
				'Criteria.download_score', 'Criteria.collaboration_score', 'CriteriasUser.score_obtained', 'CriteriasDocument.no_eval',
				'CriteriasDocument.yes_eval', 'CriteriasDocument.answer', 'Document.id');
		
		
		$options['recursive'] = -1;
		
		return $this->find('all', $options);
	}
	
	/*Find the quantity of questions for a challenge for a criteria*/
	function getQ($criteria_id = null){
		if(is_null($criteria_id))
			return 3;
		
		$options['conditions'] = array('Criteria.id' => $criteria_id);
		$options['recursive'] = -1;
		
		$row = $this->find('first', $options);
		
		return $row['Criteria']['questions_quantity'];
	}
	
	/*Obtain a criterias_user, in which the user is expert*/
	function getCriteriaByUser($criteriaid, $userid, $qu = 1){
		return $this->find('all', 
    			array('conditions' => array('Criteria.id' => $criteriaid, 'CriteriasUser.user_id' => $userid, 'CriteriasUser.quality_user_id' => $qu),
    				'fields' => array('Criteria.id', 'Criteria.name', 'Criteria.question', 'Criteria.download_score', 'Criteria.upload_score', 
    								'Criteria.collaboration_score', 'CriteriasUser.score_obtained', 'CriteriasUser.id'),
    				'joins' => array(
             		   array('table' => 'criterias_users',
                  	       'alias' => 'CriteriasUser',
                   	       'type' => 'inner',
                     	   'conditions' => array(
                                'CriteriasUser.criteria_id = Criteria.id'
                        	)
                		)
    				)));
	}
	
	/*Obtain a list of criterias in which user is expert*/
	function getCriteriasForExpert($userid){
		$options['joins'] = array(
  			array('table' => 'criterias_users',
  					'alias' => 'CriteriasUser',
  					'type' => 'inner',
  					'conditions' => array(
  							'CriteriasUser.criteria_id = Criteria.id'
  					)
  			));
		
		$options['conditions'] = array('CriteriasUser.user_id' => $userid, 'CriteriasUser.quality_user_id' => 1);
		
		$options['fields'] = array('Criteria.id', 'Criteria.name');
		
		$options['recursive'] = -1;
		
		return $this->find('list', $options);
	}
	
}