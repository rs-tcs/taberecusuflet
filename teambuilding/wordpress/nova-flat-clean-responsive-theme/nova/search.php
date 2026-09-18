<?php get_header(); ?>

<!-- BEGIN blog loop posts -->
<div class="row-fluid bluebox-container">
    
<?php if (have_posts()): ?>
    
    <?php while (have_posts()): the_post(); ?>
        <?php global $post; ?>
        
        
        <div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
            <h3 class="line-heading"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
            <div class="post-content-wrap">
                <?php the_excerpt();?>
        	</div>
        </div>

    <?php endwhile; ?>
	
	<?php
	if ($wp_query->max_num_pages > 1) {
        echo $gummHtmlHelper->nextPostsLink(__('Load More', 'gummfw'), array(
            'class' => 'load-more-link json-load-more',
        ));
	}
	?>
	

<?php else: ?>
    <div class="msg error">
        <p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'gummfw'); ?></p>
    </div>
<?php endif; ?>

</div>

<?php get_footer(); ?>