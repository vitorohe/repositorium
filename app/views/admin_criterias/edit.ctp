
<?php
$this->viewVars['title_for_layout'] = $title;
$this->Html->addCrumb('Criterias', '/admin_criterias');
$this->Html->addCrumb($title);
?>


<?php echo $this->Html->image('admin.png',array('class' => 'imgicon')) ; ?><h1 class="h1icon" style="margin-top: 15px;"><?php echo $title; ?></h1>
<div class="clearicon"></div>

<?php echo 
	   $this->element($menu, array(
		 'isLogged' => $this->Session->check('User.id'), 
		 'isAdmin' => $this->Session->check('User.isAdmin'),
		 'isExpert' => false,
         'current' => $current
	   ));       
?> 

<?php echo $this->Form->create(); ?>

<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>

<?php echo $this->Form->input('name'); ?>

<?php echo $this->Form->input('question', array('label' => 'Question to ask in a challenge, for this criteria')); ?>

<?php echo $this->Form->input('download_score', array('label' => 'Cost (in points) of each document with this criteria to be downloaded')); ?>

<?php echo $this->Form->input('upload_score', array('label' => 'Cost (in points) of each document with this criteria to be uploaded')); ?>

<?php echo $this->Form->input('collaboration_score', array('label' => 'Score of this criteria to be given, when a user evaluates documents in challenges')); ?>

<?php echo $this->Form->input('score_obtained', array('label' => 'Quantity of points'));?>
<br /><br />
<!--  
<div name="ponderation_div" class="yui-u padded"> 
<label for="ponderation_div"> <strong>Duplicate Data Control</strong> </label>
<label for="ponderation_elements">Score given to each new document if there is already a similar entry in this Repositorium. Upon reaching a score of 100 the document is considered to be a duplicate and labeled as such.</label>
<?php echo $this->Form->input('pdr_tittle', array('label' => 'Points added if title is similar')); ?>
<?php echo $this->Form->input('pdr_tags', array('label' => 'Points added when is identical tags')); ?>
<?php echo $this->Form->input('pdr_text', array('label' => 'Points added when text is similar')); ?>
<?php echo $this->Form->input('pdr_files', array('label' => 'Points added for each file already on the repository')); ?>
</div>
<br />-->

<?php echo $this->Form->end('Done'); ?>
