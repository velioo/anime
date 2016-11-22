$(document).ready(function() {
	
	var asset_url = get_asset_url();
	var site_url = get_site_url();
	var search_value = "";
	
	var options = {
		url: asset_url + "json/autocomplete.json",

		theme: "plate-dark",
		getValue: "all_names",
		
		list: {
			match: {
				enabled: true
			},
			
			onKeyEnterEvent: function() {
				$("#search_box").val(search_value);
				window.location = $('#eac-container-search_box').find("li.selected").find("div.eac-item").find("a").attr("href");
			},	
			
			onSelectItemEvent: function() {
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
				return "<a title='" + title + "' href=" + site_url + "/" + item.id + "> " +
						"<img class='auto_image' src='" + asset_url + "poster_images/" + item.image + "' onerror=\"this.src='" + asset_url + "imgs/None.jpg'\" />" + "<div class='auto_div'>" + item.name + "</div>";
			}
		}
	};

	$("#search_box").easyAutocomplete(options);
	$("#small_search_box").easyAutocomplete(options);	
	
	$('#search_box').keyup(function(){
        search_value = $("#search_box").val();
    });
	
});