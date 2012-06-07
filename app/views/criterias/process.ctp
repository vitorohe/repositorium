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
				echo '<a href="http://'.Configure::read('mywebroot').$file['Attachfile']['location'].'/'.$file['Attachfile']['name'].'" target="_blank" style="margin: 0 0 0 10px;">
						<span class="ui-icon ui-icon-arrowthickstop-1-s" style="display:inline-block;"></span>Download attached files</a>';

			else				
				echo '<a href="../documents/getZip?title='.$document['Document']['name'].'&id='.$document['Document']['id'].'" style="margin: 0 0 0 10px;">
						<span class="ui-icon ui-icon-arrowthickstop-1-s" style="display:inline-block;"></span>Download attached files</a>';

			
				
		}
		echo '<br />';
		echo '<br />';
	}
?>

<br />
<br />
<br />