<?php
	if(!empty($criterias_autocomplete)) {
	
		$criterias_names = $this->Session->read('criterias_names');
		$criterias_ids = $this->Session->read('criterias_ids');		

		$i=0;

		if($category_create){


			$criterias_points_upload = $this->Session->read('criterias_points_upload');
			$criterias_points_download = $this->Session->read('criterias_points_download');
			$criterias_points_collaboration = $this->Session->read('criterias_points_collaboration');


			foreach ($criterias_autocomplete as $criteria) {
				echo "<li class=\"ui-state-default\" ";
				echo "id=\"criterias_".$criterias_ids[$keys[$i++]]."\" >";
				echo $criteria." (".$criterias_points_upload[$keys[$i-1]];
				echo "/".$criterias_points_download[$keys[$i-1]];
				echo "/".$criterias_points_collaboration[$keys[$i-1]].")";
				echo "</li>";
			}


		} else {

			$criterias_points = $this->Session->read('criterias_points');

			foreach ($criterias_autocomplete as $criteria) {
				echo "<li class=\"ui-state-default\" ";
				echo "id=\"criterias_".$criterias_ids[$keys[$i++]]."\" >";
				echo $criteria." - ".$criterias_points[$keys[$i-1]]." points ";
				echo "</li>";
			}

		}
	}
	else {
		//echo "<p style=\"padding:5px;\">There aren't results for: ".$search_data."</p>";
	}
?>