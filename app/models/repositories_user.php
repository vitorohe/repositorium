<?php
class RepositoriesUser extends AppModel {
	var $name = 'RepositoriesUser';
	/*var $validate = array(
		'points' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Points must be a number',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'positive' => array(
				'rule' => array('positive'),
				'message' => 'Points cannot be negative',
			)
		),
	);*/
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Repository' => array(
			'className' => 'Repository',
			'foreignKey' => 'repository_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


	/*Save data for the creator of the repository*/
	function saveRepositoryUser($user_id, $repository_id) {

		$ds = $this->getDataSource();
		$ds->begin($this);

		$this->create();

		$this->set(
		array(
			'RepositoriesUser' => array(
				'activation_id' => 'A',
				'internalstate_id' => 'A',
				'user_id' => $user_id,
				'repository_id' => $repository_id,
				'user_type_id' => 2
				)
			)
		);

		if(!$this->save()){
			$ds->rollback($this);
			return false;
		}

		$ds->commit($this);
		return true;
	}
	
	function positive($value) {
		return $value['points'] >= 0;
	}
	
	
}
?>