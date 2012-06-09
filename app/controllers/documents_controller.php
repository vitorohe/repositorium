<?php
class DocumentsController extends AppController {
	
	var $helpers = array('Html', 'Javascript', 'Ajax');
	
	var $name = 'Documents';
	var $uses = array('Document', 'User', 'Repository','Criteria', 'CategoryCriteria', 'Attachfile', 'CriteriasUser', 'RepositoryRestriction');

  var $paginate = array(
    'Document' => array(
      'limit' => 10,
      'recursive' => -1,
      'order' => array('Document.id' => 'desc')
    ),
    'Repository' => array(
      'recursive' => -1
    )
  );
	
	/**
	 * User Model
	 * @var User
	 */
	var $User;
	
	/**
	 * Document Model
	 * @var Document
	 */
	var $Document;
	
	/**
	 * SessionComponent
	 * @var SessionComponent
	 */
	var $Session;
	
	function beforeFilter() {
		/*if(!$this->Session->check('Document.continue')) {
			$this->Session->write('Action.type', $this->action);			
			$this->redirect(array('controller' => 'points', 'action' => 'process'));	
		}*/
	}
	
  function index() {
	$this->e404();
  }
  
  function _clean_session() {
  	$this->Session->delete('Document');
  }
  
  /**
   * 
   * @TODO points handling
   * @TODO dispatch handling
   */
  function upload() {

    $this->Session->delete('restrictions');

  	$repo = $this->requireRepository();
  	$user = $this->getConnectedUser();
    if($this->isAnonymous()){
      $this->Session->setFlash('You must log in first', 'flash_errors');
      $this->redirect(array('controller' => 'login', 'action' => 'index'));
    }
  	
    $criterias = $this->Criteria->find('all');

    $criterias_names = array();
    foreach ($criterias as $criteria) {
        $criterias_names[] = $criteria['Criteria']['name'];
    }

    $criterias_ids = array();
    foreach ($criterias as $criteria) {
        $criterias_ids[] = $criteria['Criteria']['id'];
    }

    $criterias_points = array();
    foreach ($criterias as $criteria) {
        $criterias_points[] = $criteria['Criteria']['upload_score'];
    }

    $options['joins'] = array(
        array('table' => 'categories',
            'alias' => 'Category',
            'type' => 'inner',
            'conditions' => array(
                'CategoryCriteria.category_id = Category.id'
            )
        )
    );
    
    $options['fields'] = array('Category.id', 'Category.name', 'CategoryCriteria.criteria_id');
    
    $options['recursive'] = -1;
    
    $options['group'] = 'Category.id HAVING COUNT(*) = 
          (SELECT COUNT(*) FROM category_criterias
          WHERE category_criterias.category_id = Category.id 
              AND category_criterias.criteria_id IN ('.implode(', ',$criterias_ids).'))';
    
    $categories = $this->CategoryCriteria->find('all', $options);
    
    $categories_ids = array();
    foreach($categories as $category){
      $categories_ids[] = $category['Category']['id'];
    }
    
    unset($options['group']);
    
    $options['joins'][] = array('table' => 'criterias',
            'alias' => 'Criteria',
            'type' => 'inner',
            'conditions' => array(
                'CategoryCriteria.criteria_id = Criteria.id'
            )
        );
    
    $options['fields'][] = 'Criteria.name';
    $options['fields'][] = 'Criteria.upload_score';
    
    $options['conditions'] = array('Category.id' => $categories_ids);
    
    $categories = $this->CategoryCriteria->find('all', $options);
    
    $categories_names = array();
    $categories_points = array();
    $criterias_categories = array();
    foreach($categories as $category){
      $categories_names[$category['Category']['name']][] = $category['Criteria']['name'];
      $criterias_categories[$category['Criteria']['name']] = $category['Category']['name'];
    
      if(!isset($categories_points[$category['Category']['name']])){
        $categories_points[$category['Category']['name']] = $category['Criteria']['upload_score'];
      }
      else {
        $categories_points[$category['Category']['name']] += $category['Criteria']['upload_score'];
      }
    }


    $this->Session->write('criterias_names',$criterias_names);
    $this->Session->write('criterias_ids',$criterias_ids);
    $this->Session->write('criterias_points',$criterias_points);
    $this->Session->write('categories_ids', $categories_ids);
    $this->Session->write('categories_names', $categories_names);
    $this->Session->write('categories_points', $categories_points);
    $this->Session->write('criterias_categories', $criterias_categories);

  	$restrictions = $this->RepositoryRestriction->find('first', array(
  	  				'conditions' => array('RepositoryRestriction.repository_id' => $repo['Repository']['id']), 
  	   				'recursive' => -1));

    $this->Session->write('restrictions', $restrictions);
  	
  	if(!empty($this->data)) {  		
		  $this->save($this->data);
  	}
  	
  	$this->set(compact('criterias', 'restrictions'));    
  }

  
  /**
   * 
   */
  function download() {
  	if($this->Session->check('Search.document_ids')) {
  		$document_ids = $this->Session->read('Search.document_ids');  		
  		
  		$repo = $this->requireRepository();
  		$doc_pack = $repo['Repository']['documentpack_size'];
  		  		
  		$docs = array();  		
  		foreach($document_ids as $id) {
  			$docs[] = $this->Document->find('first', array(
  		 		'conditions' => array('Document.id' => $id),
  		  		'recursive' => -1,)
  			);
  		}
  		
  		// if there are more documents, shuffle them
  		if(count($docs) > $doc_pack) {
  			shuffle($docs);
  			$docs_ids = array_rand($docs, $doc_pack);
  			$docs_ids_array = (is_array($docs_ids) ? $docs_ids : array($docs_ids));
  			$docs = array_intersect_key($docs, array_flip($docs_ids_array));
  		}
  		
  		// cgajardo: constituents to be attached
  		$constituents = $this->ConstituentsKit->find('all', array('conditions' => array('ConstituentsKit.kit_id' => $repo['Repository']['kit_id'], 'ConstituentsKit.constituent_id' != '0'), 'recursive' => 2, 'fields' => array("Constituent.sysname")));
  		
  		// cgajardo: attach folios that belongs to each document
  		foreach ($docs as &$doc){
  			$doc['files'] = array();
  			$doc['files'] = $this->Attachfile->find('all' , array('conditions' => array('Attachfile.document_id' => $doc['Document']['id']), 'recursive' => -1, 'fields' => array("Attachfile.id","Attachfile.filename","Attachfile.type")));
  		}
  		
  		$this->set(compact('docs', 'doc_pack', 'constituents'));
  		$this->_clean_session();  			
  	}
  }
  
  /**
   * 
   *  @deprecated
   */
  public function get($criterio = null) {
  	if(!$this->Session->check('Desafio.docs') and is_null($criterio)) {
  		$this->Session->setFlash(	
  		'Ganaste la posibilidad de descargar documentos, haz una búsqueda para poder acceder a ellos!');
  		$this->redirect(array('controller' => 'tags'));
  	} else if(!is_null($criterio)) {
  		$docs = $this->Tag->findDocumentsByTags(array($criterio));
  	} else {
  		$docs = $this->Session->read('Desafio.docs');
  	}
  
  	$this->Session->delete('Desafio');
  	$criterio = $this->Criterio->find('first', array('recursive' => -1));
  	$pack = $criterio['Criterio']['tamano_pack'];
  
  	$doc_objs = $this->Documento->find('all', array(
  	  'conditions' => array(
  		'Documento.id_documento' => $docs
  	),
  	  'recursive' => -1,
  	));
  	$premio = array();
  	if(count($doc_objs) > 0) {
  		if(count($doc_objs) < $pack)
  		$pack = count($doc_objs);
  
  		/* shuffle documents */
  		shuffle($doc_objs);
  		$tmp = array_rand($doc_objs, $pack);
  		$tmp = (is_array($tmp) ? $tmp : array($tmp));
  		/* insersect by keys from documents and some random subset of size $pack of $doc_objs */
  		/* $premio are $pack random documents from search result */
  		$premio = array_intersect_key(
  		$doc_objs,
  		array_flip($tmp)
  		);
  	}
  	$this->set(compact('premio', 'doc_objs'));
  }
  function checkcontents() {
	$this->autoRender = false;
	$q=$_GET["q"];
	$this->redirect('/checkcontents?q='.$q);
	die();
	//return false;
  }
  function checktitles() {
	$this->autoRender = false;
	$q=$_GET["q"];
	$this->redirect('/checktitles/check_title?q='.$q);
	die();
	//return false;
  }
  function checktags() {
	$this->autoRender = false;
	$q=$_GET["q"];
	$this->redirect('/checktags/check_tag?q='.$q);
	die();
	//return false;
  }
  function getWarnedDocuments($sim_titles,$sim_texts,$sim_tags,$sim_files,$sim_files_sha){
  $result=array();
  $i=0;
  foreach($sim_titles as $doc){
	$result[$i]=$doc;
	$i++;
  }
    foreach($sim_texts as $doc){
	$result[$i]=$doc;
	$i++;
  }
   if($sim_tags!=""){
  foreach($sim_tags as $doc){
	$result[$i]=$doc;
	$i++;
  }
	}
  if($sim_files!=""){
    foreach($sim_files as $doc){
	$result[$i]=$doc;
	$i++;
	}
    foreach($sim_files_sha as $doc){
	$result[$i]=$doc;
	$i++;
	}
  }
  $result=array_unique($result);
  $result=array_values($result);
  $string_result=implode(",",$result);
  return $string_result;
  }
  function set_warned(&$data){
  $repo = $this->requireRepository();
  	$max_sim=100;
	$aux_title=$this->data['Document']['name'];
	$aux_text=$this->data['Document']['description'];
	$title_val=0;
	$text_val=0;
	$tags_val=0;
	$all_tags=0;
	$files_val=0;
	$files_sha_val=0;
	$id= $repo['Repository']['id'];
	$tags = explode(',', $data['Document']['tags']);
	$tags = array_map("trim", $tags);
	$files=array();
	$files_tmp=array();
	if(isset($data['files'])) {
	for ($i = 0; $i < count($this->data['files']); $i++) {
				if($this->data['files'][$i]['error']!= 4){
				$files[$i] = $this->data['files'][$i]['name'];
				$files_tmp[$i]=sha1_file($this->data['files'][$i]['tmp_name']);
				}
			}
		$files_val=$this->Attachfile->findFilesCount($id,$files,$this);
		$files_sha_val=$this->Attachfile->findFilesShaCount($id,$files_tmp,$this);
		
		}

	//$tags_val=$this->Tag->findTagsCount($id, $tags,$this);
	$result_title= $this->Document->find('count', array('conditions' => array('Document.name' => $aux_title,'Document.repository_id' => $id)));
	$title_array=$this->Document->find('list', array('conditions' => array('Document.name' => $aux_title,'Document.repository_id' => $id)));
	$title_keys=array_keys($title_array);
	$this->Session->write("sim_titles", $title_keys);
	$result_text= $this->Document->find('count', array('conditions' =>array('Document.description' => $aux_text,'Document.repository_id' => $id )));
	$text_array=$this->Document->find('list', array('conditions' =>array('Document.description' => $aux_text,'Document.repository_id' => $id )));
	$text_keys=array_keys($text_array);
	$this->Session->write("sim_texts", $text_keys);
	if($result_title!=0){$title_val=1;}
	if($result_text!=0){$text_val=1;}
	if($tags_val > 0){$all_tags=1;}
	//$title_pdr = $repo['Repository']['pdr_tittle'];
	//$text_pdr = $repo['Repository']['pdr_text'];
	//$tags_pdr = $repo['Repository']['pdr_tags'];
	//$files_pdr = $repo['Repository']['pdr_files'];
	//$total_pdr=($title_pdr*$title_val)+($text_pdr*$text_val)+($tags_pdr*$all_tags)+($files_pdr*$files_val)+($files_pdr*$files_sha_val);	//old total_pdr
	$total_pdr=($title_pdr*$result_title)+($text_pdr*$result_text)+($tags_pdr*$tags_val)+($files_pdr*$files_val)+($files_pdr*$files_sha_val);	//pdr nuevo
	$results_not_used= array("result_title" => $result_title,"result_text" => $result_text,"tags_val" => $tags_val);
	$pdr_val_debug= array("title_pdr" => $title_pdr,"title_val" => $title_val,"text_pdr" => $text_pdr, "text_val" => $text_val,"tags_pdr" => $tags_pdr,"all_tags" => $all_tags,"files_pdr" => $files_pdr,"files_val" => $files_val,"files_sha_val" => $files_sha_val,"total_pdr" => $total_pdr);
	$this->Session->write("sha_files_count", $files_sha_val);
	if($total_pdr>$max_sim){
		$this->data['Document']['warned'] = 1;
		if(isset($data['files'])) {
		$this->data['Document']['warned_documents'] =$this->getWarnedDocuments($this->Session->read("sim_titles"),$this->Session->read("sim_texts"),$this->Session->read("sim_tags"),$this->Session->read("sim_files"),$this->Session->read("sim_files_sha"));
		}
		else{$this->data['Document']['warned_documents'] =$this->getWarnedDocuments($this->Session->read("sim_titles"),$this->Session->read("sim_texts"),$this->Session->read("sim_tags"),"","");}
		}
		else{
		$this->data['Document']['warned']=0;
		$this->data['Document']['warned_documents']="";
		}
		$this->data['Document']['warned_score']=$total_pdr;
		$this->data['Document']['warned_score_title']=$title_pdr*$result_title;
		$this->data['Document']['warned_score_text']=$text_pdr*$result_text;
		$this->data['Document']['warned_score_tags']=$tags_pdr*$tags_val;
		$this->data['Document']['warned_score_files']=$files_pdr*$files_val;
		$this->data['Document']['warned_score_files_sha']=$files_pdr*$files_sha_val;
  }


  function checkRestrictions(&$data, $restrictions = null) {

    if(empty($data)) {
      return false;
    }

    $files = $data['files'];

    foreach ($files as $file) {
      if($file['name'] === "")
        return true;
      else
        break;
    }

    $res_extension = $restrictions['RepositoryRestriction']['extension'];
    $res_amount = $restrictions['RepositoryRestriction']['amount'];
    $res_size = $restrictions['RepositoryRestriction']['size'];

    if($res_amount != 0)
      if(count($files) > $res_amount) {
        return false;
      }

    foreach ($files as $file) {
      if($file['error']==4){
        continue;
      }
    
      if($file['size'] > 0) {

        if($res_extension != '*') { 
          $extension = end(explode('.', $file['name']));
          if(strpos($res_extension, $extension) === false){
            return false;
          }
        }

        if($res_size != 0) {
          if($file['size']/1048576 > $res_size){
            return false;
          }
        }
      }
    }
    return true;
  }


  function save(&$data){
  	
  	$repo = $this->requireRepository();
  	$user = $this->getConnectedUser(); 

    $restrictions = $this->Session->read('restrictions');

    if(!empty($restrictions))
      if(!$this->checkRestrictions($this->data, $restrictions)) {
        $this->Session->setFlash('Your attached files don\'t satisfy with the restrictions');
        $this->redirect($this->referer());
      }

  	$this->data['Document']['repository_id'] = $repo['Repository']['id'];
  	$this->data['Document']['user_id'] = $user['User']['id'];
  	$this->data['Document']['activation_id'] = 'A';
  	$this->data['Document']['internalstate_id'] = 'A';
  	$this->data['Document']['document_state_id'] = 1;
    $this->data['Document']['register_date'] = date('Y-m-d H:i:s');
  	$this->Document->set($this->data);

    if(empty($this->data['Criteria']['criterias']) && empty($this->data['Criteria']['categories'])) {
      $this->Session->setFlash('You must include at least one criteria or category');
      $this->redirect($this->referer());
    }
	
  	$criterias = explode('&', $this->data['Criteria']['criterias']);
  	$criterias = array_map("trim", $criterias);

    $categories = explode('&', $this->data['Criteria']['categories']);
    $categories = array_map("trim", $categories);
  	
  	$criteria_ids = array();
  	foreach($criterias as $criteria) {
      $id = substr($criteria, strpos($criteria, '=')+1);
      if(empty($id))
        continue;
  		$criteria_ids[] = $id;
  	}

    foreach ($categories as $category) {
      $category = substr($category, strpos($category, '=')+1);
      if(empty($category))
        continue;
      $criterias_categories = array();
      $criterias_categories = $this->CategoryCriteria->find('all', array(
          'conditions' => array('CategoryCriteria.category_id' => $category),
            'recursive' => -1,)
        );

      foreach ($criterias_categories as $crit_cat) {
        $criteria_ids[] = $crit_cat['CategoryCriteria']['criteria_id'];
      }
    }

    $criteria_ids = array_unique($criteria_ids);
  	
  	$criterias_users = $this->CriteriasUser->find('all',
  			array('joins' => array(
  					array('table' => 'criterias',
  							'alias' => 'Criteria',
  							'type' => 'inner',
  							'conditions' => array(
  									'CriteriasUser.criteria_id = Criteria.id'
  							)
  					)),
  					'conditions' => array('CriteriasUser.user_id' => $user['User']['id'], 'CriteriasUser.criteria_id' => $criteria_ids),
  					'recursive' => -1,
  					'fields' => array('DISTINCT CriteriasUser.id', 'Criteria.name','Criteria.upload_score', 'CriteriasUser.score_obtained')));

    if(count($criterias_users) < count($criteria_ids)){
      $url = Router::url(array('controller'=>'points', 'action'=>'earn'));
      $this->Session->setFlash(sprintf('You haven\'t done enough challenges yet, you can click <a href="%s">here</a> to do it', $url));
  		$this->redirect($this->referer());
  	} else if(!$this->Document->validates()) {
  		$errors = $this->Document->invalidFields();
  		$this->Session->setFlash($errors, 'flash_errors');
  	} else if(($str = $this->CriteriasUser->saveAndVerify($criterias_users, 1)) != 'success'){
  		$this->Session->setFlash($str, 'flash_errors');
  		$this->redirect($this->referer());
  	} else if(!$this->Document->saveWithCriterias($this->data) || !$this->Attachfile->saveAttachedFiles($this->data)){
  		$this->Session->setFlash('There was an error trying to save the document. Please try again later');
  	} else {
		if(false){//$this->data['Document']['warned'] == 1){
  		$str_dup='Document saved and will be reviewed by an admin because it may be duplicated';
  		//$this->Session->setFlash('Document saved but its gonna be reviewed by an admin because it may be duplicated');
  		//$this->Session->setFlash($str_dup);
    		if($this->Session->read("sha_files_count")>0){
    		//$this->Session->setFlash('Warned by sha');
    		$str_sha= "There are " .$this->Session->read("sha_files_count"). " documents with the same content of one (or more) of your uploaded files";
    		$this->Session->setFlash(nl2br($str_dup."\n".$str_sha));
    		}
    		else{$this->Session->setFlash($str_dup);}
    		}
  		//if(false){}
  		else{
    		$this->Session->setFlash('Document saved successfully');
  		}
  		$this->_clean_session();
  		$this->redirect(array('controller' => 'repositories', 'action' => 'index', $repo['Repository']['internal_name']));
		
  	}
  }

  function list_documents(){
    $user = $this->getConnectedUser();
    $repo = $this->getCurrentRepository();

    if(is_null($repo) || empty($repo)){
      $this->paginate['Document']['conditions'] = array('Document.user_id' => $user['User']['id']);
      $this->paginate['Document']['joins'] = array(
        array('table' => 'repositories',
            'alias' => 'Repository',
            'type' => 'inner',
            'conditions' => array(
                'Document.repository_id = Repository.id'
            )
        )
      );
      $this->paginate['Document']['fields'] = array('DISTINCT Document.id', 'Document.name', 'Document.description', 'Repository.id', 'Repository.name', 'Repository.internal_name');
    }
    else{
      $this->paginate['Document']['conditions'] = array('Document.user_id' => $user['User']['id'], 'Document.repository_id' => $repo['Repository']['id']);
    }

    $documents = $this->paginate();
    
    $documents_with_files = array();
    if(!empty($documents))
      foreach ($documents as $document) {
        $document['files'] = array();
        $document['files'] = $this->Attachfile->find('all' , 
          array('conditions' => 
            array('Attachfile.document_id' => $document['Document']['id']), 
            'recursive' => -1, 
            'fields' => array("Attachfile.id","Attachfile.name","Attachfile.extension","Attachfile.location")));
        $documents_with_files[] = $document;
      }


    $data = array(
      'documents_with_files' => $documents_with_files,
      'current' => 'My documents',
      'title' => "Documents of '{$user['User']['name']}'",
      'cond' => 'owner',
    );

    $this->set($data);
  }  

  function getZip() {

    $files = $this->Attachfile->find('all', array(
      'conditions' => array('Attachfile.document_id' => $this->params['url']['id']),
      'recursive' => -1
      )
    );
      

    $zip = new ZipArchive;

    $tmp_zip = tempnam ("tmp", "file") . ".zip";

    $zip->open($tmp_zip, ZipArchive::CREATE);
    foreach ($files as $file) {
      $dir = WWW_ROOT.substr($file['Attachfile']['location'],1).'/'.$file['Attachfile']['name'];

      if(strpos(WWW_ROOT, "\\"))
        $dir = str_replace('/', '\\', $dir);

      $zip->addFile($dir, $file['Attachfile']['name']);
    }


    $zip->close();

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename='.$this->params['url']['title'].'.zip');
    header("Content-Transfer-Encoding: binary");
    header('Content-Length: ' . filesize($tmp_zip));
    


    readfile($tmp_zip);

    unlink($tmp_zip);
  }
}
?>