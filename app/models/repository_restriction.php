<?php
class RepositoryRestriction extends AppModel {
	var $name = 'RepositoryRestriction';
	
	
	/*Save a restriction for the repository*/
	function saveRestriction($restrictions = null,$user_id = null,$repo_id = null) {

		if(empty($restrictions)) {
			return false;
		}

		$ds = $this->getDataSource();
		$ds->begin($this); 

		$this->create();
		
		$this->set(
		$repository_restriction = array(
			'RepositoryRestriction' => array(
	    	   'name' => "",
               'extension' => $restrictions['extension'],
               'size' => $restrictions['size'],
               'amount' => $restrictions['amount'],
               'register_date' => date('Y-m-d H:i:s'),
               'activation_id' => 'A',
               'internalstate_id' => 'A',
               'user_id' => $user_id,
               'repository_id' => $repo_id	             
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

}
?>