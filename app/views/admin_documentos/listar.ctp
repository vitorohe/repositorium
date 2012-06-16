<?php App::import('Sanitize'); ?>
<script type="text/javascript">	
	$(document).ready(function() {		
		$("#select-all").click(function() {
			var checked = this.checked;
			$(".adm-checkbox-form").each(function() {
				this.checked = checked;
				update_checked($(this));
			}).button("refresh");
		});		
		$("#adm-mass-reset").click(function(e) {			
			 e.preventDefault();
			 $("#ActionMassAction").val("reset");
			 var form = $(this).parent("form");
			 var ok = confirm("Are you sure to reset stats of selected documents?");
			 if(ok)
			 	form.submit();			 
		});
		$("#adm-mass-validate").click(function(e) {			
			 e.preventDefault();
			 $("#ActionMassAction").val("validate");
			 var form = $(this).parent("form");
			 var ok = confirm("Are you sure to validate the selected documents?");
			 if(ok)
			 	form.submit();			 
		});
		$("#adm-mass-invalidate").click(function(e) {			
			 e.preventDefault();
			 $("#ActionMassAction").val("invalidate");
			 var form = $(this).parent("form");
			 var ok = confirm("Are you sure to invalidate the selected documents?");
			 if(ok)
			 	form.submit();			 
		});
		$("#adm-mass-delete").click(function(e) {			
			 e.preventDefault();
			 $("#ActionMassAction").val("delete");
			 var form = $(this).parent("form");
			 var ok = confirm("Are you sure to delete selected documents?");
			 if(ok)
			 	form.submit();			 
		});

		//Add Style the checkboxes
		$("#select-all, .adm-checkbox-form").button({icons: {
		                primary: "ui-icon-check"
				            }, text: false}).addClass("adm-checkbox-form");
				            
		//Add Hover functions to rows		
		$("#tabla_documentos tbody tr").hover(hover_tr, hover_tr_out);
		
		$('#tabla_documentos tbody :checkbox').click(function() {
			update_checked($(this));
		});
		
	});

	function hover_tr(){
		$(this).addClass("table-hover");
	}
	
	function hover_tr_out(){
		$(this).removeClass("table-hover");
	}

	function update_checked(item){	
		if(item.attr("checked"))	
			item.parent().parent().parent().addClass('table-hover-checked');
		else
			item.parent().parent().parent().removeClass('table-hover-checked');
	}
</script>
<?php
	function porcentaje($q,$tot) {
		if($tot == 0)
			return 0;
		  return 100*$q/($tot);
		}
	function consenso($a,$b){
		if(($a + $b) == 0)
		  return 0;
		return 100*abs($a-$b)/($a+$b);
	}
	
	$en_valid = (strcmp($current,'no_validados') == 0) ? false : true; 

	if(!$en_valid) {
		$title = 'Pending validation documents';
	} else {
		if(strcmp($current,'all') == 0)
			$title = 'All documents';
		else{
			if(strcmp($current,'validados') == 0)
				$title = 'Validated Documents';
				else{$title = 'Warned Documents';}
			}
	}
	if(!$en_valid)
		$rest = 'no_validado';
	else
		$rest = 'como_desafio';

	$this->viewVars['title_for_layout'] = "Administer $title";
	
	/* breadcrumbs */
	$this->Html->addCrumb($repo['Repository']['name'], '/repositories/'.$repo['Repository']['internal_name']);
	$this->Html->addCrumb('Manage', '/manage/');	
	$this->Html->addCrumb($title);
	/* end breadcrumbs */ 
	 

?>
<?php echo $this->Html->image('admin.png',array('class' => 'imgicon')) ; ?><h1 class="h1icon"><?php echo $title; ?></h1>
<div class="clearicon"></div>
<?php echo 
	   $this->element($menu, array(
         'current' => $current
	   ));       
?> 

<!-- expert tools -->
<div id="expert-tools">
	<div class="adm-first-row">
		<!-- number of items -->	
		<div class="adm-limit">
			<?php echo $this->Form->create(null, array('url' => '/admin_documentos/'.$current, 'name' => 'select_limit')); ?>
			<span class="adm-opt">Showing: </span>
			<?php			 
				$options = array(
					'5' => '5 documents',
					'10' => '10 documents',
					'20' => '20 documents',
					'50' => '50 documents' 
				);
				echo $this->Form->select('Document.limit', $options, $limit, array('empty' => false, 'onChange' => 'select_limit.submit()'));			   
			?>
			</form>
		</div>
		<!-- end number of items -->
		
		<!-- ordering -->
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
		<!-- end ordering -->
	</div>
	
	<div class="adm-second-row">
		<!-- select criteria -->
		<div class="adm-criteria">
			<?php echo $this->Form->create(null, array('url' => '/admin_documentos/'.$current, 'name' => 'select_criterio')); ?>
			<span class="adm-opt">Criteria: </span>
			<?php			 
				echo $this->Form->select('id', $criterio_list, $criterio_n, array('empty' => false, 'onChange' => 'select_criterio.submit()'));
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
				
		<!-- mass edit -->
		<div class="adm-mass">
		<?php echo $this->Form->create(null, array('id' => 'adm-process', 'url' => array('controller' => 'admin_documentos', 'action' => 'mass_edit'))); ?>	
			<span class="adm-opt">Selected Documents: </span>
			<?php		
				echo $this->Form->hidden('Action.mass_action');
				echo '&nbsp;&nbsp;&nbsp;';
				echo $this->Form->button('Reset stats', array('id' => 'adm-mass-reset'));
				echo '&nbsp;&nbsp;&nbsp;';
				echo $this->Form->button('Invalidate', array('id' => 'adm-mass-invalidate'));
				echo '&nbsp;&nbsp;&nbsp;';
				echo $this->Form->button('Validate', array('id' => 'adm-mass-validate'));
			?>
		</div>
		<!-- end mass edit-->	
	</div>
</div>
<!-- end expert tools -->

<!-- core table -->
<table id="tabla_documentos" class="ui-widget ui-widget-content tabla" style="width: 100%">
  <thead>
	<tr class="ui-widget-header">
	  <th width="3%" style="text-align:center;font-size:9px" class="clickable"><input type="checkbox" id="select-all" /><label for="select-all">select</label></th> 
	  <th width="50%">Document</th>
	  <th width="20%">Attached files</th>
	  <th width="27%">Statistics <?php /* echo $this->Paginator->sort('Statistics', "CriteriasDocument.total_respuestas_2_{$rest}"); */ ?></th>
	</tr>
  </thead>
  <tbody>
  <?php 
  	$i = 0;
  	foreach($data_with_files as $d):
  		$id = $d['Document']['id'];
  		$c_id = $d['Criteria']['id'];	
  ?>
	<tr>
		<td class="clickable"><div class="adm-checkbox" style="font-size:9px"><?php echo $this->Form->checkbox('Document.'.$i.'.id', array('value' => $id.' '.$c_id, 'class' => 'adm-checkbox-form')); 
						    echo "<label for='Document".$i."Id'>check</label>"; ?></div></td>
		<td>
			<!-- doc -->
			<span class="admin-doc-titulo">
				<?php echo $this->Html->link(Sanitize::html($d['Document']['name']), array('action' => 'edit', $id, $criterio_n), array('escape' => false)) ;?>
			</span>
			<div class="admin-doc-texto">		
				<?php
				echo $this->Text->truncate(
					str_replace(
						'\n', 
						'<br />', 
						Sanitize::html($d['Document']['description'])), 
					350, 
					array(
						'ending' => '<a href="'.$this->Html->url(array('controller' => 'admin_documentos', 'action' => 'edit', $id, $criterio_n)).'">...</a>', 
						'exact' => false, 
						'html' => true));
				?>				
			</div>
			<div class="created-by">
				Created on <?php echo $d['Document']['register_date']; ?> by <?php echo $d['User']['username']; ?>. 
				<br>From Criteria: <?php echo $d['Criteria']['name']?>
				<br><?php 
				if($d['CriteriasDocument']['answer'] == 1){
					echo 'Validated'; 
				}else if($d['CriteriasDocument']['answer'] == 2){
					echo 'Invalidated';
				}else{
					echo 'Without evaluation';
				} ?>
			</div>
		</td>
		<td>
			<?php
				if(!empty($d['files'])) {
					echo '<br />';
					echo '<strong>Attached files:</strong> ';
					echo '<br />';
					echo '<ol style="margin: 0 0 0 10px;">';
					foreach ($d['files'] as $file) {
						echo '<li>';
						echo $file['Attachfile']['name'];
						echo '</li>';
					}
					echo '</ol>';

					if(count($d['files']) === 1)
						echo '<span class="ui-icon ui-icon-arrowthickstop-1-s" style="display:inline-block;"></span>'.$this->Html->link('Download attached files', array('controller' => 'documents', 'action' => 'getFile', $d['Document']['name'], $d['Document']['id']));


					else        
						echo '<span class="ui-icon ui-icon-arrowthickstop-1-s" style="display:inline-block;"></span>'.$this->Html->link('Download attached files', array('controller' => 'documents', 'action' => 'getZip', $d['Document']['name'], $d['Document']['id']));
				}
			?>
		</td>
		<td>
			<!-- consenso -->
			<?php				
				// convencion............. 1 = no, 2 = si
				$no = $d['CriteriasDocument']["no_eval"];
				$si = $d['CriteriasDocument']["yes_eval"];
				$tot = $no + $si;
				
				$pno = porcentaje($no, $tot);
				$psi = porcentaje($si, $tot);				
			?>
			<?php if($d['CriteriasDocument']['total_eval']>0): ?>
			<div style="width: 95%; clear:both; margin: 0 auto; height: 2em">
				<div style="float:left;"><?php echo 'Yes ('.$this->Number->precision($psi, 1).'%)'; ?></div>
				<div style="float:right;"><?php echo 'No ('.$this->Number->precision($pno, 1).'%)'; ?></div>				
			</div>		
			<div class="progressbar-doc-<?php echo $id; ?>" style="width: 95%; margin: 0 auto;background-image:none;background-color:#E79A3D"></div>
			<script>$('.progressbar-doc-<?php echo $id; ?>').progressbar({value: <?php echo $psi; ?>});</script>
			<div style="text-align:center;width:95%;clear:both; margin: 0 auto; height: 2em">
				<?php echo $d['CriteriasDocument']['total_eval'] ; ?> 
				answers<?php //if($d['CriteriasDocument']['answer']==1){echo ", official ".((0==0)?("No"):("Yes")) ;} ?>
			</div>
			<?php else: ?>
				<div style="text-align:center">
					There is no data to display yet...
				</div>
			<?php endif;?>
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
	

 
