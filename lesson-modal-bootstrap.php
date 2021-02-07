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
.wp-block-image {
    margin-bottom: 0;
}
</style>

<div class="modal fade" id="lesson-modal" tabindex="-1" role="dialog" aria-labelledby="important-msg-label" aria-hidden="true">
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

<?php $nonce = wp_create_nonce( "sp-ajax-nonce" ); ?>

<script>
	
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    console.log(ca);
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}
	
function getModalContent(post_id) {
	
	// jQuery('.modal-body').empty();
	jQuery('.button1').remove();
	jQuery('.button2').remove();
	jQuery('.button3').remove();
	
	var email = readCookie('email');
	var name = readCookie('name');
	
	var content = {};
	content.post = post_id;
	content.security = '<?php echo $nonce; ?>';
	content.action = 'lessons';
	content.email = email;
	content.name = name;
	
	jQuery.ajax({
		type:    'POST',
		url:     ajax_var.url,
		data:    content,
		success: function(data) {
        	
        	var obj = jQuery.parseJSON(data);
        	
        	var close = '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
            
            var comments = obj.response;
            
            var display_content = close + obj.post_content + comments;
                                  
        	jQuery('.modal-body').html(display_content);
        	
        	jQuery('.modal-body').animate({ scrollTop: 0 }, 'slow');
        	
        	jQuery('#post_id').attr('value', obj.id);
        	
        	var button1 = obj.button1;   	
        	var button2 = obj.button2;
        	var button3 = obj.button3;
        	
        	jQuery('.button1').remove();
        	jQuery('.button2').remove();
        	jQuery('.button3').remove();

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

jQuery(document).ready(function() {

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
	
	
	
	
	
	
	jQuery(document).on('click','.respondsubmit',function(e) {
		respondSubmit();
	});
		
	function respondSubmit() {
		
		// jQuery('.fa-spinner').show();
		// jQuery('.one-moment-message').show();
		jQuery('.respondsubmit').prop('disabled', true);
				
				
		var response = {};
		response.respondent = jQuery('input[name=name]').val();
		response.email = jQuery('input[name=email]').val();
		response.commenttext = jQuery('#comment').val();
		response.action = 'respond';
		response.security = '<?php echo $nonce; ?>';
		response.post_id = jQuery('input[name=post_id]').val();
		response.comment_parent = jQuery('input[name=comment_parent]').val();
		
		jQuery('.respondsubmit').prop('disabled', true);
		
		jQuery.ajax({
			action: 'respond',
			type:    'POST',
			url:     ajax_var.url,
			data:    response,
			success: function(data) {
	        	var obj = jQuery.parseJSON(data);
	        	
	        	if(obj.error == '1') {
		        	
		        	jQuery('.respondsubmit').prop('disabled', false);
		        	jQuery('#respond').before(obj.message);
		        	
	        	} else {
		        	
		        	createCookie('email', jQuery('input[name=email]').val(), 365);
		        	createCookie('name', jQuery('input[name=name]').val(), 365);
		        	jQuery('input[name=name]').prop('disabled', true);
		        	jQuery('input[name=email]').prop('disabled', true);
		        	jQuery('#respond').before(obj.message);
		        	jQuery('#respond').hide();
		        		
	        	}
	
	      	}
	    });
	    
	    return false;
	}
});

</script>
