<?php 
$title = 'Search Results'; 
$this->viewVars['title_for_layout'] = $title; 
?>

<?php echo $this->Html->image('docs.png',array('class' => 'imgicon')) ; ?>
<h1 class="h1icon" style="margin-top: 15px;"><?php echo $title; ?></h1>
<div class="clearicon"></div>

<?php if(empty($documents) && empty($document_with_files))
		echo '<span>There aren\'t results for: ';
	else 
		echo '<span>Search result for: ';
	foreach($criterias_name as $name){
		echo $name['Criteria']['name'] . ' ';
	}
	echo '</span>';
?>

<br />

<?php 
	foreach($all_documents as $document){
		echo '<strong>Title:</strong> ' . $document['Document']['name'];
		echo '<br />';
		echo '<strong>Content:</strong> ' . $document['Document']['description'];
		
		if($document['Attachfile']['name'] != '') {

			echo '<br />';
			echo '<strong>Attached file:</strong> ' . '<a href="http://'.Configure::read('mywebroot').$document['Attachfile']['location'].'/document_'.$document['Document']['id'].'.'.$document['Attachfile']['extension'].'" target="_blank" title="'.$document['Attachfile']['name'].'">'.$document['Attachfile']['name'].'</a>';
		}
		echo '<br />';
		echo '<br />';
	}
?>

<br />
<br />
<br />