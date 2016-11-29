var total_records = 0;
var base_url;
var total_groups;
var anime_id;
var loading = false;

function initScroll(total_g, base_u, anime_i) {
	total_groups = total_g;
	base_url = base_u;
	anime_id = anime_i;
	
	loading = true;
	$('#loader_image_div').show();
    $.post(base_url + 'reviews/load_reviews/' + anime_id,{'group_number': total_records},
            function(data){ 
                if (data != "") {              
              	  $(data).each(function(index, element) {
              		  $("#reviews_div").append(element);
              	  });
              	  total_records++;
                }
                loading = false;
                $('#loader_image_div').hide(); 
            });  	

	
	$(window).scroll(function() {    
	    if(($(window).scrollTop() + $(window).height() == $(document).height()) && loading == false) {    
	        if(total_records < total_groups) {
	          loading = true; 
	          $('#loader_image_div').show(); 
	          setTimeout(function() {
		          $.post(base_url + 'reviews/load_reviews/' + anime_id,{'group_number': total_records},
	                  function(data){ 
	                      if (data != "") {              
	                    	  $(data).each(function(index, element) {
	                    		  $("#reviews_div").append(element);
	                    	  });
	                    	  total_records++;
	                      }
	                      loading = false;
	                      $('#loader_image_div').hide(); 
	                  });         	  
	          }, 300);
 
	        }
	    }
	});
}