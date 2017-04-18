$(document).ready(function() {
	
	var get_url = getNotificationsUrl();
	var mark_url = getMarkAsReadUrl();
	var limit = 6;
	var total_groups = 0;
	var total_records = 0;
	var unseen_count = 0;
	var loading = false;
	
    $.post(get_url, {limit: limit, first_load: 1},
    	function(data){ 
        if (data != "") { 
	    	  $(data).each(function(index, element) {       		  
	    		  if(index == 0) {
	    			  total_groups = parseInt($(element).text(), 10);
	    		  } else if(index == 1) {
	    			  if($(element).text() > 0) {
	        			  $('.notifications_number').text($(element).text());
	        			  $('.notifications_number').show();
	        			  unseen_count = parseInt($(element).text(), 10);
	    			  }
	    		  } else {
	        		  $('.notifications').append(element);
	    		  }       		  
	    	  }); 
	    	  
	    	  var elements_count = $(data).length - 2;
	    	  
	    	  if(elements_count == 5) {
	    		  $('#notifications').css('height', '305px');
	    	  } else if(elements_count == 4) {
	    		  $('#notifications').css('height', '244px');
	    	  } else if(elements_count == 3) {
	    		  $('#notifications').css('height', '183px');
	    	  } else if(elements_count == 2) {
	    		  $('#notifications').css('height', '122px');
	    	  } else if(elements_count == 1) {
	    		  $('#notifications').css('height', '61px');
	    	  } else if(elements_count == 0){
	    		  $('#notifications').css('height', '0px');
	    	  }
	    	  
	    	  total_records++;
        }
    });
    
    setInterval(function(){ 
	    $.post(get_url, {},
	        	function(data){ 
	            if (data != "") {   
		    		var data_id;
		    		var temp_element;
	    	    	  $(data).each(function(index, element) {       	    		  
	    	    		  data_id = $(element).children(":first").data("id");	    	 	    	    		
	    	    		  temp_element = $(".notifications").find("[data-id='" + data_id + "']");  
	    	    		  if(temp_element.data("id") != data_id) {
	    	    			  $('.notifications').prepend(element);	 
	    	    			  unseen_count++; 	    			
    	        			  $('.notifications_number').text(unseen_count);
    	        			  $('.notifications_number').show();
    	        			  limit++;
	    	    		  }    		  
	    	    	  }); 
	    	    	  
	            }
	        });
    }, 5000);
    
	$('#notifications').scroll(function() {
		if(total_records >= total_groups) {
			$('#notifications').off('scroll');
		}
	    if(($('#notifications').scrollTop() + $('#notifications').innerHeight() > $('#notifications')[0].scrollHeight - 20) && loading == false) {    
	        if(total_records < total_groups) {
	          loading = true; 
	          //$('#loader_image_div').show(); 
		          $.post(get_url,{'limit': limit, 'group_number': total_records},
	                  function(data){ 
	                      if (data != "") {              
	                    	  $(data).each(function(index, element) {
	                    		  if(index == 0) {
	            	    		  } else {
	            	        		  $('.notifications').append(element);
	            	    		  }  
	                    	  });
	                    	  total_records++;
	                      }
	                      loading = false;
	                      //$('#loader_image_div').hide(); 
	                  });         	  
	        }
	    }
	});
	
	$('.notifications_icon').click(function(e) {
		e.stopPropagation();
		if($('.notifications_content').is(":hidden")) {
			$('.notifications_content').show();
			$('.notifications_number').hide();	
						
			unseen_count = 0;
			
  		  $.ajax({
		        method: "POST",
		        url: mark_url,
		        data: {}
		      })
		    .done(function(msg) { 	
		    }); 
			
		} else {
			$('.notifications_content').css('-webkit-animation-name', 'zoom_out ');
			$('.notifications_content').css('animation-name', 'zoom_out');
			$('.notifications_content').css("-webkit-animation-duration", "0.15s");
			$('.notifications_content').css("animation-duration", "0.15s");
			setTimeout(function(){	
				$('.notifications_content').hide();
				$('.notifications_content').css('-webkit-animation-name', 'zoom ');
				$('.notifications_content').css('animation-name', 'zoom');
				$(".notifications_content").css("-webkit-animation-duration", "0.2s");
				$(".notifications_content").css("animation-duration", "0.2s");
			}, 120);
		}		
	});
	
	$(window).click(function() {
		$('.notifications_content').css('-webkit-animation-name', 'zoom_out ');
		$('.notifications_content').css('animation-name', 'zoom_out');
		$('.notifications_content').css("-webkit-animation-duration", "0.15s");
		$('.notifications_content').css("animation-duration", "0.15s");
		setTimeout(function(){	
			$('.notifications_content').hide();
			$('.notifications_content').css('-webkit-animation-name', 'zoom ');
			$('.notifications_content').css('animation-name', 'zoom');
			$(".notifications_content").css("-webkit-animation-duration", "0.2s");
			$(".notifications_content").css("animation-duration", "0.2s");
		}, 120);
	});
	
	$('.notifications').on( 'mousewheel DOMMouseScroll', function (e) { 
	  if(e.ctrlKey != true) {
		  var e0 = e.originalEvent;
		  var delta = e0.wheelDelta || -e0.detail;
	
		  this.scrollTop += ( delta < 0 ? 1 : -1 ) * 30;
		  e.preventDefault();  
	  }
	});
	
});