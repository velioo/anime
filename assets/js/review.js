function initEditor() {
	tinymce.init({	
	    selector: "textarea",
	    setup: function(editor) {
	        editor.on('keyup', function(e) {
	        	$('#textarea_characters').text("Characters: " + getStats('review_text_editor').chars);
	        });
	        editor.on('init', function(args) {
	        	$('#loading_div').css("display", "none");
	        	$('#textarea_characters').css("display", "inline-block");
	        	$('#textarea_characters').text("Characters: " + getStats('review_text_editor').chars);
	        });
	    },
	    height : "400",
	    auto_focus: "review_text_editor",
	    plugins: "advlist,autolink,link,image,lists,charmap,print,preview,media,searchreplace,fullscreen,nonbreaking,visualchars", 
	    toolbar: "undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink | image media | preview",
	    skin: "lightgray"
	});	

}

$('#submit_review_form').submit(function () {
	
	if(getStats('review_text_editor').chars < 50) {
        alert("You need to enter 50 chars or more.");
        return false;
    }
	
	if(!$("input:radio[name='overall']").is(":checked")) {
		window.alert("You must select an overall score")
		return false;
	}
	
	$('#raw_text').val(tinyMCE.activeEditor.getContent({format : 'raw'}));
	
	document.forms[0].submit();
	
});

function fill_scores(story, animation, sound, characters, enjoyment, overall) {
	if(!isNaN(story) && story >= 1 && story <= 10){
		$("input[name=story][value=" + story + "]").attr('checked', 'checked');
	}
	if(!isNaN(animation) && animation >= 1 && animation <= 10){
		$("input[name=animation][value=" + animation + "]").attr('checked', 'checked');
	}
	if(!isNaN(sound) && sound >= 1 && sound <= 10){
		$("input[name=sound][value=" + sound + "]").attr('checked', 'checked');
	}
	if(!isNaN(characters) && characters >= 1 && characters <= 10){
		$("input[name=characters][value=" + characters + "]").attr('checked', 'checked');
	}
	if(!isNaN(enjoyment) && enjoyment >= 1 && enjoyment <= 10){
		$("input[name=enjoyment][value=" + enjoyment + "]").attr('checked', 'checked');
	}
	if(!isNaN(overall) && overall >= 1 && overall <= 10){
		$("input[name=overall][value=" + overall + "]").attr('checked', 'checked');
	}
}

function getStats(id) {
    var body = tinymce.get(id).getBody(), text = tinymce.trim(body.innerText || body.textContent);

    return {
        chars: text.length,
        words: text.split(/[\w\u2019\'-]+/).length
    };
}

function turnOn(button) {
	$(button).blur();
	$(button).removeClass('font_weight').addClass('font_weight_on');
	$(button).attr("onclick","turnOff(this)");
}

function turnOff(button) {
	$(button).blur();
	$(button).removeClass('font_weight_on').addClass('font_weight');
	$(button).attr("onclick","turnOn(this)");
}