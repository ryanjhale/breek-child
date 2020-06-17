<?php

add_action( 'wp_enqueue_scripts', 'epcl_breek_child_styles', 100 );

function epcl_breek_child_styles() {
    wp_enqueue_style( 'breek-child-css', get_stylesheet_uri() );
}

function epcl_child_theme_slug_setup() {
    load_child_theme_textdomain( 'breek', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'epcl_child_theme_slug_setup' );

/* You can add your custom functions just below */

class epcl_theme_setup {

	public function __construct() {

		/* Theme Includes */

		add_action('after_setup_theme', array( $this, 'includes' ), 4 );

		/* Main Theme Options */

        add_action('after_setup_theme', array( $this, 'theme_support') );
        
        /* AMP functions */
        
        add_action('plugins_loaded', array( $this, 'amp_functions') );

	}

	public function includes(){

		/* Main Includes */

		require_once(EPCL_ABSPATH.'/functions/import/import-demo.php');
		require_once(EPCL_ABSPATH.'/functions/post-formats.php');
        require_once(EPCL_ABSPATH.'/functions/enqueue-scripts.php');
        require_once(EPCL_ABSPATH.'/functions/color-helper.php');
		require_once(EPCL_ABSPATH.'/functions/custom-styles.php');
		require_once(EPCL_ABSPATH.'/functions/theme-functions.php'); // Specific functions for this particular theme
        require_once(EPCL_ABSPATH.'/functions/core.php'); // Common functions for all EP themes
        // require_once(EPCL_ABSPATH.'/functions/googlefont.php');

		/* Plugins */

		require_once(EPCL_ABSPATH.'/functions/plugins/class-tgm-plugin-activation.php');
        require_once(EPCL_ABSPATH.'/functions/plugins/recommended-plugins.php');
        
        /* Theme Wizard */

        if (!is_customize_preview()  && is_admin() ) {
            require_once(EPCL_ABSPATH.'/functions/merlin/vendor/autoload.php');
            require_once(EPCL_ABSPATH.'/functions/merlin/class-merlin.php');
            require_once(EPCL_ABSPATH.'/functions/merlin/merlin-config.php');
            require_once(EPCL_ABSPATH.'/functions/merlin/merlin-import-demo.php');
        }

 

    }
    
    public function amp_functions(){
        exit;

        if( epcl_is_amp() ){
         
           require_once(EPCL_ABSPATH.'/amp/functions.php');
        }

	}

	public function theme_support(){

		/* Languages */

		load_theme_textdomain('breek', EPCL_ABSPATH.'/languages');

		/* Thumbs */

		if( function_exists('add_theme_support') ){
			add_theme_support('post-formats', array( 'video', 'gallery', 'audio', 'aside' ) );
			add_theme_support('post-thumbnails');
			add_theme_support('automatic-feed-links');
			add_theme_support('html5');
            add_theme_support('title-tag');
            add_theme_support('editor-styles'); // Gutenberg Support
            add_theme_support('responsive-embeds');
            add_theme_support( 'amp', array(
                'paired' => true,
                'template_dir' => 'amp',
                'templates_supported' => array(
                    // 'is_front_page' => true,
                    // 'is_home' => true,
                    'is_singular' => true,
                    'is_search' => false,
                ),
            ) );
            
            if( epcl_get_option('enable_gutenberg_admin') !== '0'){
                add_editor_style( epcl_gutenberg_fonts_url() ); // Enqueue fonts in the gutenberg editor               
                add_editor_style( 'assets/dist/gutenberg.min.css' ); // Enqueue custom styles in the Gutenberg editor
            }

			$prefix = EPCL_THEMEPREFIX.'_';

			add_image_size($prefix.'admin_thumb', 100, 100, false);

			add_image_size($prefix.'classic_post', 850, 600, true);

			add_image_size($prefix.'single_standard', 950, 500, true);
			add_image_size($prefix.'single_related', 600, 450, true);
			add_image_size($prefix.'single_content', 700, 450, false);

			add_image_size($prefix.'page_header', 1950, 500, true);

			add_image_size($prefix.'widget_thumb', 120, 120, true); // Required on widgets

			add_image_size($prefix.'large', 1600, 1200, false); // Required on portfolio lightbox

		}

		/* Menus */

		register_nav_menus(array(
			'epcl_header' => esc_html__('Header', 'breek')
		));

		/* Register Sidebars */

		require_once(EPCL_ABSPATH.'/functions/sidebars.php');

    }       

}

new epcl_theme_setup;



function cfm_display_post_format($format = '', $post_id){

	if( !function_exists('acf_add_local_field_group') ) // If not custom metaboxes, always uses format image
		$format = 'image';

	$prefix = EPCL_THEMEPREFIX.'_';
	switch($format){

        default: // Standard and Image post format
		case 'image':
			return epcl_get_image_format($post_id);
        break;
        
		case 'video':
			$video_type = get_field('video_type', $post_id);
            $video_url = get_field('video_url', $post_id);
            
			if($video_type)
				return epcl_get_video_format($post_id, $video_type, $video_url, 250);
        break;
        
		case 'gallery':
			$gallery_images = get_field('gallery', $post_id);
			if( !empty($gallery_images) )
				return epcl_get_gallery_format($gallery_images);
        break;
        
		case 'audio':
			$audio_url = get_field('soundcloud_url', $post_id);
			if( $audio_url )
				return epcl_get_audio_format( $post_id, $audio_url );
        break;
        
		case 'link':
            $link_url = get_field('link_url', $post_id);
			if($link_url)
				return epcl_get_link_format($link_url);
        break;
        
        case 'aside':
			$video_type = get_field('aside_video_type', $post_id);
            $video_url = get_field('aside_video_url', $post_id);
            
			if($video_type)
				return epcl_get_video_format($post_id, $video_type, $video_url, 250);
        break;
        
	}
}

function cfm_get_aside_format($post_id){  
	
	/* Custom function by Ryan
		
	   Copied from epcl_get_image_format but...
	   
	   1.  removed display of categories
	   
	   (Intended for display of landing page)
	   
    */
	
    $epcl_theme = epcl_get_theme_options();
   
    $post_style = get_query_var('epcl_post_style');
    $class =  $image_alt = $video_lightbox = '';
    // Loop
    if( !is_single() ){
        $optimized_image = '';
        $size = 'epcl_single_content';
        if( $post_style == 'classic' ){
            $size = 'epcl_classic_post';
        }
        $thumb_url = get_the_post_thumbnail_url($post_id, $size);
        if( function_exists('get_field') ){
            $optimized_image = get_field('optimized_image');
            $video_lightbox = get_field('video_lightbox', $post_id);
            $video_url = get_field('video_url', $post_id);
            if( !empty($optimized_image) ){
                $image_alt = $optimized_image['alt'];
            }            
        }           
        if( !empty($optimized_image) ){
            $thumb_url = $optimized_image['url'];
        }
        if( !$thumb_url ){
            $class = 'hidden';
        }
    // Single Post
    }else{
        $single_size = 'epcl_single_standard';
        if( function_exists('get_field') && get_field('enable_sidebar') === false ){
            $single_size = 'epcl_page_header';
        }
        $image_id = get_post_thumbnail_id( get_the_ID() );
        $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true);
        $image_url = get_the_post_thumbnail_url( get_the_ID() , $single_size);
        // $image_url = str_replace( array('.jpg', '.png', '.gif'), '.webp', $image_url );
        $image_webp_url = str_replace( 'uploads', 'uploads-webpc', $image_url ).'.webp';
    }
    if( !$image_alt ){
        $image_alt = get_the_title();
    }
    // if( is_single() && !has_post_thumbnail() ) return;
?>
	<div class="post-format-image post-format-wrapper <?php echo esc_attr($class); ?>">
        <?php if( is_single() ): // Single Post ?>
            <?php if( has_post_thumbnail() ): ?>
                <div class="featured-image">
                    <?php if( epcl_is_amp() ): ?>
                        <!-- <amp-img alt="<?php echo esc_attr($image_alt); ?>"
                        width="950"
                        height="500"
                        layout="intrinsic"
                        src="<?php echo esc_url($image_webp_url); ?>">
                        <amp-img alt="<?php echo esc_attr($image_alt); ?>"
                            fallback
                            width="950"
                            height="500"
                            layout="intrinsic"
                            src="<?php echo esc_url($image_url); ?>"></amp-img>
                        </amp-img> -->
                        <?php the_post_thumbnail( $single_size ); ?>
                    <?php else: ?>
                        <?php the_post_thumbnail( $single_size ); ?>
                    <?php endif ; ?>
                    <?php // echo epcl_render_categories(); ?>
                </div>
            <?php else: ?>
                <?php // echo epcl_render_categories( '' ); ?>
            <?php endif; ?>
		<?php else: // Loops ?>
			<div class="featured-image">
                <?php if( $video_lightbox == true && $video_url ): ?>    
                    <?php
                    $video_type = get_field('video_type', $post_id);
                    if ($video_type == 'custom') {
                        $custom_embed = get_field('custom_embed', $post_id);
                        if( $custom_embed ){
                            preg_match('/src="([^"]+)"/', $custom_embed, $match);
                            $video_url = $match[1];
                        }                        
                    }
                    ?>
                    <a class="video-overlay full-link lightbox mfp-iframe" href="<?php echo esc_url($video_url); ?>"></a>
                <?php endif; ?>
                <?php if( $thumb_url ): ?>
                    <a href="<?php the_permalink(); ?>" class="thumb hover-effect">
                        <?php if( epcl_is_amp() ): ?>
                            <amp-img class="cover" layout="fill" src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($image_alt); ?>"></amp-img>
                        <?php else: ?>
                            <?php if( !empty($epcl_theme) && $epcl_theme['enable_lazyload'] == '1' ): ?>
                                <span class="fullimage cover lazy" role="img" aria-label="<?php echo esc_attr($image_alt); ?>" data-src="<?php echo esc_url($thumb_url); ?>" ></span>
                            <?php else: ?>
                                <span class="fullimage cover" role="img" aria-label="<?php echo esc_attr($image_alt); ?>" style="background-image: url(<?php echo esc_url($thumb_url); ?>);"></span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
                <?php // echo epcl_render_categories(); ?>
			</div>
		<?php endif; ?>
	</div>
<?php
}



$url = get_bloginfo('url');
define('CFM_URL', $url);

$path = strtok($_SERVER["REQUEST_URI"],'?');
define('CFM_PATH', $path);

add_filter('template_redirect', 'cfm_redirect');

function cfm_redirect() {

    global $wp_query;
    
    if (in_array(CFM_PATH, array('/contact', '/contact/'))) {
        
        if ($wp_query->is_404) {
	        $wp_query->is_404 = false;
	        header("HTTP/1.1 200 OK");
	    }
        
        include( get_stylesheet_directory().'/contact.php' );
        exit;
    }
    


}


/**
 * Sets the extension and mime type for .webp files.
 *
 * @param array  $wp_check_filetype_and_ext File data array containing 'ext', 'type', and
 *                                          'proper_filename' keys.
 * @param string $file                      Full path to the file.
 * @param string $filename                  The name of the file (may differ from $file due to
 *                                          $file being in a tmp directory).
 * @param array  $mimes                     Key is the file extension with value as the mime type.
 */
add_filter( 'wp_check_filetype_and_ext', 'wpse_file_and_ext_webp', 10, 4 );
function wpse_file_and_ext_webp( $types, $file, $filename, $mimes ) {
    if ( false !== strpos( $filename, '.webp' ) ) {
        $types['ext'] = 'webp';
        $types['type'] = 'image/webp';
    }

    return $types;
}

/**
 * Adds webp filetype to allowed mimes
 * 
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/upload_mimes
 * 
 * @param array $mimes Mime types keyed by the file extension regex corresponding to
 *                     those types. 'swf' and 'exe' removed from full list. 'htm|html' also
 *                     removed depending on '$user' capabilities.
 *
 * @return array
 */
add_filter( 'upload_mimes', 'wpse_mime_types_webp' );
function wpse_mime_types_webp( $mimes ) {
    $mimes['webp'] = 'image/webp';

  return $mimes;
}


$prefix_key = 'epcl_post_';

// Aside as Video Format
acf_add_local_field_group( array(
	'key' => 'epcl_post_aside',
	'title' => esc_html__('Video Information', 'epcl_framework'),
	'fields' => array (
        array (
			'key' => $prefix_key.'show_featured_image_video_aside',
			'name' => 'show_featured_image_aside',
			'label' => esc_html__('Show Featured Image', 'epcl_framework'),
			'instructions' => esc_html__('By default it will be displayed the video on home pages, archives, etc. Enabling this option will show the featured image instead.', 'epcl_framework'),
			'type' => 'true_false',
			'default_value' => false,
			'ui' => true,
        ),
        array (
			'key' => $prefix_key.'aside_video_lightbox',
			'name' => 'aside_video_lightbox',
			'label' => esc_html__('Use Lightbox on Post Lists', 'epcl_framework'),
			'instructions' => esc_html__('If enabled, on click Youtube/Vimeo iframes will open a lightbox instead.', 'epcl_framework'),
			'type' => 'true_false',
			'default_value' => false,
			'ui' => true,
        ),
		array (
			'key' => $prefix_key.'aside_video_type',
			'name' => 'aside_video_type',
			'label' => esc_html__('Video Type', 'epcl_framework'),
			'instructions' => esc_html__('Select platform.', 'epcl_framework'),
			'type' => 'button_group',
			'choices' => array(
				'youtube' => esc_html__('Youtube', 'epcl_framework'),
                'vimeo' => esc_html__('Vimeo', 'epcl_framework'),
                'custom' => esc_html__('Custom Embed', 'epcl_framework'),
			),
			'layout' => 'horizontal',
			'default_value' => 'youtube',
		),
		array (
			'key' => $prefix_key.'aside_video_url',
			'name' => 'aside_video_url',
			'label' => esc_html__('Video URL', 'epcl_framework'),
			'instructions' => esc_html__('eg. https://www.youtube.com/watch?v=v9nBysHSzhE', 'epcl_framework'),
            'type' => 'url',
            'conditional_logic' => array (
                array (
                    array (
                        'field' => $prefix_key.'video_type',
                        'operator' => '!=',
                        'value' => 'custom',
                    ),
                ),
            )
        ),
        array (
			'key' => $prefix_key.'aside_custom_embed',
			'name' => 'aside_custom_embed',
			'label' => esc_html__('Custom Embed Code', 'epcl_framework'),
			'instructions' => esc_html__('eg. <iframe>....</iframe>', 'epcl_framework'),
            'type' => 'textarea',
            'conditional_logic' => array (
                array (
                    array (
                        'field' => $prefix_key.'video_type',
                        'operator' => '==',
                        'value' => 'custom',
                    ),
                ),
            )
		),
	),
	'menu_order' => 0,
	'label_placement' => 'left',
	'instruction_placement' => 'label',
	'location' => array (
		array (
			array (
				'param' => 'post_format',
				'operator' => '==',
				'value' => 'aside',
			),
		),
	)
));

?>