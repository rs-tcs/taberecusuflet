<?php
class PostColumnComponent {
    private $columns = array();
    
    public function __construct($columns) {
        $this->columns = $columns;
    }
    
    public function getColumns($columns) {
        return $this->columns;
    }
    
    public function getColumn($column) {
        global $post;
        switch ($column) {
         case 'category':
            echo get_the_term_list( $post->ID, $post->post_type . '_category', '', ', ', '' );
            break;
         case 'thumbnail':
            if ($post->Thumbnail) {
                echo GummRegistry::get('Helper', 'Media')->display($post->Thumbnail->guid, array('width' => 70, 'height' => 70));
            }
            break;
         case 'gallery_album':
            echo get_the_term_list( $post->ID, 'gallery_album', '', ', ', '' );
            break;
         case 'portfolio_cats':
            echo get_the_term_list( $post->ID, 'portfolio_cat', '', ', ', '' );
            break;
         case $post->post_type . '_author':
            $author = (isset($post->PostMeta['author'])) ? $post->PostMeta['author'] : '';
            $organisation = (isset($post->PostMeta['organisation'])) ? $post->PostMeta['organisation'] : '';
            
            echo '<span class="testimonial_author_name">' . $author . '</span>';
            if ($organisation) echo ' - <span class="testimonial_author_occupation">' . $organisation . '</span>';
            break;
         case $post->post_type . '_excerpt':
            edit_post_link(GummRegistry::get('Helper', 'Text')->truncate(get_the_excerpt(), 100, array('exact' => false)));
            break;
        }
    }
}
?>