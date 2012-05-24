<?php 
$title = 'Search Results'; 
$this->viewVars['title_for_layout'] = $title; 
?>

<?php echo $this->Html->image('docs.png',array('class' => 'imgicon')) ; ?>
<h1 class="h1icon" style="margin-top: 15px;"><?php echo $title; ?></h1>
<div class="clearicon"></div>

<?php if(empty($documents_with_files))
		echo '<span>There aren\'t results for: ';
	else 
		echo '<span>Search result for: ';
	foreach($criterias_name as $name){
		echo $name['Criteria']['name'] . ' ';
	}
	echo '</span>';
?>

<br />
<br />

<?php 
	foreach($documents_with_files as $document){
		echo '<strong>Title:</strong> ' . $document['Document']['name'];
		echo '<br />';
		echo '<strong>Content:</strong> ' . $document['Document']['description'];
		
		if(!empty($document['files'])) {

			echo '<br />';
			echo '<strong>Attached files:</strong> ';
			echo '<br />';
			echo '<ol style="margin: 0 0 0 10px;">';
			foreach ($document['files'] as $file) {
				echo '<li>';
				echo '<a href="http://'.Configure::read('mywebroot').$file['Attachfile']['location'].'/document_'.$document['Document']['id'].'.'.$file['Attachfile']['extension'].'" target="_blank" title="'.$file['Attachfile']['name'].'">'.$file['Attachfile']['name'].'</a>';
				echo '</li>';
			}
			echo '</ol>';
		}
		echo '<br />';
		echo '<br />';
	}
?>

<br />
<br />
<br />