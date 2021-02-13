<?php
function epcl_display_post_format($format = '', $post_id){

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
			return cfm_get_aside_format($post_id);
        break;
        
	}
}

function epcl_get_image_format($post_id){
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
                    <?php echo epcl_render_categories(); ?>
                </div>
            <?php else: ?>
                <?php echo epcl_render_categories( '' ); ?>
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
                <?php echo epcl_render_categories(); ?>
			</div>
		<?php endif; ?>
	</div>
<?php
}



function epcl_get_video_format($post_id, $type = 'youtube', $url, $height = 225){
    $epcl_theme = epcl_get_theme_options();

	$width = '100%';
    $video_id = $video_url = '';
    $show_featured_image = get_field('show_featured_image', $post_id);
    $video_lightbox = get_field('video_lightbox', $post_id);

    if( !is_single() && $show_featured_image === true ){
        return epcl_get_image_format($post_id);
    }

	if ($type == 'youtube') {
        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
        if( !$url ) return;
        $video_id = $matches[0];
		$video_url ='https://www.youtube.com/embed/'.$matches[0].'?rel=0&controls=0&showinfo=0&modestbranding=1';
	} elseif ($type == 'vimeo') {
        $result = preg_match('/(\d+)/', $url, $matches);
        if( !$url ) return;
		if($result){
			$video_id = $matches[0];
		}else{
			$video_id = $url;
		}
		$video_url = 'https://player.vimeo.com/video/'.$video_id;
    } elseif ($type == 'custom') {
        $custom_embed = get_field('custom_embed', $post_id);
        if( !$custom_embed ) return;
        preg_match('/src="([^"]+)"/', $custom_embed, $match);
        $video_url = $url = $match[1];
    }

?>
	<div class="post-format-wrapper">
        <?php if( !epcl_is_amp() ): ?>
            <?php echo epcl_render_categories(); ?>
        <?php endif; ?>
        <div class="post-format-video">   
            <?php if( epcl_is_amp() ): ?>
                <amp-iframe layout="responsive" width="480" height="250" sandbox="allow-scripts allow-same-origin allow-popups" title="<?php the_title(); ?>" src="<?php echo esc_url($video_url); ?>" allowfullscreen>
                    <amp-img layout="fill" src="<?php echo EPCL_THEMEPATH; ?>/assets/images/transparent.gif" placeholder></amp-img>
                </amp-iframe>
            <?php else: ?>
                <?php if( $video_lightbox == true ): ?>         
                    <a class="video-overlay full-link lightbox mfp-iframe" href="<?php echo esc_url($url); ?>"></a>
                <?php endif; ?>
                <?php if( isset($epcl_theme['enable_lazyload_embed']) && $epcl_theme['enable_lazyload_embed'] === '1' ): ?>
                    <?php the_post_thumbnail(); ?>
                    <?php echo '<img class="play-button" src="/wp-content/themes/breek-child/assets/images/video-player.png"'; ?>
                <?php else: ?>
                    <iframe title="<?php the_title(); ?>" src="<?php echo esc_url($video_url); ?>" allowfullscreen height="<?php echo esc_attr($height); ?>" style="width: <?php echo esc_attr($width); ?>"></iframe>
                <?php endif; ?>
            <?php endif; ?>
        </div>
	</div>
<?php
}

function epcl_get_gallery_format($gallery_images){
    $epcl_theme = epcl_get_theme_options();
?>
	<div class="post-format-wrapper">
        <?php echo epcl_render_categories(); ?>
        <div class="post-format-gallery">
            <?php if( epcl_is_amp() ): ?>
                <amp-carousel height="225" layout="fixed-height" type="slides">
                    <?php foreach($gallery_images as $image): ?>
                        <amp-img class="cover" src="<?php echo esc_url( $image['sizes']['epcl_single_standard'] ); ?>" layout="fill" alt="<?php echo esc_attr( $image['alt'] ); ?>"></amp-img>
                    <?php endforeach; ?>
                </amp-carousel>
            <?php else: ?>
                <div class="slick-slider" data-rtl="<?php echo is_rtl(); ?>">
                    <?php foreach($gallery_images as $image): ?>
                        <div class="item thumb">
                            <?php if( !empty($epcl_theme) && $epcl_theme['enable_lazyload'] == '1' ): ?>
                                <span class="fullimage cover lazy" data-src="<?php echo esc_url( $image['sizes']['epcl_single_standard'] ); ?>"></span>
                            <?php else: ?>
                                <span class="fullimage cover" style="background-image: url(<?php echo esc_url( $image['sizes']['epcl_single_standard'] ); ?>);"></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
	</div>
<?php
}

/* To do: self hosted audio */

function epcl_get_audio_format($post_id, $url){
    $show_featured_image = get_field('show_featured_image', $post_id);
    
    if( !is_single() && $show_featured_image === true ){
        return epcl_get_image_format($post_id);
    }

	$width = '100%';
	$embed_code = wp_oembed_get( $url );
	preg_match('/src="([^"]+)"/', $embed_code, $match);
	$url = $match[1];
	$url = str_replace('&', '&amp;', $url);
?>
	<div class="post-format-audio post-format-wrapper">
        <iframe src="<?php echo esc_url($url); ?>" allowFullScreen height="225" style="width: <?php echo esc_attr($width); ?>"></iframe>
	</div>
<?php
}

function epcl_get_link_format($link_url){
?>
	<div class="post-format-link post-format-wrapper">
        <div class="featured-image">
            <a href="<?php echo esc_url($link_url);?>" class="thumb hover-effect" target="_blank">
                <span class="fullimage cover" style="background-image: url(<?php the_post_thumbnail_url('epcl_classic_post'); ?>);"></span>
            </a>
        </div>
	</div>
<?php
}