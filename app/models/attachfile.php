<?php
class Attachfile extends AppModel {
	var $name = 'Attachfile';
	var $displayField = 'filename';

	var $belongsTo = array(
		'Document' => array(
			'className' => 'Document',
			'foreignKey' => 'document_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
		//devuelve la cantidad de archivos del documento nuevo que ya existen
		//y asigna en session los documentos que tienen al menos un archivo igual
	function findFilesCount($repo_id = null, $files = array(),$documents_controller) {
			if(is_null($repo_id)) {
			return null;
		}
				$docs = array();

		foreach ($files as $file) {
			//comparar content
			$tmp = $this->find('all', array(
			  		'conditions' => array(
						'Attachfile.filename' => $file,
						'Document.repository_id' => $repo_id
					),
			  		'fields' => array('DISTINCT Attachfile.document_id')
				)
			);
			$hola = array();
			foreach($tmp as $t) {
				$hola[] = $t['Attachfile']['document_id'];
			}
			if(count($tmp)==0){return 0;}
			else{$docs[] = $hola;}
		}
				if(count($docs) > 0) {
				$res = array();
			// for ($i = 0; $i+1 < count($docs); $i++) {
				// $res = array_intersect($docs[$i], $docs[$i+1]);
			// }
			$foo= array();
			$k=0;
			for ($i = 0; $i < count($docs);$i++){
			    foreach($docs[$i] as $doc){
				$foo[$k]=$doc;
				$k++;
				}
			}
			$foo=array_unique($foo);
				//echo '<pre>';
				//echo '$foo en files es:';
				//print_r($foo);
				//echo '</pre>';
				$foo=array_values($foo);
				$documents_controller->Session->write("sim_files", $foo);
				// echo '<pre>';
				// echo 'sim_files en Session es:';
				// print_r($documents_controller->Session->read("sim_files"));
				// echo '</pre>';

		}

		return count($docs);
	}
	//Retorna la cantidad de archivos que tienen igualdad de sha(content) en al menos 1 de los files del documento nuevo
	//Genera una variable "sim_files_sha" en Session, con la lista de documentos con igualdad de shas
	function findFilesShaCount($repo_id = null, $files = array(),$documents_controller) {
			if(is_null($repo_id)) {
			return null;
		}
		//echo '<pre>';
		//echo 'El files_tmp que le llego a findFilesSha es el siguiente';
		//print_r($files);
		//print_r($this->test_sha());
		//echo '</pre>';
		
				$docs = array();
		foreach ($files as $file) {
			//comparar content
			$tmp = $this->find('all', array(
			  		'conditions' => array(
						'SHA1(Attachfile.content)' => $file,
						'Document.repository_id' => $repo_id
					),
			  		'fields' => array('Attachfile.document_id')
				)
			);
			//echo '<pre>';
			//echo 'tmp sha tiene lo siguiente:';
			//print_r($tmp);
			//echo '</pre>';
			$hola = array();
			foreach($tmp as $t) {
				$hola[] = $t['Attachfile']['document_id'];
			}
			if(count($tmp)==0){return 0;}
			else{$docs[] = $hola;}
		}
				//echo '<pre>';
				//echo '$docs en sha es:';
				//print_r($docs);
				//echo '</pre>';
		
				if(count($docs) > 0) {
				$res = array();
			$foo= array();
			$k=0;
			for ($i = 0; $i < count($docs);$i++){
			    foreach($docs[$i] as $doc){
				$foo[$k]=$doc;
				$k++;
				}
			}
			$foo=array_unique($foo);
				//echo '<pre>';
				//echo '$foo en sha es:';
				//print_r($foo);
				//echo '</pre>';
				$foo=array_values($foo);
				$documents_controller->Session->write("sim_files_sha", $foo);
				 //echo '<pre>';
				 //echo 'sim_files_sha en Session es:';
				 //print_r($documents_controller->Session->read("sim_files_sha"));
				 //echo '</pre>';

		}
		
		return count($docs);
	}
	function fallar(){
		print("fail");
	}
	/*@rmeruane*/
	
	function findDocumentsByFilename($repo_id = null, $words = array()){
		if(is_null($repo_id)) {
			return null;
		}
		$docs = array();
		foreach ($words as $word) {
			$tmp = $this->find('all', array(
			  		'conditions' => array(
						'Attachfile.filename LIKE ' => '%'.$word.'%',
						'Document.repository_id' => $repo_id
					),
			  		'fields' => array('Attachfile.document_id')
				)
			);
				
			$hola = array();
			foreach($tmp as $t) {
				$hola[] = $t['Attachfile']['document_id'];
			}
			$docs[] = $hola;
		}
		if(count($docs) > 0) {
			$res = $docs[0];
			for ($i = 1; $i < count($docs); $i++) {
				$res = array_intersect($res, $docs[$i]);
			}
		} else {
			/*$res = $this->find('all', array(
				'conditions' => array('Document.repository_id' => $repo_id),
		  		'fields' => 'DISTINCT Tag.document_id',
				)
			);*/
		}
	
		return $res;
	}
	

	
	function test_sha(){
	return 8;
	}	

	/* This function replace not English characters to English */
	function normalize ($string) {
		$table = array(
		 'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
		 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
		 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
		 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
		 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
		 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
		 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
		 'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
		);
     
    	return strtr($string, $table);
 	}


	/*save attached files*/
	function saveAttachedFiles($data) {

		/* If there are not files, there is not problem */

		if(empty($data) || !isset($data['files']))
			return true;

		/* Get info files from data */

		$fileData = $data['files'];


		/* Get last created document id */
		$doc = $this->Document->query("select id from documents order by id desc limit 1");

		$doc = $doc[0];

		$document_id = $doc['documents']['id'];

		$ds = $this->getDataSource();
		$ds->begin($this);


		/* Save files from data */

		foreach ($fileData as $file){

			/**
			 * Data has some info if there was an error with the file, 
			 * if it's equal to 4, we continue with the next file 
			 */

			if($file['error']==4){
				continue;
			}

			/* Only save files with size > 0 */

			if($file['size'] > 0) {

				/* Get the extension from file name */
				
				$extension = end(explode('.', $file['name']));

				/**
				 * We normalize the file name, then there won't be problems
				 * saving the file 
				 */

				$filename = $this->normalize($file['name']);


				/* Set the file info */

				$this->create();
				$this->set(
				$attachfile = array(
					'Attachfile' => array(
						'name' => $filename,
						'location' => '/uploaded_files/document_'.$document_id,
						'activation_id' => 'A',
						'internalstate_id' => 'A',
						'document_id' => $document_id,
						'extension' => $extension						)
					)
				);

				if(!$this->save()){
					$ds->rollback($this);
					return false;
				}

				$ds->commit($this);

				/* This is the address wher the file will be saved */

				$directory = WWW_ROOT.'/uploaded_files/document_'.$document_id;

				if(!file_exists($directory))
					if(!mkdir($directory, 0700))
						return false;
				
				$filename_ = $directory.'/'.$filename;

				/* Read original file, and then write content in new file */

				$handle = fopen($file['tmp_name'],'r');
				$content = fread($handle, filesize($file['tmp_name']));
				fclose($handle);

				$handle = fopen($filename_, 'wr');
				fwrite($handle, $content);
				fclose($handle);

			}
		}
		return true;
	}

}
?>