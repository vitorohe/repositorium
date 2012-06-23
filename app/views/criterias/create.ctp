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

<?php echo $this->Form->input('questions_quantity', array('label' => 'Quantity of documents for a challenge', 'value' => 3)); ?>

<?php echo $this->Form->input('download_score', array('label' => 'Cost (in points) of each document with this criteria to be downloaded', 'value' => 5)); ?>

<?php echo $this->Form->input('upload_score', array('label' => 'Cost (in points) of each document with this criteria to be uploaded', 'value' => 5)); ?>

<?php echo $this->Form->input('collaboration_score', array('label' => 'Score of this criteria to be given, when a user evaluates documents in challenges', 'value' => 10)); ?>

<br />
<br /><br />

<?php echo $this->Form->end('Done'); ?>


