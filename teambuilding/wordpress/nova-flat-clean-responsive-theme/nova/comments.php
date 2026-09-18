<?php
	// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	
	$comments_by_type = &separate_comments($comments);
?>

<div id="comments" class="comments-wrap">
    
	<?php if (post_password_required()): ?>
		<p class="nopassword"><?php _e('This post is password protected. Enter the password to view any comments.', 'gummfw'); ?></p>
		</div><!-- #comments -->

	<?php
		return;
		endif;
	?>
	
	<?php if (have_comments() && comments_open()): ?>
        <div class="bluebox-heading-wrap">
            <h3 class="bluebox-heading"><?php comments_number(__('No Comments', 'gummfw'), __('One Comment', 'gummfw'), __('% Comments', 'gummfw'));?></h3>
        </div>
        
        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'callback' => array(
                    GummRegistry::get('Controller', 'Comments'),
                    'view'
                ),
            ));
            ?>
        </ol>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')): // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous">
					<?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'gummfw' ) ); ?>
				</div>
				<div class="nav-next">
					<?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'gummfw' ) ); ?>
				</div>
			</div> <!-- .navigation -->
		<?php endif; // check for comment navigation ?>


	<?php elseif (!comments_open()): // this is displayed if comments are closed ?>
			
	<?php else: ?>

	<?php endif; ?>
	
</div>

<?php if ( comments_open() ) : ?>
    <?php
    add_filter('comment_form_default_fields', 'gummFilterFormDefaultFields');
    
    echo '<div class="bluebox-heading-wrap"><h3 class="bluebox-heading">' . __('Leave a reply', 'gummfw') . '</h3></div>';
    echo '<div class="bluebox-contact type-one">';

    comment_form(array(
        'title_reply' => '',
        'id_submit' => 'button-submit',
        'comment_notes_after' => '',
        'comment_field' => '<textarea id="comment" name="comment" placeholder="' . __('Comment', 'gummfw') . '*" cols="45" rows="8" aria-required="true"></textarea>'
    ));
    echo '</div>';
    ?>
<?php endif; // if you delete this the sky will fall on your head ?>

<?php
function gummFilterFormDefaultFields($fields) {
    global $gummHtmlHelper;
    
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    
    $inputAtts = array(
        'author' => array(
            'id' => 'author',
            'name' => 'author',
            'type' => 'text',
            'placeholder' => __('Name', 'gummfw'),
            'value' => esc_attr($commenter['comment_author']),
        ),
        'email' => array(
            'id' => __('Email', 'gummfw'),
            'name' => 'email',
            'type' => 'text',
            'placeholder' => __('Email', 'gummfw'),
            'value' => esc_attr(  $commenter['comment_author_email']),
        ),
        'url' => array(
            'id' => 'url',
            'name' => 'url',
            'type' => 'text',
            'placeholder' => __('Website', 'gummfw'),
            'value' => esc_attr( $commenter['comment_author_url']),
        ),
    );
    
    foreach ($inputAtts as $type => &$atts) {
        if ($req) {
            $atts['placeholder'] .= '*';
            $atts['aria-required'] = 'true';
        }
    }
    
    $fields['author'] = '<input' . $gummHtmlHelper->_constructTagAttributes($inputAtts['author']) .' />';
    $fields['email'] = '<input' . $gummHtmlHelper->_constructTagAttributes($inputAtts['email']) .' />';
    $fields['url'] = '<input' . $gummHtmlHelper->_constructTagAttributes($inputAtts['url']) .' />';

    return $fields;
}
?>
