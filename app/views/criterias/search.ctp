<?php 
$title = 'Search'; 
$this->viewVars['title_for_layout'] = $title; 
?>

<?php echo $this->Html->image('docs.png',array('class' => 'imgicon')) ; ?>
<h1 class="h1icon" style="margin-top: 15px;"><?php echo $title; ?></h1>
<div class="clearicon"></div>

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
	echo '<span style="font-size:12pt">There aren\'t validated documents in this repository</span>';
} else {?>
<?php echo $this->Form->create('Criteria', array('action' => 'process')); ?>

<span style="font-size:12pt">Select Criterias and/or Categories for the search of documents</span>

<br />
<br />

<p>Please, drag and drop to choose a criteria or category</p>

<br />
<!--Search box-->
<div id="container">
    <div class="ui-grid ui-widget ui-widget-content ui-corner-all">
     
        <div class="ui-grid-header ui-widget-header ui-corner-top clearfix">
			
			<div class="header-left">
                Search term: <input style="width:150px;" id="searchData" type="text"></div>
            
        </div>

        <table class="ui-grid-content ui-widget-content" id="cStoreDataTable">
            <thead>
                <tr>
                    <th class="ui-state-default" width="20%">Categories</th>
              		<th class="ui-state-default" width="20%">Chosen Categories</th>
                    <th class="ui-state-default" width="20%">Criterias</th>
                    <th class="ui-state-default" width="20%">Chosen Criterias</th>
                    <th class="ui-state-default" width="20%">Details</th>
                </tr>
            </thead>
            <tbody id="results">
                <tr>
                    <td>
                        <div id="msgc">
                        </div>
                        <ul id="sortable3" name="hola" class="connectedSortablec">
                            <?php
                                $categories_names = $this->Session->read('categories_names');
                                $categories_ids = $this->Session->read('categories_ids');
                                $categories_points = $this->Session->read('categories_points');                        

                                $i=0;
                                foreach ($categories_names as $categories) {
                                    echo "<li class=\"ui-state-default\" ";
                                    echo "id=\"categories_".$categories_ids[$i++]."\" >";
                                    echo key($categories_names)." - ".$categories_points[key($categories_names)]." points ";
                                    echo "</li>";
                                    if($i > 8)
                                        break;
                                }
                            ?>
                        </ul>
                    </td>
                    <input type="hidden" name="data[Criteria][categories]" id="thedata2">
                    <td>
                        <ul id="sortable4" name="hola" class="connectedSortablec">
                        </ul>
                    </td>
                    <td>
                        <div id="msg">
                        </div>
                        <ul id="sortable1" name="hola" class="connectedSortable">
                            <?php
                                $criterias_names = $this->Session->read('criterias_names');
                                $criterias_ids = $this->Session->read('criterias_ids');
                                $criterias_points = $this->Session->read('criterias_points');
                            
                                $i=0;
                                foreach ($criterias_names as $criteria) {
                                    echo "<li class=\"ui-state-default\" ";
                                    echo "id=\"criterias_".$criterias_ids[$i]."\" >";
                                    echo $criteria." - ".$criterias_points[$i++]." points ";
                                    echo "</li>";
                                    if($i > 8)
                                        break;
                                }
                            ?>
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
                        "amount" name="data[Payed_search][documents_amount]" value="1"/>
                        <label for="counter">Total points (1 document):</label>
                        <input type="text" disabled="disabled" id=
                        "counter" value="0"/>
                        <label for="total">Total points to spend:</label>
                        <input type="text" disabled="disabled" id="total" name="data[Payed_search][toal_spent_points]" value="0"/>
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
