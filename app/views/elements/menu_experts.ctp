<?php

if(isset($current)) {
  if(strcmp($current, 'not_experts') == 0) {
	$classes['ne'] = 'current';
  } else if(strcmp($current, 'experts') == 0) {
	$classes['e'] = 'current';
  }
}
?>
<div class="admin-menu">
<?php
echo $this->Form->radio('radiomenu',
        					array('not_experts' => 'Not Experts'),
							array(
								'value' => $current , 
								'onClick' => 'document.location="'.$this->Html->url(array('controller' => 'admin_experts', 'action' => 'list_notexperts', $criteria['Criteria']['id'])) .'";'));
								
								
echo $this->Form->radio('radiomenu',
        					array('experts' => 'Experts'),
							array(
								'value' => $current , 
								'onClick' => 'document.location="'.$this->Html->url(array('controller' => 'admin_experts', 'action' => 'list_experts', $criteria['Criteria']['id'])) .'";'));
	
									
?>
</div>
<script>
	$(".admin-menu").buttonset();
</script>
