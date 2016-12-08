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
		if($key_values[0] == "\"alt\"") {
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

function get_show_type($type) {
	
	if(is_numeric($type)) {
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
	} else {
		switch($type) {
			case "Unknown":
				$show_type = 0;
				break;
			case "TV":
				$show_type = 1;
				break;
			case "Special":
				$show_type = 2;
				break;
			case "OVA":
				$show_type = 3;
				break;
			case "ONA":
				$show_type = 4;
				break;
			case "Movie":
				$show_type = 5;
				break;
			case "Music":
				$show_type = 6;
				break;
			default: 
				$show_type = 0;
				break;
		}
	}
	
	return $show_type;
}

function get_age_rating($age_rating) {
	
	if(is_numeric($age_rating)) {
		switch($age_rating) {
			case 0:
				$age_rating = "-";
				break;
			case 1:
				$age_rating = "G";
				break;
			case 2:
				$age_rating = "PG";
				break;
			case 3:
				$age_rating = "PG13";
				break;
			case 4:
				$age_rating = "R17+";
				break;
			default:		
		}
	} else {
		if($age_rating != null) {
			switch($age_rating) {
				case "-":
					$age_rating = 0;
					break;
				case "G":
					$age_rating = 1;
					break;
				case "PG":
					$age_rating = 2;
					break;
				case "PG13":
					$age_rating = 3;
					break;
				case "R17+":
					$age_rating = 4;
					break;
				default:		
			}
		} else {
			$age_rating = 0;
		}
	}
	
	return $age_rating;
}

function get_age_rating_guide($age_rating) {
	$age_rating_guide;
	switch($age_rating) {
		case 0:
			$age_rating_guide = "None";
			break;
		case 1:
			$age_rating_guide = "All ages";
			break;
		case 2:
			$age_rating_guide = "Children";
			break;
		case 3:
			$age_rating_guide = "Teens 13 or older";
			break;
		case 4:
			$age_rating_guide = "Violence, Profanity or Mild Nudity";
			break;
		default: 
			$age_rating_guide = "";
	}
	
	return $age_rating_guide;
}

function strip_review_tags($review_text) {
	
	$text = strip_tags($review_text, "<style>");
	$substring = substr($text, strpos($text, "<style"), strpos($text, "</style>"));					
	$text = str_replace($substring, "", $text);
	$text = str_replace(array("\t", "\r", "\n"), "", $text);				
	$text = str_replace("&nbsp;", " ", $text);
	$text = trim(stripslashes($text));
	
	return $text;
}

function get_watchlist_status_name($status) {
	switch($status) {
		case 1:
			$status_name = "Watched";
			break;
		case 2:
			$status_name = "Watching";
			break;
		case 3:
			$status_name = "Want to Watch";
			break;
		case 4:
			$status_name = "Stalled";
			break;
		case 5:
			$status_name = "Dropped";
			break;
		default:
			$status_name = "";
			break;
	}
	
	return $status_name;
}

function validateDate($date, $format = 'Y-m-d H:i:s') {
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) == $date;
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