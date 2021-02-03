<?php
	
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
	
	setcookie('response["name"]', sanitize_text_field($_REQUEST['respondent']), $expiration);
	setcookie('response["email"]', sanitize_text_field($_REQUEST['email']), $expiration);

	echo json_encode(array('message' => '<div class="alert alert-success" role="alert">Thank you!  We received your response and our team will follow up with you soon.</div>'));
	
	wp_die();
}