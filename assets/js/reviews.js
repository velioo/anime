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
    $.post(url + id,{'group_number': total_records},
        function(data){ 
            if (data != "") {              
          	  $(data).each(function(index, element) {
          		  $("#reviews_div").append(element);
          	  });
          	  total_records++;
            }
            loading = false;
     });  	

	
	$(window).scroll(function() {		
		if(total_records >= total_groups) {
			$(window).off('scroll');
		}
	    if(($(window).scrollTop() + $(window).height() == $(document).height()) && loading == false) {    
	        if(total_records < total_groups) {
	          loading = true; 
	          $('#loader_image_div').show(); 
		          $.post(url + id,{'group_number': total_records},
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
	        }
	    }
	});
}

function deleteReview(anime_id) {
	$('.btn.danger').off('click');
	var user_id = getUserId();
	var url = getDeleteUrl();
	console.log("User id: " + user_id);
	console.log("Url: " + url);
    $.ajax({
        method: "POST",
        url: url,
        data: { anime_id: anime_id, user_id: user_id}
      })
    .done(function(msg) {	  
    	if(msg == "Success") {
    		$('.review_div .edit_delete .delete_review').each(function() {    			
    			var onclick_id = $(this).attr("data-id");
    			if(anime_id == onclick_id) {
    				$(this).parent().parent().remove();
    	    		$('#confirm_delete_modal').modal('hide');
    				return false;
    			}
    		});    		    
    	} else {
    		window.alert("Failed to delete review");
    	}
    }); 
}

$(document).ready(function() {
	var id;
	$('#confirm_delete_modal').on('shown.bs.modal', function () {
		id = $(this).data('id'),
	        removeBtn = $(this).find('.danger');
		
	    removeBtn.on('click', function() {    	
	    	deleteReview(id);
	    });

	});
	
	$('#reviews_div').on("click", ".delete_review", function(event){
	    var id = $(this).data('id');	    
	    $('.review_div .edit_delete .delete_review').each(function() {    			
			var onclick_id = $(this).attr("data-id");
			if(id == onclick_id) {
				anime_name = ($(this).parent().parent().find("div a:first").text());
				$('#review_name').html('Delete Review: <strong>' + anime_name + '</strong>');
				return false;
			}
		});   	    
	    $('#confirm_delete_modal').data('id', id);	
	    $('#confirm_delete_modal').modal({
	        show: true
	    });
	    $('body').css("padding-right", "0");
	});
	
});







