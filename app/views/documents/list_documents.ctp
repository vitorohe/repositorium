<?php 
$title = (isset($title) ? $title : ucwords($current));
$this->viewVars['title_for_layout'] = $title;
/* breadcrumbs */
//$this->Html->addCrumb('Manage', '/manage/');
$this->Html->addCrumb($title);
/* end breadcrumbs */ 
?>

<?php echo $this->Html->image('admin.png',array('class' => 'imgicon')) ; ?><h1 class="h1icon" style="margin-top: 15px;"><?php echo (isset($message) ? $message : $title); ?></h1>
<div class="clearicon"></div>


<?php echo $this->element('paginator_info'); ?>
<!-- core table -->
<table id="tabla_documentos" class="ui-widget ui-widget-content tabla" style="width: 100%">
  <thead>
	<tr class="ui-widget-header">
	  <th width="10%"><?php echo $this->Paginator->sort('Id', 'Document.id'); ?> </th>
	  <th width="15%"><?php echo $this->Paginator->sort('Title', 'Document.name'); ?></th>
	  <th width="30%"><?php echo $this->Paginator->sort('Content', 'Document.description'); ?></th>
    <th width="20%">Attached files</th>
	  <?php if(!$this->Session->check('Repository.current')){ ?>
	  	<th width="10%"><?php echo $this->Paginator->sort('Repository Id', 'Repository.id');?></th>
	  	<th width="15%"><?php echo $this->Paginator->sort('Repository Name', 'Repository.name');?></th>
	  <?php } ?>
	  <th width="20%">Options</th>
	</tr>
  </thead>
  <tbody>
  	<?php
  		foreach($documents as $cr):
  			
  	?>
  		<tr>
  			<td><?php echo $cr['Document']['id']; ?></td>
  			<td><?php echo $cr['Document']['name'];?></td>
  			<td><?php echo $cr['Document']['description'];?></td>
        <td><?php echo $cr['Document']['description'];?></td>
  			<?php if(!$this->Session->check('Repository.current')){ ?>
  				<td><?php echo $cr['Repository']['id'];?></td>
  				<td><?php echo $cr['Repository']['name'];?></td>
  			<?php }?>  			
  			<td>
  				<!-- options -->
				<div class="admin-doc-edit">
					<?php echo $this->Html->link('Edit', array('controller' => 'documents','action' => 'edit', $cr['Document']['id'])); ?>
					&nbsp; | &nbsp;   
					<?php echo $this->Html->link('Remove', array('controller' => 'documents','action' => 'remove', $cr['Document']['id']), array(), "Are you sure to delete this document!? "); ?>
				</div>  				
  			</td>
  		</tr>
  	<?php
  		endforeach;
  	?>
  </tbody>
</table>
<!-- end core table-->

<?php echo $this->element('paginator'); ?> 