<?php
class CriteriasDocument extends AppModel {
	var $name = 'CriteriasDocument';
	var $validate = array(
		/*'total_answers_1' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'total_answers_2' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'validated' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'challengeable' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),*/
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Document' => array(
			'className' => 'Document',
			'foreignKey' => 'document_id',
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
	
	
	/* virtualFields ftw! */
	/*var $virtualFields = array(
	    	'total_respuestas' => 'total_answers_1 + total_answers_2',
	    	'consenso' => 'ABS(total_answers_2 - total_answers_1)*100/(total_answers_1 + total_answers_2)',
	    	'total_app' => 'total_answers_2*100/(total_answers_1 + total_answers_2)'
	);
	*/


	function saveCriteriaDocument($criterias = null, $categories = null, $id = null) {

		if(empty($criterias) && empty($categories))
			return false;

		$ds = $this->getDataSource();
		$ds->begin($this);

		$criteria_ids = array();

		if(!empty($criterias))
			foreach($criterias as $criteria)
				$criteria_ids[] = substr($criteria, strpos($criteria, '=')+1);
		
		if(!empty($categories))
			foreach ($categories as $category) {
				$category = substr($category, strpos($category, '=')+1);
				$criterias_categories = array();
				$criterias_categories =  ClassRegistry::init("CategoryCriteria")->find('all', array(
			  	'conditions' => array('CategoryCriteria.category_id' => $category),
				    'recursive' => -1,)
				);

				foreach ($criterias_categories as $crit_cat) {
					$criteria_ids[] = $crit_cat['CategoryCriteria']['criteria_id'];
				}
	    	}

	    $criteria_ids = array_unique($criteria_ids);

		foreach($criteria_ids as $criteria_id) {
			$this->create();
			$this->set(
			$criteria_document = array(
				'CriteriasDocument' => array(
		    	   'criteria_id' => $criteria_id,
	               'document_id' => $id,
	               'internalstate_id' => 'A',
	               'activation_id' => 'A'
	             )
               )
			);

			if(!$this->save()){
				$ds->rollback($this);
				return false;
			}
		}

		$ds->commit($this);
		return true;
	}

	function massCreateAfterCriteria($id_criterio = null, $repository_id = null) {
		if(!is_null($id_criterio) && !is_null($repository_id)) {
			$docs = $this->Document->find('all', array(
				'conditions' => array(
					'Document.repository_id' => $repository_id
				),
				'fields' => array('Document.id'), 
				'recursive' => -1)
			);

			foreach($docs as $doc) {
				$this->create();
				$this->set(
					array(
						'document_id' => $doc['Document']['id'],
						'criteria_id' => $id_criterio,
					    'total_answers_1' => 0,
					    'total_answers_2' => 0,
					    'validated' => false,
					    'challengeable' => true,
						)
					);
				if(!$this->save())
					return false;
			}
		} else {
			return false;
		}
		return true;
	}
	
	function massCreateAfterDocument($id_documento = null, $repository_id = null) {
		if(!is_null($id_documento) && !is_null($repository_id)) {
			$criterios = $this->Criteria->find('all', array(
				'conditions' => array(
					'Criteria.repository_id' => $repository_id
				),
				'fields' => 'Criteria.id', 
				'recursive' => -1)
			);
			
			$ds = $this->getDataSource();
			$ds->begin($this);
			
			foreach($criterios as $c) {
				$this->create();
				$this->set(
					array(
						'document_id' => $id_documento,
					  	'criteria_id' => $c['Criteria']['id'],
					  	'total_answers_1' => 0,
					  	'total_answers_2' => 0,
				      	'validated' => false,
						'challengeable' => true,
						)
					);
				if(!$this->save()) {
					$ds->rollback($this);
					return false;
				}
			}	
			$ds->commit($this);
			return true;
		}
		
		return false;
	}
	
	/**
	 * $data = compact('criteria_id', 'confirmado', 'preguntable', 'quantity', 'user_id');
	 */
	function getRandomDocuments($data = null) {
		if(!isset($data['confirmado']) || !isset($data['criteria_id']) || !isset($data['quantity']))
			return null;
	
		// we only want InformacionDesafio and Documento entries
		$this->unbindModel(array('belongsTo' => array('Criteria')));
	
		$preguntable 	= (isset($data['preguntable']) ? $data['preguntable'] : true) ;
		$usuario_id 	= (isset($data['user_id']) ? $data['user_id'] : 1) ;
		$criteria_id 	= $data['criteria_id'];
		$confirmado 	= $data['confirmado'];
		$quantity 		= $data['quantity'];
		$repository_id	= $data['repository_id'];

		
		$options['joins'] = array(
				array('table' => 'documents',
						'alias' => 'Document',
						'type' => 'inner',
						'conditions' => array(
								'CriteriasDocument.document_id = Document.id'
						)
				)
		);
		
		$options['conditions'] = array(
				'CriteriasDocument.criteria_id' => $criteria_id,
				'CriteriasDocument.answer' => $confirmado,
				'Document.repository_id' => $repository_id);
		
		$options['fields'] = array(
				'DISTINCT Document.id', 'Document.name', 'Document.description');
		
		$options['recursive'] = -1;
		
		$ids = $this->find('all', $options);
		
		/* cgajardo: this will make attached files available in challenges view. */ 
		/*foreach ($ids as $key => $id){
			$files = ClassRegistry::init('Attachfile')->find('all', array(
					'conditions' => array(
						'Attachfile.document_id' => $id['Document']['id'] 
					),
					'fields' => array('Attachfile.id', 'Attachfile.filename') 
			));
			$ids[$key]['Files'] = $files; 
		}*/
		
		// shuffles the result and then extract the first $quantity $ids
		shuffle($ids);
		$result = array_slice($ids, 0, $quantity);
		// 		pr($result);
		return $result;
	}
	
	/*
	 * first reCaptcha validation
	* returns true if validated documents are well answered
	* false otherwise
	*/
	function validateChallenge($data = null) {
		if(is_null($data) || !is_array($data))
			return false;
		
		$docs = array();
		// first, identify which documents are which
		foreach($data as $d) {
			if(!isset($d['criteria_id']) || !isset($d['document_id']) || !isset($d['respuesta']))
				return false;
	
			$info = $this->_validatedEntry($d);
				
			if(!is_null($info)) {
				$answer = $info['CriteriasDocument']['answer'];
				$given = $d['respuesta'];
				
				/* answer :    1, 2
				 * given  :    1, 2	 */
				if(	$answer != $given )
					return false;
			}
		}
	
		return true;
	}
	
	/**
	 * if $d: informacion_desafio entry
	 * corresponds to a validated document
	 * then returns that entry
	 *
	 * null otherwise
	 *
	 */
	function _validatedEntry($d = null) {
		if(is_null($d)) return null;
		$info = $this->entry($d);
	
		if($info['CriteriasDocument']['answer'] != 3)
			return $info;
		return null;
	}
	
	/**
	 *
	 * returns info_desafio of given id_criterio and id_documento
	 * @param array $d
	 */
	function entry($d = null) {
		if(is_null($d)) return null;
		
		$info = $this->find('first', array(
				'recursive' => -1,
	 			'fields' => array('id' ,'answer'),
				'conditions' => array(
					'CriteriasDocument.criteria_id' => $d['criteria_id'],
					'CriteriasDocument.document_id' => $d['document_id']
			)
		));
	
		return $info;
	}
	
	/**
	 *
	 * saves answer statistics
	 * if $correctChallenge then save all data
	 * otherwise, only validated documents
	 *
	 * @param array $data
	 * @param boolean $correctChallenge
	 */
	function saveStatistics($data = null, $correctChallenge = false) {
		if(is_null($data)) return;
	
		foreach($data as $d) {
			$info = $this->entry($d);
	
			/*
			 * if challenge was correct, then save all documents' statistics
			* otherwise, only validated documents' statistcs
			*/
			if($info) {
				$id = $info['CriteriasDocument']['id'];
	
				$ans = $d['respuesta'] == 1? 'yes_eval' : 'no_eval';
				$new_total = $info['CriteriasDocument']['total_eval'] + 1;
				$new_value = $info['CriteriasDocument'][$ans] + 1;
					
				$this->id = $id;
				$this->saveField($ans, $new_value);
				$this->saveField('total_eval', $new_total);
			}
		}
	}
	
	//cgajardo: this function will delete any record realted to an already removed documents
	// this hopefully will resolve issue #57
	function deleteRecord($docId){
		$ids = $this->find('all', array(
						'recursive' => -1,
			 			'fields' => array('id'),
						'conditions' => array(
							'CriteriasDocument.document_id' => $docId
						)
				));
		foreach($ids as $id ){
			$this->delete($id['CriteriasDocument']['id']);
		}
	}
}
?>