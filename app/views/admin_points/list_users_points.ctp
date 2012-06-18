<?php
$title = "Administer $title";
$this->viewVars['title_for_layout'] = $title;

/* breadcrumbs */
if(isset($rep)){
	$this->Html->addCrumb($rep['Repository']['name'], '/repositories/'.$rep['Repository']['internal_name']);
	$this->Html->addCrumb('Manage', '/manage/');	
}
$this->Html->addCrumb($title);
/* end breadcrumbs */ 
	 

?>
<?php echo $this->Html->image('admin.png',array('class' => 'imgicon')) ; ?><h1 class="h1icon"><?php echo $title; ?></h1>
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

<!-- expert tools -->
<div id="expert-tools">
	<div class="adm-first-row">
		<!-- number of items -->	
		<div class="adm-limit">
			<?php echo $this->Form->create(null, array('url' => '/admin_points/listUsersPoints', 'name' => 'select_limit')); ?>
			<span class="adm-opt">Showing: </span>
			<?php			 
				$options = array(
					'5' => '5 users',
					'10' => '10 users',
					'20' => '20 users',
					'50' => '50 users' 
				);
				echo $this->Form->select('User.limit', $options, $limit, array('empty' => false, 'onChange' => 'select_limit.submit()'));	
				echo $this->Form->end();		   
			?>
			</form>
		</div>
		<!-- end number of items -->
		
		<!-- ordering
		<div class="adm-ordering">
			<?php echo $this->Form->create(null, array('url' => '/admin_documentos/'.$current, 'name' => 'ordering')); ?>
			<span class="adm-opt">Order by: </span>
			<?php
				$options = array(
					'more-ans' => 'More answers',
					'less-ans' => 'Less answers',
					'more-cs' => 'More consensus',
					'less-cs' => 'Less consensus'
				);						 
				echo $this->Form->select('CriteriasDocument.order', $options, $ordering, array('empty' => false, 'onChange' => 'ordering.submit()'));
				echo $this->Form->end(); 
			?>
		</div>
		end ordering -->
	</div>
	
	<div class="adm-second-row">
		<!-- select criteria -->
		<div class="adm-criteria">
			<?php echo $this->Form->create(null, array('url' => '/admin_points/listUsersPoints', 'name' => 'select_criterio')); ?>
			<span class="adm-opt">Criteria: </span>
			<?php			 
				echo $this->Form->select('Criteria.id', $criteria_list, $criteria_n, array('empty' => false, 'onChange' => 'select_criterio.submit()'));
				echo $this->Form->end(); 
			?>
		</div>
		<!-- end select criteria -->
		
		<!-- filter -->
		<div class="adm-filter">
			<?php //echo $this->Form->create(null, array('url' => '/admin_documentos/'.$current, 'name' => 'select_filter')); ?>
			<span class="adm-opt"> </span>
			<?php
				/*$options = array(
					'all' => 'All documents',
					'app' => 'Documents with 50% or more approval',
					'dis' => 'Documents with 50% or more disapproval'
				);						 
				echo $this->Form->select('CriteriasDocument.filter', $options, $filter, array('empty' => false, 'onChange' => 'select_filter.submit()'));
				echo $this->Form->end(); */
			?>
		</div>
		<!-- end filter -->

	</div>
</div>
<!-- end expert tools -->

<!-- core table -->
<table id="tabla_documentos" class="ui-widget ui-widget-content tabla" style="width: 100%">
  <thead>
	<tr class="ui-widget-header">
	  <th width="3%" style="text-align:center;font-size:9px">Id</th> 
	  <th width="20%">Name</th>
	  <th width="20%">Username</th>
	  <th width="27%">E-mail <?php /* echo $this->Paginator->sort('Statistics', "CriteriasDocument.total_respuestas_2_{$rest}"); */ ?></th>
	  <th width="30%">Add points</th>
	</tr>
  </thead>
  <tbody>
  <?php 
  	$i = 0;
  	foreach($users as $u):
  ?>
	<tr>
	<?php 
		echo $this->Form->create(null, array('url' => array('controller' => 'admin_points', 'action' => 'add_points')));
		echo $this->Form->hidden('User.id', array('value' => $u['User']['id']));
		echo $this->Form->hidden('Criteria.id', array('value' => $criteria_n));
	?>
		<td><?php echo $u['User']['id'];?></td>
		<td><?php echo $u['User']['name'];?></td>
		<td><?php echo $u['User']['username'];?></td>
		<td><?php echo $u['User']['email'];?></td>
		<td>
		<?php
			$str = 'Current Points: ';
			if(isset($crit_user[$u['User']['id']])){
				$str = $str.$crit_user[$u['User']['id']];
			}
			else {
				$str = $str.'0';
			}
			echo $str;
			echo $this->Form->input('User.points', array('value','label' => 'Points to add (or substract)'));
			echo $this->Form->button('Add');
			echo $this->Form->end(); 
		?>
		</td>
	</tr>  
  <?php 
  	$i += 1;
  	endforeach; 
  ?>
  </tbody>
</table>
<!-- end core table -->
</form>
<?php echo $this->element('paginator_info'); ?>

<?php echo $this->element('paginator'); ?> 
	

 
