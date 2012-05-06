<?php 
$title = 'Search Results'; 
$this->viewVars['title_for_layout'] = $title; 
?>

<?php echo $this->Html->image('docs.png',array('class' => 'imgicon')) ; ?>
<h1 class="h1icon" style="margin-top: 15px;"><?php echo $title; ?></h1>
<div class="clearicon"></div>

<?php if(empty($documents))
		echo '<span>There aren\'t results for: ';
	else 
		echo '<span>Search result for: ';
	foreach($criterias_name as $name){
		echo $name['Criteria']['name'] . ' ';
	}
	echo '</span>';
?>

<br />
<?php foreach($documents as $document){
	echo '<strong>Title:</strong> ' . $document['Document']['name'];
	echo '<br />';
	echo '<strong>Content:</strong> ' . $document['Document']['description'];
	echo '<br />';
	echo '<br />';
}?>
<?php foreach($document_with_files as $document_wf){
	echo '<strong>Title:</strong> ' . $document_wf['Document']['name'];
	echo '<br />';
	echo '<strong>Content:</strong> ' . $document_wf['Document']['description'];
	echo '<br />';
	echo '<strong>Attached file:</strong> ' . $document_wf['Attachfile']['name'];
	echo '<br />';
	echo '<br />';
}?>

</br>
</br>
</br>