$(document).ready(function() {
	
	var get_url = getNotificationsUrl();
	var total_groups = getTotalNotificationGroups();
	var total_records = 0;
	var loading = false;
	
	$('#loader_image_div').show();
    $.post(get_url, {'limit': 15, 'group_number': total_records},
        	function(data){ 
            if (data != "") { 
    	    	  $(data).each(function(index, element) {       		  
    	    		  if(index == 0) {
    	    		  } else {
    	        		  $('.notifications_content_all').append(element);
    	    		  }       		  
    	    	  });    
    	    	  total_records++;
            }
            $('#loader_image_div').hide(); 
        });
    
	$(window).scroll(function() {
		if(total_records >= total_groups) {
			$(window).off('scroll');
		}
	    if(($(window).scrollTop() + $(window).height() > $(document).height() - 100) && loading == false) {    
	        if(total_records < total_groups) {
	          loading = true; 
	          $('#loader_image_div').show(); 
		          $.post(get_url,{'limit': 15, 'group_number': total_records},
	                  function(data){ 
	                      if (data != "") {              
	                    	  $(data).each(function(index, element) {
	                    		  if(index == 0) {
	            	    		  } else {
	            	        		  $('.notifications_content_all').append(element);
	            	    		  }  
	                    	  });
	                    	  total_records++;
	                      }
	                      loading = false;
	                      $('#loader_image_div').hide(); 
	                  });         	  
	        }
	    }
	});
	
});
