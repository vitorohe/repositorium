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

<?php echo $this->element('fancybox'); ?>

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
  		foreach($documents_with_files as $cr):
  			
  	?>
  		<tr>
  			<td><?php echo $cr['Document']['id']; ?></td>
  			<td><?php echo $cr['Document']['name'];?></td>
  			<td><?php echo $cr['Document']['description'];?></td>
        <td>
          <?php
            if(!empty($cr['files'])) {
              echo '<br />';
              echo '<strong>Attached files:</strong> ';
              echo '<br />';
              echo '<ol style="margin: 0 0 0 10px;">';
              foreach ($cr['files'] as $file) {
                $extension =  $file['Attachfile']['extension'];
                echo '<li>';
                echo $file['Attachfile']['name'];
                if(strtolower($extension) == 'pdf'){
                  echo '<a class="fancybox" href="#inline1" title="" style="color:blue; font-size: 0.8em;">(View)</a>';
                  echo '<div id="inline1" style="width:980px; height: 500px; display: none;">
                          <object data="http://'.Configure::read('mywebroot').$file['Attachfile']['location'].'/'.$file['Attachfile']['name'].'" type="application/pdf" width="100%" height="99%">
                            <p>Your web browser doesn\'t have a PDF plugin.</p>
                          </object>
                        </div>';
                }
                else if(in_array(strtolower($extension), array('png', 'gif', 'bmp', 'jpg', 'jpeg'))){
                  echo '<a class="fancybox-effects-a" href="http://'.Configure::read('mywebroot').$file['Attachfile']['location'].'/'.$file['Attachfile']['name'].'" title="" style="color:blue; font-size: 0.8em;">(View)</a>';
                }
                echo '</li>';
              }
              echo '</ol>';

              if(count($cr['files']) === 1)
                  echo '<span class="ui-icon ui-icon-arrowthickstop-1-s" style="display:inline-block;"></span>'.$this->Html->link('Download attached files', array('controller' => 'documents', 'action' => 'getFile', $cr['Document']['name'], $cr['Document']['id']));
              

              else        
                  echo '<span class="ui-icon ui-icon-arrowthickstop-1-s" style="display:inline-block;"></span>'.$this->Html->link('Download attached files', array('controller' => 'documents', 'action' => 'getZip', $cr['Document']['name'], $cr['Document']['id']));
            }
          ?>

        </td>
  			<?php if(!$this->Session->check('Repository.current')){ ?>
  				<td><?php echo $cr['Repository']['id'];?></td>
  				<td><?php echo $this->Repo->link($cr['Repository']['name'], $cr['Repository']['internal_name']);?></td>
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