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

<script type="text/javascript" language="JavaScript">
function AskAndSubmit(t)
{
  var answer = confirm("Are you sure you want to do this?");
  if (answer)
  {
    t.form.submit();
  }
  else
    return false;
}
</script>

<span style="font-size:12pt">Select Criterias for the search of documents</span>

<br />
<br />

<p>Please, drag and drop to choose a criteria</p>

<br />
<!--Search box-->
<div id="container">
    <div class="ui-grid ui-widget ui-widget-content ui-corner-all">
     
        <div class="ui-grid-header ui-widget-header ui-corner-top clearfix">

            <div class="header-left">
                <!-- Left side of table header -->
            </div>

            <div class="header-right">
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

<?php echo $this->Form->submit('Search', array('onclick' => 'AskAndSubmit(this)')); ?> 
<?php }?>
