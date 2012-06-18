
<?php
$title = "Edit Repository";	
$this->viewVars['title_for_layout'] = $title;
$this->Html->addCrumb('Repositories', '/admin_repositories');
$this->Html->addCrumb($title);
?>


<?php echo $this->Html->image('admin.png',array('class' => 'imgicon')) ; ?><h1 class="h1icon" style="margin-top: 15px;"><?php echo $title; ?></h1>
<div class="clearicon"></div>

<?php echo 
	   $this->element('menu_admin', array(
		 'isLogged' => $this->Session->check('User.id'), 
		 'isAdmin' => $this->Session->check('User.isAdmin'),
		 'isExpert' => false,
         'current' => $current
	   ));       
?> 

<?php echo $this->Form->create(); ?>

<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>

<?php echo $this->Form->input('user_id', array('type' => 'hidden')); ?>

<?php echo $this->Form->input('name'); ?>

<?php echo $this->Form->input('internal_name', array('label' => 'URL of this repository: ______.repositorium.cl')); ?>

<?php echo $this->Form->input('description'); ?>

<br />

<?php if(empty($restrictions)) { ?>

	<?php echo $this->Form->checkbox('restrictions', array('value' => 'attachfile', 'hiddenField' => false)); ?>

	Attach File: Allow users to attach files to Document

	<?php echo $this->Form->input('max_documents', array('label' => 'Maximum amount of files to attach on a document (0: unlimited):', 'value' => 0)); ?>

	<?php echo $this->Form->input('max_size', array('label' => 'Maximum of file size (MB) (0: unlimited):', 'value' => 0)); ?>

	<?php echo $this->Form->input('extension', array('label' => 'File extension (eg:"jpg" or "jpg,bmp,gif") (*: any extension):', 'value' => '*')); ?>

<?php } else { ?>

	<?php echo $this->Form->checkbox('restrictions', array('value' => 'attachfile', 'hiddenField' => false, 'checked' => 'checked')); ?>

	Attach File: Allow users to attach files to Document

	<?php echo $this->Form->input('max_documents', array('label' => 'Maximum amount of files to attach on a document (0: unlimited):', 'value' => $restrictions['RepositoryRestriction']['amount'])); ?>

	<?php echo $this->Form->input('max_size', array('label' => 'Maximum of file size (MB) (0: unlimited):', 'value' => $restrictions['RepositoryRestriction']['size'])); ?>

	<?php echo $this->Form->input('extension', array('label' => 'File extension (eg:"jpg" or "jpg,bmp,gif") (*: any extension):', 'value' => $restrictions['RepositoryRestriction']['extension'])); ?>

<?php } ?>

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
