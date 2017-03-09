<?php
function asset_url(){
   return base_url().'assets/';
}

function convert_titles_to_hash($temp) {
	$temp = explode(", \"", $temp);
	$hash_titles = array();
	$counter = 0;
	foreach ($temp as $title) {
		$counter++;
		$key_values = explode("=>", $title);
		$key_values[0] = trim($key_values[0]);
		$key_values[1] = trim($key_values[1]);	
		//if($key_values[0] == "\"alt\"") {
		if($counter == 1) {
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
			default:
				$show_type = "Unknown";
				break;
		}
	} else {
		$type = strtolower($type);
		switch($type) {
			case "unknown":
				$show_type = 0;
				break;
			case "tv":
				$show_type = 1;
				break;
			case "special":
				$show_type = 2;
				break;
			case "ova":
				$show_type = 3;
				break;
			case "ona":
				$show_type = 4;
				break;
			case "movie":
				$show_type = 5;
				break;
			case "music":
				$show_type = 6;
				break;
			default: 
				$show_type = 0;
				break;
		}
	}
	
	return $show_type;
}

function get_age_rating($age_rating, $age_rating_guide="") {
	
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
					if(strpos($age_rating_guide, "13")) {
						$age_rating = 3;
					} else {
						$age_rating = 2;
					}					
					break;
				case "PG13":
					$age_rating = 3;
					break;
				case "R":
					$age_rating = 4;
					break;
				case "R17+":
					$age_rating = 4;
					break;
				default:	
					$age_rating = 0;
					break;				
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

function get_watchlist_status_id($status) {
	switch($status) {
		case "all":
			$status_id = 0;
			break;
		case "watched":
			$status_id = 1;
			break;
		case "watching":
			$status_id = 2;
			break;
		case "want_to_watch":
			$status_id = 3;
			break;
		case "stalled":
			$status_id = 4;
			break;
		case "dropped":
			$status_id = 5;
			break;
		default:
			$status_id = "";
			break;
	}
	
	return $status_id;
}

function get_users_sort($sort_by) {
	$sort = array();
	switch($sort_by) {
		case "name_asc":
			$sort[] = "username";
			$sort[] = "ASC";
			break;
		case "name_desc":
			$sort[] = "username";
			$sort[] = "DESC";
			break;
		case "join_asc":
			$sort[] = "joined_on";
			$sort[] = "ASC";
			break;
		case "join_desc":
			$sort[] = "joined_on";
			$sort[] = "DESC";
			break;
		case "animes_asc":
			$sort[] = "anime_count";
			$sort[] = "ASC";
			break;
		case "animes_desc":
			$sort[] = "anime_count";
			$sort[] = "DESC";
			break;
		case "eps_asc":
			$sort[] = "eps_watched";
			$sort[] = "ASC";
			break;			
		case "eps_desc":
			$sort[] = "eps_watched";
			$sort[] = "DESC";
			break;
		case "rating_asc":
			$sort[] = "score";
			$sort[] = "ASC";
			break;
		case "rating_desc":
			$sort[] = "score";
			$sort[] = "DESC";
			break;
		case "date_asc":
			$sort[] = "status_updated_at";
			$sort[] = "ASC";
			break;
		case "date_desc":
			$sort[] = "status_updated_at";
			$sort[] = "DESC";
			break;
		default:
			$sort[] = "username";
			$sort[] = "ASC";
	}
	
	return $sort;
}

function get_status_square($status) {
	switch($status) {
		case 1:
			$status = "blue";
			break;
		case 2:
			$status = "green";
			break;
		case 3:
			$status = "yellow";
			break;
		case 4:
			$status = "orange";
			break;
		case 5:
			$status = "red";
			break;
		default:
			break;
	}
	
	return $status;
}

function process_and_return_stats($user_stats) {
	$processed_stats = array(0, 0, 0, 0, 0);
	for($i = 0; $i < count($user_stats); $i++) {
		switch($user_stats[$i]['status']) {
			case 1:
				$processed_stats[0] = $user_stats[$i]['users_count'];
				break;
			case 2:
				$processed_stats[1] = $user_stats[$i]['users_count'];
				break;
			case 3:
				$processed_stats[2] = $user_stats[$i]['users_count'];
				break;
			case 4:
				$processed_stats[3] = $user_stats[$i]['users_count'];
				break;
			case 5:
				$processed_stats[4] = $user_stats[$i]['users_count'];
				break;
			default:
				break;
		}
	}
	
	return $processed_stats;
}

function validateDate($date, $format = 'Y-m-d H:i:s') {
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) == $date;
}

function get_alternate_title($canonical, $titles) {
	$alternate_title = "";
	if(isset($titles->en)) {
		if($titles->en == $canonical) {
			if(isset($titles->en_jp)) {
				if($titles->en_jp != null)
					$alternate_title = $titles->en_jp;
			} else if(isset($titles->ja_jp)) {
				if($titles->ja_jp != null)
					$alternate_title = $titles->ja_jp;
			}
		}
	}
	
	if(isset($titles->en_jp)) {
		if($titles->en_jp == $canonical) {
			if(isset($titles->en)) {
				if($titles->en != null)
					$alternate_title = $titles->en;
			} else if(isset($titles->ja_jp)) {
				if($titles->ja_jp != null)
					$alternate_title = $titles->ja_jp;
			}
		}
	}
	
	if(isset($titles->ja_jp) ) {
		if($titles->ja_jp == $canonical) {			
			if(isset($titles->en_jp)) {
				if($titles->en_jp != null)
					$alternate_title = $titles->en_jp;
			} else if(isset($titles->en)) {
				if($titles->en != null)
					$alternate_title = $titles->en;
			}
		}
	}
	
	return $alternate_title;
}

function format_info($info_text) {	
	$delete_first_nl = FALSE;
	if(strpos($info_text, '__') !== false) {
		$split_info = explode("  ", $info_text);		
		$info_text = "";
		for($i = 0; $i < count($split_info); $i++) {
			$words = explode(" ", $split_info[$i]);
			$split_info[$i] = "";		
			$count = 0;
			for($j = 0; $j < count($words); $j++) {
				if(strpos($words[$j], '__') !== false) {
					if(substr_count($words[$j], "__") == 2) {		
						if(strpos($words[$j], "\n") !== false) {	
							$temp = explode("\n", $words[$j]);
							$words[$j] = "";
							for($t = 0; $t < count($temp); $t++) {
								if(strpos($temp[$t], "__") !== false) {
									$words[$j].= "<strong>" . $temp[$t] . "</strong>";
								} else {
									$words[$j].=$temp[$t];
								}
							}
						} else {
							$words[$j] = "<strong>" . $words[$j] . "</strong>";
							$count = 0;
						}						
					} else {
						$temp = explode("\n", $words[$j]);
						if(count($temp) > 1) {
							$words[$j] = "";
							for($t = 0; $t < count($temp); $t++) {
								if(strpos($temp[$t], "__") !== false) {
									$words[$j].= "<strong>" . $temp[$t] . "</strong>";
								} else {
									$words[$j].=$temp[$t] . "\n";
								}
							}
						} else {
							$count++;
							if($count == 1) {
								$words[$j] = "<strong>" . $words[$j] . " ";
							} else if($count == 2){
								$words[$j] = $words[$j] . "</strong>";
								$count = 0;
							}
						}
					}
				} else if(strpos($words[$j], ':') !== false) {
					if(strpos($words[$j], "\n") != false) {
						$temp = explode("\n", $words[$j]);
						$words[$j] = "";
						for($t = 0; $t < count($temp); $t++) {
							if(strpos($temp[$t], ":") != false) {
								$words[$j].= " <strong>" . $temp[$t] . "</strong>";
							} else {
								$words[$j].=$temp[$t]. "\n";;
							}
						}
					} else {
						if($i == 0 && $j == 0) {
							$delete_first_nl = TRUE;
						}
						$words[$j] = "\n<strong>" . $words[$j] . "</strong>";
					}
				}
				if(strpos($words[$j], '*') !== false) {
					$words[$j].="\n" . $words[$j];
				}
				$split_info[$i].=$words[$j] . " ";
			}
				
			$info_text.=$split_info[$i];
		}		
	
		$length = strlen($info_text);	
		$count = 0;
		for($i = 0; $i < $length; $i++) {
			if($info_text[$i] == '_') {
				$count++;
				if($i != 0) {
					if($info_text[$i - 1] == '_') {
						if($count == 2) {
							$info_text[$i] = "\n";
							$info_text[$i - 1] = "";
						} else if($count == 4) {
							$info_text[$i] = "";
							$info_text[$i - 1] = "";
							$count = 0;
						}
					}
				}
			}
		}
		
		if(strpos($info_text, "~~~") !== false) {
			$info_text = str_replace("~~~", "", $info_text);
			$info_text = str_replace("_", "", $info_text);
		}
		
		$delete_first_nl = TRUE;
	
	} else {	
		$split_info = explode(" ", $info_text);
		//$split_info = preg_split('/[\s]+/', $info_text);
		$info_text = "";
		for($i = 0; $i < count($split_info); $i++) {
			$words = explode(" ", $split_info[$i]);
			$split_info[$i] = "";
			$count = 0;
			for($j = 0; $j < count($words); $j++) {
				if(strpos($words[$j], ':') !== false) {
					if(strpos($words[$j], "\n") != false) {
						$temp = explode("\n", $words[$j]);
						$words[$j] = "";
						for($t = 0; $t < count($temp); $t++) {
							if(strpos($temp[$t], ":") != false) {
								$words[$j].= " <strong>" . $temp[$t] . "</strong>";
							} else {
								$words[$j].=$temp[$t]. "\n";;
							}
						}
					} else {
						if($i == 0 && $j == 0) {
							$delete_first_nl = TRUE;
						}
						$words[$j] = "\n<strong>" . $words[$j] . "</strong>";
					}
				} 
				if(strpos($words[$j], '*') !== false) {
					$words[$j].="\n" . $words[$j];
				}
				$split_info[$i].=$words[$j] . " ";
			}
			$info_text.=$split_info[$i];
		}		
	}
	
	if($delete_first_nl) {
		$pos = strpos($info_text, "\n");
		if ($pos !== false) {
			$info_text = substr_replace($info_text, "", $pos, 1);
		}	
	}
	
	$info_text = str_replace("~!", "", $info_text);
	$info_text = str_replace("!~", "", $info_text);	
	$info_text = nl2br($info_text);
	
	return $info_text;
}

function character_skip($id) {
	$character_ids = array(88575);
	
	if(in_array($id, $character_ids)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function convert_cyrillic_to_latin($name) {
	$cyr = [
		'Р°','Р±','РІ','Рі','Рґ','Рµ','С‘','Р¶','Р·','Рё','Р№','Рє','Р»','Рј','РЅ','Рѕ','Рї',
		'СЂ','СЃ','С‚','Сѓ','С„','С…','С†','С‡','С€','С‰','СЉ','С‹','СЊ','СЌ','СЋ','СЏ',
		'Рђ','Р‘','Р’','Р“','Р”','Р•','РЃ','Р–','Р—','Р�','Р™','Рљ','Р›','Рњ','Рќ','Рћ','Рџ',
		'Р ','РЎ','Рў','РЈ','Р¤','РҐ','Р¦','Р§','РЁ','Р©','РЄ','Р«','Р¬','Р­','Р®','РЇ'
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