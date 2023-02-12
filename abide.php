<?php 	
global $post;
$post_id = $post->ID;
$post_format = get_post_format($post_id);

if(CFM_ENV == 'prod-english') {
	$sheet_name = 'Banding Together - EN';
}

if(CFM_ENV == 'prod-italian') {
	$sheet_name = 'Banding Together - IT';
}

$scriptures = google_spreadsheet_get_data('1-LBAc4y6veOL8iXDbejgVUNusxBVC-fffLCgysDntJc', $sheet_name, $cell_range = '');

if(CFM_ENV == 'prod-english') {
	  	
  	setlocale(LC_ALL, 'en_US');
  	
  	$monday = date( 'l F j', strtotime( 'monday this week' ) );
  	$tuesday = date( 'l F j', strtotime( 'tuesday this week' ) );
	$wednesday = date( 'l F j', strtotime( 'wednesday this week' ) );
	$thursday = date( 'l F j', strtotime( 'thursday this week' ) );
	$friday = date( 'l F j', strtotime( 'friday this week' ) );
	$saturday = date( 'l F j', strtotime( 'saturday this week' ) );
	$sunday = date( 'l F j', strtotime( 'sunday this week' ) );
	
}

if(CFM_ENV == 'prod-italian') {
	  	
  	setlocale(LC_ALL, 'it_IT');
  	
  	$monday = date( 'l j F', strtotime( 'monday this week' ) );
  	$tuesday = date( 'l j F', strtotime( 'tuesday this week' ) );
	$wednesday = date( 'l J F', strtotime( 'wednesday this week' ) );
	$thursday = date( 'l j F', strtotime( 'thursday this week' ) );
	$friday = date( 'l j F', strtotime( 'friday this week' ) );
	$saturday = date( 'l j F', strtotime( 'saturday this week' ) );
	$sunday = date( 'l j F', strtotime( 'sunday this week' ) );
	
}

$monday_month = date( 'n', strtotime( 'monday this week' ) );
$tuesday_month = date( 'n', strtotime( 'tuesday this week' ) );
$wednesday_month = date( 'n', strtotime( 'wednesday this week' ) );
$thursday_month = date( 'n', strtotime( 'thursday this week' ) );
$friday_month = date( 'n', strtotime( 'friday this week' ) );
$saturday_month = date( 'n', strtotime( 'saturday this week' ) );
$sunday_month = date( 'n', strtotime( 'sunday this week' ) );

$monday_day = date( 'j', strtotime( 'monday this week' ) );
$tuesday_day = date( 'j', strtotime( 'tuesday this week' ) );
$wednesday_day = date( 'j', strtotime( 'wednesday this week' ) );
$thursday_day = date( 'j', strtotime( 'thursday this week' ) );
$friday_day = date( 'j', strtotime( 'friday this week' ) );
$saturday_day = date( 'j', strtotime( 'saturday this week' ) );
$sunday_day = date( 'j', strtotime( 'sunday this week' ) );

$week = array('monday' =>
				 array('display' 	=> $monday,
				 	   'month'		=> $monday_month,
				 	   'day'		=> $monday_day
				 	  ),
		      'tuesday' => 
		         array('display'	=> $tuesday,
		               'month'		=> $tuesday_month,
		               'day'		=> $tuesday_day
		               ),
		       'wednesday' => 
		         array('display'	=> $wednesday,
		               'month'		=> $wednesday_month,
		               'day'		=> $wednesday_day
		               ),
		        'thursday' => 
		         array('display'	=> $thursday,
		               'month'		=> $thursday_month,
		               'day'		=> $thursday_day
		               ),
		        'friday' => 
		         array('display'	=> $friday,
		               'month'		=> $friday_month,
		               'day'		=> $friday_day
		               ),
		        'saturday' => 
		         array('display'	=> $saturday,
		               'month'		=> $saturday_month,
		               'day'		=> $saturday_day
		               ),
		        'sunday' => 
		         array('display'	=> $sunday,
		               'month'		=> $sunday_month,
		               'day'		=> $sunday_day
		               ),
		);

get_header();

while(have_posts()): the_post();  ?>
    <?php
	$post_thumbnail = get_the_post_thumbnail_url();
	$post_style = 'standard';
	$single_class = '';
    $enable_sidebar = true;
    $sidebar_name = 'epcl_sidebar_default';

	if( function_exists('get_field') && function_exists('get_fields') ){
        $views = absint( get_field('views_counter') );
		if(!$views) $views = 0;
        update_post_meta($post_id, 'views_counter', ++$views);

        $fields = get_fields();
		$post_style = get_field('style');
		if( $post_style === '' ) $post_style = 'standard';

		$enable_sidebar = get_field('enable_sidebar');
        if( $enable_sidebar === false ) $single_class .= ' no-sidebar';
        
        if( $fields['sidebar'] != ''){
            $sidebar_name = $fields['sidebar'];
        }

	}
	if( $post_style == 'fullcover' ){
		$post_thumbnail = get_the_post_thumbnail_url($post, 'epcl_page_header');
    }
    if( !is_active_sidebar( $sidebar_name ) ){
        $enable_sidebar = false;
        $single_class .= ' no-sidebar';
    }
    if( !$post_style || !has_post_thumbnail() ){
        $post_style = 'standard';
    }
    
    if( !empty($epcl_theme) && $epcl_theme['single_post_layout'] === '2' && $post_format == ''){
        $post_style = 'standard';
    }
    if( !empty($epcl_theme) && $epcl_theme['single_post_layout'] === '3' && $post_format == ''){
        $post_style = 'fullcover';
    }
    if( !empty($epcl_theme) && $epcl_theme['enable_post_sidebar'] === '2'){
        $enable_sidebar = true;
        $single_class = '';
    }
    if( !empty($epcl_theme) && $epcl_theme['enable_post_sidebar'] === '3'){
        $enable_sidebar = false;
        $single_class .= ' no-sidebar';
    }
    // Disable featured image globally
    if( !empty($epcl_theme) && isset($epcl_theme['enable_featured_image']) && $epcl_theme['enable_featured_image'] === '0'){
        $post_style = 'standard';
    }
    // Fix featured image titles
    if( !$post_style || !has_post_thumbnail() ){
        $post_style = 'standard';
    }
    set_query_var('epcl_share_bottom', false);
	?>
	<!-- start: #single -->
	<main id="single" class="main grid-container <?php echo esc_attr($post_style.$single_class); ?>" data-aos="fade">

		<!-- start: .center -->
	    <div class="content"> <?php // Because of clash with Atomic Blocks styling, the center class is removed ?>

			<!-- Fullcover Style -->
            <?php if( has_post_thumbnail() && $post_style == 'fullcover' && $post_format == '' ): ?>
                <?php get_template_part('partials/single/fullcover'); ?>
            <?php endif; ?>

            <!-- start: .epcl-page-wrapper -->
            <div class="epcl-page-wrapper">

                <!-- start: .content -->
                <div class="left-content grid-70 np-mobile">

                    <article <?php post_class('main-article'); ?>>

                        <?php edit_post_link( esc_html__('Edit this post', 'breek'), '', '', '', 'edit-post-button epcl-button dark'); ?>
                    
                        <?php if( $post_style == 'standard'): ?>
                            <?php get_template_part('partials/single/standard'); ?>
                        <?php endif; ?>

                        <section class="post-content">

                            <?php
                            if( function_exists( 'epcl_render_global_ads' ) ){
                                epcl_render_global_ads('single_top');
                            }
                            ?>

                            <?php if( $epcl_theme['enable_sticky_share_buttons'] !== '0' && !empty($epcl_theme) ): ?>
                                <div class="epcl-share-container hide-on-mobile">
                                    <?php get_template_part('partials/share-buttons'); ?>
                                </div>
                            <?php endif; ?>

                            <div class="text">
                                <?php the_content(); ?>
                                <pre>
	                                <?php print_r($week);?>
                                </pre>
                                <?php
	                            
	                            foreach($week as $day) {
		                            $m = $day['month'];
		                            $d = $day['day'];
		                            echo $day['display'] . ' : ' . $scriptures[$d][$m] . '<br />';
	                            }
	                            ?>
                                <?php
                                    if ( is_singular( 'attachment' ) ) {
                                        echo '<h2 class="title usmall">'.esc_html__('Published in:', 'breek').'</h2>';
                                        // Parent post navigation.
                                        the_post_navigation();
                                    }
                                ?>
                            </div>
                            <div class="clear"></div>

                            <?php
                            if( function_exists( 'epcl_render_global_ads' ) ){
                                epcl_render_global_ads('single_bottom');
                            }
                            ?>

                            <?php if( isset( $fields['enable_download'] ) && $fields['enable_download'] == true ): ?>
                                <!-- <hr> -->
                                <div class="epcl-download">
                                    <?php if( isset($fields['edd_download_id']) && $fields['edd_download_id'] && function_exists('edd_get_checkout_uri') ): ?>
                                        <a href="<?php echo esc_url( edd_get_checkout_uri().'?edd_action=add_to_cart&download_id='.$fields['edd_download_id'] ); ?>" data-post-id="<?php echo esc_attr($post->ID); ?>"><?php echo esc_attr( $fields['download_title' ]); ?></a>
                                    <?php else: ?>
                                        <a href="<?php echo esc_url( $fields['download_url'] ); ?>" data-post-id="<?php echo esc_attr($post->ID); ?>" target="_blank"><?php echo esc_attr( $fields['download_title' ]); ?></a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if( $epcl_theme['enable_share_buttons'] != false && !empty($epcl_theme) && $post_format != 'aside'): ?>
                                <!-- start: .share-buttons -->
                                <div class="share-buttons section">
                                    <h5 class="title small"><?php esc_html_e('Share Article:', 'breek'); ?></h5>
                                    <?php set_query_var('epcl_share_bottom', 'true'); ?>
                                    <?php get_template_part('partials/share-buttons'); ?>
                                    <div class="clear"></div>
                                    <div class="permalink">
                                        <input type="text" name="shortlink" value="<?php echo urldecode( get_the_permalink() ); ?>" id="copy-link" readonly>
                                        <span class="copy"><svg><use xlink:href="#copy"></use></svg></span>
                                    </div>
                                </div>
                                <!-- end: .share-buttons -->
                            <?php endif; ?>

                        </section>

                        <?php if( get_the_tags() ): ?>
                            <div class="bottom-tags">
                                <i class="remixicon remixicon-price-tag-3-line"></i>
                                <?php the_tags(''); ?>
                            </div>
                        <?php endif; ?>

                        <?php
                            $link_pages_args = array(
                                'before'           => '<div class="epcl-pagination link-pages section"><div class="nav"><span class="page-number">'.esc_html__( 'Pages', 'breek' ).'</span>',
                                'after'            => '</div></div>',
                                'link_before'      => '',
                                'link_after'       => '',
                                'next_or_number'   => 'number',
                                'separator'        => '',
                                'nextpagelink'     => esc_html__( 'Next', 'breek'),
                                'previouspagelink' => esc_html__( 'Previous', 'breek' ),
                                'pagelink'         => '<span class="page-number">%</span>',
                                'echo'             => 1
                            );
                            wp_link_pages( $link_pages_args );
                        ?>

                    </article>
                    <div class="clear"></div>

                    <?php get_template_part('partials/author-box'); ?>
                    
                    <?php if($epcl_theme['siblings_posts'] != false && $post_format != 'aside'): ?>

                        <section class="related section">
                            <?php
                            $same_category = epcl_get_option('siblings_posts_category');                            
                            $prev_post = get_adjacent_post(true, array(), true, 'category');
                            if( !empty($prev_post) ){
                                $prev_url = get_the_permalink($prev_post->ID);
                                $prev_thumb = get_the_post_thumbnail_url($prev_post->ID, 'epcl_single_content');
                            }
                            ?>
                            <?php if( !empty($prev_post) ): ?>
                                <article class="prev grid-50 tablet-grid-50 grid-parent">
                                    <?php if($prev_thumb): ?>
                                        <?php if( epcl_get_option('enable_lazyload_posts') == '1'): ?>
                                            <div class="thumb cover lazy" data-src="<?php echo esc_url($prev_thumb); ?>"></div>
                                        <?php else: ?>
                                            <div class="thumb cover" style="background: url('<?php echo esc_url($prev_thumb); ?>');"></div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <a href="<?php echo esc_url($prev_url); ?>" class="full-link"></a>                              
                                    <div class="info">
                                        <!--
                                        <time datetime="<?php echo get_the_time('Y-m-d', $prev_post); ?>">
                                            <?php // echo get_the_time( get_option('date_format'), $prev_post ); ?>
                                        </time>
                                        -->
                                        <h4 class="title white no-margin"><?php echo get_the_title($prev_post->ID); ?></h4>
                                    </div>                                
                                    <span class="epcl-button red"><img src="<?php echo EPCL_THEMEPATH; ?>/assets/images/left-arrow.svg" width="15" alt="<?php esc_attr_e('Left Arrow', 'breek'); ?>"></span>
                                    <div class="overlay"></div>
                                </article>
                            <?php endif; ?>

                            <?php
                            $next_post = get_adjacent_post(true, array(), false, 'category');
                            if( !empty($next_post) ){
                                $next_url = get_the_permalink($next_post->ID);
                                $next_thumb = get_the_post_thumbnail_url($next_post->ID, 'epcl_single_content');
                            }
                            ?>
                            <?php if( !empty($next_post) ): ?>
                                <article class="next grid-50 tablet-grid-50 grid-parent">
                                    <?php if($next_thumb): ?>
                                        <?php if( epcl_get_option('enable_lazyload_posts') == '1'): ?>
                                            <div class="thumb cover lazy" data-src="<?php echo esc_url($next_thumb); ?>"></div>
                                        <?php else: ?>
                                            <div class="thumb cover" style="background: url('<?php echo esc_url($next_thumb); ?>');"></div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <a href="<?php echo esc_url($next_url); ?>" class="full-link"></a>                            
                                    <div class="info">
                                        <!--
                                        <time datetime="<?php echo get_the_time('Y-m-d', $next_post); ?>">
                                            <?php // echo get_the_time( get_option('date_format'), $next_post ); ?>
                                        </time>
										-->
                                        <h4 class="title white no-margin"><?php echo get_the_title($next_post->ID); ?></h4>                                
                                    </div>
                                    <span class="epcl-button red"><img src="<?php echo EPCL_THEMEPATH; ?>/assets/images/right-arrow.svg" width="15" alt="<?php esc_attr_e('Right Arrow', 'breek'); ?>"></span>
                                    <div class="overlay"></div>
                                </article>
                            <?php endif; ?>
                            <div class="clear"></div>
                        </section>

                    <?php endif; ?>

                    <div class="clear"></div>

                </div>
                <!-- end: .content -->

                <?php get_sidebar(); ?>
<?php /*
                if($post_format == 'aside') {
					get_sidebar('aside');
				} else {
					if( $enable_sidebar !== false ){
	                    
	                }
				}
				*/
                ?>

            </div>
            <!-- end: .epcl-page-wrapper -->            
        
        </div>
        <!-- end: .center -->

	</main>
	<!-- end: #single -->

<?php endwhile; ?>

<?php get_footer(); ?>
