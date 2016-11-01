<?php
function asset_url(){
   return base_url().'assets/';
}

function convert_titles_to_hash($temp) {
	$temp = explode(", \"", $temp);
		$hash_titles = array();
		foreach ($temp as $title) {
		$key_values = explode("=>", $title);
		$key_values[0] = trim($key_values[0]);
		$key_values[1] = trim($key_values[1]);	
		if($key_values[0] == "\"en\"") {
			$key = substr($key_values[0], 1, (strlen($key_values[0]) - 2));
		} else {
			$key = substr($key_values[0], 0, (strlen($key_values[0]) - 1));
		}
		if($key_values[1] == "NULL") {
			$value = "NULL";
		} else {
			$value = substr($key_values[1], 1, (strlen($key_values[1]) - 2));	
		}
		$hash_titles[$key] = $value;
	}
	
	return $hash_titles;
}
?>