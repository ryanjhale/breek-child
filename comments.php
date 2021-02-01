<?php
if ( post_password_required() )
    return;

add_filter('comment_reply_link', 'epcl_replace_reply_link_class');


function epcl_replace_reply_link_class($class){
    $class = str_replace("class='comment-reply-link", "class='comment-reply-link epcl-button green", $class);
    return $class;
}    
$count = 0;
function epcl_comments_callback($comment, $args, $depth) {
	global $count;
	$count++;
    $avatar = get_avatar($comment, 144);
    $optimized_avatar = get_the_author_meta('avatar', $comment->user_id);
    if( $optimized_avatar && $comment->user_id != '0'){
        $avatar_src = wp_get_attachment_image_src( $optimized_avatar, 'epcl_widget_thumb' );
        $avatar = '<img src="'.$avatar_src[0].'" width="144" width="144" class="avatar" alt="'.esc_attr__('Avatar', 'breek').'">'; 
    }
    $class = (!$avatar) ? ' no-avatar' : '';
?>
    <li <?php comment_class('count-'.$count.$class); ?> id="comment-<?php comment_ID() ?>">
    	<?php if($avatar): ?>
            <div class="avatar grid-10 tablet-grid-15 mobile-grid-15"><?php echo wp_kses_post($avatar) ; ?></div>
        <?php endif; ?>
        <div class="right grid-90 tablet-grid-85 mobile-grid-85">
            <cite class="comment-author"><?php comment_author_link(); ?></cite>
            <span class="date"><?php esc_html_e('on', 'breek'); ?> <?php comment_date(); ?></span>
            <div class="clear"></div>
            <div class="text">
                <?php if ($comment->comment_approved == '0') : ?>
                    <p><?php esc_html_e( 'Your comment is awaiting moderation.', 'breek');?></p>
                <?php endif; ?>
                <?php comment_text(); ?>			
            </div>
            <?php comment_reply_link( array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']) ) ); ?>
        </div>
        <div class="clear"></div>
<?php
}
?>

<?php if( comments_open() || have_comments() ): ?>
    <!-- start: #comments -->
    <div id="comments" class="bg-white hosted <?php if( have_comments() ) echo 'section have-comments'; ?>">

        <?php if ( have_comments() ) : ?>
            <h3 class="title bordered">
                <?php esc_html( printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'breek'), number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' ) );
                ?>
            </h3>
            <!-- start: .commentlist -->
            <ol class="commentlist">
                <?php wp_list_comments( array( 'callback' => 'epcl_comments_callback' ) ); ?>
            </ol>
            <!-- end: .commentlist  -->

            <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
                <div class="clear"></div>
                <!-- start: #comment-nav -->
                <nav id="comment-nav" class="pagination section">
                    <div class="nav-previous alignleft"><?php previous_comments_link( esc_html__('Older Comments', 'breek') ); ?></div>
                    <div class="nav-next alignright"><?php next_comments_link( esc_html__('Newer Comments', 'breek') ); ?></div>
                    <div class="clear"></div>
                </nav>
                <!-- end: #comment-nav -->
            <?php endif; ?>

            <?php if ( ! comments_open() && get_comments_number() ) : ?>
                <h4 class="title section np-bottom no-margin textcenter"><?php esc_html_e('Comments are closed.', 'breek'); ?></h4>
            <?php endif; ?>

        <?php endif; // have_comments() ?>
        <?php
            $commenter = wp_get_current_commenter();
            $req = get_option( 'require_name_email' );
            $aria_req = ( $req ? " aria-required='true' required" : '' );
            $fields =  array(
                'author' => '<input class="form-author" name="author" type="text" placeholder="' . esc_attr__('Name', 'breek') . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />',
                'email' => '<input class="form-email" name="email" type="text" placeholder="' . esc_attr__('Email',  'breek') . '" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />',
                'url' => '<input class="form-website" name="url" type="text" placeholder="' . esc_attr__('Website',  'breek'). '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />',
            );
            $fields = apply_filters('comment_form_default_fields', $fields);
            $comments_args = array(
                'fields' => $fields,
                'comment_field' => '<textarea id="comment" name="comment" aria-required="true" rows="10" placeholder="'.esc_attr__( 'Comment', 'breek').'"></textarea>',
                'must_log_in' => '<p class="must-log-in"><a href="' . wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) . '">'.  esc_html__('Log In', 'breek') .'</a></p>',
                'comment_form_top' => '',
                'comment_notes_after' => '',
                'comment_notes_before' => '',
                'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title title bordered">',
                'title_reply_after' => '</h3>',
                'class_submit' => 'epcl-button'
            );
            comment_form($comments_args);
        ?>
        <div class="clear"></div>
    </div>
    <!-- end: #comments -->
<?php endif; ?>