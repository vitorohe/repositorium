<?php
	if(!empty($categories_autocomplete)) {
	
		$categories_names = $this->Session->read('categories_names');
		$categories_ids = $this->Session->read('categories_ids');
		$categories_points = $this->Session->read('categories_points');
	
		$i=0;
		foreach ($categories_autocomplete as $categories) {
			echo "<li class=\"ui-state-default\" ";
			echo "id=\"categories_".$categories_ids[$keys[$i++]]."\" >";
			echo $categories." - ".$categories_points[$categories]." points ";
			echo "</li>";
		}
	}
	else {
		//echo "<p style=\"padding:5px;\">There aren't results for: ".$search_data."</p>";
	}
?>