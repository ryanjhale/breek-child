<?php

add_filter('template_redirect', 'cfm_redirects');

function cfm_redirects() {

    if(is_404()) {
	    
	    global $wp_query;
	    
	    $url = strtok($_SERVER["REQUEST_URI"],'?');
		$parts = explode('/', $url);
	    
	    if(isset($parts[1]) && $parts[1] == 'post') {
		
			$post_id = intval($parts[2]);
			
			$post = get_post($post_id);
			
			if(!empty($post) && isset($post->ID)) {
				
				$permalink = get_permalink($post);
				
				if ($wp_query->is_404) {
			        $wp_query->is_404 = false;

					wp_redirect($permalink, 301);
					exit;
			    }
			}
		}
    }
    /*
    if (in_array(CFM_PATH, array('/abide', '/abide/', '/dimorare', '/dimorare/'))) {
        
        if ($wp_query->is_404) {
	        $wp_query->is_404 = false;
	        header("HTTP/1.1 200 OK");
	    }
        
        include( get_stylesheet_directory().'/abide.php' );
        exit;
    }
    */
    
}