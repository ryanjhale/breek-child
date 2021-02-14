<?php

add_filter('template_redirect', 'cfm_redirects');

function cfm_redirects() {

    global $wp_query;
    $url = strtok($_SERVER["REQUEST_URI"],'?');
	$parts = explode('/', $url);
	
	if(isset($parts[3]) && $parts[3] == 'post') {
		
		$post_id = intval($parts[4]);
		
		$post = get_post($post_id);
		
		if(!empty($post) && isset($post->ID)) {
			
			$permalink = get_permalink($post);
			
			if ($wp_query->is_404) {
		        $wp_query->is_404 = false;
		        
		        // 301 Moved Permanently
				header("Location: " . $permalink, TRUE, 301);
				wp_redirect($permalink);
				exit;
		    }
		}
	}
    
}