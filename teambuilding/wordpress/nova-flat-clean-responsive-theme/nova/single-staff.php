<?php get_header(); ?>
    
<?php if (have_posts()): ?>

    <?php while(have_posts()): the_post(); ?>
        <div class="row-fluid project-wrap wide">
            <div class="span12">
                <div class="bluebox-single-staff-wrap">
                    <?php get_template_part('loop', 'builder-elements'); ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    
<?php else: ?>
    <div class="msg error">
        <p><?php _e('No posts were found', 'gummfw'); ?></p>
    </div>
<?php endif; ?>

<?php get_footer(); ?>