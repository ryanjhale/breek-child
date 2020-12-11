<style>
p {
	margin-bottom: 24px;
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
		  	<div class="modal-footer">
            	<div class="row">
	            	<div class="col-md-9 lesson-buttons">
	            	</div>
		            <div class="col-md-3">
			            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		            </div>
            	</div>
          	</div>
        </div>
    </div>
</div>
    
    
    
<script>

/* Ajax call to get content for modal */
		
function getModalContent(post_id) {
	jQuery.ajax({
		type:    'GET',
		url:     '<?php echo get_site_url(get_current_blog_id(), "/wp-json/wp/v2/lessons/"); ?>' + post_id,
		success: function(data) {
        	// jQuery('.modal-title').html(data.title.rendered);
        	jQuery('.modal-body').html(data.content.rendered);
        	
        	var button1_color = data.button_meta.button1_color;
        	var button1_text = data.button_meta.button1_text;
        	var button1_postid = data.button_meta.button1_postid;
        	
        	console.log(button1_color.length);
        	console.log(button1_text.length);
        	console.log(button1_postid.length);
        	
        	var button2_color = data.button_meta.button2_color;
        	var button2_text = data.button_meta.button2_text;
        	var button2_postid = data.button_meta.button2_postid;
        	
        	var button3_color = data.button_meta.button3_color;
        	var button3_text = data.button_meta.button3_text;
        	var button3_postid = data.button_meta.button3_postid;

        	if(button1_color.length === 0 || button1_text.length === 0 || button1_postid.length === 0) {
	        	var button1 = '<button type="button" class="btn btn-default" style="display: none;"></button>';
        	} else {
	        	var button1 = '<button type="button" class="btn btn-' + button1_color + '" onclick="getModalContent(' + button1_postid + ')">' + button1_text + '</button>';
        	}
        	
        	if(button2_color.length === 0 || button2_text.length === 0 || button2_postid.length === 0) {
	        	var button2 = '<button type="button" class="btn btn-default" style="display: none;"></button>';
        	} else {
	        	var button2 = '<button type="button" class="btn btn-' + button2_color + '" onclick="getModalContent(' + button2_postid + ')">' + button2_text + '</button>';
        	}
        	
        	if(button3_color.length === 0 || button3_text.length === 0 || button3_postid.length === 0) {
	        	var button3 = '<button type="button" class="btn btn-default" style="display: none;"></button>';
        	} else {
	        	var button3_color = obj.button_meta.button3_color;
	        	var button3_text = obj.button_meta.button3_text;
	        	var button3_postid = obj.button_meta.button3_postid;
	        	var button3 = '<button type="button" class="btn btn-' + button3_color + '" onclick="getModalContent(' + button3_postid + ')">' + button3_text + '</button>';
        	}
        	
        	jQuery('.lesson-buttons').html(button1);
        	jQuery('.lesson-buttons').html(button2);
        	jQuery('.lesson-buttons').html(button3);
        	
        	
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