<?php
class CriteriasUser extends AppModel {
	var $name = 'CriteriasUser';
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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
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
	
	function positive($value) {
		return $value['challenge_size'] >= 0;
	}
	
	/*Gets a row of criterias user, given the user and the criteria id*/
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
	

	/*Add points to user in a given criteria*/
	function addPoints($user_id = null, $criteria_id = null, $points = 10) {
		if(!is_null($user_id) && !is_null($criteria_id)) {
			$ru = $this->find('first', array('conditions' => compact('user_id', 'criteria_id'), 'recursive' => -1));
			$new_value = $points + $ru['CriteriasUser']['score_obtained'];
				
			$this->id = $ru['CriteriasUser']['id'];
			if($this->saveField('score_obtained', $new_value))
				return true;
		}
		return false;
	}
	
	/* Count the evaluation of the user, in a challenge, as positive or negative */
	function countEvaluation($user_id = null, $criteria_id = null, $challengeCorrect = false){
		$td = $this->_entry($user_id, $criteria_id);
		$des = ($challengeCorrect ? 'successful' : 'negative');
		
		if(!$td){
			$td['CriteriasUser'] = array(
					'successful_evaluation' => 0,
					'negative_evaluation' => 0,
					'score_obtained' => 0,
					'activation_id' => 'A',
					'internalstate_id' => 'A',
					'user_id' => $user_id,
					'criteria_id' => $criteria_id,
					'quality_user_id' => 2);
			if(!$this->createNewCriteriasUser($td))
				return false;
			
			$td['CriteriasUser']['id'] = $this->getLastInsertID();
		}

		$value = $td['CriteriasUser'][$des . '_evaluation'];
		
		$new_value = $value + 1;

		$this->id = $td['CriteriasUser']['id'];
		if($this->save($td))
			return true;
		
		return false;
	}
	
	/*Create a row of criterias_user*/
	function createNewCriteriasUser($data) {
		$ds = $this->getDataSource();
		$ds->begin($this);


		if(!$this->save($data)) {
			$ds->rollback($this);
			return null;
		}

		$ds->commit($this);
		return $this->find('first', array('conditions' => array('id' => $this->getLastInsertID()), 'recursive' => -1));
	}
	
	/*Verifies if the user can pay the points for a search or upload, and save the points*/
	function saveAndVerify($data = array(), $mode, $doc_quantity = 1){
		if(empty($data))
			return 'There is no data';
	
		$score_type = $mode == 0 ? 'download_score' : 'upload_score';
		$ds = $this->getDataSource();
		$ds->begin($this);
		foreach($data as $criterias_user){
			if($criterias_user['Criteria'][$score_type]*$doc_quantity > $criterias_user['CriteriasUser']['score_obtained']){
				$ds->rollback($this);
				return 'You haven\'t enough points for the criteria '.$criterias_user['Criteria']['name'];
			}
			$this->id = $criterias_user['CriteriasUser']['id'];
			$criterias_user['CriteriasUser']['score_obtained'] = $criterias_user['CriteriasUser']['score_obtained'] - $criterias_user['Criteria'][$score_type]*$doc_quantity;
			if(!$this->save($criterias_user)){
				$ds->rollback($this);
				return 'An error has occurred, please blame the developer';
			}
		}
		$ds->commit($this);
		
		return 'success';
	}
	
	/*Gets a subquery, consisting of obtaining all the experts, or all non experts
	 * for using it in a major query
	 */
	function getExpertsSubquery($critid = null, $non_experts){
		
		$dbo = $this->getDataSource();
		
		$options['recursive'] = -1;
		$options['limit'] = $options['offset'] = $options['order'] = $options['group'] = null;
		$options['table'] = $dbo->fullTableName($this);
		$options['alias'] = 'CriteriasUser';
		 
		$options['conditions'] = array('CriteriasUser.criteria_id' => $critid, 'CriteriasUser.quality_user_id' => 1);
		 
		$options['fields'] = array('DISTINCT CriteriasUser.user_id');
		 
		$subquery = $dbo->buildStatement($options, $this);
		if($non_experts){
			$subquery = ' `User`.`id` NOT IN (' . $subquery . ') ';
		}
		else 
			$subquery = ' `User`.`id` IN (' . $subquery . ') ';
		$subQueryExpression = $dbo->expression($subquery);
		
		return $subQueryExpression;
	}
	
	/*Sets/unset the expertise of a user*/
	function setExpert($userid, $criteriaid, $qu){
		$crus = $this->find('first', array('conditions' => array('CriteriasUser.user_id' => $userid, 'CriteriasUser.criteria_id' => $criteriaid), 
				'recursive' => -1));	
		
		if(is_null($crus) || empty($crus)){
			$crus['CriteriasUser']['activation_id'] = 'A';
			$crus['CriteriasUser']['internalstate_id'] = 'A';
			$crus['CriteriasUser']['score_obtained'] = 0;
			$crus['CriteriasUser']['successful_evaluation'] = 0;
			$crus['CriteriasUser']['negative_evaluation'] = 0;
			$crus['CriteriasUser']['user_id'] = $userid;
			$crus['CriteriasUser']['criteria_id'] = $criteriaid;
		}
		else{
			$this->id = $crus['CriteriasUser']['id'];
		}
		$crus['CriteriasUser']['quality_user_id'] = $qu;

		if(!$this->save($crus)){
			return null;
		}
		
		return true;
	}
	
	/*Get the points of the user in a criteria*/
	function getUsersPoints($criteriaid){
		$options['conditions'] = array('CriteriasUser.criteria_id' => $criteriaid);
		
		$options['fields'] = array('CriteriasUser.user_id', 'CriteriasUser.score_obtained');
		
		$options['recursive'] = -1;
		
		return $this->find('list', $options);
	}
	
}
?>