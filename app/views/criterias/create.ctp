<?php
$title = "New Criteria";	
$this->viewVars['title_for_layout'] = $title;
$this->Html->addCrumb('Criterias', '/criterias');
$this->Html->addCrumb($title);
?>


<?php echo $this->Html->image('admin.png',array('class' => 'imgicon')) ; ?><h1 class="h1icon" style="margin-top: 15px;"><?php echo $title; ?></h1>
<div class="clearicon"></div>

<?php echo $this->Form->create('Criteria'); ?>

<?php echo $this->Form->input('name'); ?>

<?php echo $this->Form->input('question', array('label' => 'Question to ask in a challenge, for this criteria')); ?>

<?php echo $this->Form->input('questions_quantity', array('label' => 'Quantity of documents for a challenge')); ?>

<!-- source types for repo -->

<!-- 
<div class="select required">
	<label for="constituent_id">Select some modifiers for this Repository</label>
	<input type="hidden" name="data[Repository][Constituents][0]" value="0" id="RepositoryConstituents0">
	<?php
	# cgajardo: fix to persist "content" selection even when it's actually disabled, using javascript
	/*$constituents[0] = array(
		'name' => $constituents[0]." (required)",
		'value' => '0',
		'onClick' => 'this.checked=true'
	);
	echo $this->Form->input("Constituents", array("type"=>"select", "multiple"=>"checkbox", "default"=>"0", "options"=>$constituents));
	*/?>
</div> -->

<?php //echo $this->Form->input('min_points', array('label' => 'Minimum points assigned to each new user of this repository')); ?>

<?php echo $this->Form->input('download_score', array('label' => 'Cost (in points) of each document with this criteria to be downloaded', 'value' => 5)); ?>

<?php echo $this->Form->input('upload_score', array('label' => 'Cost (in points) of each document with this criteria to be uploaded', 'value' => 10)); ?>

<?php echo $this->Form->input('collaboration_score', array('label' => 'Score of this criteria to be given, when a user evaluates documents in challenges', 'value' => 5)); ?>

<?php //echo $this->Form->input('challenge_reward', array('label' => 'Amount of points to be rewarded after passing successfuly a challenge')); ?>
<br /><br />

<?php echo $this->Form->end('Done'); ?>


