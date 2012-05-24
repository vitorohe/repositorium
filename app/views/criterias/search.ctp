<?php 
$title = 'Search'; 
$this->viewVars['title_for_layout'] = $title; 
?>

<?php echo $this->Html->image('docs.png',array('class' => 'imgicon')) ; ?>
<h1 class="h1icon" style="margin-top: 15px;"><?php echo $title; ?></h1>
<div class="clearicon"></div>


<script>
$(function() {
    var mycounter = 0;
    var amount = 0;
    var total = 0;
    $( "#sortable1, #sortable2" ).sortable({
        connectWith: ".connectedSortable",    
    }).disableSelection();

    $("#sortable2").sortable({
        update: function(event, ui) {
            mycounter = 0;
            amount = 0;
            if($('#amount').val() == "")
                amount = 0;
            else
                amount = parseInt($('#amount').val());
            $('#sortable2 li').each(function(index){
                var start = $(this).text().indexOf('-')+1;
                var end = $(this).text().indexOf('points')-1;
                mycounter +=parseInt($(this).text().substring(start, end));
                $('#counter').val(mycounter);
                total = mycounter*amount;
                $('#total').val(total);
            });
            if(mycounter == 0) {
                $('#counter').val(mycounter);
                total = mycounter*amount;
                $('#total').val(total);
            }
        }
    });

    $('form').submit(function(){ 
        $('#thedata').val($( "#sortable2" ).sortable("serialize"));
        //return false;
    });

});
</script>

<script>
    $(function() {

        $( "#dialog:ui-dialog" ).dialog( "destroy" );
    
        $( "#dialog-confirm" ).dialog({
            autoOpen: false,
            resizable: false,
            height:160,
            modal: true,
            buttons: {
                "Search": function() {
                    document.getElementById('CriteriaProcessForm').submit();
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            }
        });

        $('form#CriteriaProcessForm').submit(function(e){
            e.preventDefault();
            $("p#total-dialog").text($("#total").val());
            $('#dialog-confirm').dialog('open');
        });

    });
</script>


<?php if(empty($criterias)){
	echo '<span style="font-size:12pt">There aren\'t documents in this repository</span>';
} else {?>
<?php echo $this->Form->create('Criteria', array('action' => 'process')); ?>

<span style="font-size:12pt">Select Criterias for the search of documents</span>

<br />
<br />

<p>Please, drag and drop to choose a criteria</p>

<br />
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
                    <th class="ui-state-default" width="33%">Criterias</th>
                    <th class="ui-state-default" width="33%">Chosen Criterias</th>
                    <th class="ui-state-default" width="34%">Details</th>
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
                    <td>
                        <label for="amount">Amount of documents:</label>
                        <input type="text" id=
                        "amount" value="3"/>
                        <label for="counter">Total points (1 document):</label>
                        <input type="text" disabled="disabled" id=
                        "counter" value="0"/>
                        <label for="total">Total points to spend:</label>
                        <input type="text" disabled="disabled" id="total" value="0"/>
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
<div id="dialog-confirm" title="Search documents">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>You are going to spend <p id="total-dialog" style="color: red; font-size:1.5em;"></p> points. Are you sure?</p>
</div>
<?php echo $this->Form->submit('Search'); ?> 
<?php }?>
