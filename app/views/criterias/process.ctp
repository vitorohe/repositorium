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
				echo $file['Attachfile']['name'];
				echo '</li>';
			}
			echo '</ol>';

			if(count($document['files']) === 1)
				echo '<span class="ui-icon ui-icon-arrowthickstop-1-s" style="display:inline-block;"></span>'.$this->Html->link('Download attached files', array('controller' => 'documents', 'action' => 'getFile', $document['Document']['name'], $document['Document']['id']));


			else        
				echo '<span class="ui-icon ui-icon-arrowthickstop-1-s" style="display:inline-block;"></span>'.$this->Html->link('Download attached files', array('controller' => 'documents', 'action' => 'getZip', $document['Document']['name'], $document['Document']['id']));

			
				
		}
		echo '<br />';
		echo '<br />';
	}
?>

<br />
<br />
<br />