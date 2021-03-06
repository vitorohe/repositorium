<?php
	$title = 'Manage criterias';
	$this->viewVars['title_for_layout'] = $title;
	/* breadcrumbs */
	//$this->Html->addCrumb('Manage', '/manage/');
	if(isset($rep)){
		$this->Html->addCrumb($rep['Repository']['name'], '/repositories/'.$rep['Repository']['internal_name']);
	}
	$this->Html->addCrumb($title);
	/* end breadcrumbs */ 
?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#adm-new-criteria").click(function(e) {
			e.preventDefault();
			$(window.location).attr('href', '<?php echo $this->Html->url(array('controller' => 'criterias', 'action' => 'add'));?>');
		});	
	});	
</script>

<?php echo $this->Html->image('admin.png',array('class' => 'imgicon')) ; ?><h1 class="h1icon" style="margin-top: 15px;"><?php echo $title; ?></h1>
<div class="clearicon"></div>

<?php 
	if(isset($rep)){
		echo 
		  	$this->element($menu, array(
	        	'current' => $current
			));
	} 
	else {
		echo 
		  	$this->element('menu_expert_mini', array(
	        	'current' => $current
			)); 
	}
?> 


<div id="expert-tools">
	<!-- number of items -->
	<div class="adm-limit" style="float: left">
		<?php echo $this->Form->create(null, array('url' => '/admin_criterias/', 'name' => 'select_limit')); ?>
		<span class="adm-opt">Show: </span>
		<?php			 
			$options = array(
				'5' => '5 criteria',
				'10' => '10 criteria',
				'20' => '20 criteria',
				'50' => '50 criteria' 
			);

			echo $this->Form->select('Criteria.limit', $options, $limit, array('empty' => false, 'onChange' => 'select_limit.submit()'));			   
		?>
		</form>
	</div>
	<!-- end number of items -->
</div>


<!-- core table -->
<table id="tabla_documentos" class="ui-widget ui-widget-content tabla" style="width: 100%">
  <thead>
	<tr class="ui-widget-header">
	  <th width="250"><?php echo $this->Paginator->sort('Name', 'Criteria.name'); ?></th>
	  <th width="500"><?php echo $this->Paginator->sort('Question', 'Criteria.question'); ?></th>
	  <th width="180" title=""><?php echo $this->Paginator->sort('Upload score', 'Criteria.upload_score'); ?></th>
	  <th width="180"><?php echo $this->Paginator->sort('Download score', 'Criteria.download_score');?></th>
	  <th width="180"><?php echo $this->Paginator->sort('Collaboration score', 'Criteria.collaboration_score');?></th>
	  <th width="180"><?php echo $this->Paginator->sort('Points', 'CriteriasUser.score_obtained');?></th>
	  <th width="200">Options</th>
	</tr>
  </thead>
  <tbody>
  	<?php
  		foreach($criterias as $cr):
  	?>
  		<tr>
  			<td><?php echo $this->Html->link(Sanitize::html($cr['Criteria']['name']), array('action' => 'edit', $cr['Criteria']['id']));?></td>
  			<td><?php echo $this->Html->link(Sanitize::html($cr['Criteria']['question']), array('action' => 'edit', $cr['Criteria']['id']));?></td>
  			<td><?php echo $cr['Criteria']['upload_score'];?></td>
  			<td><?php echo $cr['Criteria']['download_score'];?></td>
  			<td><?php echo $cr['Criteria']['collaboration_score'];?></td>
  			<td><?php echo $cr['CriteriasUser']['score_obtained'];?></td>
  			<td>
  				<!-- options -->
				<div class="admin-doc-edit">
					<?php echo $this->Html->link('Edit', array('action' => 'edit', $cr['Criteria']['id'])); ?>
					&nbsp; | &nbsp;   
					<?php echo $this->Html->link('Remove', array('action' => 'remove', $cr['Criteria']['id']), array(), "Are you sure to delete this criteria?"); ?>
					<br>   
					<?php echo $this->Html->link('Manage Experts', array('controller' => 'admin_experts', 'action' => 'list_notexperts', $cr['Criteria']['id'])); ?>
				</div>  				
  			</td>
  		</tr>
  	<?php
  		endforeach;
  	?>
  </tbody>
</table>
<!-- end core table-->
<?php echo $this->element('paginator_info'); ?>

<?php echo $this->element('paginator'); ?> 