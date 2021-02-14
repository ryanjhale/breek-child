<?php
// if( epcl_is_amp() ){
//     get_template_part('amp/footer');
//     return;
// }
?>


        <?php get_template_part('partials/footer'); ?>

        <div class="clear"></div>
    </div>
    <!-- end: #wrapper --> 

    <!-- W3TC-include-css -->
    <!-- W3TC-include-js-head -->

    <?php wp_footer(); ?>
    <?php
    $ajax_scripts = epcl_get_option('custom_ajax_scripts');
    ?>
    
	    
    <?php if ( !empty($ajax_scripts) ): ?>
        <div id="epcl-ajax-scripts" style="display: none;">
            <?php foreach( $ajax_scripts as $item ): ?>
                <?php if( $item['script_src'] !== ''): ?>
                    <div
                        data-src="<?php echo esc_attr( $item['script_src'] ); ?>"
                        data-cache="<?php echo esc_attr( $item['script_cache'] ); ?>"
                        data-timeout="<?php echo absint( $item['script_timeout'] ); ?>">
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
	<?php // include 'lesson-modal-bootstrap.php'; ?>
    </body>
</html>
