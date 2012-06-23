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
            height:300,
            width:350,
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
            var text_dialog = "";
            if($('#amount').val() == "") {
                amount = 0;
                text_dialog +="\t\t  0 points";
            }
            else {
                text_dialog += "Criterias:\n";

                amount = parseInt($('#amount').val());
                $('#sortable2 li').each(function(index){
                    var start = $(this).text().indexOf('-')+1;
                    var end = $(this).text().indexOf('points')-1;
                    mycounter = parseInt($(this).text().substring(start, end));
                    var criteria = $(this).text().substring(0, start-2);
                    text_dialog += "   " + criteria + ":\n\t" + mycounter + " points x " + amount + " = " + mycounter*amount + " points\n";
                });

                text_dialog += "Categories:\n";

                $('#sortable4 li').each(function(index){
                    var start = $(this).text().indexOf('-')+1;
                    var end = $(this).text().indexOf('points')-1;
                    mycounter = parseInt($(this).text().substring(start, end));
                    var category = $(this).text().substring(0, start-2);
                    text_dialog += "   " + category + ":\n\t" + mycounter + " points x " + amount + " = " + mycounter*amount + " points\n";
                });
            }

            $("pre#total-dialog").text(text_dialog);
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
                        "amount" name="data[Payed_search][documents_amount]" value="1" maxlength="3" size="3"/>
                        <label for="counter">Total points to <strong>search</strong> 1 document:</label>
                        <p id="counter">0</p>
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
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>You are going to spend <br/><br/><br/><pre id="total-dialog"></pre><br/><br/>Are you sure?</p>
</div>
<?php echo $this->Form->submit('Search'); ?> 
<?php }?>
