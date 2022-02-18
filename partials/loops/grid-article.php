<?php
$epcl_theme = epcl_get_theme_options();
$epcl_module = epcl_get_module_options();

$index = absint( get_query_var('index') );

$column_class = 'grid-33';
$grid_posts_column = 3;
$post_class = $thumb_url = '';

$english_url = get_field('english_url');
$italian_url = get_field('italian_url');
$french_url = get_field('french_url');

if( !get_post_format() && !has_post_thumbnail() ){
    $post_class .= ' no-thumb';
}
// Loaded by Flexible content ACF
if( !empty($epcl_module) ){
    $column_class = 'grid-33';
    if( !empty($epcl_module['grid_posts_column']) ){
        $grid_posts_column = $epcl_module['grid_posts_column'];
    }
	
	switch ($grid_posts_column) {
		case '1':
			$column_class = 'grid-50';
		break;
		case '2':
			$column_class = 'grid-50';
        break;
        case '4':
			$column_class = 'grid-25';
		break;
		default:
			$column_class = 'grid-33';
		break;
	}
	if( $epcl_module['acf_fc_layout'] == 'grid_sidebar' ){
		$grid_posts_column = 2;
	    $column_class = 'grid-50';
    }
}
// Columns Posts Page
if( is_home() ){
    if( $epcl_theme['posts_page_layout'] == 'grid_4_cols' ){
        $grid_posts_column = 4;
        $column_class = 'grid-25';
    }
    if( $epcl_theme['posts_page_layout'] == 'grid_sidebar' ){
        $grid_posts_column = 2;
        $column_class = 'grid-50';
    }
}
// Columns archive
if( is_archive() && !is_author() ){
    if( $epcl_theme['archive_layout'] == 'grid_4_cols' ){
        $grid_posts_column = 4;
        $column_class = 'grid-25';
    }
    if( $epcl_theme['archive_layout'] == 'grid_sidebar' ){
        $grid_posts_column = 2;
        $column_class = 'grid-50';
    }
}
// Columns search results
if( is_search() ){
    if( $epcl_theme['search_layout'] == 'grid_4_cols' ){
        $grid_posts_column = 4;
        $column_class = 'grid-25';
    }
    if( $epcl_theme['search_layout'] == 'grid_sidebar' ){
        $grid_posts_column = 2;
        $column_class = 'grid-50';
    }
}

$thumb_url = get_the_post_thumbnail_url(get_the_ID());
$views = 0;
if( function_exists('get_fields') ){
    $fields = get_fields();
    $views = get_field('views_counter');
    if( !empty( $fields['optimized_image'] ) ){
        $thumb_url = $fields['optimized_image']['url'];
    }
}
if( !empty($epcl_theme) && $epcl_theme['grid_display_author'] == '0'){
	$post_class .= ' no-author';
}
set_query_var( 'epcl_post_style', 'grid' );
if( isset($_GET['ads']) ){
    $epcl_theme['ads_enabled_grid_loop'] = '1';
}
// Ads integration
if( !epcl_is_amp() && !empty($epcl_theme) && function_exists( 'epcl_render_global_ads' ) && $epcl_theme['ads_enabled_grid_loop'] == '1' && $index === ( absint($epcl_theme['ads_position_grid_loop']) - 1  ) ){
    if( $epcl_theme['ads_mobile_grid_loop'] == '0' && wp_is_mobile() ){

    }else{
        echo '<article class="index-'.$index.' '.$column_class.' tablet-grid-50 np-mobile">';
            epcl_render_global_ads('grid_loop');
        echo '<div class="border"></div></article>';
        $index++;
    }
}
?>

<article <?php post_class('default index-'.$index.' '.$column_class.$post_class.' tablet-grid-50 mobile-grid-100 np-mobile"'); ?>>
    <div class="article-wrapper">

        <header>
            <?php if( !$thumb_url  && !get_post_format() && get_the_category() ): ?>
                <?php echo epcl_render_categories( 'no-thumb' ); ?>
            <?php endif; ?>
            <?php epcl_display_post_format( get_post_format(), get_the_ID() );  ?>
            <?php if( epcl_get_option('enable_global_date') !== '0' ): ?>
                <div class="meta">
                    <time datetime="<?php the_time('Y-m-d'); ?>">
                    	<i class="remixicon remixicon-calendar-line"></i>
                    	<?php 
						if(CFM_URL == 'https://searchparty.org/comefollowme/') {
							setlocale(LC_TIME, 'en_US.utf8');
							the_time('F j, Y');
						} 
					
						if(CFM_URL == 'https://searchparty.org/vienieseguimi/') {
							setlocale(LC_TIME, 'it_IT.utf8');
							the_time('j F Y');
						}
					
						if(CFM_URL == 'https://viensetsuismoi.it') {
							setlocale(LC_TIME, 'fr_FR.utf8');
							the_time('j F Y');
						}
						?>
                    </time>
                    <div class="clear"></div>
                </div>
            <?php endif; ?>
            <?php if( is_archive() || is_search() ): ?>
                <h2 class="title main-title gradient-effect"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php else: ?>
                <h1 class="title main-title gradient-effect"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            <?php endif; ?>            
        </header>

        <div class="post-excerpt">
            <?php if( epcl_get_option('grid_display_excerpt') !== '0'): ?>
                <?php the_excerpt(); ?>
            <?php endif; ?>
            <div class="clear"></div>
        </div>  

        <?php
        $author_id = get_the_author_meta('ID');
        $author_avatar = get_avatar_url( get_the_author_meta('email'), array( 'size' => 90 ));
        $optimized_avatar = get_the_author_meta('avatar');
        if( $optimized_avatar ){
            $author_avatar = wp_get_attachment_url( $optimized_avatar );
        }
        $author_name = get_the_author();
        $footer_class = '';
        ?>

        <footer class="author-meta">
            <?php if( epcl_get_option('grid_display_author') !== '0' ): ?>
                <a href="<?php echo get_author_posts_url($author_id); ?>" class="author" title="<?php echo esc_attr($author_name); ?>">
                    <?php if($author_avatar): ?>
                        <span class="author-image cover" style="background-image: url('<?php echo esc_url($author_avatar); ?>');"></span>
                    <?php endif; ?>
                    <span class="author-name"><?php echo esc_attr($author_name); ?></span>
                </a>
            <?php endif; ?>
            <?php if( !empty($english_url) ): ?>
                <span class="english meta-info" title="English">
                    <a href="<?php echo esc_url($english_url); ?>">
                    	<img style="vertical-align: middle; margin-right: 5px;" src="/wp-content/themes/breek-child/assets/images/uk.png">&nbsp;EN
                    </a>
                </span>
            <?php endif; ?>
            
            <?php if( !empty($italian_url) ): ?>
                <span class="italian meta-info" title="Italiano">
                    <a href="<?php echo esc_url($italian_url); ?>">
                    	<img style="vertical-align: middle; margin-right: 5px;" src="/wp-content/themes/breek-child/assets/images/italy.png">&nbsp;IT
                    </a>
                </span>
            <?php endif; ?>
            
            <?php if( !empty($french_url) ): ?>
                <span class="french meta-info" title="Français">
                    <a href="<?php echo esc_url($french_url); ?>">
	                    <img style="vertical-align: middle; margin-right: 5px;" src="/wp-content/themes/breek-child/assets/images/france.png">&nbsp;FR
                    </a>
                </span>
            <?php endif; ?>
            <?php if( is_sticky() ): ?>
                <span class="meta-info alignright" title="<?php esc_attr_e('Featured', 'breek'); ?>"><i class="remixicon remixicon-star-line"></i></span>
            <?php endif; ?>
            <?php if( epcl_get_option('enable_global_comments') !== '0' ): ?>
                <a href="<?php the_permalink(); ?>#comments" class="comments meta-info alignright" title="<?php esc_attr_e('Go to comments', 'breek'); ?>">         <?php if( epcl_get_option('hosted_comments') !== '2' && epcl_get_option('hosted_comments') !== '3' ): ?>
                        <span class="comment-count"><?php echo get_comments_number($post->ID); ?></span>
                    <?php elseif( $epcl_theme['hosted_comments'] == 3 ): // Facebook commments ?>
                        <span class="fb-comments-count" data-href="<?php the_permalink(); ?>">0</span>
                    <?php else: // Disqus Comments ?>
                        <span class="disqus-comment-count" data-disqus-url="<?php the_permalink(); ?>" data-disqus-identifier="<?php the_ID(); ?>">0</span>
                    <?php endif; ?>
                    <i class="remixicon remixicon-chat-1-line fa-flip-horizontal"></i>
                </a>
            <?php endif; ?>
            <?php if( isset($epcl_theme['enable_global_views']) && $epcl_theme['enable_global_views'] === '1' ): ?>
                <div class="views-counter meta-info alignright" title="<?php esc_attr_e('Views', 'breek'); ?>">
                    <?php echo absint( $views ); ?> <i class="remixicon remixicon-fire-line"></i>
                </div>
            <?php endif; ?>
            <div class="clear"></div>
        </footer>
        <div class="border"></div>        
    </div>
</article>

<?php $index++; set_query_var('index', $index); ?>

<?php if($index % $grid_posts_column == 0): ?>
	<div class="clear hide-on-tablet"></div>
<?php endif; ?>

<?php if($index % 2 == 0): ?>
	<div class="clear hide-on-desktop hide-on-mobile"></div>
<?php endif; ?>
