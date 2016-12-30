var total_records = 0;
var url;
var total_groups;
var id;
var loading = false;

show_hide_text();

$("#actor_image").click(function(){
	$('#actor_modal').css("display", "block");
    $("#modal_image").attr("src", $(this).attr("src"));
});

$('#close_modal').click(function(){
	$("#center_div").css("-webkit-animation-name", "zoom_out");
	$("#center_div").css("animation-name", "zoom_out");
	$("#center_div").css("-webkit-animation-duration", "0.3s");
	$("#center_div").css("animation-duration", "0.3s");
	setTimeout(function(){
		 $('#actor_modal').css("display", "none"); 
		 $("#center_div").css("-webkit-animation-name", "zoom");
		 $("#center_div").css("animation-name", "zoom");
		 $("#center_div").css("-webkit-animation-duration", "0.6s");
		 $("#center_div").css("animation-duration", "0.6s");
	}, 250);
});

$('.see_all').click(function() {
	$('#actor_info_div').hide();
	$("#wrap_left_side_without_image").hide();
	$("#show_actor_info").show();
	var url = getActorUserStatusLoadUrl();
	var actor_id = getActorId();
	var actor_name = getActorName();
	if($(this).hasClass('all_loved')) {				
		var status = 1;	
		var total_groups = getTotalLoveGroups();
		$('#all_users .user_status_header').text("Users who love " + actor_name);
	} else {			
		var status = 0;
		var total_groups = getTotalHateGroups();
		$('#all_users .user_status_header').text("Users who hate " + actor_name);
	}
	initScroll(total_groups, url, actor_id, status);
});

$('#show_actor_info').click(function() {
	$('#actor_info_div').css("display", "inline-block");
	$("#wrap_left_side_without_image").show();
	$("#show_actor_info").hide();
	$("#all_users").hide();
});

function initScroll(total_groups, url, id, status) {
	$("#all_users").css("display", "inline-block");
	this.total_groups = total_groups;
	this.url = url;
	this.id = id;			
	
	total_records = 0;
	
	$(window).off('scroll');
	$('#user_status_tbody').empty();

	loading = true;
    $.post(url + id,{'group_number': total_records, 'status': status},
        function(data){ 
            if (data != "") {              
          	  $(data).each(function(index, element) {
          		  $("#user_status_tbody").append(element);
          	  });
          	  total_records++;
            }
            loading = false;
     });  	

	
	$(window).scroll(function() {		
		if(total_records >= total_groups) {
			$(window).off('scroll');
		}
	    if(($(window).scrollTop() + $(window).height() > $(document).height() - 100) && loading == false) {    
	        if(total_records < total_groups) {
	          loading = true; 
	          $('#loader_image_div').show(); 
		          $.post(url + id,{'group_number': total_records, 'status': status},
	                  function(data){ 
	                      if (data != "") {              
	                    	  $(data).each(function(index, element) {
	                    		  $("#user_status_tbody").append(element);
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

function show_hide_text() {
	var showChar = 1500;
	var ellipsestext = "...";
	var moretext = "Show more";
	var lesstext = "Show less";
	
	$('.more').each(function() {
	    var content = $(this).html();
	
	    if(content.length > showChar) {
	
	        var c = content.substr(0, showChar);
	        var h = content.substr(showChar, content.length - showChar);
	
	        var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<p class="morelink red-text">' + moretext + '</p></span>';
	
	        $(this).html(html);
	    }
	
	});
	
	$('#actor_info_div').css("display", "inline-block");
	
	$(".morelink").click(function(){
	    if($(this).hasClass("less")) {
	        $(this).removeClass("less");
	        $(this).html(moretext);
	    } else {
	        $(this).addClass("less");
	        $(this).html(lesstext);
	    }
	    $(this).parent().prev().toggle();
	    $(this).prev().toggle();
	    return false;
	});
}