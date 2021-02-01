<style>
p {
	margin-bottom: 24px;
}

h2 {
	margin-bottom: 15px;
}
ul {
	list-style-type: circle;
	padding-left: 20px;
}
.modal {
  text-align: center;
  padding: 0!important;
}
.modal:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px;
}
.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
}
</style>

<div class="modal fade " id="lesson-modal" tabindex="-1" role="dialog" aria-labelledby="important-msg-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="overflow-y: initial !important;">
          	<!--
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="important-msg-label"></h4>
          	</div>
          	-->
		  	<div class="modal-body" style="height: 65vh; overflow-y: auto;">
          	</div>
		  	<div class="modal-footer lesson-buttons">
          	</div>
        </div>
    </div>
</div>

<script>

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
            var comments = '<div class="clear"></div><!-- start: facebook comments --><div id="comments" class="section bg-white"><h3 class="title bordered no-margin"><?php esc_html_e('Comments', 'breek'); ?></h3><div class="clear"></div><div class="fb-comments" data-href="' + data.link + '" data-width="100%" data-numposts="5"></div></div><!-- end: facebook comments --><div id="fb-root"></div>';
            
            var display_content = close + data.content.rendered;
            
            var content = this.text;
			var term1 = '<h2>Question 1</h2>';
			var term2 = '<h2>Question 2</h2>';
			var term3 = '<h2>Question 3</h2>';
			
			if( data.content.rendered.indexOf(term1) != -1 ) {
			    var post_content = post_content + comments;
			}
			
			if( data.content.rendered.indexOf(term2) != -1 ) {
			    var post_content = post_content + comments;
			}
			
			if( data.content.rendered.indexOf(term3) != -1 ) {
			    var post_content = post_content + comments;
			}
                                  
        	jQuery('.modal-body').html(close + data.content.rendered + comments);
        	
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
</script>
