<?php get_header(); ?>

<div class="bluebox-builder-row">
<div class="row-fluid bluebox-container">
    
<?php if (have_posts()): ?>

    <?php
    global $wp_query;
    
    request_action(array(
        'controller' => 'layout_elements',
        'action' => 'display',
        'Portfolio',
        array(
            'posts' => $wp_query->posts,
            'postsNumber' => $wp_query->query_vars['posts_per_page'],
            'excerptLength' => 100,
            'layout' => 'grid',
            'gridLayoutStyle' => '1',
            'lightBoxLinkDisplay' => 'true',
            'enablePaginate' => 'true',
        ),
    ));
    
    ?>
	

<?php else: ?>
    <div class="msg error">
        <p><?php _e('No posts were found', 'gummfw'); ?></p>
    </div>
<?php endif; ?>
    
</div>
</div>
    
<?php get_footer(); ?>