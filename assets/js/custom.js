function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function checkLaunchModal() {
	var action = getParameterByName('action');
	
	if(action == 'read') {
		
		jQuery( "details:contains('Read')" ).attr('open', '');
		jQuery( "body,html" ).animate({scrollTop: jQuery("details:contains('Read')").offset().top},1000);
		// jQuery( "details:contains('Leggi')" ).attr('open');
		// jQuery( "details:contains('Lire')" ).attr('open');
		
	}
	
	if(action == 'share') {

		jQuery('#share-modal').modal('show');
		
	}
	
	if(action == 'lesson') {
		
		var lesson = getParameterByName('id');
		
		jQuery('#lesson-modal').modal('show');
		
		getModalContent(lesson);
		
	}
	
}

checkLaunchModal();

function getModalContent(post_id) {

	jQuery('.modal-body').empty();
	jQuery('.button1').remove();
	jQuery('.button2').remove();
	jQuery('.button3').remove();
	
	jQuery.ajax({
		type:    'GET',
		url:     '<?php echo get_site_url(get_current_blog_id(), "/wp-json/wp/v2/lessons/"); ?>' + post_id,
		success: function(data) {
        	
        	var close = '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
        	jQuery('.modal-body').html(close + data.content.rendered);
        	
        	var button1_color = data.button_meta.button1_color;
        	var button1_text = data.button_meta.button1_text;
        	var button1_postid = data.button_meta.button1_postid;
        	
        	var button2_color = data.button_meta.button2_color;
        	var button2_text = data.button_meta.button2_text;
        	var button2_postid = data.button_meta.button2_postid;
        	
        	var button3_color = data.button_meta.button3_color;
        	var button3_text = data.button_meta.button3_text;
        	var button3_postid = data.button_meta.button3_postid;
        	
        	jQuery('.button1').remove();
        	jQuery('.button2').remove();
        	jQuery('.button3').remove();

        	if(button1_color.length > 0 && button1_text.length > 0 && button1_postid.length > 0) {
	        	var button1 = '<button type="button" class="btn btn-' + button1_color + ' button1" onclick="getModalContent(' + button1_postid + ')">' + button1_text + '</button>';
        	} else {
	        	var button1 = '<button type="button" class="btn btn-default" style="display: none;"></button>';
        	}
        	
        	if(button2_color.length > 0 && button2_text.length > 0 && button2_postid.length > 0) {
	        	var button2 = '<button type="button" class="btn btn-' + button2_color + ' button2" onclick="getModalContent(' + button2_postid + ')">' + button2_text + '</button>';
        	} else {
	        	var button2 = '<button type="button" class="btn btn-default" style="display: none;"></button>';
        	}
        	
        	if(button3_color.length > 0 && button3_text.length > 0 && button3_postid.length > 0) {
	        	var button3 = '<button type="button" class="btn btn-' + button3_color + ' button3" onclick="getModalContent(' + button3_postid + ')">' + button3_text + '</button>';
        	} else {
	        	var button3 = '<button type="button" class="btn btn-default" style="display: none;"></button>';
        	}
        	
        	jQuery('.lesson-buttons').prepend(button3);
        	jQuery('.lesson-buttons').prepend(button2);
        	jQuery('.lesson-buttons').prepend(button1);
        	
        	var videos = jQuery('.modal-body').find('iframe');
        	
        	jQuery(videos).each(function() {
	        	var src = jQuery(this).attr('data-src');
	        	jQuery(this).attr('src', src);
	        	jQuery(this).attr('data-lazy', 'false');
        	})
        	
        	var images = jQuery('.modal-body').find('img');
        	
        	jQuery(images).each(function() {
	        	var src = jQuery(this).attr('data-src');
	        	jQuery(this).attr('src', src);
	        	jQuery(this).attr('data-lazy', 'false');
        	})
        	
      	}
    });
    
    return false;
}
