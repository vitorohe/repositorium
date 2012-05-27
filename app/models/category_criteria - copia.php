<?php
class CategoriesCriteria extends AppModel {
	var $name = 'CategoriesCriteria';
	/*var $validate = array(
		'challenge_size' => array(
				'numeric' => array(
						'rule' => array('numeric'),
						'message' => 'The challenge size must be a number',
						//'allowEmpty' => false,
						//'required' => false,
						//'last' => false, // Stop validation after this rule
						//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
				'positive' => array(
						'rule' => array('positive'),
						'message' => 'The challenge size must be a positive number'
				)
		),
	);*/
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
			'Category' => array(
					'className' => 'Category',
					'foreignKey' => 'category_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'Criteria' => array(
					'className' => 'Criteria',
					'foreignKey' => 'criteria_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);

	/*****************************************************************************************************************/

	function _entry($user_id = null, $criteria_id = null) {
		if(is_null($user_id) || is_null($criteria_id))
			return null;
			
		return $this->find('first', array(
				'conditions' => array(
						'CriteriasUser.user_id' => $user_id,
						'CriteriasUser.criteria_id' => $criteria_id
				)
		));
	}


	function createNewCategoriesCriteria($data, $criteria_ids) {
		$ds = $this->getDataSource();
		$ds->begin($this);

		foreach($criteria_ids as $criteria_id){
			$data['CategoriesCriteria']['criteria_id'] = $criteria_id;
			if(!$this->save($data)) {
				$ds->rollback($this);
				return null;
			}
		}

		$ds->commit($this);
		return $this->find('first', array('conditions' => array('id' => $this->getLastInsertID()), 'recursive' => -1));
	}

}