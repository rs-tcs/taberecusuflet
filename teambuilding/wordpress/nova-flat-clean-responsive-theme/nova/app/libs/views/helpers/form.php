<?php
class FormHelper extends GummHelper {
    
    /**
     * @var array
     */
    public $helpers = array('Wp', 'Html', 'Media');
    
    /**
     * 
     */
    public $wpActions = array(
        'after_wp_tiny_mce' => array(
            '_actionPrintTextEditorJs',
        ),
        'admin_print_footer_scripts' => array(
            '_actionJquerySliderJs',
            '_actionPrintGummSwitchJs',
        )
    );
    
    /**
     * @var string
     */
    private $value;
    
    /**
     * @var string
     */
    private $model;
    
    /**
     * @var array
     */
    private $textEditorSettings = array();
    
    /**
     * @var array
     */
    private $sliderInputSettings = array();
    
    /**
     * @var array
     */
    private $gummSwitchJsSettings = array();
    
    
    /**
     * Outputs form tag with additional security fields if needed.
     * 
     * @param array $attribtues
     * @return string
     */
    public function create(array $attributes=array()) {
        $attributes = array_merge(array(
            'id' => 'gumm-form',
            'action' => get_site_url(),
            'method' => 'post',
            '_wpnonce' => Controller::WPNONCE,
            'ajax' => false,
            'admin' => false,
        ), $attributes);
        
        extract($attributes, EXTR_SKIP);
        
        unset($attributes['_wpnonce']);
        unset($attributes['ajax']);
        unset($attributes['admin']);
        
        if ($_wpnonce !== false) $_wpnonce = wp_create_nonce($_wpnonce);
        
        $htmlAttributes = $attributes;
        
        // Start tag output
        $outputHtml = '<form';
        
        $securityInputs = array();
        $securityInputs[] = $this->hidden(array('id' => '_wpnonce'), array('name' => '_wpnonce', 'value' => $_wpnonce));
        
        if (is_array($action)) {
            if (($admin === true || is_admin()) && $ajax === false) $htmlAttributes['action'] = get_admin_url();
            elseif (($admin === true || is_admin()) && $ajax === true) $htmlAttributes['action'] = admin_url('admin-ajax.php');
            else $htmlAttributes['action'] = get_site_url();
            
            foreach ($action as $key => $value) {
                if ($key == 'action') $value = GUMM_FW_PREFIX . $value;
                else $key = 'gumm' . $key;
                
                $securityInputs[] = $this->hidden(array('id' => $key), array('name' => $key, 'value' => $value));
            }
        }
        
        // Close tag output
        $outputHtml .= $this->_constructTagAttributes($htmlAttributes) . '>';
        
        $outputHtml .= '<fieldset class="gumm-security-fields">';
        foreach ($securityInputs as $securityInput) {
            $outputHtml .= $securityInput;
        }
        $outputHtml .= '</fieldset>';
        
        return $outputHtml;
        
    }
    
    /**
     * Creates closing form tag. Put form cleanup logic here.
     * 
     * @return string
     */
    public function end() {
        return '</form>';
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function input($model = '', array $option, array $attributes=array(), array $settings=array()) {
        global $post;
        if (!isset($attributes['value'])) {
            switch ($model) {
             case 'Option':
                $this->value = $this->Wp->getOption($option['id']);
                if ($this->value === false && isset($option['default'])) $this->value = $option['default'];
                break;
             case 'LayoutBlock':
                $this->value = $this->Wp->getOption($option['id']);
                 if ($this->value === false && isset($option['default'])) $this->value = $option['default'];
                break;
             case 'GummPostMeta':
                $this->value = false;
                if ($post) $this->value = $this->Wp->getPostMeta($post->ID, $option['id'], true);
                if ($this->value === false && isset($option['default'])) $this->value = $option['default'];
                break;
             default:
                $this->value = (isset($option['default'])) ? $option['default'] : false;
                break;
            }
        } else {
            $this->value = $attributes['value'];
        }
        
        $this->model = $model;
        $option = array_merge(array(
            'type' => 'text',
            'before' => '',
            'after' => '',
        ), $option);
        $attributes = $this->_mergeAttributesForOption($option, $attributes);
        $settings   = $this->_mergeSettingsForOption($option, $settings);
        $divClass   = false;
        if ($option['type'] != 'hidden') {
            $divClass = 'input-wrap wrap-' . $option['type'];
            if ((isset($settings['prepend']) && $settings['prepend'])) {
                $divClass .= ' input-prepend';
            } else {
                $divClass .= ' row-fluid';
            }
        }
        $settings = array_merge(array(
            'div' => $divClass,
            'label' => ($option['type'] != 'hidden') ? true : false,
            'prepend' => null,
            'description' => null,
            'multiple' => false,
        ), $settings);
        if (isset($attributes['multiple']) && $attributes['multiple'] && $option['type'] === 'select') {
            $settings['div'] .= ' multiple-select-wrap';
        }
        
        $outputHtml = '';
        
        $outputHtml .= $option['before'];
        $outputHtml .= $this->_openInputWrapper($settings);
        $outputHtml .= $this->label($option, $settings);
        
        switch ($option['type']) {
         case 'text':
            $outputHtml .= $this->text($option, $attributes, $settings);
            break;
         case 'textarea':
            $outputHtml .= $this->textarea($option, $attributes);
            break;
         case 'hidden':
            $outputHtml .= $this->hidden($option, $attributes);
            break;
         case 'checkboxes':
            $outputHtml .= $this->checkboxes($option, $attributes, $settings);
            break;
         case 'checkbox':
            if ($settings['multiple']) {
                $outputHtml .= $this->checkboxes($option, $attributes, $settings);
            } else {
                $outputHtml .= $this->checkbox($option, $attributes);
            }
            break;
         case 'select':
            $outputHtml .= $this->select($option, $attributes);
            break;
         case 'radio':
            $outputHtml .= $this->radio($option, $attributes, $settings);
            break;
         case 'number':
            $sliderSettings = (isset($settings['slider'])) ? $settings['slider'] : array();
            $outputHtml .= $this->number($option, $attributes, $sliderSettings);
            break;
         case 'url':
            $outputHtml .= $this->url($option, $attributes, $settings);
            break;
         case 'gummselect':
            $outputHtml .= $this->gummSelect($option, $attributes);
            break;
         case 'colorpicker':
            $outputHtml .= $this->colorPicker($option, $attributes);
            break;
         case 'font':
            $outputHtml .= $this->fontManager($option, $attributes);
            break;
         case 'media':
            $outputHtml .= $this->mediaManager($option, $attributes, $settings);
            break;
         case 'icon':
            $outputHtml .= $this->iconsManager($option, $attributes);
            break;
         case 'upload':
            $outputHtml .= $this->imageUpload($option, $attributes, $settings);
            break;
         case 'text-editor':
            $outputHtml .= $this->richTextEditor($option, $attributes);
            break;
         case 'date':
            $outputHtml .= $this->date($option, $attributes, $settings);
            break;
         case 'postspicker':
            $outputHtml .= $this->postsPicker($option, $attributes, $settings);
            break;
         case 'pages-picker':
            $outputHtml .= $this->pagesPicker($option, $attributes, $settings);
            break;
         case 'post-type':
            $outputHtml .= $this->postTypePicker($option, $attributes, $settings);
            break;
         case 'rating':
            $outputHtml .= $this->ratingPicker($option, $attributes, $settings);
            break;
         case 'tabbed-input':
            $outputHtml .= $this->tabbedInput($option, $attributes, $settings);
            break;
         case 'content-tabs':
            $outputHtml .= $this->contentTabs($option, $attributes, $settings);
            break;
         case 'content-tab':
            $outputHtml .= $this->contentTab($option, $attributes, $settings);
            break;
         case 'google-map':
            $outputHtml .= $this->googleMapEditor($option, $attributes, $settings);
            break;
         case 'slider-inputs':
            $outputHtml .= $this->sliderInputs($option, $attributes, $settings);
            break;
         case 'button-input':
            $outputHtml .= $this->buttonInputs($option, $attributes, $settings);
            break;
         case 'timezone':
            $outputHtml .= $this->timeZonesPicker($option, $attributes, $settings);
            break;
         case 'requestAction':
            $outputHtml .= $this->requestActionInputs($option, $attributes);
            break;
        }
        
        $outputHtml .= $this->_closeInputWrapper($settings);
        $outputHtml .= $option['after'];
        
        return $outputHtml;
    }
    
    public function gummSelect(array $option, array $attributes) {
        $groupId = uniqid();
?>
<style>
.gumm-tabs {
/*    border: 1px solid #FAFAFA;*/
    position: relative;
}
.gumm-tabs-nav, .gumm-tabs-panels {
    float: left;
    padding: 2px 10px;
}
.gumm-tabs-nav {
    width: 180px;
}
.gumm-tabs-panels {
    margin-left: 40px;
    border: 1px solid #D1D1D1;
}

.gumm-tab-panel {
    display: none;
}
.gumm-tabs-nav > a {
    padding: 5px 5px;
    display: block;
    color: #000000;
}
.gumm-tabs-navigator {
    height: 32px;
}
.gumm-tabs-navigator .navigator-body {
    border-top: 1px solid #d3d3d3;
    border-left: 1px solid #d3d3d3;
    border-bottom: 1px solid #d3d3d3;
    border-radius: 2px 0 0 2px;
    box-shadow: inset 1px 1px 1px #FFFFFF;
    background-color: #f0f0f0;
    height: 100%;
    float: left;
}
.gumm-tabs-navigator .navigator-arrow {
    float: left;
    height: 34px;
    width: 15px;
    background-image: url(<?php echo GUMM_THEME_IMG_URL; ?>gumm-tabs-arrow-15x34.png);
    background-repeat: no-repeat;
}

.gumm-tab-nav-item .gumm-radio {
    top: 10px;
    left: auto;
}
.gumm-select .gumm-tabs-nav > a {
/*    height: 36px;*/
}
.gumm-select-nav-text {
    margin-left: 25px;
}
.gumm-select .gumm-tab-panel {
    width: 300px;
    height: 300px;
    background-repeat: no-repeat;
    background-position: center;
    font-size: 0;
}
.gumm-select .gumm-tab-panel.blog-loop-type-1 {
    background-image: url(<?php echo GUMM_THEME_IMG_URL; ?>blog-loop-type-1-preview.png);
}
.gumm-select .gumm-tab-panel.blog-loop-type-2 {
    background-image: url(<?php echo GUMM_THEME_IMG_URL; ?>blog-loop-type-2-preview.png);
}
.gumm-select .gumm-tab-panel.blog-loop-type-3 {
    background-image: url(<?php echo GUMM_THEME_IMG_URL; ?>blog-loop-type-3-preview.png);
}
.gumm-select .gumm-tab-panel.blog-loop-type-4 {
    background-image: url(<?php echo GUMM_THEME_IMG_URL; ?>blog-loop-type-4-preview.png);
}
.gumm-select .gumm-tab-panel.blog-loop-type-5 {
    background-image: url(<?php echo GUMM_THEME_IMG_URL; ?>blog-loop-type-5-preview.png);
}
</style>
<script>
(function (window, $, undefined) {

	$.gummTabs = function gummTabs(options, callback, element) {
        this.element = $(element);
        this._create(options, callback);

	};
	$.gummTabs.prototype = {
	    _create: function(options, callback) {
            this.navBlock = this.element.find('.gumm-tabs-nav');
            this.panelBlock = this.element.find('.gumm-tabs-panels');
            this.navElement = $('<div class="gumm-tabs-navigator"><div class="navigator-body"></div><div class="navigator-arrow"></div></div>');
            this.navElement.css({
                left: 5,
                top: 0,
                position: 'absolute',
                zIndex: 0
            });
            this.navBlock.prepend(this.navElement);
        
            this.navBlock.children('.gumm-tab-nav-item').css({
                position: 'relative',
                zIndex: 10
            });
            
            var theSelectedNavItem = this.navBlock.find('.selected');
            if (theSelectedNavItem.size() < 1) theSelectedNavItem = this.navBlock.find('.gumm-tab-nav-item:first');
            
            this.selectedNavItem = theSelectedNavItem;
            // this.navElement.children('.navigator-body').width(theSelectedNavItem.width() + 30);
            this.navElement.children('.navigator-body').width(this.navBlock.width());
            
            this._select(this.selectedNavItem);
            
            this._bindListeners();
	    },
	    _bindListeners: function() {
	        var instance = this;
	        this.navBlock.children('.gumm-tab-nav-item').bind('mouseenter', function(e){
	            instance._select($(this));
	        });

	    },
	    _select: function(navItem) {
	        var instance = this;
            instance.navElement.stop().animate({
                top: navItem.position().top
            }, 150, function(){
                instance.panelBlock.find('.gumm-tab-panel').hide();
                $(navItem.attr('href')).show();
            });
	    }
	};
	
    $.fn.gummTabs = function _gummTabsInit(options, callback) {
        this.each(function () {

            var instance = $.data(this, 'gummTabs');

            if (instance) {
                // update options of current instance
                // instance.update(options);

            } else {
                // initialize new instance
                $.data(this, 'gummTabs', new $.gummTabs(options, callback, this));
            }
            
        });
        
        return this;
    }

})(window, jQuery);

(function ($){
$(document).ready(function(){
    $('.gumm-tabs').gummTabs();
    $('.gumm-select .gumm-select-option').live('click', function(e){
        e.preventDefault();
        // e.stopPropagation();
        // $(this).children('.gumm-radio').trigger('click');
    });

});
}) (jQuery);
</script>
        <div class="gumm-select gumm-tabs">
            <div class="gumm-select-options gumm-tabs-nav gumm-radio-fieldset">
                <?php $counter = 0; ?>
                <?php foreach ($option['inputOptions'] as $optionValue => $optionTex): ?>
                    <?php $currClass = ($optionValue == $this->value) ? 'selected' : ''; ?>
                    <?php $selectedRadioClass = ($optionValue == $this->value) ? 'selected-radio' : ''; ?>
                    <a href="#tabgroup-<?php echo $groupId . $counter; ?>" class="gumm-tab-nav-item gumm-select-option gumm-admin-option <?php echo $currClass; ?>" title="<?php echo $optionValue; ?>">
                        <span class="gumm-radio small <?php echo $selectedRadioClass; ?>" title="<?php echo $optionValue; ?>"></span>
                        <span class="gumm-select-nav-text"><?php echo $optionTex; ?></span>
                    </a>
                    <?php $counter++; ?>
                <?php endforeach; ?>
                
                <?php $attributes['value'] = $this->value; ?>
                <?php $attributes['class'] .= ' gumm-radio-value'; ?>
                <?php echo $this->hidden($option, $attributes); ?>
            </div>
            <div class="gumm-select-data gumm-tabs-panels">
                <?php $counter = 0; ?>
                <?php foreach ($option['inputOptions'] as $optionValue => $optionTex): ?>
                    <div id="tabgroup-<?php echo $groupId . $counter; ?>" class="gumm-tab-panel <?php echo $optionValue; ?>">
                        <?php echo $optionValue; ?>
                    </div>
                    <?php $counter++; ?>
                <?php endforeach; ?>
            </div>
        </div>
<?php
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @return string
     */
    public function text(array $option, array $attributes, array $settings) {
        if (!$attributes['class']) $attributes['class'] = 'text-input span12';
        return $this->_constructInputTag('text', $attributes);
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @return string
     */
    public function textarea(array $option, array $attributes) {
        if (isset($attributes['value'])) unset($attributes['value']);
        if (!$attributes['class']) $attributes['class'] = 'admin-textarea-full';
        if (!isset($attributes['rows'])) $attributes['rows'] = 6;
        
        return '<textarea' . $this->_constructTagAttributes($attributes) . '>' . $this->value . '</textarea>';
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @return string
     */
    public function richTextEditor(array $option, array $attributes) {
        if ($this->value) $this->value = wpautop($this->value);
        $attributes['id'] = strtolower($attributes['id']);
        $outputHtml = '<div class="gumm-rich-text-editor" data-mce-init-height="60">';
        ob_start();

        wp_editor($this->value, $attributes['id'], array(
            'textarea_name' => $attributes['name'],
        ));
        
        $outputHtml .= ob_get_clean();
        // $outputHtml .= '<div class="wp-media-buttons">
        //     <a href="#" class="button insert-media add_media" title="' . __('Add Media', 'gummfw') . '">
        //         <span class="wp-media-buttons-icon"></span> ' . __('Add Media', 'gummfw') . '</a>
        //     </div>';
        // $outputHtml .= $this->textarea($option, $attributes);
        $outputHtml .= '</div>';
        
        // $this->textEditorSettings[$attributes['id']] = array();

        return $outputHtml;
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @return string
     */
    public function hidden(array $option, array $attributes) {
        return $this->_constructInputTag('hidden', $attributes);
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function checkboxes(array $option, array $attributes, array $settings=array()) {
        $outputHtml = '';
        
        $values = $this->value && is_array($this->value) ? $this->value : array();
        foreach ($option['inputOptions'] as $inputOptionId => $inputOptionValue) {
            $_attributes = $attributes;
            $_attributes['id'] .= $inputOptionId;
            $_attributes['name'] .= '[' . $inputOptionId . ']';
            $_attributes['value'] = $this->value = isset($values[$inputOptionId]) ? $values[$inputOptionId] : 'false';
            
            $option['name'] = $inputOptionValue;
            
            $outputHtml .= $this->checkbox($option, $_attributes);
        }
        
        return $outputHtml;
    }
            
    /**
     * @param array $option
     * @param array $attributes
     * @return string
     */
    public function checkbox(array $option, array $attributes) {
        if ($this->value == 'true') $attributes['checked'] = 'checked';
        $attributes['value'] = 'true';
        
        $helperAttributes = array_merge($attributes, array(
            'id' => isset($attributes['id']) ? $attributes['id'] . '_' : '',
            'value' => 'false',
            'class' => '',
            'checked' => null
        ));
        
        $outputHtml = '';
        
        $outputHtml .= '<label class="checkbox">';
        $outputHtml .= $this->_constructInputTag('hidden', $helperAttributes);
        $outputHtml .= $this->_constructInputTag('checkbox', $attributes);
        $outputHtml .= $option['name'];
        $outputHtml .= '</label>';
        
        return $outputHtml;
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @return string
     */
    public function select(array $option, array $attributes) {
        extract($attributes, EXTR_OVERWRITE);

        if (!isset($option['inputOptions'])) return '';
        $value = $this->value;
        if (isset($attributes['value']) && $attributes['value'] !== null) {
            $value = $attributes['value'];
            unset($attributes['value']);  
        }
        
        $outputHtml = '';
        $renderClearButton = false;
        if (isset($attributes['multiple']) && $attributes['multiple']) {
            $hiddenAttributes = array(
                'type' => 'hidden',
                'name' => $attributes['name'],
                'value' => '0',
            );
            
            $attributes['multiple'] = 'multiple';
            $attributes['name'] .= '[]';
            $attributes['class'] .= ' multi';
            if (isset($attributes['clearButton']) && $attributes['clearButton']) {
                $renderClearButton = true;
                unset($attributes['clearButton']);
            }
            $outputHtml .= '<input' . $this->_constructTagAttributes($hiddenAttributes) . ' />';
        }
        
        $outputHtml .= '<select' . $this->_constructTagAttributes($attributes) . '>';
        foreach ($option['inputOptions'] as $inputOptionValue => $inputOptionTitle) {
            $selected = (in_array($inputOptionValue, (array) $value))  ? ' selected="selected"' : '';
            
            $outputHtml .= '<option value="' . $inputOptionValue . '"' . $selected .'>' . $inputOptionTitle . '</option>';
        }
        $outputHtml .= '</select>';
        
        if ($renderClearButton) {
            $outputHtml .= '<a href="#" class="gumm-clear-multiple-select">' . __('select none', 'gummfw') . '</a>';
        }
        
        return $outputHtml;
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function radio(array $option, array $attributes, array $settings=array()) {
        $settings = array_merge(array(
            'fieldLabel' => true,
            'labelGroup' => true,
        ), $settings);
        extract($attributes, EXTR_OVERWRITE);
        
        if (!isset($option['inputOptions'])) return '';
        
        if (isset($attributes['value']))  unset($attributes['value']);
        $attributes['type'] = 'radio';
        
        $outputHtml = '';
        foreach ($option['inputOptions'] as $k => $v) {
            $inputAttributes = $attributes;
            if ($k == $this->value) $inputAttributes['checked'] = 'checked';
            $inputAttributes['value'] = $k;
            $inputAttributes['id'] .= '-' . $k;
            if (!isset($inputAttributes['title'])) {
                $inputAttributes['title'] = $v;
            }
            if ($settings['labelGroup']) {
                $outputHtml .= '<div class="radio-label-group">';
            }
            
            if ($settings['fieldLabel']) {
                $outputHtml .= '<label for="' . $inputAttributes['id'] . '" class="radio">' . $v;
            }
            $outputHtml .= '<input' . $this->_constructTagAttributes($inputAttributes) . ' />';
            if ($settings['fieldLabel']) {
                $outputHtml .= '</label>';
            }
            if ($settings['labelGroup']) {
                $outputHtml .= '</div>';
            }
        }
        
        return $outputHtml;
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function date(array $option, array $attributes, array $settings=array()) {
        $attributes['value'] = $this->value;
        $outputHtml = $this->text($option, $attributes, $settings);
        
        $timeZonesEnabled = GummRegistry::get('Helper', 'Time')->getTimeZonesEnabled();
        
        $defaultAttribute = null;
        $timezonesJsString = '';
        foreach ($timeZonesEnabled as $abbr => $tz) {
            if ($defaultAttribute === null) $defaultAttribute = $abbr;
            $timezonesJsString .= "{ value: '" . $abbr . "', label: '" . $tz['fullName'] . "' },";
        }
        $timezonesJsString = trim($timezonesJsString);
        
        $this->scriptBlockStart();
?>
        $('#<?php echo $this->_friendlyId($option['id']); ?>').datetimepicker({
            dateFormat: 'yy/mm/dd',
            timeFormat: 'hh:mm z',
            showTimezone: <?php echo ($defaultAttribute) ? 'true' : 'false'; ?>,
            timezone: 'CET',
            timezoneList: [
                <?php echo $timezonesJsString; ?>
            ]
        });
<?php
        $this->scriptBlockEnd();
        
        return $outputHtml;
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @return string
     */
    public function fontManager(array $option, array $attributes) {
        $outputHtml = '';
        
        $outputHtml .= '<script type="text/javascript">loadGoogleWebFonts(["' . $this->value . '"]);</script>';
        
        if (isset($option['label'])) $outputHtml .= $this->label($option, array('label' => $option['label']));
        
        $outputHtml .= '<h3 class="update-on-change" style="font-family:' . $this->value . ';">' . $this->value . '</h3>';
        
        $attributes['value'] = $this->value;
        $attributes['class'] .= ' input-update-on-change admin-font-input';
        
        $outputHtml .= $this->hidden($option, $attributes);
        
        return $outputHtml;
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @return string
     */
    public function mediaManager(array $option, array $attributes, array $settings) {
        $settings = array_merge(array(
            'buttons' => array('media', 'embed'),
        ), $settings);
        $outputHtml = '';
        
        $uploadedMedia = '';

        $optionId = $this->model . '.' . $option['id'];
        
        if (is_array($this->value)) {
            foreach ($this->value as $attachmentId) {
                if (is_numeric($attachmentId)) {
                    $attachmentPost = get_post($attachmentId);
                    if (!$attachmentPost) continue;

                    $uploadedMedia .= View::renderElement('media/admin_add', array(
                        'optionId' => $optionId,
                        'model' => $this->model,                        
                        'attachmentPost' => $attachmentPost,
                    ), false);
                }
            }
        }
        
        $outputHtml .= $this->Media->mediaManager(array(
            'name' => $option['id'],
            'content' => $uploadedMedia,
            'optionId' => $optionId,
            'buttons' => $settings['buttons'],
        ));
        
        $outputHtml .= '<input type="hidden" name="_mergeonsave" value="0" />';


        return $outputHtml;
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @return string
     */
    public function iconsManager(array $option, array $attributes) {
        $previewClass = array(
            'icons-manager-preview-icon'
        );
        if ($this->value) $previewClass[] = $this->value;
        $uuid = uniqid();
        
        $iconAtts = array(
            'data-icon' => $this->value,
            'class' => $previewClass,
            'style' => 'position:absolute; bottom: 0; left:0; font-size: 26px; min-width: 20px;',
        );
        $buttonAtts = array(
            'class' => 'icons-manager-browse btn',
            'type' => 'button',
            'style' => 'margin-left:30px;',
        );
        $buttonRemoveAtts = array(
            'class' => 'btn icons-manager-remove-icon',
            'type' => 'button',
            'style' => 'margin-left:3px; font-size:16px;',
        );
        $inputAtts = array_merge($attributes, array(
            'class' => 'icon-manager-value',
            'value' => $this->value
        ));
        if (isset($attributes['class'])) {
            $inputAtts['class'] .= ' ' . $attributes['class'];
        }
        
        $outputHtml = '<div style="position:relative;">';
        $outputHtml .= '<i' . $this->Html->_constructTagAttributes($iconAtts) . '></i>';
        $outputHtml .= $this->hidden($option, $inputAtts);
        // $outputHtml .= '<a href="#" class="icons-manager-browse btn">' . __('Choose bullet', 'gummfw') . '</a>';
        $outputHtml .= '<button' . $this->Html->_constructTagAttributes($buttonAtts) . '>' . __('Choose bullet', 'gummfw') . '</button>';
        $outputHtml .= '<a' . $this->Html->_constructTagAttributes($buttonRemoveAtts) . '><i class="icon-remove"></i></a>';
        $outputHtml .= '</div>';
        
        return $outputHtml;
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @return string
     */
    public function iconsManagerOld(array $option, array $attributes) {
        App::uses('FontAwesome/FontAwesome', 'Vendor');
        $FontAwesome = new FontAwesome();
        $iconsData = $FontAwesome->getData();
        $id = 'gumm-filterable-content-' . uniqid();
        $outputHtml = '<div id="' . $id . '" class="gumm-filterable-content">';
        $outputHtml .= $this->hidden($option, array_merge($attributes, array('class' => 'icon-value', 'value' => $this->value)));
        $outputHtml .= '<div class="input-wrap wrap-text filter-input-wrap"><label for="filter-' . $option['id'] . '">' . __('Filter', 'gummfw') . '</label>';
        $outputHtml .= '<input id="filter' . $option['id'] . '" type="text" class="filter-input" /></div>';
        $outputHtml .= '<div class="gumm-filtarable-content-container">';
        $counter = 0;
        foreach ($iconsData as $groupId => $groupData) {
            $outputHtml .= '<h5>' . $groupData['title'] . '</h5>';
            $chunks = array_chunk( $groupData['icons'], ceil(count($groupData['icons']) / 4) );
            foreach ($chunks as $chunk) {
                $outputHtml .= '<ul style="float: left; width: 25%;">';
                foreach ($chunk as $iconClass) {
                    $iconName = str_replace('icon-', '', $iconClass);
                    $liClass = '';
                    if ($iconClass == $this->value) $liClass = 'selected';
                    $outputHtml .= '<li class="' . $liClass . '"><i data-icon-name="' . $iconName . '" class="' . $iconClass . '"></i><span>' . Inflector::humanize(Inflector::slug($iconClass, '_')) . '</span>';
                    $outputHtml .= '</li>';
                    $counter++;
                }
                $outputHtml .= '</ul>';
            }
            $outputHtml .= '<div class="clear"></div>';
        }
        $outputHtml .= '</div>';
        $outputHtml .= '</div>';
        
        $this->scriptBlockStart();
?>
        $('#<?php echo $id; ?>').gummFilterableContent();
<?php
        $this->scriptBlockEnd();
        
        return $outputHtml;
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function imageUpload(array $option, array $attributes, array $settings) {
        $settings = array_merge(array(
            'dimensions' => false,
        ), $settings);
        
        $id = $option['id'] . '-button';

        if (isset($option['inputAttribtues']) && isset($option['inputAttribtues']['defaultUrl'])) {
            if (!$this->value && !is_array($this->value)) {
                $this->value = GUMM_THEME_URL . $option['inputAttribtues']['defaultUrl'];
            } elseif ( (is_array($this->value) && isset($this->value['url']) && !$this->value['url']) || (is_array($this->value) && !isset($this->value['url'])) ) {
                $this->value['url'] = GUMM_THEME_URL . $option['inputAttribtues']['defaultUrl'];
            }
        }
        $attributes['value'] = $this->value;
        
        $outputHtml = '<div class="span4">';
        $outputHtml .= $this->Media->singleUploadButton(array(
            'button' => '<button id="' . $id . '" type="button" class="btn btn-success btn-admin-right-bottom">' . __('Upload', 'gummfw') . '</button>',
            'scriptData' => array(
                'gummcontroller' => 'media',
                'action' => 'gumm_upload',
                'gummadmin' => true,
                '_render' => '0',
            ),
            'callbacks' => array('onComplete' => 'themeOptions_onAjaxUploadComplete'),
        ), array('id' => $id));
        
        // $outputHtml .= '</div>';
        
        // $outputHtml .= '<div class="upload-input-container">';
        // $outputHtml .= '<label for="' . Inflector::camelize($option['id']) . '"><strong>' . __('Or enter URL', 'gummfw') . ':</strong></label>';
        if ($settings['dimensions']) {
            $vals = array(
                'url' => ($this->value && is_string($this->value)) ? $htis->value : '',
                'width' => '',
                'height' => '',
            );
            
            if (is_array($this->value)) {
                $vals = array_merge($vals, $this->value);
            }
            $rootId = $option['id'];
            $option['id'] .= '.url';
            $attributes['value'] = $vals['url'];
            
            $outputHtml .= $this->input($this->model, array('id' => $rootId . '.width', 'type' => 'hidden'), array('value' => $vals['width'], 'class' => 'upload-width-input'));
            $outputHtml .= $this->input($this->model, array('id' => $rootId . '.height', 'type' => 'hidden'), array('value' => $vals['height'], 'class' => 'upload-height-input'));
        }
        $outputHtml .= '<br>';
        $option['type'] = 'text';
        $attributes['name'] = null;
        $outputHtml .= $this->input($this->model, $option, $attributes, array('label' => __('URL', 'gummfw'), 'prepend' => true, 'div' => 'input-wrap wrap-text input-prepend'));
        $outputHtml .= '</div>';
        
        $outputHtml .= '<div class="upload-preview-container admin-fieldset span8">';
        if ($this->value && is_string($this->value)) {
            $outputHtml .= '<img src="' . $this->value . '" />';
        } elseif ($settings['dimensions'] && isset($vals) && $vals['url']) {
            $outputHtml .= '<img src="' . $vals['url'] . '" />';
        }
        $outputHtml .= '<span class="after-detail">' . __('Current', 'gummfw') . '</span>';
        $outputHtml .= '</div>';
        
        return $outputHtml;
    }
    
    /**
     * @param array $options
     * @return string
     */
    public function colorPicker($option, $attributes) {
        $inputAttributes = array_merge($attributes, array('value' => $this->value));
        
        $outputHtml = GummRegistry::get('Helper', 'Layout')->colorpicker(array(
            // 'class' => 'image-preview-container bgcolor-preview-container',
            'input' => array(
                'id' => $this->model . '.' . $option['id'],
                'type' => 'hidden',
            ),
            'label' => $option['name'],
            'inputAttributes' => $inputAttributes,
            'color' => (isset($inputAttributes['color'])) ? $inputAttributes['color'] : $this->value,
        ));
        
        return $outputHtml;
    }
    
    /**
     * @param array $options
     * @return string
     */
    public function number(array $option, array $attributes, array $settings=array()) {
        $settings = array_merge(array(
            'fieldLabel' => false,
        ), $settings);
        
        $sliderId = Inflector::slug($option['id'], '-') . '-slider-input';
        
        $settings = array_merge(array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'maxTitle' => null,
            'maxValue' => null,
            'animate' => false,
            // 'value' => 0,
            // 'label' => true,
            // 'inputId' => false,
            'numberType' => null,
            'orientation' => 'horizontal',
        ), $settings);
        
        $attributes = array_merge(array(
            'data-gumm-min' => $settings['min'],
            'data-gumm-max' => $settings['max'],
            'data-gumm-step' => $settings['step'],
            'data-gumm-animate' => $settings['animate'],
            'data-gumm-orientation' => $settings['orientation'],
            'data-gumm-max-title' => $settings['maxTitle'],
            'data-gumm-max-value' => $settings['maxValue'],
            'data-gumm-number-type' => $settings['numberType'],
        ), $attributes);
        
        // $settings = array_merge(array(
        //     'inputId' => $option['id'],
        //     'value' => (int) $this->value,
        //     'numberType' => '%',
        //     'orientation' => 'horizontal'
        // ), $settings);
        
        // $this->sliderInputSettings[$sliderId] = $settings;
        
        $attributes['class'] .= ' gumm-slider-input';
        
        $outputHtml = '<div class="gumm-slider-input-wrapper gumm-slider-' . $settings['orientation'] . '">';
        
        $outputHtml .= '<div class="gumm-slider-label popover top"><div class="arrow"></div><div class="popover-content"><span class="value">' . $this->value . '</span><span class="number-type">' . $settings['numberType'] . '</span></div></div>';
        $outputHtml .= '<div id="' . $sliderId . '" class="gumm-slider-container"></div>';
        
        // min: $(this).data('gumm-min'),
        // max: $(this).data('gumm-max'),
        // step: $(this).data('gumm-step'),
        // animate: $(this).data('gumm-animate'),
        // value: $(this).val(),
        // orientation: $(this).data('gumm-orientation'),
        
        if (isset($option['inputOptions']) && $option['inputOptions']) {
            $outputHtml .= $this->radio($option, array_merge(array('style' => 'display:none'), $attributes), $settings);
        } else {
            $outputHtml .= $this->hidden($option, $attributes);
        }
        
        
        $outputHtml .= '</div>';
        
        return $outputHtml;
    }
    
    /**
     * @param array $options
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function url(array $option, array $attributes, array $settings) {
        return $this->_constructInputTag('text', $attributes);
    }
    
    /**
     * @param array $options
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function postsPicker(array $option, array $attributes, array $settings) {
        // debug($option);
        App::import('Component', 'Paginator');
        $Paginator = new PaginatorComponent();
        $posts = $Paginator->paginate(array('limit' => 2));

        $outputHtml = '<div class="gumm-postpicker-input-wrapper">';
        if ($posts) {
            $outputHtml .= '<ul>';
            foreach ($posts as $post) {
                $outputHtml .= '<li>' . $post->post_title . '</li>';
            }
            $outputHtml .= '</ul>';
        }
        $outputHtml .= '</div>';
        
        return $outputHtml;
    }
    
    /**
     * @param array $options
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function pagesPicker(array $option, array $attributes, array $settings) {
        $pages = get_pages();
        $inputOptions = array();
        foreach ($pages as $page) {
            $inputOptions[$page->ID] = apply_filters('the_title', $page->post_title);
        }
        $option['inputOptions'] = $inputOptions;
        
        return $this->select($option, $attributes, $settings);
    }
    
    /**
     * @param array $options
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function postTypePicker(array $option, array $attributes, array $settings) {
        $postTypes = array_merge(array('post' => 'post'), get_post_types(array('capability_type' => 'post', '_builtin' => false, 'public' => true)));
        $postTypes = Set::applyNative($postTypes, 'ucwords');
        
        // if (isset($attributes['name'])) $option['id'] = $attributes['name'];
        
        $originalValue = $this->value;
        
        $value = null;
        $val = 'post';
        
        if ($originalValue && is_array($originalValue) && isset($originalValue['post_type'])) {
            $val = $value = $originalValue['post_type'];
        }
        
        $outputHtml = '';
        // $outputHtml = '<div class="layout-element-post-type-select">';
        
        $theInputField = array(
            'id' => $option['id'] . '.post_type',
            'name' => $option['name'],
            'inputOptions' => $postTypes,
            'type' => 'tabbed-input',
            'tabs' => array(),
        );
        
        foreach ($postTypes as $postType => $postTypeName) {
            $inputOptions = array();
            $termName = $postType == 'post' ? 'category' : $postType . '_category';

            if ($terms = get_terms($termName)) {
                if (!isset($terms->errors)) {
                    foreach ($terms as $term) {
                        $inputOptions[$term->term_id] = $term->name;
                    }
                }
            }
            
            $hiddenClass = ($postType == $val) ? '' : ' hidden';
            
            $value = null;
            if ($originalValue && is_array($originalValue) && isset($originalValue[$postType . '-category'])) {
                $value = $originalValue[$postType . '-category'];
            }
            
            $theCategoriesContent = '<em>' . __('There are no available categories for this post type.', 'gummfw') . '</em>';
            
            if ($inputOptions) {
                $theCategoriesContent = $this->input($this->model, array(
                    'name' => ucwords($postType) . ' ' . __('Category', 'gummfw'),
                    'id' => $option['id'] . '.' . $postType . '-category',
                    'type' => 'checkboxes',
                    'inputOptions' => $inputOptions,
                ), array(
                    'value' => $value,
                ));
            }
            
            $theInputField['tabs'][] = $theCategoriesContent;
        }
        
        $outputHtml .= $this->input($this->model, $theInputField, array(
            'value' => $val,
        ));
        
        $value = null;
        if ($originalValue && is_array($originalValue) && isset($originalValue['posts_number'])) {
            $value = $originalValue['posts_number'];
        }
        $outputHtml .= $this->input($this->model, array(
            'name' => __('Number of posts', 'gummfw'),
            'id' => $option['id'] . '.posts_number',
            'type' => 'number',
            'default' => 4
        ), array(
            'value' => $value,
        ), array(
            'slider' => array(
                'min' => 1,
                'max' => 50,
                'numberType' => ''
            ),
        ));
        
        // $outputHtml .= '</div>';
        
        return $outputHtml;
    }
    
    /**
     * @param array $options
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function ratingPicker(array $option, array $attributes, array $settings) {
        $val = (int) $this->value;
        $attributes['value'] = $val;
        $settings = array_merge(array(
            'ratingNum' => 5,
        ));
        $elementId = 'gumm-rating-picker-' . uniqid();
        
        $outputHtml = '';
        $outputHtml .= '<div id="' . $elementId .'">';
        $outputHtml .= '<ul class="rating-starts">';
        for ($i=0; $i<$settings['ratingNum'];$i++) {
            $selectedClass = 'icon-star-empty';
            if ($val > $i) $selectedClass = 'icon-star';
            $outputHtml .= '<li><a href="#" class="star ' . $selectedClass . '"></a></li>';
        }
        $outputHtml .= '<li class="clear-rating-container"><a href="#" class="clear-rating icon-remove"></a></li>';
        $outputHtml .= '</ul>';
        $outputHtml .= $this->hidden($option, $attributes);
        $outputHtml .= '</div>';
        
        $this->scriptBlockStart();
?>
        $('#<?php echo $elementId; ?>').gummRatingPicker();
<?php
        $this->scriptBlockEnd();
        
        return $outputHtml;
    }
    
    /**
     * @param array $options
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function tabbedInput($option, $attributes, $settings) {
        $id = 'tabbed-input-' . uniqid();
        $option['type'] = 'radio';
        $outputHtml = '<div id="' . $id . '" class="tabbable tabbed-input">';
            $outputHtml .= '<ul class="nav nav-tabs">';
                $activeTab = $counter = 0;
                foreach ($option['inputOptions'] as $value => $name) {
                    $liAtts = array('class' => null);
                    if ($value == $this->value) {
                        $liAtts['class'] = 'active';
                        $activeTab = $counter;
                    }
                    $outputHtml .= '<li' . $this->_constructTagAttributes($liAtts) . '>';
                    $outputHtml .= '<a href="#">' . $name . '</a>';
                    $outputHtml .= $this->radio(array(
                        'id' => $option['id'],
                        'inputOptions' => array($value => $name),
                    ), $attributes, array('fieldLabel' => false, 'labelGroup' => false));
                    $outputHtml .= '</li>';
                    
                    $counter++;
                }
            $outputHtml .= '</ul>';
            $outputHtml .= '<div class="tabbed-inputs">';
                $counter = 0;
                foreach ($option['tabs'] as $tab) {
                    $divAtts = array();
                    if ($counter !== $activeTab) {
                        $divAtts['style'] = 'display:none;';
                    }
                    $outputHtml .= '<div' . $this->_constructTagAttributes($divAtts) . '>';
                    if (is_string($tab)) {
                        $outputHtml .= $tab;
                    } elseif (is_array($tab)) {
                        if (isset($tab['text'])) {
                            $outputHtml .= '<em>' . $tab['text'] . '</em>';
                        } else {
                            foreach ($tab as $_tabInput) {
                                $inputAttributes    = array();
                                $inputSettings      = array();
                                if (isset($_tabInput['inputAttributes'])) {
                                    $inputAttributes = $_tabInput['inputAttributes'];
                                    unset($_tabInput['inputAttributes']);
                                }
                                if (isset($_tabInput['inputSettings'])) {
                                    $inputSettings = $_tabInput['inputSettings'];
                                    unset($_tabInput['inputSettings']);
                                }
                                $outputHtml .= $this->input($this->model, $_tabInput, $inputAttributes, $inputSettings);
                            }
                        }
                    }
                    
                    $outputHtml .= '</div>';
                    
                    $counter++;
                }
            $outputHtml .= '</div>';
        $outputHtml .= '</div>';
        
        $this->scriptBlockStart();
?>
        $('#<?php echo $id; ?>').gummTabbedInput();
<?php
        $this->scriptBlockEnd();
        
        return $outputHtml;
    }
    
    /**
     * @param array $options
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function contentTabs($option, $attributes, $settings) {
        $settings = array_merge(array(
            'contentTypes' => array('post', 'text'),
            'additionalInputs' => array(),
            'fields' => array('title', 'textarea'),
            'fieldsSettings' => array(),
            'tabLabel' => __('Tab', 'gummfw'),
            'buttonLabel' => __('Add New Tab', 'gummfw'),
            'deleteButtonLabel' => __('Delete Current Tab', 'gummfw'),
        ), $settings);
        $id = 'content-tabs-editor-' . uniqid();
        
        $tabs = $this->value;
        if (!$tabs) $tabs = array(uniqid() => array());
        
        $addTabUrl = GummRouter::url(array(
            'ajax' => true,
            'admin' => true,
            'controller' => 'layout_elements',
            'action' => 'content_tab_add',
            'optionId' => $option['id'],
            'modelName' => $this->model,
            'inputSettings' => array(
                'contentTypes' => $settings['contentTypes'],
                'additionalInputs' => $settings['additionalInputs'],
                'fields' => $settings['fields'],
                'fieldsSettings' => $settings['fieldsSettings'],
                'tabLabel' => $settings['tabLabel'],
            ),
        ));
        
        $outputHtml = '<div id="' . $id . '" class="tabbable span12">';
        
            $outputHtml .= '
            <div class="bb-option-row">
                <a href="#" class="gumm-add-content-tab-inputs btn btn-success gumm-action-button" data-add-new-url="' . $addTabUrl . '" data-tab-editor="#' . $id . '"><span>' . $settings['buttonLabel'] . '</span><i class="icon-spinner icon-spin"></i></a>
            </div>';
        
            $outputHtml .= '<ul class="nav nav-tabs" data-tab-label="' . $settings['tabLabel'] . '">';
                $counter = 0;
                foreach ($tabs as $tabId => $tabData) {
                    $liAtts = array('class' => null);
                    if ($counter === 0) {
                        $liAtts['class'] = 'active';
                    }
                    $outputHtml .= '<li' . $this->_constructTagAttributes($liAtts) . '>';
                    $outputHtml .= '<a href="#">' . $settings['tabLabel'] . ' #' . ($counter+1) . '</a>';
                    $outputHtml .= '</li>';
                
                    $counter++;
                }
            $outputHtml .= '</ul>';
        
            $outputHtml .= '<div class="tabbed-inputs bb-option-row">';
                $counter = 0;
                foreach ($tabs as $tabId => $tabData) {
                    $divAtts = array();
                    if ($counter !== 0) {
                        $divAtts['style'] = 'display:none;';
                    }
                    $outputHtml .= '<div' . $this->_constructTagAttributes($divAtts) . '>';
                        $outputHtml .= $this->input($this->model, array(
                            'id' => $option['id'] . '.' . $tabId,
                            'name' => '',
                            'type' => 'content-tab',
                        ), array(
                            'value' => $tabData,
                        ), array(
                            'contentTypes' => $settings['contentTypes'],
                            'additionalInputs' => $settings['additionalInputs'],
                            'fields' => $settings['fields'],
                            'fieldsSettings' => $settings['fieldsSettings'],
                            'tabLabel' => $settings['tabLabel'],
                        ));
                    $outputHtml .= '</div>';
                
                    $counter++;
                }
            $outputHtml .= '</div>';
            
            $outputHtml .= '
                <div class="bb-option-row">
                    <button type="button" class="btn btn-danger gumm-delete-content-tab" data-tab-editor="#' . $id . '">' . $settings['deleteButtonLabel'] . '</button>
                </div>';
        
        $outputHtml .= '</div>';
        
        $this->scriptBlockStart();
?>
        $('#<?php echo $id; ?>').gummTabbedInput({sortable: true});
<?php
        $this->scriptBlockEnd();
        
        return $outputHtml;
        
        
        
        /// ===== //
        
        
        foreach ($tabs as $tabId => $tabData) {
            $outputHtml .= $this->input($this->model, array(
                'id' => $option['id'] . '.' . $tabId,
                'name' => '',
                'type' => 'content-tab',
            ), array(
                'value' => $tabData,
            ), array(
                'contentTypes' => $settings['contentTypes'],
                'additionalInputs' => $settings['additionalInputs'],
                'fields' => $settings['fields'],
                'fieldsSettings' => $settings['fieldsSettings'],
                'tabLabel' => $settings['tabLabel'],
            ));
        }
        $addTabUrl = GummRouter::url(array(
            'ajax' => true,
            'admin' => true,
            'controller' => 'layout_elements',
            'action' => 'content_tab_add',
            'optionId' => $option['id'],
            'modelName' => $this->model,
            'inputSettings' => array(
                'contentTypes' => $settings['contentTypes'],
                'additionalInputs' => $settings['additionalInputs'],
                'fields' => $settings['fields'],
                'fieldsSettings' => $settings['fieldsSettings'],
                'tabLabel' => $settings['tabLabel'],
            ),
        ));
        
        $outputHtml .= '
            <div class="input-wrap">
                <a href="#" class="gumm-add-content-tab-inputs btn btn-success" data-add-new-url="' . $addTabUrl . '">' . $settings['buttonLabel'] . '</a>
            </div>';
        $outputHtml .= '</div>';
        
        return $outputHtml;
    }
    
    /**
     * @param array $options
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    function googleMapEditor($option, $attributes, $settings) {
        $defaults = array(
            'address'           => '',
            'maptypeid'         => '',
            'latlng'            => '',
            'zoom'              => '',
            'markerlatlng'      => '',
            'fullWidth'         => '',
            'height'            => 350,
            'enablescrollwheel' => 'false',
        );
        
        if ($this->value && is_array($this->value)) {
            $val = array_merge($defaults, $this->value);
        } else {
            $val = $defaults;
        }
        // debug($option['id']);
        $canvasId = strtolower(Inflector::slug($option['id'] . '-gmap-editor-canvas', '-'));
        
        $outputHtml = '';
        $outputHtml .= '<div id="' . $canvasId . '" class="gumm-editor-google-maps-canvas"></div>';
        $outputHtml .= '<div class="gumm-editor-google-maps-inputs">';
            
            $outputHtml .= $this->input($this->model, array(
                'id'    => $option['id'] . '.address',
                'type'  => 'text'
            ), array(
                'value'         => $val['address'],
                'class'         => 'gumm-input gmaps-address-input span12',
                'placeholder'   => __('Type address here', 'gummfw'),
            ));
            
            $outputHtml .= '<div class="input-wrap row-fluid">';
                $outputHtml .= '<a class="btn gmaps-drop-marker" href="#">' . __('Drop Pin', 'gummfw') . '</a>';
                $outputHtml .= '<a class="btn btn-danger gmaps-remove-marker" href="#">' . __('Remove Pin', 'gummfw') . '</a>';
            $outputHtml .= '</div>';
            
            $outputHtml .= $this->input($this->model, array(
                'id'    => $option['id'] . '.maptypeid',
                'type'  => 'hidden'
            ), array(
                'value' => $val['maptypeid'],
                'class' => 'gmaps-maptype-input',
            ));
            $outputHtml .= $this->input($this->model, array(
                'id'    => $option['id'] . '.latlng',
                'type'  => 'hidden'
            ), array(
                'value' => $val['latlng'],
                'class' => 'gumm-input gmaps-latlng-input',
            ));
            $outputHtml .= $this->input($this->model, array(
                'id'    => $option['id'] . '.zoom',
                'type'  => 'hidden'
            ), array(
                'value' => $val['zoom'],
                'class' => 'gumm-input gmaps-zoom-input',
            ));
            $outputHtml .= $this->input($this->model, array(
                'id'    => $option['id'] . '.markerlatlng',
                'type'  => 'hidden'
            ), array(
                'value' => $val['markerlatlng'],
                'class' => 'gumm-input gmaps-marker-input',
            ));
            
            $outputHtml .= $this->input($this->model, array(
                'id'    => $option['id'] . '.height',
                'type'  => 'number',
                'name'  => __('Map height', 'gummfw'),
            ), array(
                'value' => $val['height'],
            ), array(
                'slider' => array(
                    'min' => 100,
                    'max' => 500,
                    'step' => 25,
                    'numberType' => 'px',
                ),
            ));
            
            $outputHtml .= $this->input($this->model, array(
                'id'    => $option['id'] . '.enablescrollwheel',
                'type'  => 'checkbox',
                'name'  => __('Enable Scroll Wheel', 'gummfw'),
            ), array(
                'value' => $val['enablescrollwheel'],
            ));
            

        $outputHtml .= '</div>';
        
        $this->scriptBlockStart();
?>
        $('#<?php echo $canvasId; ?>').gummGoogleMap({
            editor: '.gumm-editor-google-maps',
            width: '100%',
            height: 300,
            useEditorInputsToInit: true,
            controls: {
                pan: false,
                scale: false,
                streetView: false,
                overviewMap: false
            }
        });
<?php      
        $this->scriptBlockEnd();

        return $outputHtml;
    }
    
    /**
     * @param array $options
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function contentTab($option, $attributes, $settings) {
        $settings = array_merge(array(
            'contentTypes' => array('post', 'text'),
            'additionalInputs' => array(),
            'fields' => array('title', 'textarea'),
            'fieldsSettings' => array(),
            'tabLabel' => __('Tab', 'gummfw'),
        ), $settings);
        $contentTypes = (array) $settings['contentTypes'];
        $fields = (array) $settings['fields'];
        
        $outputHtml = '';
        // $outputHtml .= '<div class="admin-fieldset" style="margin-bottom:20px;">';
        $outputHtml .= '<div class="row-fluid">';
        
        $originValue = $this->value;
        
        $attributes = array(
            'placeholder' => __('Title Here', 'gummfw'),
        );
        if ($originValue && is_array($originValue) && isset($originValue['title'])) {
            $attributes['value'] = $originValue['title'];
        }
        $inputSettings = array();
        if (isset($settings['fieldsSettings']['title'])) {
            $inputSettings = $settings['fieldsSettings']['title'];
        }
        $outputHtml .= $this->input($this->model, array(
            'id' => $option['id'] . '.title',
            'name' => '',
            'type' => 'text',
        ), $attributes, $inputSettings);
        
        $textareaField = '';
        $postTypeFields = '';
        
        if ( in_array('text', $contentTypes) && (in_array('textarea', $fields) || isset($fields['textarea'])) ) :
            $attributes = array();
            if ($originValue && is_array($originValue) && isset($originValue['text'])) $attributes['value'] = $originValue['text'];
            $attributes['class'] = 'span12';
            
            $textareaType = 'text-editor';
            if (isset($fields['textarea'])) {
                $textareaType = $fields['textarea'] === 'plain' ? 'textarea' : 'text-editor';
            }
            if ($textareaType === 'textarea') {
                $attributes['placeholder'] = __('Content Here', 'gummfw');
            }
            
            $textareaField .= $this->input($this->model, array(
                'id' => $option['id'] . '.text',
                'name' => '',
                'type' => $textareaType,
            ), $attributes);
            
        endif;
        
        if (in_array('post', $contentTypes)) :
            $attributes = array();
            if ($originValue && is_array($originValue) && isset($originValue['post_type'])) $attributes['value'] = $originValue['post_type'];
            $postTypeFields = $this->input($this->model, array(
                'id' => $option['id'] . '.post_type',
                'name' => '',
                'type' => 'post-type'
            ), $attributes);
        endif;
        
        if (count($contentTypes) > 1) {
            $attributes = array();
            if ($originValue && is_array($originValue) && isset($originValue['source'])) $attributes['value'] = $originValue['source'];
            $outputHtml .= $this->input($this->model, array(
                'id' => $option['id'] . '.source',
                'name' => '',
                // 'name' => __('Content', 'gummfw'),
                'type' => 'tabbed-input',
                'default' => 'custom',
                'inputOptions' => array(
                    'custom' => __('Use text', 'gummfw'),
                    'post' => __('... or posts', 'gummfw'),
                ),
                'tabs' => array(
                    $textareaField,
                    $postTypeFields,
                ),
            ), $attributes);
        } elseif ($textareaField) {
            $outputHtml .= $textareaField;
        } elseif ($postTypeFields) {
            $outputHtml .= $postTypeFields;
        }
        
        foreach ($settings['additionalInputs'] as $adInputId => $additionalInput) {
            $attributes = array();
            $inputSettings = array();
            if ($originValue && is_array($originValue) && isset($originValue[$adInputId])) $attributes['value'] = $originValue[$adInputId];
            $additionalInput['id'] = $option['id'] . '.' . $adInputId;
            
            if (isset($additionalInput['inputAttributes']) && $additionalInput['inputAttributes']) {
                $attributs = array_merge($attributs, $additionalInput['inputAttributes']);
            }
            if (isset($additionalInput['inputSettings']) && $additionalInput['inputSettings']) {
                $inputSettings = array_merge($inputSettings, $additionalInput['inputSettings']);
            }
            $outputHtml .= $this->input($this->model, $additionalInput, $attributes, $inputSettings);
        }
        
        // $outputHtml .= '</div>';
        // if ($settings['tabLabel']) {
        //     $outputHtml .= '<span class="after-detail">' . $settings['tabLabel'] . '</span>';
        // }
        // $outputHtml .= '<a href="#" class="admin-close-button gumm-delete-tab close-parent remove-on-close">×</a>';
        $outputHtml .= '</div>';
        
        return $outputHtml;
    }
    
    /**
     * @param array $options
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function sliderInputs(array $option, array $attributes, array $settings) {
        $sliderInputs = array(
            array(
                'title' => __('iosSlider', 'gummfw'),
                'value' => 'ios',
                'contentInputs' => array(
                    array(
                        'id' => 'controlNav',
                        'name' => __('Display control navigation', 'gummfw'),
                        'default' => 'true',
                        'type' => 'checkbox'
                    ),
                    array(
                        'id' => 'directionNav',
                        'name' => __('Display directional navigation', 'gummfw'),
                        'default' => 'true',
                        'type' => 'checkbox'
                    ),
                ),
            ),
            array(
                'title' => __('Flex Slider', 'gummfw'),
                'value' => 'flex',
                'contentInputs' => array(
                    array(
                        'id' => 'animation',
                        'name' => __('Type of animation', 'gummfw'),
                        'type' => 'select',
                        'default' => 'slide',
                        'inputOptions' => array(
                            'slide' => __('Slide', 'gummfw'),
                            'fade' => __('Fade', 'gummfw'),
                        )
                    ),
                    array(
                        'id' => 'controlNav',
                        'name' => __('Display control navigation', 'gummfw'),
                        'default' => 'true',
                        'type' => 'checkbox'
                    ),
                    array(
                        'id' => 'directionNav',
                        'name' => __('Display directional navigation', 'gummfw'),
                        'default' => 'true',
                        'type' => 'checkbox'
                    ),
                    array(
                        'id' => 'animationLoop',
                        'name' => __('Enable animation loop', 'gummfw'),
                        'default' => 'true',
                        'type' => 'checkbox'
                    ),
                    array(
                        'id' => 'animationSpeed',
                        'name' => __('Animation speed', 'gummfw'),
                        'type' => 'number',
                        'default' => 600,
                        'inputSettings' => array(
                            'slider' => array(
                                'min' => 0,
                                'max' => 5000,
                                'step' => 50,
                                'numberType' => 'ms'
                            ),
                        )
                    ),
                    array(
                        'id' => 'slideShowSpeed',
                        'name' => __('Slideshow speed (0 for no slideshow)', 'gummfw'),
                        'type' => 'number',
                        'default' => 0,
                        'inputSettings' => array(
                            'slider' => array(
                                'min' => 0,
                                'max' => 20000,
                                'step' => 1000,
                                'numberType' => 'ms'
                            ),
                        )
                    ),
                ),
            ),
            array(
                'title' => __('Windy Slider', 'gummfw'),
                'value' => 'windy',
                'contentInputs' => array(
                    array(
                        'id' => 'controlNav',
                        'name' => __('Display control navigation', 'gummfw'),
                        'type' => 'checkbox'
                    ),
                    array(
                        'id' => 'directionNav',
                        'name' => __('Display directional navigation', 'gummfw'),
                        'default' => 'true',
                        'type' => 'checkbox'
                    ),
                ),
            ),
        );
        
        $attributes['value'] = $this->value;
        
        $tabInputOptions = array();
        $tabsContent = array();
        foreach ($sliderInputs as $n => $inputData) {
            $tabInputOptions[$inputData['value']] = $inputData['title'];
            
            $tabsContent[$n] = '';
            foreach ($inputData['contentInputs'] as $contentInput) {
                $inputAttributes = array();
                $inputSettings = array();
                if (isset($contentInput['inputSettings'])) {
                    $inputSettings = $contentInput['inputSettings'];
                    unset($contentInput['inputSettings']);
                }
                $contentInput['id'] = $option['id'] . '-settings.' . $inputData['value'] . '.' . $contentInput['id'];
                
                $tabsContent[$n] .= $this->input($this->model, $contentInput, $inputAttributes, $inputSettings);
            }
            
        }
        $option['inputOptions'] = $tabInputOptions;
        $option['tabs'] = $tabsContent;
        $option['type'] = 'tabbed-input';
        
        $this->value = $attributes['value'];
        $outputHtml = '<div class="wrap-tabbed-input">';
        $outputHtml .= $this->tabbedInput($option, $attributes, $settings);
        $outputHtml .= '</div>';
        
        return $outputHtml;
    }
    
    /**
     * @param array $options
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function buttonInputs(array $option, array $attributes, array $settings) {
        $val = array(
            'title' => '',
            'href' => '',
            'newWindow' => 'false',
        );
        if ($this->value && is_array($this->value)) {
            $val = array_merge($val, $this->value);
        }
        $outputHtml = '';
        
        $outputHtml .= '<div class="row-fluid">';
        $outputHtml .= $this->input($this->model, array(
            'name' => '',
            'id' => $option['id'] . '.title',
            'type' => 'text',
        ), array(
            'value' => $val['title'],
        ), array(
            'label' => __('Title', 'gummfw'),
            'prepend' => true,
        ));
        $outputHtml .= '</div>';
        
        $outputHtml .= '<div class="row-fluid">';
        $outputHtml .= $this->input($this->model, array(
            'name' => '',
            'id' => $option['id'] . '.href',
            'type' => 'text',
        ), array(
            'value' => $val['href'],
        ), array(
            'label' => __('URL', 'gummfw'),
            'prepend' => true,
        ));
        $outputHtml .= '</div>';
        
        $outputHtml .= $this->input($this->model, array(
            'name' => __('Open in new window', 'gummfw'),
            'id' => $option['id'] . '.newWindow',
            'type' => 'checkbox',
        ), array(
            'value' => $val['newWindow'],
        ));

        return $outputHtml;
    }
    
    /**
     * @param array $options
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    public function timeZonesPicker(array $option, array $attributes, array $settings) {
        $timeZonesAvailable = GummRegistry::get('Helper', 'Time')->getTimeZonesAvailable();
        $timeZonesEnabled = GummRegistry::get('Helper', 'Time')->getTimeZonesEnabled($this->value);
        $outputHtml = '';
        
        $outputHtml .= '<div class="span12">';
        $outputHtml .= '<div class="gumm-time-zone-picker">';
            // Available time zones output
            $outputHtml .= '<div class="admin-fieldset gumm-items-available-wrap" style="margin-bottom:20px;">';
                $outputHtml .= '<div class="scrollable-table-container">';
                $outputHtml .= '<table class="table table-condensed">';
                $outputHtml .= '<tbody class="gumm-items-available-container">';
                foreach ($timeZonesAvailable as $abbr => $timeZone) {
                    $outputHtml .= '<tr>';
                    $outputHtml .= '<td class="action"><a class="icon-plus add-item" href="#"></a></td>';
                    $outputHtml .= '<td class="time-zone-abbr">' . $abbr . '</td>';
                    $outputHtml .= '<td class="time-zone-name">' . $timeZone['fullName'] . '</td>';
                    $outputHtml .= '<td class="time-zone-hour">' . $timeZone['timeZone'] . '</td>';
                    $outputHtml .= '<input type="hidden" name="' . $attributes['name'] . '[]" value="' . $abbr . '" disabled="disabled" />';
                    $outputHtml .= '</tr>';
                }
                $outputHtml .= '</tbody>';
                $outputHtml .= '</table>';
                $outputHtml .= '</div>';
                $outputHtml .= '<span class="after-detail">' . __('Available Time Zones', 'gummfw') . '</span>';
            $outputHtml .= '</div>';
            
            // Enabled time zones output
            $outputHtml .= '<div class="admin-fieldset time-zones-enabled-wrap" style="margin-bottom:20px;">';
                $outputHtml .= '<div class="scrollable-table-container">';
                $outputHtml .= '<table class="table table-condensed">';
                $outputHtml .= '<tbody class="gumm-items-enabled-container">';
                foreach ($timeZonesEnabled as $abbr => $timeZone) {
                    $outputHtml .= '<tr>';
                    $outputHtml .= '<td class="action"><a class="icon-minus remove-item" href="#"></a></td>';
                    $outputHtml .= '<td class="time-zone-abbr">' . $abbr . '</td>';
                    $outputHtml .= '<td class="time-zone-name">' . $timeZone['fullName'] . '</td>';
                    $outputHtml .= '<td class="time-zone-hour">' . $timeZone['timeZone'] . '</td>';
                    $outputHtml .= '<input type="hidden" name="' . $attributes['name'] . '[]" value="' . $abbr . '" />';
                    $outputHtml .= '</tr>';
                }
                $outputHtml .= '</tbody>';
                $outputHtml .= '</table>';
                $outputHtml .= '</div>';
                $outputHtml .= '<span class="after-detail">' . __('Enabled Time Zones', 'gummfw') . '</span>';
            $outputHtml .= '</div>';
            
        $outputHtml .= '</div>';
        $outputHtml .= '</div>';
        
        // 
        // 
        // 
        // $outputHtml .= '<div class="time-zones-available-wrap">';
        // $outputHtml .= '<h4>' . __('Available Time Zones', 'gummfw') . '</h4>';
        // $outputHtml .= '<div class="gumm-items-available-wrap">';
        // $outputHtml .= '<table class="gumm-items-available-container">';
        // 
        // foreach ($timeZonesAvailable as $abbr => $timeZone) {
        //     $outputHtml .= '<tr>';
        //     $outputHtml .= '<td class="action"><i class="icon-plus-sign add-item"></i></td>';
        //     $outputHtml .= '<td class="time-zone-abbr">' . $abbr . '</td>';
        //     $outputHtml .= '<td class="time-zone-name">' . $timeZone['fullName'] . '</td>';
        //     $outputHtml .= '<td class="time-zone-hour">' . $timeZone['timeZone'] . '</td>';
        //     $outputHtml .= '<input type="hidden" name="' . $attributes['name'] . '[]" value="' . $abbr . '" />';
        //     $outputHtml .= '</tr>';
        // }
        // 
        // $outputHtml .= '</table>';
        // $outputHtml .= '</div>';
        // $outputHtml .= '</div>';
        // 
        // // Enabled time zones output
        // $outputHtml .= '<div class="time-zones-enabled-wrap">';
        // $outputHtml .= '<h4>' . __('Enabled Time Zones', 'gummfw') . '</h4>';
        // $outputHtml .= '<div class="gumm-items-enabled-wrap">';
        // $outputHtml .= '<table class="gumm-items-enabled-container">';
        // 
        // $outputHtml .= '<input type="hidden" name="' . $attributes['name'] . '" value="" />';
        // foreach ($timeZonesEnabled as $abbr => $timeZone) {
        //     $outputHtml .= '<tr>';
        //     $outputHtml .= '<td class="action"><i class="icon-minus-sign remove-item"></i></td>';
        //     $outputHtml .= '<td class="time-zone-abbr">' . $abbr . '</td>';
        //     $outputHtml .= '<td class="time-zone-name">' . $timeZone['fullName'] . '</td>';
        //     $outputHtml .= '<td class="time-zone-hour">' . $timeZone['timeZone'] . '</td>';
        //     $outputHtml .= '<input type="hidden" name="' . $attributes['name'] . '[]" value="' . $abbr . '" />';
        //     $outputHtml .= '</tr>';
        // }
        // 
        // $outputHtml .= '</table>';
        // $outputHtml .= '</div>';
        // $outputHtml .= '</div>';
        
        return $outputHtml;
    }
    
    /**
     * @param array $options
     * @return string
     */
    public function requestActionInputs(array $option, array $attributes) {
        $outputHtml = '';
        
        $controllers = App::objects('controller');
        
        $selected = ($this->value && is_array($this->value)) ? $this->value : array();
        $selected = array_merge(array('controller' => '', 'action' => ''), $selected);
        
        $outputHtml .= '<div class="request-action-form-handler">';
        
        $controllerSelectAttributes = array_merge($attributes, array(
            'class' => 'request-action-controllers',
            'name' => $attributes['name'] . '[controller]'
        ));
        $outputHtml .= '<select' . $this->_constructTagAttributes($controllerSelectAttributes) . '>';
        $outputHtml .= '<option value=""></option>';
        foreach ($controllers as $controller => $controllerClass) {
            $selectedAttr = $selected['controller'] == $controller ? ' selected="selected"' : '';
            $outputHtml .= '<option value="' . $controller . '"' . $selectedAttr . '>' . $controller . '</option>';
        }
        $outputHtml .= '</select>';
        
        $superMethods = get_class_methods('Controller');
        
        $selectedController = ($selected['controller']) ? $selected['controller'] : reset(array_keys($controllers));
        // $counter = 0;
        foreach ($controllers as $controller => $controllerClass) {
            $methodSelectAttributes = array_merge($attributes, array(
                'class' => 'request-action-methods for-controller-' . $controller,
                'name' => $attributes['name'] . '[action]'
            ));
            if ($selectedController != $controller) {
                $methodSelectAttributes['class'] .= ' hidden';
                $methodSelectAttributes['disabled'] = 'disabled';
            }
            
            $outputHtml .= '<select' . $this->_constructTagAttributes($methodSelectAttributes) . '>';
            // $outputHtml .= '<option value=""></option>';
            
            App::import('Controller', $controller);
            $methods = array_diff(get_class_methods($controllerClass), $superMethods);
            foreach ($methods as $method) {
                if (strpos($method, '_') === 0) continue;
                
                $selectedAttr = ($selected['controller'] == $controller && $selected['action'] == $method) ? ' selected="selected"' : '';
                $outputHtml .= '<option value="' . $method . '"' . $selectedAttr . '>' . $method . '</option>';
            }

            $outputHtml .= '</select>';
            
            // $counter++;
        }
        $outputHtml .= '<div class="request-action-params">';
        
        $outputHtml .= '<label>Named Key:</label><input type="text" name="' . $attributes['name'] . '[namedKey][]" />';
        $outputHtml .= '<label>Named Val:</label><input type="text" name="' . $attributes['name'] . '[namedVal][]" />';
        $outputHtml .= '</div>';
        $outputHtml .= '<a href="#" class="add-request-params">+</a>';
        $outputHtml .= '</div>';
        
        $outputHtml .= '
        <script type="text/javascript">
            jQuery(document).ready(function(){

            });
        </script>
        ';
        
        return $outputHtml;
    }
    
    /**
     * @param array $option
     * @param array $settings
     * @return string
     */
    public function label(array $option, array $settings) {
        if ($settings['label'] === false) return '';
        elseif ($option['type'] === 'checkbox') return '';
        $settings = array_merge(array(
            'prepend' => false,
        ), $settings);
        
        $for = '';
        if (isset($option['id'])) $for = ' for="' . $this->_friendlyId($option['id']) . '"';
        
        $label = isset($option['name']) ? $option['name'] : '';
        if (is_string($settings['label'])) $label = $settings['label'];
        elseif (isset($option['label']) && $option['label']) $label = $option['label'];
        
        $outputHtml = '';
        if ($label) {
            if ($settings['prepend']) {
                $outputHtml = '<span class="add-on">' . $label . '</span>';
            } else {
                $outputHtml = '<label' . $for . '>' . $label . '</label>';
            }
        }
        return $outputHtml;
    }
    
    /**
     * @param array $settings
     * @return string
     */
    private function _openInputWrapper(array $settings) {
        $outputHtml = '';
        if ($settings['div'] !== false) {
            $outputHtml = ($settings['div']) ? '<div class="' . $settings['div'] . '">' : '<div>';
        }
        return $outputHtml;
    }
    
    /**
     * @param array $settings
     * @return string
     */
    private function _closeInputWrapper(array $settings) {
        $outputHtml = '';
        if ($settings['div'] !== false) {
            if ($settings['description']) {
                $outputHtml .= '<div class="alert alert-info">' . $settings['description'] . '</div>';
            }
            $outputHtml .= '</div>';
        }
        
        return $outputHtml;
    }
    
    /**
     * @param string $tag
     * @param array $attributes
     * @return string
     */
    private function _constructInputTag($tag, array $attributes) {
        return '<input type="' . $tag . '"' . $this->_constructTagAttributes($attributes) . ' />';
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @return array
     */
    private function _mergeAttributesForOption(array $option, array $attributes) {
        $attributes = array_merge(array(
            'id' => '',
            'class' => '',
        ), $attributes);
        
        if (isset($option['id']) && !$attributes['id']) $attributes['id'] = $this->_friendlyId($option['id']);
        if (isset($option['id']) && !isset($attributes['name'])) {
            
            $nameParts = array($option['id']);
            if (strpos($option['id'], '.') !== false) {
                $nameParts = explode('.', $option['id']);
            }
            $firstChunk = reset($nameParts);
            if ($firstChunk != $this->model && $this->model) {
                array_unshift($nameParts, $this->model);
            }
            
            $model = array_shift($nameParts);
            if ($nameParts)
                $attributes['name'] = $model . '[' . implode('][', $nameParts) . ']';
            else
                $attributes['name'] = $model;
                
            $attributes['name'] = str_replace('{n}', '', $attributes['name']);
        }
        // if (
        //     (isset($option['type'])) &&
        //     ($option['type'] == 'text' || $option['type'] == 'number' || $option['type'] == 'hidden') && 
        //     (!isset($attributes['value'])) &&
        //     ($this->value)
        // ) {
        //     $attributes['value'] = $this->value;
        // }
        
        switch ($option['type']) {
         case 'text':
         case 'number':
         case 'hidden':
            if (!isset($attributes['value']) && $this->value) {
                $attributes['value'] = $this->value;
            }
            break;
         case 'url':
            $attributes['class'] = ' text-input span12';
            $attributes['placeholder'] = __('http(s)://example.com', 'gummfw');
            $attributes['value'] = esc_url($this->value);
            break;
         case 'textarea':
            $this->value = esc_textarea($this->value);
            break;
        }
        
        return $attributes;
    }
    
    /**
     * @param array $option
     * @param array $attributes
     * @return array
     */
    private function _mergeSettingsForOption($option, $settings) {
        switch ($option['type']) {
         case 'url':
            $settings['label'] = __('URL', 'gummfw');
            $settings['prepend'] = true;
            break;
        }
        
        return $settings;
    }
    
    /**
     * @param string $id
     * @return string
     */
    private function _friendlyId($id) {
        return Inflector::camelize(str_replace(array('.', '{', '}'), array('_', '_', ''), $id)); 
    }
    
    /**
     * @return void
     */
     public function _actionPrintTextEditorJs() {

         if (!$this->textEditorSettings) return;
?>

<script type="text/javascript">
//<![CDATA[
    try {
        
    <?php foreach ($this->textEditorSettings as $editorId => $editorSettings): ?>
        var id = '<?php echo $editorId; ?>';
        var theEditor = jQuery('#' + id);
        
        tinyMCE.execCommand('mceAddControl', false, '<?php echo $editorId; ?>');
        
        theEditor.prev('.wp-media-buttons').children('.button.insert-media.add_media').on('click', function(e){
            var __ed = tinyMCE.EditorManager.get(id);
            if (__ed !== undefined && __ed.editorId !== undefined) {
                tinyMCE.activeEditor = __ed;
                window.wpActiveEditor = __ed.id;
            }
        });
    <?php endforeach; ?>

    } catch(err){};
//]]>
</script>

<?php
     }
     
     public function _actionJquerySliderJs() {
         // d($this->sliderInputSettings);
         if (!$this->sliderInputSettings) return;

?>

<script type="text/javascript">
//<![CDATA[
    try {
        <?php foreach ($this->sliderInputSettings as $sliderId => $settings): ?>
        <?php
            $settings = array_merge(array(
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'animate' => false,
                'value' => 0,
                'label' => true,
                'inputId' => false,
                'orientation' => 'horizontal',
            ), $settings);
        ?>
        
        <?php if ($settings['inputId']): ?>
        jQuery("#<?php echo $settings['inputId']; ?>").bind('change', function(e){
            jQuery('#<?php echo $sliderId;?>').slider('value', jQuery(this).val());
        });
        <?php endif; ?>
        
        jQuery('#<?php echo $sliderId;?>').slider({
            min: <?php echo $settings['min']; ?>,
            max: <?php echo $settings['max']; ?>,
            step: <?php echo $settings['step']; ?>,
            animate: <?php echo ($settings['animate']) ? (int) $settings['animate'] : 'false'; ?>,
            value: <?php echo (int) $settings['value']; ?>,
            orientation: <?php echo "'{$settings['orientation']}'"; ?>,
            create: function(event, ui) {
                <?php if ($settings['label']): ?>
                var theLabel = jQuery(this).parent().find('.gumm-slider-label');
                var theHandle = jQuery(this).children('.ui-slider-handle');
                var min = jQuery(this).slider('option', 'min');
                var max = jQuery(this).slider('option', 'max');
                var val = jQuery(this).slider('option', 'value');
                var left = (val-min)/(max-min)*100;
                var top = 100 - left;
                
                if (jQuery(this).slider('option', 'orientation') == 'vertical') {
                    theLabel.css({
                        top: top + '%'
                    });
                } else {
                    theLabel.css({
                        left: left + '%',
                        'margin-left': -(theLabel.outerWidth()/2)
                    });
                }


                <?php endif; ?>
            },
            slide: function(event, ui) {
                var theInputs = jQuery(this).parent().find('input.gumm-slider-input');
                if (theInputs.is(':radio')) {
                    theInputs.filter('[value=' + ui.value + ']').prop('checked', 'checked').trigger('change');
                } else {
                    theInputs.val(ui.value).trigger('change');
                }

                <?php if ($settings['label']): ?>
                    var theLabel = jQuery(this).parent().find('.gumm-slider-label');
                    var min = jQuery(this).slider('option', 'min');
                    var max = jQuery(this).slider('option', 'max');
                    var val = ui.value;
                    var left = (val-min)/(max-min)*100;
                    var top = 100 - left;
                    
                    theLabel.children('span:first').text(ui.value);
                    if (jQuery(this).slider('option', 'orientation') == 'vertical') {
                        theLabel.css({
                            top: top + '%'
                        });
                    } else {
                        theLabel.css({
                            left: left + '%'
                        });
                    }
                    
                <?php endif; ?>
            },
            change: function(event, ui) {
                <?php if ($settings['label']): ?>
                    var theLabel = jQuery(this).parent().find('.gumm-slider-label');
                    var min = jQuery(this).slider('option', 'min');
                    var max = jQuery(this).slider('option', 'max');
                    var val = ui.value;
                    var left = (val-min)/(max-min)*100;
                    var top = 100 - left;
                    
                    
                    theLabel.children('span:first').text(ui.value);
                    if (jQuery(this).slider('option', 'orientation') == 'vertical') {
                        theLabel.css({
                            top: top + '%'
                        });
                    } else {
                        theLabel.css({
                            left: left + '%'
                        });
                    }
                    
                <?php endif; ?>
            }
        });
        
        <?php endforeach; ?>
    } catch(err){};
    
//]]>
</script>

<?php
    }
    
    public function _actionPrintGummSwitchJs() {
        if (!$this->gummSwitchJsSettings) return;
        
?>

<script type="text/javascript">
//<![CDATA[
    try {
        
    } catch(err){};
    
//]]>
</script>

<?php
    }
}
?>