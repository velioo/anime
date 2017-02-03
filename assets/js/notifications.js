$(document).ready(function() {
	
	var get_url = getNotificationsUrl();
	var mark_url = getMarkAsReadUrl();
	
    $.post(get_url, {limit: 6, first_load: 1},
    	function(data){ 
        if (data != "") { 
	    	  $(data).each(function(index, element) {       		  
	    		  if(index == 0) {
	    			  if($(element).text() > 0) {
	        			  $('.notifications_number').text($(element).text());
	        			  $('.notifications_number').show();
	    			  }
	    		  } else {
	        		  $('.notifications').append(element);
	    		  }       		  
	    	  });      	         	
        }
    });
    
    setInterval(function(){ 
	    $.post(get_url, {limit: 6},
	        	function(data){ 
	            if (data != "") {     	    	 
	    	    	  $(data).each(function(index, element) {       		  
	    	    		  if(index == 0) {
	    	    			  if($(element).text() > 0) {
	    	    				  $('.notifications').empty();
	    	        			  $('.notifications_number').text($(element).text());
	    	        			  $('.notifications_number').show();
	    	    			  } else {
	    	    				  $('.notifications_number').hide();
	    	    			  }
	    	    		  } else {
	    	        		  $('.notifications').append(element);
	    	    		  }       		  
	    	    	  });      	         	
	            }
	        });
    }, 5000);
	
	$('.notifications_icon').click(function(e) {
		e.stopPropagation();
		if($('.notifications_content').is(":hidden")) {
			$('.notifications_content').show();
			$('.notifications_number').hide();			

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
	
});