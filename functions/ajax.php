<?php
	
/* Get lessons */

add_action('wp_ajax_nopriv_lessons', 'cfm_get_lesson');
add_action('wp_ajax_lessons', 'cfm_get_lesson');

function cfm_get_lesson(){
	
	// Check the data

	check_ajax_referer('sp-ajax-nonce', 'security');

	
	if(!isset($_REQUEST['post'])) {
		$error = array('error' => 1, 'message' => '<div class="alert alert-danger" role="alert">Please enter a valid email address.</div>');
	} else {
		$post_id = $_REQUEST['post'];
	}
	
	if(isset($_REQUEST['email']) && is_email($_REQUEST['email'])) {
		$email = $_REQUEST['email'];
	}
	
	// Post data
	
	$post = get_post($post_id);
	
	// Button data
	
	$button_meta = get_post_meta($post_id);
    
    $button1 = '';
    $button2 = '';
    $button3 = '';
    
    if(!empty($button_meta['button1_postid'][0])) {
    	$button1 = '<button type="button" class="btn btn-' . $button_meta['button1_color'][0] . ' button1" onclick="getModalContent(' . $button_meta['button1_postid'][0] . ')">' . $button_meta['button1_text'][0] . '</button>';
	}
	
	if(!empty($button_meta['button2_postid'][0])) {
    	$button2 = '<button type="button" class="btn btn-' . $button_meta['button2_color'][0] . ' button2" onclick="getModalContent(' . $button_meta['button2_postid'][0] . ')">' . $button_meta['button2_text'][0] . '</button>';
	}
	
	if(!empty($button_meta['button3_postid'][0])) {
    	$button3 = '<button type="button" class="btn btn-' . $button_meta['button3_color'][0] . ' button3" onclick="getModalContent(' . $button_meta['button3_postid'][0] . ')">' . $button_meta['button3_text'][0] . '</button>';
	}
    
    // Response form for questions
    
    $lesson_type = get_field('lesson_type', $post_id);
    
    $response_form = '';
    
    $prefilled_form = '<div id="respond" class="comment-respond"><h3 id="reply-title" class="comment-reply-title title bordered" style="margin-top:40px;">Respond</h3><textarea id="comment" name="comment" aria-required="true" rows="10">' . $comment[0]->comment_content . '</textarea><input class="form-author" name="name" type="text" value="' . $comment[0]->comment_author . '" size="30" aria-required="true" required=""><input class="form-email" name="email" type="text" value="' . $comment[0]->comment_author_email . '" size="30" aria-required="true" required=""><p class="form-submit"><button class="btn respondsubmit" style="display:inline-block;color:#fff;border:2px solid transparent;letter-spacing:0.5px;font-weight600;border-radius:25px;background-color:#E84E89;font-size:18px;padding:10px 30px;appearance:none;">Submit</button><input type="hidden" name="post_id" value="" id="post_id"><input type="hidden" name="comment_parent" id="comment_parent" value="0"></p></form></div>';
    
    $empty_form = '<div id="respond" class="comment-respond"><h3 id="reply-title" class="comment-reply-title title bordered" style="margin-top:40px;">Respond</h3><textarea id="comment" name="comment" aria-required="true" rows="10" placeholder="Your Response"></textarea><input class="form-author" name="name" type="text" placeholder="Name" value="" size="30" aria-required="true" required=""><input class="form-email" name="email" type="text" placeholder="Email" value="" size="30" aria-required="true" required=""><p class="form-submit"><button class="btn respondsubmit" style="display:inline-block;color:#fff;border:2px solid transparent;letter-spacing:0.5px;font-weight600;border-radius:25px;background-color:#E84E89;font-size:18px;padding:10px 30px;appearance:none;">Submit</button><input type="hidden" name="post_id" value="" id="post_id"><input type="hidden" name="comment_parent" id="comment_parent" value="0"></p></form></div>';
    
    if($lesson_type == 'question') {
	    
	    if(isset($email)) {
	    
		    $args = array('post_id' 		=> $post_id,
		    			  'author_email' 	=> $email,
		    			  'count'			=> true,
		    );
		    
		    $comment = get_comments();
		    
		    if($comment > 0) {
			    
			    $args = array('post_id' 		=> $post_id,
		    			  	  'author_email' 	=> $email,
		    			  	  'number'			=> 1,
		    			  	  'orderby'			=> 'comment_date',
						  	  'order'			=> 'DESC'
						);
		    
				$comment = get_comments();
				
				$response_form = $prefilled_form;
			
			} else {
				
				$response_form = $empty_form;
				
			}
				
		} else {
			
			$response_form = $empty_form;
			
		}
		
	}
	
	$data = array(
				  'id'					=> $post_id,
				  'post_title' 			=> $post->post_title,
				  'post_content' 		=> post_password_required( $post ) ? '' : apply_filters( 'the_content', $post->post_content ),
				  'button1'				=> $button1,
				  'button2'				=> $button2,
				  'button3'				=> $button3,
				  'response'			=> $response_form,
	);
	

	echo json_encode($data);
	
	wp_die();
}

/* Handle responses through yellow light and green light forms */

add_action('wp_ajax_nopriv_respond', 'cfm_handle_response');
add_action('wp_ajax_respond', 'cfm_handle_response');

function cfm_handle_response(){
	
	// Check the data

	check_ajax_referer('sp-ajax-nonce', 'security');

	
	if(!is_email($_REQUEST['email'])) {
		$error = array('error' => 1, 'message' => '<div class="alert alert-danger" role="alert">Please enter a valid email address.</div>');
	}
	
	if(empty($_REQUEST['respondent'])) {
		$error = array('error' => 1, 'message' => '<div class="alert alert-danger" role="alert">Please enter your name.</div>');
	}
	
	if(empty($_REQUEST['commenttext'])) {
		$error = array('error' => 1, 'message' => '<div class="alert alert-danger" role="alert">Please enter your response.</div>');
	}
		
	if(isset($error)) {
		echo json_encode($error);
		wp_die();
	}
	
	$response_data = array(
		'comment_author'			=> sanitize_text_field($_REQUEST['respondent']),
		'comment_author_email'		=> sanitize_text_field($_REQUEST['email']),
		'comment_content'			=> sanitize_text_field($_REQUEST['commenttext']),
		'comment_post_ID'			=> sanitize_text_field($_REQUEST['post_id']),
	);
	
	$comment_id = wp_insert_comment($response_data);
	
	if(!$comment_id) {
		$error = array('error' => 1, 'message' => '<div class="alert alert-danger" role="alert">We are sorry.  There was an error in recording your response.</div>');
		wp_die();
	}
	
	$expiration = time()+(3600 * 1000 * 24 * 365);
	
	// setcookie('name', sanitize_text_field($_REQUEST['respondent']), $expiration);
	// setcookie('email', sanitize_text_field($_REQUEST['email']), $expiration);

	echo json_encode(array('message' => '<div class="alert alert-success" role="alert">Thank you!  We received your response and our team will follow up with you soon.</div>'));
	
	wp_die();
}