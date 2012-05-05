<?php 
$title = 'Search'; 
$this->viewVars['title_for_layout'] = $title; 
?>

<?php echo $this->Html->image('docs.png',array('class' => 'imgicon')) ; ?>
<h1 class="h1icon" style="margin-top: 15px;"><?php echo $title; ?></h1>
<div class="clearicon"></div>

<?php if(empty($criterias)){
	echo '<span style="font-size:12pt">There aren\'t documents in this repository</span>';
} else {?>
<?php echo $this->Form->create('Criteria', array('action' => 'process')); ?>


<script>
$(function() {
    $( "#sortable1, #sortable2" ).sortable({
        connectWith: ".connectedSortable"
    }).disableSelection();

    $('form').submit(function(){ 
        $('#thedata').val($( "#sortable2" ).sortable("serialize"));
        //return false;
    });

});
</script>

<span style="font-size:12pt">Select Criterias for the search of documents</span>
<table class="criterias_selector" border="5" width="500">
    <tr>
        <th align="center"><strong>Criterias</strong></th>
        <th align="center"><strong>Chosen Criterias</strong></th>
    </tr>
    <tr>
        <td colspan="2">
            <div class="criterias">
                <ul id="sortable1" class="connectedSortable">
                    <?php
                        foreach ($criterias as $criteria) {
                            echo "<li class=\"ui-state-default\"";
                            echo "id =\"criterias_".$criteria['Criteria']['id']."\">";
                            echo $criteria['Criteria']['name'];
                            echo "</li>";
                        }
                        
                    ?>
                </ul>
            </div>
            <input type="hidden" name="data[Criteria][criterias]" id="thedata">
            <div class="chosen_criterias">
                <ul id="sortable2" name="hola" class="connectedSortable">
                </ul>
            </div>
        </td>
    </tr>
</table>
<!--FIN-->
<?php echo $this->Form->end('Search'); ?> 
<?php }?>


<script language="javascript" type="text/javascript">
	add_textboxlist("#TagSearch");
</script>