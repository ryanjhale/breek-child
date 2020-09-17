<?php
$epcl_theme = epcl_get_theme_options();
$epcl_module = epcl_get_module_options();

$prefix = EPCL_THEMEPREFIX.'_';
$sidebar_name = 'epcl_sidebar_default';

$sidebar_class = '';
if( epcl_get_option('enable_mobile_sidebar') == false || epcl_get_option('mobile_sidebar') ){
	$sidebar_class = 'no-sidebar';
}
if( function_exists('get_field') ){
    if( get_field('sidebar') != '' ) $sidebar_name = get_field('sidebar');
}
if( is_home() || is_archive() || is_search() || is_page_template('page-templates/home.php') ){
	$sidebar_name = 'epcl_sidebar_home';
}
if( !empty($epcl_theme) && $epcl_theme['enable_sticky_sidebar'] == '1'){
    $sidebar_class .= ' sticky-enabled';
}
if( !empty($epcl_module) && isset($epcl_module['sidebar']) &&  $epcl_module['sidebar'] != ''){
    $sidebar_name = $epcl_module['sidebar'];
}

if(CFM_URL == 'https://vienieseguimi.it') {
	$learn_text = 'Conoscere Gesù';
	$learn_url = 'https://vienieseguimi.it/post/8';
	$contact_text = 'Hai una domanda?';
	$contact_button_text = 'Contattaci su Facebook';
	$contact_url = 'https://m.me/vienieseguimi.it';
	$calls_text = 'Gesù ti chiama';
	$calls_button = 'Comincia ora';
	$calls_link = 'https://viensetsuismoi.it/post/61';
} elseif(CFM_URL == 'https://viensetsuismoi.it') {
	$learn_text = 'En savoir plus sur Jésus';
	$learn_url = 'https://viensetsuismoi.it/post/6';
	$contact_text = 'Vous avez une question ?';
	$contact_button_text = 'Contactez-nous sur Facebook';
	$contact_url = 'https://m.me/viensetsuismoi.it';
	$calls_text = 'Jésus vous appelle';
	$calls_button = 'Commencer maintenant';
	$calls_link = 'https://vienieseguimi.it/post/93';
} else {
	$learn_text = 'Learn about Jesus';
	$learn_url = 'https://comefollowme.it/post/438';
	$contact_text = 'Have a Question?';
	$contact_button_text = 'Contact us on Facebook';
	$contact_url = 'https://m.me/comefollowme.it';
	$calls_text = 'Jesus is calling you';
	$calls_button = 'Start Now';
	$calls_link = 'https://comefollowme.it/post/331';
}

?>
<?php if( is_active_sidebar( $sidebar_name ) ): ?>
    <!-- start: #sidebar -->
    <aside id="sidebar" class="grid-30 np-mobile <?php echo esc_attr($sidebar_class); ?>">
        <div class="default-sidebar">
            <section id="next-post" class="widget_next_post" style="position: relative; z-index: 1; height: 275px; background: #111; overflow: hidden; border-radius: 15px; margin-bottom: 30px;">
                <?php if( epcl_get_option('enable_lazyload_posts') == '1'): ?>
                    <div class="cover lazy" style="position: relative; z-index: 1; min-height: 100%" data-src="https://comefollowme.it/wp-content/uploads/2020/06/906080961.webp">
                    </div>
                <?php else: ?>
                    <div class="thumb cover" style="background: url('https://comefollowme.it/wp-content/uploads/2020/06/Come-Follow-Me.webp');"></div>
                <?php endif; ?>
                <a href="<?php echo esc_url($learn_url); ?>" style="position: absolute; left: 0; top: 0; width: 100%; height: 100%!important;">                        
	                <div style="position: absolute; left: 0; top: 30%; right: 0; text-align: center; padding: 30px; color: #fff; z-index: 3; text-shadow: 0px 2px 0px rgba(0,0,0,0.3);">
	                    <h4 class="title white no-margin"><?php echo $learn_text; ?></h4>                                
	                </div>
				</a>
                <div class="overlay"></div>
				<div class="clear"></div>
			</section>

            <section id="prev-post" class="widget_next_post" style="position: relative; z-index: 1; height: 275px; background: #111; overflow: hidden; border-radius: 15px; margin-bottom: 30px;">
                    <?php if( epcl_get_option('enable_lazyload_posts') == '1'): ?>
                        <div class="cover lazy" style="position: relative; z-index: 1; min-height: 100%" data-src="https://comefollowme.it/wp-content/uploads/2020/01/Luke-7.webp">
                        </div>
                    <?php else: ?>
                        <div class="thumb cover" style="background: url('https://comefollowme.it/wp-content/uploads/2020/06/Come-Follow-Me.webp');"></div>
                    <?php endif; ?>
                <a href="<?php echo esc_url($calls_link); ?>" style="position: absolute; left: 0; top: 0; width: 100%; height: 100%!important;">                        
	                <div style="position: absolute; left: 0; top: 25%; right: 0; text-align: center; padding: 30px; color: #fff; z-index: 3; text-shadow: 0px 2px 0px rgba(0,0,0,0.3);">
	                    <h2 class="title white no-margin"><?php echo $calls_text; ?></h2>                               
	                </div>
	                <span class="epcl-button" style="background-color: #3373dc; position: absolute; left: 15%; right: 15%; width: 70%; height: 40px; line-height: 40px;  bottom: 20px; z-index: 5; text-align: center; vertical-align: middle; padding: 0;">
	                	<?php echo $calls_button; ?>
	                </span>
				</a>
                <div class="overlay"></div>
				<div class="clear"></div>
			</section>
	    </div>
        
    </aside>
    <!-- end: #sidebar -->
<?php endif; ?>