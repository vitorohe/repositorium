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

	/*Create a new category, given the data. After, it creates the series of CategoryCriterias, given the list of criterias*/
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