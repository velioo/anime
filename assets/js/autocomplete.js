$(document).ready(function() {
	
	document.getElementById('resize_autocomplete').disabled  = true;
	
	var asset_url = get_asset_url();
	var anime_url = get_anime_url();
	var character_url = get_character_url();
	var user_url = get_user_url();
	var search_value = "";
	var url = "";
	
	var anime_options = {
		url: asset_url + "json/autocomplete.json?nocache=" + (new Date()).getTime(),
		theme: "plate-dark",
		getValue: "all_names",		
		list: {
			maxNumberOfElements: 7,
			match: {
				enabled: true
			},			
			onKeyEnterEvent: function() {
				$('#search_box').val(search_value);
				window.location = $('#eac-container-search_box').find("li.selected").find("div.eac-item").find("a").attr("href");
			},				
			onChooseEvent: function() {
				$("#search_box").val(search_value);
			}
		},		
		highlightPhrase: true,		
		template: {
			type: "custom",
			method: function(value, item) {
				var title = item.name;
				if(item.name.length > 70) {
					item.name = item.name.substr(0, 70) + "...";
				}
				return "<a title='" + title + "' href=" + anime_url + item.slug + "> " +
						"<img class='auto_image' src='" + asset_url + "poster_images/" + item.image + "' onerror=\"this.src='" + asset_url + "imgs/None.jpg'\" />" + "<div class='auto_div'>" + item.name + "</div>";
			}
		}
	};
	
	var user_options = {
			url: asset_url + "json/autocomplete_users.json?nocache=" + (new Date()).getTime(),		
			theme: "plate-dark",
			getValue: "name",
			list: {
				maxNumberOfElements: 10,
				match: {
					enabled: true
				},				
				onKeyEnterEvent: function() {
					$('#search_box').val(search_value);
					window.location = $('#eac-container-search_box').find("li.selected").find("div.eac-item").find("a").attr("href");
				},					
				onChooseEvent: function() {
					$("#search_box").val(search_value);
				}
			},			
			highlightPhrase: true,			
			template: {
				type: "custom",
				method: function(value, item) {
					var title = item.name;
					return "<a title='" + title + "' href=" + user_url + item.name + "> " +
							"<div class='user_image_div'><img class='user_image' src='" + asset_url + "user_profile_images/" + item.image + "' onerror=\"this.src='" + asset_url + "user_profile_images/Default.png'\" /></div>" + "<div class='auto_div'>" + item.name + "</div>";
				}
			}
	}
	
	var character_options = {
			url: asset_url + "json/autocomplete_characters.json?nocache=" + (new Date()).getTime(),			
			theme: "plate-dark",			
			getValue: "all_names",			
			list: {
				maxNumberOfElements: 7,
				match: {
					enabled: true
				},				
				onKeyEnterEvent: function() {
					$('#search_box').val(search_value);
					window.location = $('#eac-container-search_box').find("li.selected").find("div.eac-item").find("a").attr("href");
				},				
				onChooseEvent: function() {
					$("#search_box").val(search_value);
				}
			},			
			highlightPhrase: true,			
			template: {
				type: "custom",
				method: function(value, item) {
					var title = item.name;
					if(item.name.length > 70) {
						item.name = item.name.substr(0, 70) + "...";
					}
					return "<a title='" + title + "' href=" + character_url + item.id + "/" + item.slug + "> " +
							"<img class='auto_image' src='" + asset_url + "character_images/" + item.image + "' onerror=\"this.src='" + asset_url + "character_images/Default.jpg'\" />" + "<div class='auto_div'>" + item.name + "</div>";
				}
			}
	}

	$("#search_box").easyAutocomplete(anime_options);
	$("#small_search_box").easyAutocomplete(anime_options);	
	
	$('#search_box').keyup(function(){
        search_value = $("#search_box").val();
    });
	
	$('#default_search_select').change(function() {
		var value = $('#default_search_select').val();
		if(value == "animes") {
			$("#search_box").easyAutocomplete(anime_options);	
			document.getElementById('resize_autocomplete').disabled = true;
		} else if(value == "users"){
			$("#search_box").easyAutocomplete(user_options);
			document.getElementById('resize_autocomplete').disabled = false;
		} else if(value == "characters") {
			$("#search_box").easyAutocomplete(character_options);
			document.getElementById('resize_autocomplete').disabled = true;
		}
	});
	
	$('#small_search_select').change(function() {
		var value = $('#small_search_select').val();
		if(value == "animes") {
			$("#small_search_box").easyAutocomplete(anime_options);	
			document.getElementById('resize_autocomplete').disabled = true;
		} else if(value == "users"){
			$("#small_search_box").easyAutocomplete(user_options);	
			document.getElementById('resize_autocomplete').disabled = false;
		} else if(value == "characters") {
			$("#search_box").easyAutocomplete(character_options);
			document.getElementById('resize_autocomplete').disabled = true;
		}
	});
	
});