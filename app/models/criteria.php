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
				'forty' => array(
						'rule' => array('forty', 'name'),
						'message' => 'Name of the criteria must be of at most 40 characters',
				)
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
			)
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/*var $belongsTo = array(
		'Repository' => array(
			'className' => 'Repository',
			'foreignKey' => 'repository_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);*/

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
				'DISTINCT Criteria.id', 'Criteria.name', 'Criteria.question');
		
		$options['recursive'] = -1;
		
		return $this->find('all', $options);
	}
	
	
	function getRandomCriteria($repository_id) {
		$criterios = $this->findRepoCriterias($repository_id);
	
		if(empty($criterios))
			return null;
	
		return $criterios[array_rand($criterios)];
	}
	
	function generateChallenge($user_id = null, $criterio = null, $repository_id = null, $proportion = 0.5) {
		if(is_null($user_id) || is_null($repository_id))
			return null;
	
		if(is_null($criterio) || !isset($criterio['Criteria']['id']))
			$criterio = $this->getRandomCriteria($repository_id);
	
		if(is_null($criterio))
			return null;
	
		$criterio_id = $criterio['Criteria']['id'];
		$c = 3;//$this->CriteriasUser->getC($user_id, $criterio_id);
	
		$qty_of_validated    = ceil($proportion * $c);
		$qty_of_nonvalidated = floor((1 - $proportion) * $c);
	
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
	
		$validated = $this->CriteriasDocument->getRandomDocuments($v_params);
	
		if(count($validated) < $qty_of_validated)
			$n_params['quantity'] = $qty_of_nonvalidated + ($qty_of_validated - count($validated));
	
		$nonvalidated = $this->CriteriasDocument->getRandomDocuments($n_params);
		
		$challenge = array_merge($validated, $nonvalidated);
		shuffle($challenge);
	
		return $challenge;
	}
	
	/**
	 * 
	 * (untested)
	 * @param array $documents
	 * @param array $criterias
	 */
	function filterDocuments($documents = array(), $criterias = array()) {
		if(empty($documents) || empty($criterias)) {
			return $documents;
		}
		
		$filtered = array();
		foreach($criterias as $c) {
			foreach($documents as $d) {
				$cd = $this->CriteriasDocument->find('first', array(
					'conditions' => array(
						'document_id' => $d,
						'criteria_id' => $c),
					'recursive' => -1)
				);
				
				if($cd['CriteriasDocument']['validated'] AND $cd['CriteriasDocument']['official_answer'] == 1) {
					$filtered[] = $d;
				}
			}			
		}
		return array_unique($filtered);
	}
	
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

}