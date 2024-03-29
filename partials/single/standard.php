<?php 
$epcl_theme = epcl_get_theme_options();

$post_id = get_the_ID();
$post_format = get_post_format();
$post_style = '';
$views = 0;
if( function_exists('get_field') && function_exists('get_fields') ){
    $fields = get_fields();
    $views = get_field('views_counter');
    $post_style = get_field('style');
    $english_url = get_field('english_url');
    $italian_url = get_field('italian_url');
    $french_url = get_field('french_url');
    if( $post_style === '' ) $post_style = 'standard';
}
// Author information
$author_id = get_the_author_meta('ID');
$author_avatar = get_avatar_url( get_the_author_meta('email'), array( 'size' => 90 ));
$optimized_avatar = get_the_author_meta('avatar');
if( $optimized_avatar ){
    $author_avatar = wp_get_attachment_url( $optimized_avatar );
}
$author_name = get_the_author();
?>
<header>

    <?php echo cfm_display_post_format( $post_format, $post_id ); // Don't remove this function.  Found in functions.php ?>

    <!-- start: .meta -->
    <div class="meta">
        <?php if( !empty($epcl_theme) && isset($epcl_theme['enable_author_top']) && $epcl_theme['enable_author_top'] == '1' ): ?>
            <a href="<?php echo get_author_posts_url($author_id); ?>" title="<?php echo esc_attr($author_name); ?>" class="author meta-info hide-on-mobile">                                        
                <?php if($author_avatar): ?>
                    <span class="author-image cover" style="background-image: url('<?php echo esc_url($author_avatar); ?>');"></span>
                <?php endif; ?>
                <span class="author-name"><?php echo esc_attr($author_name); ?></span>
            </a>
        <?php endif; ?>    

        <?php if( empty($epcl_theme) || $epcl_theme['single_enable_meta_data'] !== '0' ): ?>                       
            
            <time class="meta-info" datetime="<?php the_time('Y-m-d'); ?>">
            	<i class="remixicon remixicon-calendar-line"></i> 
	            <?php 
				if(CFM_ENV == 'prod-english') {
					setlocale(LC_TIME, 'en_US.utf8');
					the_time('F j, Y');
				} 
			
				if(CFM_ENV == 'prod-italian') {
					setlocale(LC_TIME, 'it_IT.utf8');
					the_time('j F Y');
				}
			
				if(CFM_ENV == 'prod-french') {
					setlocale(LC_TIME, 'fr_FR.utf8');
					the_time('j F Y');
				}
				?>
	        </time>
			
			<?php if( !empty($english_url) ): ?>
                <span class="english meta-info" title="English">
                    <a href="<?php echo esc_url($english_url); ?>">
                    	<img style="vertical-align: middle; margin-right: 5px;" src="/wp-content/themes/breek-child/assets/images/uk.png">&nbsp;English
                    </a>
                </span>
            <?php endif; ?>
            
            <?php if( !empty($italian_url) ): ?>
                <span class="italian meta-info" title="Italiano">
                    <a href="<?php echo esc_url($italian_url); ?>">
                    	<img style="vertical-align: middle; margin-right: 5px;" src="/wp-content/themes/breek-child/assets/images/italy.png">&nbsp;Italiano
                    </a>
                </span>
            <?php endif; ?>
            
            <?php if( !empty($french_url) ): ?>
                <span class="french meta-info" title="Français">
                    <a href="<?php echo esc_url($french_url); ?>">
	                    <img style="vertical-align: middle; margin-right: 5px;" src="/wp-content/themes/breek-child/assets/images/france.png">&nbsp;Français
                    </a>
                </span>
            <?php endif; ?>
            
            <?php if( isset($epcl_theme['enable_global_views']) && $epcl_theme['enable_global_views'] === '1' ): ?>
                <span class="views-counter meta-info" title="<?php esc_attr_e('Views', 'breek'); ?>">
                    <i class="remixicon remixicon-fire-line"></i> <?php echo absint( $views ); ?>
                </span>
            <?php endif; ?>
        <?php endif; ?>

        <div class="clear"></div>

		<h1 class="title no-thumb large bold"><?php the_title(); ?></h1>

    </div>
    <!-- end: .meta -->

	<div class="clear"></div>

</header>