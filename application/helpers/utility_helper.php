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

function convert_date($date, $format = "") {
	if($date != "0000-00-00") {
		$split_by_slash_date = explode("-", $date);
	if($split_by_slash_date[0] == "0000") {
		$year = "????";
	} else {
		$year = $split_by_slash_date[0];
	}
	if($split_by_slash_date[1] == "00") {
		$month_short = "???";
	} else {
		
		switch(intval($split_by_slash_date[1])) {
			case 1:
				$month_short = "Jan";
				break;
			case 2:
				$month_short = "Feb";
				break;
			case 3:
				$month_short = "Mar";
				break;
			case 4:
				$month_short = "Apr";
				break;
			case 5:
				$month_short = "May";
				break;
			case 6:
				$month_short = "Jun";
				break;
			case 7:
				$month_short = "Jul";
				break;
			case 8:
				$month_short = "Aug";
				break;
			case 9:
				$month_short = "Sep";
				break;
			case 10:
				$month_short = "Oct";
				break;
			case 11:
				$month_short = "Nov";
				break;
			case 12:
				$month_short = "Dec";
				break;
		}

	}
	if($split_by_slash_date[2] == "00") {
		$day = "??";
	} else {
		$day = $split_by_slash_date[2];			 			
	}
		if($format = "anime_content") {
			$final_date = $day . " " . $month_short  . " " . $year;
		} else {
			$final_date = $month_short . " " . $day . ", " . $year;
		}
		
	} else {
		if($format = "anime_content") {
			$final_date = "??? ?? ????";
		} else {
			$final_date = "??? ??, ????";
		}
		
	}
	
	return $final_date;
}

function get_type($type) {
	switch($type) {
	case 0:
		$show_type = "Unknown";
		break;
	case 1:
		$show_type = "TV";
		break;
	case 2:
		$show_type = "Special";
		break;
	case 3:
		$show_type = "OVA";
		break;
	case 4:
		$show_type = "ONA";
		break;
	case 5:
		$show_type = "Movie";
		break;
	case 6:
		$show_type = "Music";
		break;
	}
	
	return $show_type;
}

function get_age_rating($age_rating, $age_rating_guide) {
	switch($age_rating) {
		case 0:
			$age_rating = "-";
			break;
		case 1:
			$age_rating = "G";
			break;
		case 2:
			if($age_rating_guide == "Children")
				$age_rating = "PG";
			else
				$age_rating = "PG-13";
			break;
		case 3:
			$age_rating = "R17+";
			break;
		case 4:
			break;
		case 5:
			break;
		default:
			
	}
	
	return $age_rating;
}

function convert_cyrillic_to_latin($name) {
	$cyr = [
		'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
		'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
		'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
		'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
     ];
	$lat = [
		'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
		'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
		'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
		'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
	];
	
	$name = str_replace($cyr, $lat, $name);
	
	return $name;
}

?>