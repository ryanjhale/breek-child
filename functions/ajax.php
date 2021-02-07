<?php
	
/* Get lessons */

add_action('wp_ajax_nopriv_lessons', 'cfm_get_lesson');
add_action('wp_ajax_lessons', 'cfm_get_lesson');

function cfm_get_lesson(){
	
	// Check the data

	check_ajax_referer('sp-ajax-nonce', 'security');

	
	if(!isset($_REQUEST['post'])) {
		$error = array('error' => 1, 'message' => '<div class="alert alert-danger" role="alert">Sorry, we need the information for this lesson but we did not receive it.</div>');
	} else {
		$post_id = $_REQUEST['post'];
	}
	
	if(isset($_REQUEST['email']) && is_email($_REQUEST['email'])) {
		$email = $_REQUEST['email'];
	}
	
	if(isset($_REQUEST['name'])) {
		$name = $_REQUEST['name'];
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
    
    $lesson_type = get_post_meta($post_id, 'lesson_type', true);
    
    $response_form = '';
    
    if($lesson_type == 'question') {
	    
	    if(CFM_ENV == 'prod-english' || CFM_ENV == 'dev') {
		    
		    $respond_header = 'Respond';
		    $comment_content_placeholder = 'Your answer';
		    $comment_author_placeholder = 'Name';
		    $comment_author_email_placeholder = 'Email';
		    $submit_button_text = 'Submit';
		    
	    } elseif(CFM_ENV == 'prod-italian') {
		    
		    $respond_header = 'Rispondi';
		    $comment_content_placeholder = 'La tua risposta';
		    $comment_author_placeholder = 'Nome';
		    $comment_author_email_placeholder = 'Email';
		    $submit_button_text = 'Invia';
		    
	    } elseif(CFM_ENV == 'prod-french') {
		    
		    $respond_header = 'Répondre';
		    $comment_content_placeholder = 'Votre réponse';
		    $comment_author_placeholder = 'Nom';
		    $comment_author_email_placeholder = 'Courriel';
		    $submit_button_text = 'Soumettre';
		    
	    }
	    
	    $comment_content_value = '';
	    $comment_author_value = '';
	    $comment_author_email_value = '';
	    
	    $disabled_comment = '';
		$disabled_author = '';
		
		$change_name_span = '';
		$change_email_span = '';
		$change_response_span = '';
	    
	    if(isset($email) && isset($name)) {
		    
		    if(CFM_ENV == 'prod-english' || CFM_ENV == 'dev') {
		    
			    $change_name = 'Change name';
			    $change_email = 'Change email';
			    
		    } elseif(CFM_ENV == 'prod-italian') {
			    
			    $change_name = 'Cambia nome';
			    $change_email = 'Cambia email';
			    
		    } elseif(CFM_ENV == 'prod-french') {
			    
			    $change_name = 'Changement de nom';
			    $change_email = 'Changer de courriel';
			    
		    }
		    
		    $change_name_span = '<span id="change-name" style="font-size:12px; cursor:pointer; color:#E84E89; text-decoration:underline; margin-bottom:15px;">' . $change_name . '</span>';
		    $change_email_span = '<span id="change-email" style="font-size:12px; cursor:pointer; color:#E84E89; text-decoration:underline; margin-bottom:15px;">' . $change_email . '</span>';
		    
		    
		    $comment_author_value = $name;
		    $comment_author_placeholder = $name;
		    
		    $comment_author_email_value = $email;
		    $comment_author_email_placeholder = $email;
		    
		    $disabled_author = 'disabled';
		    
		    $args = array(
			    
		    			  'post_id' 		=> $post_id,
	    			  	  'author_email' 	=> $email,
	    			  	  'number'			=> 1,
	    			  	  'orderby'			=> 'comment_date',
					  	  'order'			=> 'DESC'
					  	  
						);
		    
		    $comments_query = new WP_Comment_Query($args); 
			$comments = $comments_query->comments;
			
			if(!empty($comments[0])) {
				
				if(CFM_ENV == 'prod-english' || CFM_ENV == 'dev') {
		    
				    $change_response = 'Change your response';
				    
			    } elseif(CFM_ENV == 'prod-italian') {
				    
				    $change_response = 'Cambia la tua risposta';
				    
			    } elseif(CFM_ENV == 'prod-french') {
				    
				    $change_response = 'Modifier votre réponse';
				    
			    }
			    
			    $change_response_span = '<span id="change-response" style="font-size:12px; cursor:pointer; color:#E84E89; text-decoration:underline; margin-bottom:15px;">' . $change_response . '</span>';
				
				$comment_content_value = $comments[0]->comment_content;
				$comment_author_value = $comments[0]->comment_author;
				$comment_author_email_value = $comments[0]->comment_author_email;
				$disabled_comment = 'disabled';
				
			}
			
			
				
		}
		
		$response_form = '<div id="respond" class="comment-respond"><h3 id="reply-title" class="comment-reply-title title bordered" style="margin-top:40px;">' . $respond_header . '</h3><textarea id="comment" name="comment" aria-required="true" placeholder="' . $comment_content_placeholder . '" rows="10"' . ' ' . $disabled_comment . ' style="margin-bottom:0px;">' . $comment_content_value . '</textarea>' . $change_response_span . '<input class="form-author" name="name" type="text" value="' . $comment_author_value . '" size="30" aria-required="true" placeholder="' . $comment_author_placeholder . '"' . ' ' . $disabled_author . ' style="margin-bottom:0px;">' . $change_name_span . '<input class="form-email" name="email" type="text" value="' . $comment_author_email_value . '" size="30" aria-required="true" placeholder="' . $comment_author_email_placeholder . '"' . ' ' . $disabled_author . ' style="margin-bottom:0px;">' . $change_email_span . '<p class="form-submit"><button class="btn respondsubmit" style="display:inline-block;color:#fff;border:2px solid transparent;letter-spacing:0.5px;font-weight600;border-radius:25px;background-color:#E84E89;font-size:18px;padding:10px 30px;appearance:none;"' . ' ' . $disabled_comment . '>' . $submit_button_text . '</button><input type="hidden" name="post_id" value="" id="post_id"><input type="hidden" name="comment_parent" id="comment_parent" value="0"></p></form></div>';
		
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