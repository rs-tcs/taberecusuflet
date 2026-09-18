<?php
class ShortcodesHelper extends GummHelper {
    
    /**
     * @var array
     */
    public $helpers = array(
        'Form',
        'Media',
        'Html',
    );
    
    /**
     * @var array
     */
    public $wpActions = array(
        'admin_print_footer_scripts' => array(
            '_actionGummScEditorJs',
            '_actionButtonsEditorJs',
            '_actionGoogleMapJs',
            '_actionTabsEditorJs',
            '_actionSkillBarsEditorJs',
            '_actionDropCapsEditorJs',
            '_actionListEditorJs',
            '_actionPricingTableEditorJs',
        ),
    );
    
    /**
     * @var array
     */
    private $jsActionsSettings = array(
        'scEditor' => array(),
        'buttonsEditor' => array(),
        'googleMaps' => array(),
        'tabsEditor' => array(),
        'dropCapsEditor' => array(),
        'skillBarsEditor' => array(),
        'listEditor' => array(),
        'pricingTableEditor' => array()
    );
    
    /**
     * @var array
     */
    
    /**
     * @param array $shortcode
     * @return string
     */
    public function editor($shortcode) {
        $editor = 'adminDefaultEditor';
        if (isset($shortcode['editor']) && $shortcode['editor']) $editor = $shortcode['editor'];
        
        $this->jsActionsSettings['scEditor'] = true;
        
        return call_user_func_array(array(&$this, $editor), array($shortcode));
    }
    
    /**
     * @param array $shortcode
     * @return string
     */
    public function adminDefaultEditor($shortcode) {
        
    }
    
    /**
     * @param array $shortcode
     * @return string
     */
    public function adminEditorButtons($shortcode) {
        $scId = ($shortcode['parent_id']) ? $shortcode['parent_id'] : $shortcode['id'];
        
        ob_start();
?>
        <div class="gumm-sc-editor gumm-editor-buttons sc-insertdefault" title="<?php echo $scId; ?>">
            <?php
            echo $this->Form->input('GummShortcode', array(
                'id' => $shortcode['id'] . '.content',
                'type' => 'text',
                'name' => __('Button Text:', 'gummfw'),
            ), array(
                'class' => 'gumm-button-text gumm-input gumm-sc-editor-content',
            ));
            
            echo $this->Form->input('GummShortcode', array(
                'id' => $shortcode['id'] . '.link',
                'type' => 'text',
                'name' => __('Button Link:', 'gummfw'),
            ), array(
                'title' => 'link',
                'class' => 'gumm-button-link gumm-input sc-editor-attr',
            ));
            ?>
            
            <div class="gumm-switch sc-buttons-size-switch"></div>
            
            <input type="hidden" title="type" class="sc-editor-attr" value="<?php echo $shortcode['type']; ?>" />
            <input type="hidden" title="size" class="sc-editor-attr sc-editor-attr-size" value="small" />
            
        </div>
<?php
        $this->jsActionsSettings['buttonsEditor']['gumm-editor-buttons'] = true;
        
        return ob_get_clean();
    }
    
    /**
     * @param array $shortcode
     * @return string
     */
    public function adminEditorGoogleMaps($shortcode) {
        $uuid = uniqid();
        $canvasId = 'gumm-gmaps-editor-' . $uuid;
        
        ob_start();
?>

<style>
.gumm-editor-google-maps-canvas {
    float: left;
}

.gumm-editor-google-maps-inputs {
    float: right;
    width: 250px;
}
.gumm-editor-google-maps-inputs .input-wrap {
    width: auto;
}
</style>

        <div class="gumm-sc-editor gumm-editor-google-maps" title="<?php echo $shortcode['id']; ?>">
            <div id="<?php echo $canvasId; ?>"class="gumm-editor-google-maps-canvas"></div>
            <div class="gumm-editor-google-maps-inputs">
                <?php
                echo $this->Form->input('', array(
                    'id' => 'gmap-address-' . $uuid,
                    'type' => 'text',
                    'name' => __('Location', 'gummfw')    
                ), array(
                    'class' => 'gumm-input gmaps-address-input'
                ));
                ?>
                
                <div class="input-wrap">
                    <a class="link-button save gmaps-drop-marker" href="#"><span><?php _e('Drop Pin', 'gummfw'); ?></span></a>
                </div>

            </div>
            <div class="clear"></div>
        </div>
        
<?php
        $this->jsActionsSettings['googleMaps'][$canvasId] = array('width' => 300, 'height' => 200);
        
        return ob_get_clean();
    }
    
    /**
     * @param array $shortcode
     * @return string
     */
    public function adminEditorTabs($shortcode) {
        ob_start();
?>
        <div class="gumm-sc-editor gumm-editor-tabs" title="<?php echo $shortcode['id']; ?>">
            <div class="gumm-tabs-inputs-wrapper" style="position: relative;"></div>
            
            <div class="input-wrap">
                <a href="#" class="gumm-add-tab btn btn-success"><?php _e('Add Tab', 'gummfw'); ?></a>
            </div>
            
            <?php
            $scId = $shortcode['id'] . '_tab';
            if (isset($shortcode['shortcodes']) && is_array($shortcode['shortcodes'])) {
                $childShortcode = reset($shortcode['shortcodes']);
                $scId = $childShortcode['id'];
            }
            ?>
            
            <div class="gumm-tabs-inputs-shell row-fluid" style="display: none; position:relative;" title="<?php echo $scId; ?>">
                <a href="#" class="close-link gumm-delete-tab"></a>
                <?php
                echo $this->Form->input('GummShortcode', array(
                    'id' => $shortcode['id'] . '.title',
                    'type' => 'text',
                    'name' => __('Tab Title', 'gummfw'),
                ), array(
                    'class' => 'gumm-tab-title gumm-input span12',
                ), array(

                ));
                echo $this->Form->input('GummShortcode', array(
                    'id' => $shortcode['id'] . '.content',
                    'type' => 'textarea',
                    'name' => __('Tab Content', 'gummfw'),
                ), array(
                    'class' => 'gumm-tab-content gumm-input span12',
                ), array(

                ));
                
                ?>
                <div class="input-wrap wrap-radio">
                    <label for="GummScTabsActive" style="float: left; width: auto;"><?php _e('Make this the active tab', 'gummfw'); ?></label>
                    <input id="GummScTabsActive" class="gumm-tab-active gumm-tab-active-input " name="GummShortcode[tabs][<?php echo uniqid(); ?>][active]" type="radio" value="<?php echo $shortcode['id']; ?>" style="float: left; margin-left: 10px;" />
                </div>
            </div>
            
        </div>
<?php   
        return ob_get_clean();
    }
    
    public function adminEditorSkillBars($shortcode) {
        ob_start();
?>
        <div class="gumm-sc-editor gumm-editor-skill-bars" title="<?php echo $shortcode['id']; ?>">
            <div class="gumm-skill-bars-inputs-wrapper row-fluid" style="position: relative;"></div>
    
            <div class="input-wrap">
                <a href="#" class="gumm-add-skill btn btn-success"><?php _e('Add Skill', 'gummfw'); ?></a>
            </div>
            
            <?php
            $scId = $shortcode['id'] . '_tab';
            if (isset($shortcode['shortcodes']) && is_array($shortcode['shortcodes'])) {
                $childShortcode = reset($shortcode['shortcodes']);
                $scId = $childShortcode['id'];
            }
            ?>
            
            <div class="gumm-skill-bars-inputs-shell" style="display: none; position:relative; margin: 20px 0;" title="<?php echo $scId; ?>">
                <a href="#" class="close-link gumm-delete-skill"></a>
                <?php
                echo $this->Form->input('GummShortcode', array(
                    'id' => $shortcode['id'] . '.title',
                    'type' => 'text',
                    'name' => __('Skill Title', 'gummfw'),
                ), array(
                    'class' => 'gumm-tab-title gumm-input span12',
                ), array(

                ));
                echo $this->Form->input('GummShortcode', array(
                    'id' => $shortcode['id'] . '.level',
                    'type' => 'number',
                    'name' => __('Skill Level', 'gummfw'),
                ), array(
                    'value' => 0,
                    'class' => 'gumm-tab-content gumm-input span12',
                ), array(
                    'slider' => array(
                        'value' => 0,
                        'numberType' => '%'
                    )
                ));
                echo $this->Form->input('GummShortcode', array(
                    'id' => $shortcode['id'] . '.numberformat',
                    'type' => 'text',
                    'name' => __('Number Format', 'gummfw'),
                ), array(
                    'value' => '%',
                    'class' => 'gumm-tab-numberformat gumm-input span2',
                ), array(

                ));
                
                ?>
            </div>
            
        </div>
<?php
        return ob_get_clean();
    }
    
    public function adminEditorList($shortcode) {
        ob_start();
?>
        <div class="gumm-sc-editor gumm-editor-list" title="<?php echo $shortcode['id']; ?>">
            <div class="gumm-list-inputs-wrapper" style="position: relative;"></div>

            <div class="input-wrap">
                <a href="#" class="gumm-add-list-item btn btn-success"><?php _e('Add List Item', 'gummfw'); ?></a>
            </div>

            <?php
            $scId = $shortcode['id'] . '_tab';
            if (isset($shortcode['shortcodes']) && is_array($shortcode['shortcodes'])) {
                $childShortcode = reset($shortcode['shortcodes']);
                $scId = $childShortcode['id'];
            }
            ?>

            <div class="gumm-list-inputs-shell" style="display: none; position:relative;" title="<?php echo $scId; ?>">
                <a href="#" class="close-link gumm-delete-list-item"></a>
                <?php
                echo $this->Form->input('GummShortcode', array(
                    'id' => $shortcode['id'] . '.title',
                    'type' => 'text',
                    'name' => __('', 'gummfw'),
                ), array(
                    'class' => 'gumm-tab-title gumm-input span12',
                ), array(

                ));
                ?>
            </div>

        </div>
<?php
        return ob_get_clean();
    }
    
    public function adminEditorPricingTable($shortcode) {
        $scId = ($shortcode['parent_id']) ? $shortcode['parent_id'] : $shortcode['id'];
        ob_start();
?>
        <div class="gumm-sc-editor gumm-editor-pricing_table" title="<?php echo $scId; ?>">
            <div class="sc-editor-table-wrapper">
                <?php for ($i=1; $i<= 6; $i++): ?>
                <div class="gumm-table-column">
                    <?php
                    echo $this->Form->input('GummShortcode', array(
                        'id' => $shortcode['id'] . '.column_active',
                        'type' => 'radio',
                        'name' => '',
                        'inputOptions' => array($i => __('active', 'gummfw')),
                    ), array(
                        'class' => 'gumm-table-column-active gumm-input',
                    ), array(

                    ));
                    ?>
                    <a href="#" class="close-link gumm-delete-table-column"></a>
                    <h5><?php echo __('Columnt', 'gummfw') . ' ' . $i; ?></h5>
                    <ul>
                        <li class="heading-row">
                            <?php
                            echo $this->Form->input('GummShortcode', array(
                                'id' => $shortcode['id'] . '.' . $i . '.column_title',
                                'type' => 'text',
                                'name' => __('Column Title', 'gummfw'),
                            ), array(
                                'class' => 'gumm-table-column-title gumm-input span12',
                            ), array(

                            ));
                            ?>
                        </li>
                        <li class="price-row">
                            <?php
                            echo $this->Form->input('GummShortcode', array(
                                'id' => $shortcode['id'] . '.' . $i . '.column_currency',
                                'type' => 'text',
                                'name' => __('Currency', 'gummfw'),
                            ), array(
                                'class' => 'gumm-table-column-currency gumm-input span12',
                            ), array(

                            ));
                            echo $this->Form->input('GummShortcode', array(
                                'id' => $shortcode['id'] . '.' . $i . '.column_price',
                                'type' => 'text',
                                'name' => __('Price', 'gummfw'),
                            ), array(
                                'class' => 'gumm-table-column-price gumm-input span12',
                            ), array(

                            ));
                            echo $this->Form->input('GummShortcode', array(
                                'id' => $shortcode['id'] . '.' . $i . '.column_subheading',
                                'type' => 'text',
                                'name' => __('Sub Heading', 'gummfw'),
                            ), array(
                                'class' => 'gumm-table-column-subheading gumm-input span12',
                            ), array(

                            ));
                            ?>
                        </li>
                        <li class="description-row">
                            <?php
                            echo $this->Form->input('GummShortcode', array(
                                'id' => $shortcode['id'] . '.' . uniqid() . '.column_description',
                                'type' => 'text',
                                'name' => __('Column Description', 'gummfw'),
                            ), array(
                                'class' => 'gumm-table-column-description gumm-input span12',
                            ), array(

                            ));
                            ?>
                        </li>
                        <?php for ($n=1; $n<=1; $n++): ?>
                        <li class="list-item">
                            <?php
                            echo $this->Form->input('GummShortcode', array(
                                'id' => $shortcode['id'] . '.' . uniqid() . '.list_item',
                                'type' => 'text',
                                'name' => __('Item Text', 'gummfw'),
                            ), array(
                                'class' => 'gumm-table-list-item gumm-input span12',
                            ), array(

                            ));
                            ?>
                        </li>
                        <?php endfor; ?>
                        <li class="button-row">
                            <?php
                            echo $this->Form->input('GummShortcode', array(
                                'id' => $shortcode['id'] . '.' . uniqid() . '.column_button_title',
                                'type' => 'text',
                                'name' => __('Column Button Title', 'gummfw'),
                            ), array(
                                'class' => 'gumm-table-column-button-title gumm-input span12',
                            ), array(

                            ));
                            echo $this->Form->input('GummShortcode', array(
                                'id' => $shortcode['id'] . '.' . uniqid() . '.column_button_link',
                                'type' => 'text',
                                'name' => __('Column Button link', 'gummfw'),
                            ), array(
                                'class' => 'gumm-table-column-button-link gumm-input span12',
                            ), array(

                            ));
                            ?>
                        </li>
                    </ul>
                </div>
                <?php endfor; ?>
                <div class="input-wrap">
                    <a href="#" class="gumm-add-table-row btn btn-success"><?php _e('Add Row', 'gummfw'); ?></a>
                </div>
                <div class="clear"></div>
            </div>
        </div>
<?php
        return ob_get_clean();
    }
    
    public function adminEditorDropCaps($shortcode) {
        $scId = ($shortcode['parent_id']) ? $shortcode['parent_id'] : $shortcode['id'];
        
        ob_start();
?>
        <div class="gumm-sc-editor gumm-editor-dropcaps sc-insertdefault" title="<?php echo $scId; ?>">
            <?php
            echo $this->Form->input('GummShortcode', array(
                'id' => $shortcode['id'],
                'type' => 'text',
                'name' => __('Drop Cap:', 'gummfw'),
            ), array(
                'style' => 'width: 40px; height: 40px; font-size: 20px',
                'class' => 'gumm-dropcap-letter gumm-input gumm-sc-editor-content',
            ), array(

            ));

            ?>

            <input type="hidden" title="type" class="sc-editor-attr" value="<?php echo $shortcode['type']; ?>" />
            <input type="hidden" title="size" class="sc-editor-attr sc-editor-attr-size" value="small" />
            
            <div class="gumm-switch sc-dropcaps-size-switch"></div>
            
        </div>
        
<?php
        $this->jsActionsSettings['dropCapsEditor']['gumm-editor-dropcaps'] = true;
        
        return ob_get_clean();
    }
    /**
     * @param array $shortcode
     * @return string
     */
    public function adminEditorMessageBoxes($shortcode) {
        $scId = ($shortcode['parent_id']) ? $shortcode['parent_id'] : $shortcode['id'];

        ob_start();
?>
        <div class="gumm-sc-editor gumm-editor-message-boxes sc-insertdefault" title="<?php echo $scId; ?>">

            <?php
            echo $this->Form->input('GummShortcode', array(
                'id' => $shortcode['id'],
                'type' => 'textarea',
                'name' => __('Message Box Content:', 'gummfw'),
            ), array(
                'class' => 'gumm-input gumm-sc-editor-content',
            ));
            ?>

            <input type="text" title="type" class="sc-editor-attr" value="<?php echo $shortcode['type']; ?>" />

        </div>
<?php      
        return ob_get_clean();
    }
    
    /**
     * @param array $shortcode
     * @return string
     */
    public function adminEditorDividers($shortcode) {
        $scId = ($shortcode['parent_id']) ? $shortcode['parent_id'] : $shortcode['id'];
        
        ob_start();
?>
        <div class="gumm-sc-editor gumm-editor-dividers sc-insertdefault" title="<?php echo $scId; ?>">
            
            <input type="text" title="type" class="sc-editor-attr" value="<?php echo $shortcode['type']; ?>" />
            
        </div>
<?php      
        return ob_get_clean();
    }
    
    /**
     * @param array $shortcode
     * @return string
     */
    public function adminEditorMagnifyingGlass($shortcode) {
        $scId = $shortcode['id'];
        
        
        ob_start();
?>
        <div class="gumm-sc-editor gumm-editor-magnifying-glass sc-insertdefault" title="<?php echo $scId; ?>">
            <div class="row-fluid" style="margin-bottom:10px;">
                <?php
                $id = 'magnifying-glass-large-upload';
                echo $this->Media->singleUploadButton(array(
                    'button' => '<a id="' . $id . '" href="#" class="btn single-upload">' . __('Upload Large Image', 'gummfw') . '</a>',
                    'scriptData' => array(
                        'gummcontroller' => 'media',
                        'action' => 'gumm_upload',
                        'gummadmin' => true,
                        '_render' => '0',
                    ),
                    'callbacks' => array('onComplete' => 'scEditorMagnifyingGlass_onAjaxUploadComplete'),
                ), array('id' => $id));
                ?>
                
                <input type="text" title="largeurl" class="sc-editor-attr gumm-input span8" style="margin-bottom:0;" />
            </div>
            <div class="row-fluid" style="margin-bottom:10px;">
                <?php
                $id = 'magnifying-glass-small-upload';
                echo $this->Media->singleUploadButton(array(
                    'button' => '<a id="' . $id . '" href="#" class="btn single-upload">' . __('Upload Small Image', 'gummfw') . '</a>',
                    'scriptData' => array(
                        'gummcontroller' => 'media',
                        'action' => 'gumm_upload',
                        'gummadmin' => true,
                        '_render' => '0',
                    ),
                    'callbacks' => array('onComplete' => 'scEditorMagnifyingGlass_onAjaxUploadComplete'),
                ), array('id' => $id));
                ?>
                <input type="text" title="smallurl" class="sc-editor-attr gumm-input span8" style="margin-bottom:0;" />
            </div>
            
            <!-- <div class="clear"></div> -->
            <?php
            echo $this->Form->input('', array(
                'id' => $scId . '.glassSize',
                'type' => 'number',
                'name' => __('Magnifying Glass Size', 'gummfw'),
            ), array(
                'value' => 200,
                'class' => 'sc-editor-attr',
                'title' => 'glasssize',
            ), array(
                'slider' => array(
                    'min' => 20,
                    'max' => 500,
                    'step' => 10,
                    'numberType' => 'px',
                ),
            ));
            ?>
        </div>
<?php      
        return ob_get_clean();
    }
    
    public function adminEditorLightBoxImage($shortcode) {
        $scId = $shortcode['id'];

        ob_start();
?>
        <div class="gumm-sc-editor gumm-editor-magnifying-glass sc-insertdefault" title="<?php echo $scId; ?>">
            
            <div class="row-fluid" style="margin-bottom:10px;">
                <?php
                    echo $this->Html->link('<span>' . __('Large Image', 'gummfw') . '</span>', '#', array('class' => 'gumm-insert-media btn btn-success', 'data-editor' => 'gumm', 'data-multiple' => '0', 'data-preview-mode' => 'url-input'));
                ?>
                <input type="text" title="largeurl" class="sc-editor-attr gumm-input span8" style="margin:0 0 0 10px;" />
            </div>
            
            <div class="row-fluid" style="margin-bottom:10px;">
                <?php
                    echo $this->Html->link('<span>' . __('Small Image', 'gummfw') . '</span>', '#', array('class' => 'gumm-insert-media btn btn-success', 'data-editor' => 'gumm', 'data-multiple' => '0', 'data-preview-mode' => 'url-input'));
                ?>
                <input type="text" title="smallurl" class="sc-editor-attr gumm-input span8" style="margin:0 0 0 10px;" />
            </div>
            
        </div>
<?php
        return ob_get_clean();
    }
    
    /**
     * @param array $shortcode
     * @return string
     */
    public function adminEditorGaugeCircle($shortcode) {
        $scId = $shortcode['id'];

        ob_start();
?>
        <div class="gumm-sc-editor gumm-editor-gauge-circle sc-insertdefault sc-nocontent" title="<?php echo $scId; ?>">
            <?php
            echo $this->Form->input('', array(
                'id' => $scId . '.size',
                'type' => 'number',
                'name' => __('Gauge Size', 'gummfw'),
            ), array(
                'value' => $shortcode['attributes']['size'],
                'class' => 'sc-editor-attr',
                'title' => 'size',
            ), array(
                'slider' => array(
                    'min' => 12,
                    'max' => 400,
                    'step' => 2,
                    'numberType' => 'px',
                ),
            ));
            echo $this->Form->input('', array(
                'id' => $scId . '.linewidth',
                'type' => 'number',
                'name' => __('Gauge Stroke Width', 'gummfw'),
            ), array(
                'value' => $shortcode['attributes']['linewidth'],
                'class' => 'sc-editor-attr',
                'title' => 'linewidth',
            ), array(
                'slider' => array(
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                    'numberType' => 'px',
                ),
            ));
            echo $this->Form->input('', array(
                'id' => $scId . '.percent',
                'type' => 'number',
                'name' => __('Gauge Percent', 'gummfw'),
            ), array(
                'value' => $shortcode['attributes']['percent'],
                'class' => 'sc-editor-attr',
                'title' => 'percent',
            ), array(
                'slider' => array(
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                    'numberType' => '%',
                ),
            ));
            
            // echo '<div class="input-wrap wrap-colorpicker row-fluid">';
            // echo $this->Form->input('', array(
            //     'id' => $scId . '.color',
            //     'type' => 'colorpicker',
            //     'name' => __('Gauge Color', 'gummfw'),
            // ), array(
            //     'value' => $shortcode['attributes']['color'],
            //     'class' => 'sc-editor-attr',
            //     'title' => 'color',
            // ), array(
            //     'div' => false,
            //     'label' => false,
            // ));
            // echo $this->Form->input('', array(
            //     'id' => $scId . '.bgcolor',
            //     'type' => 'colorpicker',
            //     'name' => __('Bg Color', 'gummfw'),
            // ), array(
            //     'value' => $shortcode['attributes']['bgcolor'],
            //     'class' => 'sc-editor-attr',
            //     'title' => 'bgcolor',
            // ), array(
            //     'div' => false,
            //     'label' => false,
            // ));
            // echo '</div>';
            
            // icon tab
            $iconTabContent = '';
            $iconTabContent .= $this->Form->input('', array(
                'id' => $scId . '.icon',
                'type' => 'icon',
            ), array(
                'title' => 'icon',
                'class' => 'sc-editor-attr',
            ));
            $iconTabContent .= $this->Form->input('', array(
                'id' => $scId . '.iconsize',
                'type' => 'number',
                'name' => __('Icon size', 'gummfw'),
            ), array(
                'value' => $shortcode['attributes']['iconsize'],
                'title' => 'iconsize',              
                'class' => 'sc-editor-attr',
            ), array(
                'slider' => array(
                    'min' => 2,
                    'max' => 72,
                    'step' => 1,
                    'numberType' => 'px',
                ),
            ));
            
            // symbol tab
            $symbolTabContent = '';
            $symbolTabContent .= $this->Form->input('', array(
                'id' => $scId . '.symbol',
            ), array(
                'class' => 'sc-editor-attr span2',
                'value' => '%',
                'title' => 'symbol',
            ));
            $symbolTabContent .= $this->Form->input('', array(
                'id' => $scId . '.symbolsize',
                'type' => 'number',
                'name' => __('Font size', 'gummfw'),
            ), array(
                'value' => $shortcode['attributes']['symbolsize'],
                'title' => 'symbolsize',                        
                'class' => 'sc-editor-attr',
            ), array(
                'slider' => array(
                    'min' => 2,
                    'max' => 72,
                    'step' => 1,
                    'numberType' => 'px',
                ),
            ));
            
            echo $this->Form->input('', array(
                'id' => $scId . '.textsource',
                'type' => 'tabbed-input',
                'name' => __('Icon or text display', 'gummfw'),
                'inputOptions' => array(
                    'plain' => __('No Text', 'gummfw'),
                    'icon' => __('Icon', 'gummfw'),
                    'symbol' => __('Symbol', 'gummfw'),
                ),
                'tabs' => array(
                    'text' => __('No text will be displayed inside of the gauge circle', 'gummfw'),
                    'icon' =>  $iconTabContent,
                    'symbol' => $symbolTabContent,
                ),
            ), array(
                'class' => 'sc-editor-attr',
                'title' => 'textsource',
                'value' => 'plain'
            ));
            ?>
        </div>
<?php      
        return ob_get_clean();
    }
    
    // ========== //
    // WP ACTIONS //
    // ========== //
    
    public function _actionGummScEditorJs() {
        if (!$this->jsActionsSettings['scEditor']) return;
?>
<script type="text/javascript">
//<![CDATA[
    try {
        // if (jQuery('body').data('gummTinyMCECollapseSelection') === true) tinyMCE.activeEditor.selection.collapse();
        
        jQuery('.gumm-sc-editor .gumm-sc-editor-content').val(tinyMCE.activeEditor.selection.getContent());
        
        // jQuery('.gumm-insert-shortcode.execdefault').bind('click', function(e){
        //     e.preventDefault();
        //     tinyMCE.activeEditor.execCommand('gummInsertShortcode', false, jQuery(this).parents('.gumm-sc-editor:first'));
        // });
        
    }catch(err){};

//]]>
</script>
        
<?php
      
    }
    
    public function _actionButtonsEditorJs() {
        if (!$this->jsActionsSettings['buttonsEditor']) return;
?>
<script type="text/javascript">
//<![CDATA[
    try {
        <?php foreach ($this->jsActionsSettings['buttonsEditor'] as $containerClass => $settings): ?>
        
        jQuery('.<?php echo $containerClass; ?> .sc-buttons-size-switch').gummSwitch({
            change:function (stateOpen) {
                var theEditor = jQuery(this).parents('.gumm-sc-editor:first');
                var theInput = theEditor.find('input.sc-editor-attr-size');
                var theSizeValue = (stateOpen) ? 'small' : 'large';
                
                theInput.val(theSizeValue);
            }
        });
        
        <?php endforeach; ?>
    }catch(err){};

//]]>
</script>
<?php   
        
    }
    
    /**
     * @return void
     */
    public function _actionGoogleMapJs() {
        if (!$this->jsActionsSettings['googleMaps']) return;

?>

<script type="text/javascript">
//<![CDATA[
    try {
        <?php foreach ($this->jsActionsSettings['googleMaps'] as $canvasId => $mapSettings): ?>
        
        jQuery('#<?php echo $canvasId; ?>').gummGoogleMap({
            editor: '.gumm-editor-google-maps',
            width: <?php echo $mapSettings['width']; ?>,
            height: <?php echo $mapSettings['height']; ?>,
            controls: {
                pan: false,
                scale: false,
                streetView: false,
                overviewMap: false
            }
        });
        
        <?php endforeach; ?>
    }catch(err){};

//]]>
</script>

<?php

    }
    
    public function _actionTabsEditorJs() {
        if (isset($this->jsActionsSettings['tabsEditor']['initialized']) && $this->jsActionsSettings['tabsEditor']['initialized'] === true) return;
        
        $this->jsActionsSettings['tabsEditor']['initialized'] = true;
        
?>
<script type="text/javascript">
//<![CDATA[
    try {
        jQuery('.gumm-editor-tabs').gummTabsEditor({
            // insertShortcode: '.gumm-insert-shortcode'
        });
        
    }catch(err){};

//]]>
</script>
<?php

    }
    
    public function _actionSkillBarsEditorJs() {
        if (isset($this->jsActionsSettings['skillBarsEditor']['initialized']) && $this->jsActionsSettings['skillBarsEditor']['initialized'] === true) return;

        $this->jsActionsSettings['skillBarsEditor']['initialized'] = true;

?>
<script type="text/javascript">
//<![CDATA[
    try {
        jQuery('.gumm-editor-skill-bars').gummSkillBarsEditor({
            // insertShortcode: '.gumm-insert-shortcode'
        });

    }catch(err){};

//]]>
</script>
<?php

    }
    
    public function _actionListEditorJs() {
        if (isset($this->jsActionsSettings['listEditor']['initialized']) && $this->jsActionsSettings['listEditor']['initialized'] === true) return;

        $this->jsActionsSettings['listEditor']['initialized'] = true;

?>
<script type="text/javascript">
//<![CDATA[
    try {
        jQuery('.gumm-editor-list').gummListEditor({
            // insertShortcode: '.gumm-insert-shortcode'
        });

    }catch(err){};

//]]>
</script>
<?php
    }
    
    public function _actionPricingTableEditorJs() {
        if (isset($this->jsActionsSettings['pricingTableEditor']['initialized']) && $this->jsActionsSettings['pricingTableEditor']['initialized'] === true) return;

        $this->jsActionsSettings['pricingTableEditor']['initialized'] = true;

?>
<script type="text/javascript">
//<![CDATA[
    try {
        jQuery('.gumm-editor-pricing_table').gummPricingTablesEditor({
            // insertShortcode: '.gumm-insert-shortcode'
        });

    }catch(err){};

//]]>
</script>
<?php
    }
    
    public function _actionDropCapsEditorJs() {
        if (!$this->jsActionsSettings['dropCapsEditor']) return;
        
        foreach ($this->jsActionsSettings['dropCapsEditor'] as $containerClass => $settings):
?>
<script type="text/javascript">
//<![CDATA[
    try {
        
        (function (window, $, undefined) {
            jQuery('.<?php echo $containerClass; ?> .sc-dropcaps-size-switch').gummSwitch({
                change: function(stateOpen) {
                    var theEditor = jQuery(this).parents('.<?php echo $containerClass; ?>:first');
                    var theInput = theEditor.find('input.gumm-dropcap-letter:first');
                    var theSizeInput = theEditor.find('input.sc-editor-attr-size:first');
                    var fontSizeValue = (stateOpen) ? 14 : 20;
                    var sizeValue = (stateOpen) ? 'small' : 'large';

                    theInput.css({
                        fontSize: fontSizeValue
                    });
                    theSizeInput.val(sizeValue);

                }
            });
            jQuery('.<?php echo $containerClass; ?> .gumm-dropcap-letter').bind('keyup', function(e){
                var theEditor = jQuery(this).parents('.<?php echo $containerClass; ?>:first');
                var theValue = jQuery(this).val();
                if (theValue.length > 0)
                theEditor.find('.sc-dropcaps-colors').children('.dropcap').text(theValue);
            });
            
            // $('.<?php echo $containerClass; ?> .gumm-insert-shortcode').bind('click', function(e){
                // e.preventDefault();
                // e.stopPropagation();
                
                // tinyMCE.activeEditor.execCommand('gummInsertShortcode', false, $(this).parents('.<?php echo $containerClass; ?>:first'));
            // });
            
            var _currentSelection = tinyMCE.activeEditor.selection.getContent({format : 'text'}).substr(0, 1);
            if (!_currentSelection) {
                var node = tinyMCE.activeEditor.selection.getNode();
                var range = tinyMCE.activeEditor.selection.getRng()
                range.setStart(node.firstChild, 0);
                range.setEnd(node.firstChild, 1);
                tinyMCE.activeEditor.selection.setRng(range);
                _currentSelection = tinyMCE.activeEditor.selection.getContent({format : 'text'}).substr(0, 1);
                // $('body').data('gummTinyMCECollapseSelection', true);
                // $(tinyMCE.activeEditor).data('rng', range);
            } else {
                // $(tinyMCE.activeEditor).data('rng', range);
                // $('body').data('gummTinyMCECollapseSele', false);
            }
        
            var theCap = _currentSelection.substr(0, 1);
            jQuery('.<?php echo $containerClass; ?> .gumm-dropcap-letter').val(theCap).trigger('keyup');
            
        })(window, jQuery);
        
    }catch(err){};

//]]>
</script>
<?php
        endforeach;
    }

}