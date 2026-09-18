<?php
class SingleStaffDetailsLayoutElement extends GummLayoutElement {
    protected $id = '5850126F-0FC9-4C29-858A-74C60778555C';
    
    /**
     * @var string
     */
    public $group = 'single';
    
    protected $supports = array();
    
    protected $gridColumns = 12;
    
    // public $editable = false;
    
    public function title() {
        return __('Staff Member Details', 'gummfw');
    }
    
    protected function _fields() {
        return array();
    }
    
    protected function _render($options) {
        global $post;
        
        $staffFields = $this->Wp->getOption('staff_member_description_fields');
?>
        <div class="bottom-staff-wrap">
            <div class="single-staff-left-wrap">
                <?php
                if ($socialNetworks = Set::filter($post->PostMeta['social_networks_url'])) {
                    foreach ($socialNetworks as $k => $v) {
                        $networkName = str_replace('_url', '', $k);
                        $networkPrettyName = Inflector::humanize($networkName);
                        echo '<a href="' . $v . '" class="single-social-link" target="_blank">';
                            echo $networkPrettyName;
                            echo '<span class="icon-' . $networkName . '"></span>';
                        echo '</a>';
                    }
                }
                ?>
            </div>
            <div class="single-staff-right-wrap">
                <?php
                foreach ($staffFields as $staffField) {
                    $slugged = strtolower(Inflector::slug($staffField['title']));
                    if (isset($post->PostMeta['staff_fields']) && isset($post->PostMeta['staff_fields'][$slugged]) && $post->PostMeta['staff_fields'][$slugged]) {
                        echo '<div class="staff-extra-details">';
                            echo '<div class="term">';
                                echo '<strong><span class="' . $staffField['icon'] . '"></span>' . $staffField['title'] . '</strong>';
                            echo '</div>';
                            echo '<div class="description">' . $post->PostMeta['staff_fields'][$slugged] . '</div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
<?php
    }
}
?>