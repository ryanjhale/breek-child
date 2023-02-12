<?php
	
add_action('acf/init', 'ab_acf_init');

function ab_acf_init() {
        if( function_exists('acf_register_block_type') ) {
                acf_register_block_type(array(
                        'name'          => 'week_scriptures',
                        'title'         => __('Scriptures this Week'),
                        'description'       => __('A block to display the scriptures to read this week.'),
                        'render_template'   => 'blocks/scriptures/template.php',
                        // 'render_callback'   => 'ab_acf_block_render_callback',
                        'category'      => 'formatting',
                        'icon'          => 'admin-comments',
                        'keywords'      => array('scriptures', 'bible', 'verses'),
                ));
        }
}