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
    
    echo $this->element('content'."/form", array('flag' => 'value'));
    echo("<div id='checked_content'></div>");
    
    if(!empty($restrictions)) {
        
        echo $this->element('attachFile'."/form", array('flag' => 'value'));
        echo("<div id='checked_attachFile'></div>");
    }
?>

</br>
</br>
</br>

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
                                foreach (array_keys($categories_names) as $categories) {
                                    echo "<li class=\"ui-state-default\" ";
                                    echo "id=\"categories_".$categories_ids[$i++]."\" >";
                                    echo $categories." - ".$categories_points[$categories]." points ";
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
                        <label for="counter">Total points to spend:</label>
                        <input type="text" disabled="disabled" id=
                        "counter" value="0"/>
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
