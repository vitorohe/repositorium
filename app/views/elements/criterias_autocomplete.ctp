<?php
	if(!empty($criterias_autocomplete)) {

		$criterias_names = $this->Session->read('criterias_names');
		$criterias_ids = $this->Session->read('criterias_ids');
		$criterias_points = $this->Session->read('criterias_points');

		$i=0;
		foreach ($criterias_autocomplete as $criteria) {
			echo "<li class=\"ui-state-default\" ";
			//echo "<li class=\"ui-state-highlight\" ";
			echo "id=\"criterias_".$criterias_ids[$keys[$i++]]."\" >";
            echo $criteria." - ".$criterias_points[$keys[$i-1]]." points";
            echo "</li>";
		}
	}
	else {
		//echo "<p style=\"padding:5px;\">There aren't results for: ".$search_data."</p>";
	}
?>