<div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
	<div class="modal__overlay" tabindex="-1" data-micromodal-close>
    	<div id="modal-container" class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
			<header id="modal-header" class="modal__header" style="display: none;">
				<h2 class="modal__title">
        		</h2>
				<button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
      		</header>
	  		<div id="modal-content-content" class="modal-content-content">
	  			<button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
	  			<div id="modal-content" class="modal__content" style="display:none;">
	  				<!-- Put content here -->Content
        		</div>
      		</div>
      		<footer id="modal-footer" class="modal__footer">
	          <!--<button class="modal__btn" data-micromodal-close aria-label="Close this dialog window">Close</button>-->
	        </footer>
    	</div>
  	</div>
</div>
    
    
    
<script>
jQuery(document).ready(function() {
	try {
	    MicroModal.init({
	    	awaitCloseAnimation: true, // set to false, to remove close animation
			onShow: function(modal) {
	        	console.log("micromodal open");
				// addModalContentHeight('short');
				/**************************
					For full screen scrolling modal, 
					uncomment line below & comment line above
				**************************/
				addModalContentHeight('tall');
	      	},
		  	onClose: function(modal) {
	        	console.log("micromodal close");
	      	}
	    });
	} catch (e) {
	    console.log("micromodal error: ", e);
	}
	
	function addModalContentHeight(type) {
		var type = (arguments[0] != null) ? arguments[0] : 'short';
		var modalContainer = jQuery("#modal-container");
		var modalHeader = jQuery("#modal-header");
		var modalContentContent = jQuery("#modal-content-content");
		var modalContent = jQuery("#modal-content");
		var modalFooter = jQuery("#modal-footer");
		
		var modalIsDefined =
		    modalContainer.length &&
		    modalHeader.length && 
		    modalContent.length &&
		    modalFooter.length;
		
		if (modalIsDefined) {
		    var modalContainerHeight = modalContainer.outerHeight();
		    var modalHeaderHeight = modalHeader.outerHeight();
		    var modalFooterHeight = modalFooter.outerHeight();
		
		    console.log("modalContainerHeight: ", modalContainerHeight);
		    console.log("modalHeaderHeight: ", modalHeaderHeight);
		    console.log("modalFooterHeight: ", modalFooterHeight);
		    
		    var offset = 80;
		    
		    var height = modalContainerHeight - (modalHeaderHeight + modalFooterHeight + offset);
		    
		    console.log('height: ',height);
		    
		    if(!isNaN(height)){
		      	height = height > 0 ? height: 20;
			  	if(type == 'short'){
		        	modalContent.css({'height': height + 'px'});
		      	} else {
		        	modalContainer.css({'height': '80%', 'overflow-y': 'hidden', 'margin-top': '0px'});
					modalContentContent.css({'height': '100%', 'overflow-y': 'auto'});
					modalContent.css({'overflow-y': 'visible'});
					modalFooter.css({'margin-bottom': '0px'});
		      	}
			  	setTimeout(function() {
		        	modalContent.css({'display': 'block'});
					var modalContentDOM = document.querySelector('#modal-content');
					modalContentDOM.scrollTop = 0;
		      	});
		    }
		    
		}
		  
	}
	
	/* Ajax call to get content for modal 
		
	function getModalContent(post_id) {
		jQuery.ajax({
			type:    'GET',
			url:     '<?php echo get_site_url(get_current_blog_id(), "/wp-json/wp/v2/lessons/"); ?>' + post_id,
			success: function(data) {
	        	jQuery('.modal__title').html(data.title.rendered);
	        	jQuery('.modal__content').html(data.content.rendered);
	      	}
	    });
	    
	    return false;
	}
	
	*/

});

/* Ajax call to get content for modal */
		
function getModalContent(post_id) {
	jQuery.ajax({
		type:    'GET',
		url:     '<?php echo get_site_url(get_current_blog_id(), "/wp-json/wp/v2/lessons/"); ?>' + post_id,
		success: function(data) {
        	jQuery('.modal__title').html(data.title.rendered);
        	jQuery('.modal__content').html(data.content.rendered);
      	}
    });
    
    return false;
}
	

</script>