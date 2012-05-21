<?php
echo $javascript->link('checker.js',false);
?>
<?php
$title = "Add new document";	
$this->viewVars['title_for_layout'] = $title;
$this->Html->addCrumb($title);
?>

<?php echo $this->Html->image('add_doc.png',array('class' => 'imgicon')) ; ?><h1 class="h1icon" style="margin-top: 15px;"><?php echo $title;?></h1>
<div class="clearicon"></div>

<fieldset class="datafields">
<?php echo $this->Form->create(null, array('url' => '/documents/upload', 'type' => 'file', 'inputDefaults' => array('error' => false)));?>
<?php echo $this->Form->input('Document.name', array('class' => 'ingresar-documento', 'label' => 'Title', 'default' => '', 'size' => 50, 'onChange'=>"CheckTitle(DocumentTitle.value)"));?> 
<?php echo $ajax->div('checked_title'); 
	  echo $ajax->divEnd('checked_title'); ?>
<?php
	//foreach($constituents as $constituent){
		echo $this->element('content'."/form", array('flag' => 'value'));
		
	//	if ($constituent=='content'){
			
			echo("<div id='checked_content'></div>");
	//	}
	//	if ($constituent=='attachFile'){
		echo $this->element('attachFile'."/form", array('flag' => 'value'));
		echo("<div id='checked_attachFile'></div>");
	//	}
	//}
	//DocumentFileAttach.value
	//echo '<input type="button" id="file_aux_button" name="file_aux_button" value="Check files"  OnClick="CheckFile(DocumentTitle.value)" />';
?>

</br>
</br>
</br>

<!--INI-->

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

<!--<table class="criterias_selector" border="5" width="500">
    <tr>
        <th align="center"><strong>Criterias</strong></th>
        <th align="center"><strong>Chosen Criterias</strong></th>
    </tr>
    <tr>
        <td colspan="2">
            <div class="criterias">
                <ul id="sortable1" class="connectedSortable">
                    <?php
                        //foreach ($criterias as $criteria) {
                            //echo "<li class=\"ui-state-default\"";
                            //echo "id =\"criterias_".$criteria['Criteria']['id']."\">";
                            //echo $criteria['Criteria']['name'];
                            //echo "</li>";
                        //}
                        
                    ?>
                </ul>
            </div>
            <input type="hidden" name="data[Document][criterias]" id="thedata">
            <div class="chosen_criterias">
                <ul id="sortable2" name="hola" class="connectedSortable">
                </ul>
            </div>
        </td>
    </tr>
</table>-->
<!--FIN-->

<!--Search box-->
<div id="container">
    <div class="ui-grid ui-widget ui-widget-content ui-corner-all">
     
        <div class="ui-grid-header ui-widget-header ui-corner-top clearfix">

            <div class="header-right">
                <!-- Left side of table header -->
            </div>

            <div class="header-left">
                Search: <input style="width:150px;" id="searchData" type="text"></div>
            </div>

        <table class="ui-grid-content ui-widget-content" id="cStoreDataTable">
            <thead>
                <tr>
                    <th class="ui-state-default" width="50%">Criterias</th>
                    <th class="ui-state-default" width="50%">Chosen Criterias</th>
                </tr>
            </thead>
            <tbody id="results">
                <tr>
                    <td>
                        <div id="msg">
                        </div>
                        <ul id="sortable1" name="hola" class="connectedSortable">
                        </ul>
                    </td>
                    <input type="hidden" name="data[Criteria][criterias]" id="thedata">
                    <td>
                        <ul id="sortable2" name="hola" class="connectedSortable">
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="ui-grid-footer ui-widget-header ui-corner-bottom">
            <div class="grid-results">Results </div>
        </div>
    </div>
</div>
<!--Search box-->

<?php echo $this->Form->end('Done'); ?>
</fieldset>

<br />

<!-- 
<div class="ui-widget">
	<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 10px .7em;"> 
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Hey!</strong> You may add more tags separating them by commas (,)</p>
	</div>
</div>
-->