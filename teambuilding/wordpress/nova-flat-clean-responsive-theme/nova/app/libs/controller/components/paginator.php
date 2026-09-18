<?php
class PaginatorComponent extends GummObject {
    /**
     * @var int
     */
    public $number = 10;
    
    /**
     * @var int
     */
    public $count = 0;
    
    /**
     * @var int
     */
    public $pages = 0;
    
    /**
     * @var int
     */
    public $paged = 1;
    
    /**
     * @param array $contitions
     * @return array
     */
    public function paginate($conditions=array()) {
        $conditions = array_merge(array(
            'post_type' => 'post',
            'paged' => $this->paged,
            'limit' => $this->number
        ), $conditions);
        
        $conditions = $this->_parseQueryConditions($conditions);
        
        $Query = new WP_Query($conditions);
        $posts = $Query->get_posts();
        
        $this->paged = $conditions['paged'];
        $this->count = $Query->found_posts;
        $this->pages = $Query->max_num_pages;
        $this->number = $Query->post_count;
        
        wp_reset_query();
        
        return $posts;
    }
    
    /**
     * @return mixed int or boolean false if last page
     */
    public function nextPage() {
        
    }
}
?>