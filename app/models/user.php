<?php
class User extends AppModel {
	var $name = 'User';
	var $displayField = 'email';
	var $virtualFields = array(
		//'full_name' => 'CONCAT(first_name, \' \', last_name)'
		/*+++++++++++++++++++++++++++++++++INI++++++++++++++++++++++++++++++++*/
		'full_name' => 'username'
		/*+++++++++++++++++++++++++++++++++FIN++++++++++++++++++++++++++++++++*/
	);
	var $validate = array(
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Give a valid email',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'unique' => array(
					'rule' => 'isUnique',
					'message' => 'The mail is in use.'
			),
		),
		/*'first_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please give your first name',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'last_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please give your last name',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),*/
		/*+++++++++++++++++++++++++++++INI+++++++++++++++++++++++++++++++*/

		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please give your username',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'unique' => array(
		        'rule' => 'isUnique',
		        'message' => 'This username has already been taken.'
		    ),
		),
		/*+++++++++++++++++++++++++++++FIN+++++++++++++++++++++++++++++++*/
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Password cannot be empty',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_administrator' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'active' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'Document' => array(
			'className' => 'Document',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		/*'Expert' => array(
			'className' => 'Expert',
			'foreignKey' => 'user_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),*/
		'Repository' => array(
			'className' => 'Repository',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'CriteriasUser' => array(
			'className' => 'CriteriasUser',
			'foreignKey' => 'user_id',
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
		'RepositoriesUser' => array(
			'className' => 'RepositoriesUser',
			'foreignKey' => 'user_id',
			'dependent' => true,
		)
	);
	
	/* ==================== METHODS ====================== */
	
	/**
	 * DO NOT change this method unless you know what are you doing
	 * Generate a private key, and generate a MAC
	 */
	function beforeSave($options) {
		if(!empty($this->data['User']['password'])) {
			$this->data['User']['salt'] = mt_rand();
			$this->data['User']['password'] = sha1($this->data['User']['password'] . $this->data['User']['salt']);
		}
	
		return true;
	}
	
	/**
	 * Registers a new user
	 * @param array $data
	 * @return true on success, false otherwise
	 */
	function register($data=array()) {
		if(empty($data) || !array_key_exists('User', $data))
			return false;
	
		$t = array(
			array_key_exists('email', $data['User']),
			/*array_key_exists('first_name', $data['User']),
			array_key_exists('last_name', $data['User']),*/
			/*+++++++++++++++++++++++++++++++++INI++++++++++++++++++++++++++++++++*/
			array_key_exists('username', $data['User']),
			/*+++++++++++++++++++++++++++++++++FIN++++++++++++++++++++++++++++++++*/
			array_key_exists('password', $data['User']),
		);
	
//		if(!($t[0] and $t[1] and $t[2] and $t[3]))
		/*+++++++++++++++++++++++++++++++++INI++++++++++++++++++++++++++++++++*/
		if(!($t[0] and $t[1] and $t[2]))
		/*+++++++++++++++++++++++++++++++++FIN++++++++++++++++++++++++++++++++*/
			return false;
	
		$data['User']['is_administrator'] = '0';
		$data['User']['activation_id'] = 'A';
		$data['User']['internalstate_id'] = 'A';
		
		// register user
		$user = $this->save($data);
		return $user;
	}
	
	/**
	 * checks user credential
	 * @param array $data with email and password as subkeys of User, eg $data['User']['email']
	 * @return the corresponding user object, null otherwise
	 */
	function getUser($data = array()) {
		if(empty($data) or !isset($data['User']['email']) or !isset($data['User']['password'])) {
			return null;
		}
		$d = $this->findByEmail($data['User']['email']);
		
		$pass_to_check = $d['User']['password'];
		$pass_from_login = sha1($data['User']['password'] . $d['User']['salt']);
		if(strcmp($pass_to_check,$pass_from_login) == 0) {
			return $d;
		}
		return null;
	}
	

}
?>
