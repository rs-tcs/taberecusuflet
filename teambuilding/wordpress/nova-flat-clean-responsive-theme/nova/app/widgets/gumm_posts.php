<?php
class GummPostsWidget extends GummWidget {
	
	protected $customName = 'Posts';

	protected $options = array(
		'description' => 'Display list of posts',
	);
	
	protected $supports = array('title', 'postType');
	
	protected function fields() {
        return array(
            'metaFieldsDisplay' => array(
                'name' => __('Display date, comment, or author info', 'gummfw'),
                'type' => 'checkbox',
                'value' => 'true',
            ),
            'lightBoxLinkDisplay' => array(
                'name' => __('Enable LightBox on thumbnails', 'gummfw'),
                'type' => 'checkbox',
                'value' => 'true',
            ),
        );
	}

    /**
     * @return void
     */
    public function render($fields) {
        $elementId = uniqid();
        echo '<div class="blog-1-col">';
        while (have_posts()): the_post();
        global $post;
            View::renderElement('layout-components-parts/post/single-vertical-item', array(
                'post' => $post,
                'displayMetaInfo' => $fields['metaFieldsDisplay'] === 'true',
                'elementId' => $elementId,
                'lightBoxLinkDisplay' => $fields['lightBoxLinkDisplay'] === 'true',
            ));
        endwhile;
        echo '</div>';
    }
}	
?>