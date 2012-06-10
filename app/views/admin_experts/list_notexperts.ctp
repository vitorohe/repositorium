<?php
	$title = 'Manage Experts';
	$this->viewVars['title_for_layout'] = $title;
	/* breadcrumbs */
	if(isset($rep)){
		$this->Html->addCrumb($rep['Repository']['name'], '/repositories/'.$rep['Repository']['internal_name']);
	}
	$this->Html->addCrumb('Manage criterias', '/admin_criterias/');
	$this->Html->addCrumb($title);
	/* end breadcrumbs */ 
?>

<?php echo $this->Html->image('admin.png',array('class' => 'imgicon')) ; ?><h1 class="h1icon" style="margin-top: 15px;"><?php echo $title; ?></h1>
<div class="clearicon"></div>
<?php echo 
	  	$this->element($menu, array(
        	'current' => $current
		));       
?> 

<br />

<p style="font-size:1.2em;"><strong>Criteria name:</strong> <?php echo $criteria['Criteria']['name']; ?></p>
<p style="font-size:1.2em;"><strong>Criteria question:</strong> <?php echo $criteria['Criteria']['question']; ?></p>

<br />

<!-- core table -->
<?php if(!empty($users)){?>
<table id="tabla_documentos" class="ui-widget ui-widget-content tabla" style="width: 100%">
  <thead>
	<tr class="ui-widget-header">
	  <th width="250"><?php echo $this->Paginator->sort('Name', 'User.name'); ?></th>
	  <th width="450"><?php echo $this->Paginator->sort('Email', 'User.email'); 'Email'; ?></th>
	  <?php if($creator):?>
	  <th width="170" title=""><?php echo 'Set Expert'; ?></th>
	  <?php endif;?>
	</tr>
  </thead>
  <tbody>
  	<?php
  		foreach($users as $user):
  	?>
  		<tr>
  			<td><?php echo Sanitize::html($user['User']['name']); ?></td>
  			<td><?php echo Sanitize::html($user['User']['email']); ?></td>
  			<?php if($creator):?>
  			<td>
				<?php echo $this->Html->link('Set Expert', array('action' => 'setUserExpert', $criteria['Criteria']['id'], $user['User']['id'])); ?>
  			</td>
  			<?php endif;?>
  		</tr>
  	<?php
  		endforeach;
  	?>
  </tbody>
</table>
<!-- end core table-->
<?php echo $this->element('paginator_info'); ?>

<?php echo $this->element('paginator'); ?>
<?php } else {?>
<strong>All the users are experts in this Criteria</strong>
<?php }?>