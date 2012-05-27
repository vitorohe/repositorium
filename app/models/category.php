<?php
class Category extends AppModel {
	var $name = 'Category';
	var $displayField = 'description';
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
			),
			'description' => array(
					'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'The description cannot be empty',
							//'allowEmpty' => false,
							//'required' => false,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create', // Limit validation to 'create' or 'update' operations
					),
			),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
		)
	);

	var $hasMany = array(
			'CategoryCriteria' => array(
					'className' => 'CategoryCriteria',
					'foreignKey' => 'category_id',
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
	);

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


	function getRandomCriteria($repository_id) {
		$criterios = $this->find('all', array('conditions' => compact('repository_id'), 'recursive' => -1));

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
		$c = $this->CriteriasUser->getC($user_id, $criterio_id);

		$qty_of_validated    = ceil($proportion * $c);
		$qty_of_nonvalidated = floor((1 - $proportion) * $c);

		$v_params = array(
				'repository_id' => $repository_id,
				'criteria_id' => $criterio_id,
				'user_id' => $user_id,
				'confirmado' => true,
				'quantity' => $qty_of_validated
		);

		$n_params = array(
				'repository_id' => $repository_id,
				'criteria_id' => $criterio_id,
				'user_id' => $user_id,
				'confirmado' => false,
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

	function createNewCategory($data, $criteria_ids) {
		$ds = $this->getDataSource();
		$ds->begin($this);

		if(!$this->save($data)) {
			$ds->rollback($this);
			return null;
		}
		$data['CategoryCriteria']['category_id'] = $this->getLastInsertID();
		
		if(is_null($this->CategoryCriteria->createNewCategoriesCriteria($data, $criteria_ids))){
			$ds->rollback($this);
			return null;
		}
			
		$ds->commit($this);
		return $this->find('first', array('conditions' => array('id' => $this->getLastInsertID()), 'recursive' => -1));
	}

}