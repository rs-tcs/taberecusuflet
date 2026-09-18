<?php get_header(); ?>

<?php if (have_posts()): ?>
    <?php get_template_part('loop', 'builder-elements'); ?>
<?php else: ?>
    <div class="msg error">
        <p><?php _e('No posts were found', 'gummfw'); ?></p>
    </div>
<?php endif; ?>

<?php get_footer(); ?>