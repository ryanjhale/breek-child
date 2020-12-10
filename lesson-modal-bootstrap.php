<div class="modal fade " id="lesson-modal" tabindex="-1" role="dialog" aria-labelledby="important-msg-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="overflow-y: initial !important;">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="important-msg-label"></h4>
          	</div>
		  	<div class="modal-body" style="height: 70vh; overflow-y: auto;">
          	</div>
		  	<div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          	</div>
        </div>
    </div>
</div>
    
    
    
<script>
jQuery(document).ready(function() {


});

/* Ajax call to get content for modal */
		
function getModalContent(post_id) {
	jQuery.ajax({
		type:    'GET',
		url:     '<?php echo get_site_url(get_current_blog_id(), "/wp-json/wp/v2/lessons/"); ?>' + post_id,
		success: function(data) {
        	jQuery('.modal-title').html(data.title.rendered);
        	jQuery('.modal-body').html(data.content.rendered);
      	}
    });
    
    return false;
}
	

</script>