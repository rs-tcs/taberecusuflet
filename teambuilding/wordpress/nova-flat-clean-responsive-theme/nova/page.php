<?php get_header(); ?>
    
<?php if (have_posts()): ?>

    <?php while(have_posts()): the_post(); ?>
        
        <?php
        if (is_page()) {
            get_template_part('loop', 'builder-elements');
        } else {
            the_content();
        }
        ?>
        
    <?php endwhile; ?>

    <?php
    
    // if (comments_open()) {
    //     echo '<div class="bluebox-builder-row"><div class="row-fluid bluebox-container">';
    //     comments_template('', true);
    //     echo '</div></div>';
    // }
    
    if ($pageLinks = GummRegistry::get('Helper', 'Pagination')->wpLinkPages()) {
        echo '<div class="bluebox-builder-row"><div class="row-fluid bluebox-container">';
        echo $pageLinks;
        echo '</div></div>';
    }
    
    ?>
    
<?php else: ?>
    <div class="msg error">
        <p><?php _e('No posts were found', 'gummfw'); ?></p>
    </div>
<?php endif; ?>

<?php get_footer(); ?>