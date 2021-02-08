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
	
	if(isset($_REQUEST['phone'])) {
		$phone = $_REQUEST['phone'];
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
		    $change_response_text = '<div id="already-answered" style="margin-bottom:30px;">You previously answered this question.<br />If you would like, you can ';
		    $change_response_link = 'change your answer';
		    $confirmation_text = '<div id="confirmation" style="margin-top:15px;margin-bottom:15px;display:none;">Thank you!  We have received your response.<br />If you would like, you can ';
		    
	    } elseif(CFM_ENV == 'prod-italian') {
		    
		    $respond_header = 'Rispondi';
		    $comment_content_placeholder = 'La tua risposta';
		    $comment_author_placeholder = 'Nome';
		    $comment_author_email_placeholder = 'Email';
		    $submit_button_text = 'Invia';
		    $change_response_text = '<div id="already-answered" style="margin-bottom:30px;">Hai già risposto a questa domanda.<br />Se vuoi, puoi ';
		    $change_response_link = 'cambiare la tua risposta';
		    $confirmation_text = '<div id="confirmation" style="margin-top:15px;margin-bottom:15px;display:none;">Grazie!  Abbiamo ricevuto la sua risposta.<br />Se vuoi, puoi ';
		    
	    } elseif(CFM_ENV == 'prod-french') {
		    
		    $respond_header = 'Répondre';
		    $comment_content_placeholder = 'Votre réponse';
		    $comment_author_placeholder = 'Nom';
		    $comment_author_email_placeholder = 'Courriel';
		    $submit_button_text = 'Soumettre';
		    $change_response_text = '<div id="already-answered" style="margin-bottom:30px;">Vous avez déjà répondu à cette question.<br />Si vous le souhaitez, vous pouvez ';
		    $change_response = 'modifier votre réponse';
		    $confirmation_text = '<div id="confirmation" style="margin-top:15px;margin-bottom:15px;display:none;">Merci !  Nous avons reçu votre réponse.<br />Si vous le souhaitez, vous pouvez ';
		    
	    }
	  
		$confirm_response_span = $confirmation_text . '<span class="change-response" style="cursor:pointer; color:#E84E89; text-decoration:underline;">' . $change_response_link . '</span>.</div>';
	    
	    $comment_content_value = '';
	    $comment_author_value = '';
	    $comment_author_email_value = '';
	    
	    $disabled = '';
	    
	    $change_response_span = '';
	    
	    if(isset($email) && isset($name)) {
		    
		    $comment_author_value = $name;
		    $comment_author_placeholder = $name;
		    
		    $comment_author_email_value = $email;
		    $comment_author_email_placeholder = $email;
		    
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
		    
				    $change_response_text = '<div id="already-answered" style="margin-bottom:30px;">You previously answered this question.<br />If you would like, you can ';
				    $change_response_link = 'change your answer';
				    
			    } elseif(CFM_ENV == 'prod-italian') {
	
				    $change_response_text = '<div id="already-answered" style="margin-bottom:30px;">Hai già risposto a questa domanda.<br />Se vuoi, puoi ';
				    $change_response_link = 'cambiare la tua risposta';
				    
			    } elseif(CFM_ENV == 'prod-french') {
	
				    $change_response_text = '<div id="already-answered" style="margin-bottom:30px;">Vous avez déjà répondu à cette question.<br />Si vous le souhaitez, vous pouvez ';
				    $change_response = 'modifier votre réponse';
				    
			    }
			    
			    $change_response_span = $change_response_text . '<span class="change-response" style="cursor:pointer; color:#E84E89; text-decoration:underline;">' . $change_response_link . '</span>.</div>';
				
				$comment_content_value = $comments[0]->comment_content;
				$comment_author_value = $comments[0]->comment_author;
				$comment_author_email_value = $comments[0]->comment_author_email;
				$disabled = 'disabled';
				
			}
			
			
				
		}
		
		$response_form = '<div id="respond" class="comment-respond"><h3 id="reply-title" class="comment-reply-title title bordered" style="margin-top:40px;">' . $respond_header . '</h3>' . $change_response_span . '<textarea id="comment" name="comment" aria-required="true" placeholder="' . $comment_content_placeholder . '" rows="10"' . ' ' . $disabled . ' style="margin-bottom:0px;">' . $comment_content_value . '</textarea><input class="form-author" name="name" type="text" value="' . $comment_author_value . '" size="30" aria-required="true" placeholder="' . $comment_author_placeholder . '"' . ' ' . $disabled . ' style="margin-bottom:0px; margin-top:15px;"><input class="form-email" name="email" type="text" value="' . $comment_author_email_value . '" size="30" aria-required="true" placeholder="' . $comment_author_email_placeholder . '"' . ' ' . $disabled . ' style="margin-bottom:15px; margin-top:15px;">' . $confirm_response_span . '<p class="form-submit"><button class="btn respondsubmit" style="display:inline-block;color:#fff;border:2px solid transparent;letter-spacing:0.5px;font-weight600;border-radius:25px;background-color:#E84E89;font-size:18px;padding:10px 30px;appearance:none;"' . ' ' . $disabled . '>' . $submit_button_text . '<i class="fa fa-spinner fa-pulse fa-fw" style="display: none;"></i></button><input type="hidden" name="post_id" value="" id="post_id"><input type="hidden" name="comment_parent" id="comment_parent" value="0"></p></form></div>';
		
	}
	
	if($lesson_type == 'contact') {
	    
	    if(CFM_ENV == 'prod-english' || CFM_ENV == 'dev') {
		    
		    $respond_header = 'Contact Me';
		    $comment_content_placeholder = 'Comment';
		    $comment_author_placeholder = 'Name';
		    $comment_author_email_placeholder = 'Email';
		    $comment_author_phone_placeholder = 'Phone';
		    $submit_button_text = 'Submit';
		    $confirmation_text = '<div id="confirmation" style="margin-top:15px;margin-bottom:15px;display:none;">Thank you!  We have received your message and our team will contact you soon.</div>';
		    
	    } elseif(CFM_ENV == 'prod-italian') {
		    
		    $respond_header = 'Rispondi';
		    $comment_content_placeholder = 'La tua risposta';
		    $comment_author_placeholder = 'Nome';
		    $comment_author_email_placeholder = 'Email';
		    $comment_author_phone_placeholder = 'Telefono';
		    $submit_button_text = 'Invia';
		    $confirmation_text = '<div id="confirmation" style="margin-top:15px;margin-bottom:15px;display:none;">Grazie!  Abbiamo ricevuto il tuo messaggio e la nostra squadra ti contatterà presto.</div>';
		    
	    } elseif(CFM_ENV == 'prod-french') {
		    
		    $respond_header = 'Répondre';
		    $comment_content_placeholder = 'Votre réponse';
		    $comment_author_placeholder = 'Nom';
		    $comment_author_email_placeholder = 'Courriel';
		    $comment_author_phone_placeholder = 'Téléphone';
		    $submit_button_text = 'Soumettre';
		    $confirmation_text = '<div id="confirmation" style="margin-top:15px;margin-bottom:15px;display:none;">Merci !  Nous avons reçu votre message et notre équipe vous contactera bientôt.</div>';
		    
	    }
		
		$response_form = '<div id="respond" class="comment-respond"><textarea id="comment" name="comment" aria-required="true" placeholder="' . $comment_content_placeholder . '" rows="10" style="margin-bottom:0px;"></textarea><input class="form-author" name="name" type="text" value="" size="30" aria-required="true" placeholder="' . $comment_author_placeholder . '" style="margin-bottom:0px; margin-top:15px;"><input class="form-email" name="email" type="text" value="" size="30" aria-required="true" placeholder="' . $comment_author_email_placeholder . '" style="margin-bottom:15px; margin-top:15px;"><input class="form-phone" name="phone" type="text" value="" size="30" aria-required="true" placeholder="' . $comment_author_phone_placeholder . '" style="margin-bottom:15px; margin-top:15px;">' . $confirmation_text . '<p class="form-submit"><button class="btn contactmesubmit" style="display:inline-block;color:#fff;border:2px solid transparent;letter-spacing:0.5px;font-weight600;border-radius:25px;background-color:#E84E89;font-size:18px;padding:10px 30px;appearance:none;"><i class="fa fa-spinner fa-pulse fa-fw" style="display: none;"></i></button><input type="hidden" name="post_id" value="" id="post_id"><input type="hidden" name="comment_parent" id="comment_parent" value="0"></p></form></div>';
		
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

	echo json_encode(array('message' => 'Success!'));
	
	wp_die();
}

/* Handle responses through yellow light and green light forms */

add_action('wp_ajax_nopriv_contactme', 'cfm_contact_me');
add_action('wp_ajax_contactme', 'cfm_contact_me');

function cfm_contact_me(){
	
	// Check the data

	check_ajax_referer('sp-ajax-nonce', 'security');
	
	$comment_author       = ! isset( $_REQUEST['respondent'] ) ? '' : sanitize_text_field($_REQUEST['respondent']);
    $comment_author_email = ! isset( $_REQUEST['email'] ) ? '' : sanitize_text_field($_REQUEST['email']);
    $comment_author_phone   = ! isset( $_REQUEST['phone'] ) ? '' : sanitize_text_field($_REQUEST['phone']);
    $comment_content    = ! isset( $_REQUEST['commenttext'] ) ? '' : sanitize_text_field($_REQUEST['commenttext']);
    $comment_post_id    = ! isset( $_REQUEST['post_id'] ) ? '' : sanitize_text_field($_REQUEST['post_id']);
	
	$response_data = array(
		'comment_author'			=> sanitize_text_field($_REQUEST['respondent']),
		'comment_author_email'		=> sanitize_text_field($_REQUEST['email']),
		'comment_content'			=> sanitize_text_field($_REQUEST['commenttext']),
		'comment_post_ID'			=> sanitize_text_field($_REQUEST['post_id']),
		'comment_author_url'		=> sanitize_text_field($_REQUEST['phone'])
	);
	
	$comment_id = wp_insert_comment($response_data);
	
	if(!$comment_id) {
		$error = array('error' => 1, 'message' => '<div class="alert alert-danger" role="alert">We are sorry.  There was an error in recording your response.</div>');
		wp_die();
	}

	echo json_encode(array('message' => 'Success!'));
	
	wp_die();
}