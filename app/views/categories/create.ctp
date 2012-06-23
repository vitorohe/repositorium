<?php
echo $javascript->link('checker.js',false);
?>
<?php
$title = "Create new Category";    
$this->viewVars['title_for_layout'] = $title;
$this->Html->addCrumb($title);
?>

<?php echo $this->Html->image('add_doc.png',array('class' => 'imgicon')) ; ?><h1 class="h1icon" style="margin-top: 15px;"><?php echo $title;?></h1>
<div class="clearicon"></div>

<fieldset class="datafields">

<?php echo $this->Form->create(null, array('url' => '/categories/create', 'type' => 'file', 'inputDefaults' => array('error' => false)));?>
<?php echo $this->Form->input('Category.name', array('class' => 'ingresar-documento', 'label' => 'Title', 'default' => '', 'size' => 50, 'onChange'=>"CheckTitle(DocumentTitle.value)"));?> 
<?php echo $ajax->div('checked_title'); 
      echo $ajax->divEnd('checked_title'); ?>
<?php
    
    echo $this->Form->input('Category.description', array('class' => 'ingresar-documento', 'label' => 'Description', 'rows' => 10, 'cols' => 50, 'default' => '', 'onChange'=>'CheckContent(DocumentContent.value)'));

    echo("<div id='checked_content'></div>");
?>

</br>

Please, drag and drop to choose a criteria or category
</br>
</br>

<script>
$(function() {

    var mycounter_u = 0;
    var mycounter_d = 0;
    var mycounter_c = 0;

    $( "#sortable1, #sortable2" ).sortable({
        connectWith: ".connectedSortable"
    }).disableSelection();

    $("#sortable2").sortable({
        update: function(event, ui) {
            mycounter_u = 0;
            mycounter_d = 0;
            mycounter_c = 0;
            
            $('#sortable2 li').each(function(index){
                array_points = $(this).text().split("/");
                mycounter_u += parseInt(array_points[0].substring(array_points[0].indexOf('(')+1));
                mycounter_d += parseInt(array_points[1]);
                mycounter_c += parseInt(array_points[2].substring(0, array_points[2].indexOf(')')));
                $('#counter').text(mycounter_u + "/" + mycounter_d + "/" + mycounter_c);
            });
            if(mycounter_u == 0 && mycounter_d == 0 && mycounter_c == 0) {
                $('#counter').text("0/0/0");
            }
        }
    });

    $('form').submit(function(){ 
        $('#thedata').val($( "#sortable2" ).sortable("serialize"));
    });

});
</script>

<i>*Note: next representation of criterias follows this structure  <strong>name (upload score/download score/collaboration score)</strong></i>

</br>
</br>
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
                    <th class="ui-state-default" width="3%">Criterias</th>
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
                            <?php                            
                                foreach ($criterias as $criteria) {
                                    echo "<li class=\"ui-state-default\" ";
                                    echo "id=\"criterias_".$criteria['Criteria']['id']."\" >";
                                    echo $criteria['Criteria']['name']." (".$criteria['Criteria']['upload_score'];
                                    echo "/".$criteria['Criteria']['download_score'];
                                    echo "/".$criteria['Criteria']['collaboration_score'].")";
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
                        <label for="counter">Total points (upload/download/collaboration):</label>
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

<?php echo $this->Form->end('Done'); ?>
</fieldset>

<br />
