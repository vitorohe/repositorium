<?php

if(isset($current)) {
	if(strcmp($current, 'criteria') == 0) {
		$classes['cr'] = 'current';
  	}
}
?>
<div class="admin-menu">
<?php								
echo $this->Form->radio('radiomenu',
	        				array('criteria' => 'Criterias'),
							array(
								'value' => $current , 
								'onClick' => 'document.location="'.$this->Html->url(array('controller' => 'admin_criterias', 'action' => 'listCriteriasUser')) .'";'));

echo $this->Form->radio('radiomenu',
							array('points' => 'Points'),
							array(
								'value' => $current ,
								'onClick' => 'document.location="'.$this->Html->url(array('controller' => 'admin_points', 'action' => 'listUsersPoints')) .'";'));
									
									
?>
</div>
<script>
	$(".admin-menu").buttonset();
</script>
