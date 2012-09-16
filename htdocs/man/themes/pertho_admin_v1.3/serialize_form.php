<?php
	foreach($_POST as $key => $value){
		if(is_array($value)) {
			echo "<strong>".$key.":</strong> ";
			print_r($value);
			echo "\n";
		} else {
			echo $key.": <strong>".$value."</strong>,\n ";
		}
	};
?>
