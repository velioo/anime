var total_records = 0;
var url;
var total_groups;
var id;
var loading = false;

function initScroll(total_groups, url, id) {
	this.total_groups = total_groups;
	this.url = url;
	this.id = id;

	loading = true;
	$('#loader_image_div').show(); 
    $.post(url + id,{'group_number': total_records},
        function(data){ 
            if (data != "") {              
          	  $(data).each(function(index, element) {
          		if($(element).data('id') == 2) {
         			 $("#main_characters_div div table tbody").append(element);
         		  } else {
         			 $("#secondary_characters_div div table tbody").append(element);
         		  }
          	  });
          	  total_records++;
            }
            loading = false;
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
		          $.post(url + id,{'group_number': total_records},
	                  function(data){ 
	                      if (data != "") {              
	                    	  $(data).each(function(index, element) {
	                    		  if($(element).data('id') == 2) {
		                       			 $("#main_characters_div div table tbody").append(element);
		                       		  } else {
		                       			 $("#secondary_characters_div div table tbody").append(element);
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
}