<?php
class StaffLayoutElement extends GummLayoutPostsRendererElement {
    /**
     * @var string
     */
    protected $id = '0D4E47CE-B678-4F10-9340-09CE42C6D463';
    
    /**
     * @var string
     */
    public $group = 'posts';
    
    /**
     * @var array
     */
    protected $supports = array(
        'title',
        'postsNumber' => 6,
        'postType' => 'staff',
        // 'postColumns' => array(
        //     'min' => 3,
        //     'max' => 6,
        //     'value' => 6,
        //     'skip' => 5
        // ),
        'layout' => 'slider',
        'categoriesFilter',
        'excerpt' => 100,
    );
    
    /**
     * @return string
     */
    public function title() {
        return __('Staff Members', 'gummfw');
    }
}
?>