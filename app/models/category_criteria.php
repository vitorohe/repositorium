<?php

class CategoryCriteria extends AppModel {

	var $name = 'CategoryCriteria';

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

	/*Obtain a row of CriteriasUser given the user_id and criteria_id*/
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
	
	/*Save a row of CategoriesCriteria, given the data, and the array of criterias*/ 
	function createNewCategoriesCriteria($data, $criteria_ids) {
		$ds = $this->getDataSource();
		$ds->begin($this);

		foreach($criteria_ids as $criteria_id){
			$data['CategoryCriteria']['criteria_id'] = $criteria_id;
			$this->create();
			
			if(!$this->save($data)) {
				$ds->rollback($this);
				return null;
			}
		}

		$ds->commit($this);

		return $this->find('first', array('conditions' => array('id' => $this->getLastInsertID()), 'recursive' => -1));
	}
}