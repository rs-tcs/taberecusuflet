<?php
class MenusController extends AppController {
    private $customData = array(
        'gummicon'
    );
    
    public function __construct() {
        parent::__construct();
        
        add_filter('wp_edit_nav_menu_walker', array(&$this, 'addMenuEditWalker'), 10, 2);
        add_action('wp_update_nav_menu_item', array(&$this, 'beforeSave'), 10, 3);
        add_filter('wp_setup_nav_menu_item', array(&$this, 'afterFind'));

    }
    
    public function addMenuEditWalker() {
        App::uses('GummNavMenuWalkerEdit', 'Lib/Walker');
        return 'GummNavMenuWalkerEdit';
    }
    
    /*
     * Saves new field to postmeta for navigation
     */
    function beforeSave($menu_id, $menu_item_db_id, $args ) {
        foreach ($this->customData as $key) {
            $postDataName = 'menu-item-' . $key;
            if (isset($_REQUEST[$postDataName]) && is_array($_REQUEST[$postDataName])) {
                $value = $_REQUEST[$postDataName][$menu_item_db_id];
                update_post_meta($menu_item_db_id, '_menu_item_' . $key, $value);
            }
        }
    }
    
    /*
     * Adds value of new field to $item object that will be passed to     Walker_Nav_Menu_Edit_Custom
     */
    function afterFind($menu_item) {
        foreach ($this->customData as $key) {
            $menu_item->$key = get_post_meta($menu_item->ID, '_menu_item_' . $key, true);
        }

        return $menu_item;
    }
}
?>