<?php
class TwitterTweetsLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = '6318D538-436E-4BD7-BB88-EB0EDD5BAF9D';
    
    /**
     * @var string
     */
    public $group = 'social';
    
    /**
     * @var array
     */
    protected $supports = array();
    
    /**
     * @return string
     */
    public function title() {
        return __('Twitter', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        return array(
            'twitterUsername' => array(
                'name' => __('Twitter username', 'gummfw'),
                'type' => 'text',
                'value' => $this->Wp->getOption('social.twitter.username'),
                'inputAttributes' => array(
                    'placeholder' => __('@username', 'gummfw'),
                )
            ),
            'tweetsNumber' => array(
                'name' => __('Number of tweets', 'gummfw'),
                'type' => 'number',
                'value' => 5,
                'inputSettings' => array(
                    'slider' => array(
                        'min' => 1,
                        'max' => 10,
                        'numberType' => ''
                    ),
                ),
            ),
            'fullWidth' => array(
                'name' => __('Full width element', 'gummfw'),
                'type' => 'radio',
                'inputOptions' => array(
                    'true' => __('Enable', 'gummfw'),
                    'false' => __('Disable', 'gummfw'),
                ),
                'value' => 'true'
            ),
        );
    }
    
    protected function _render($options) {
        $username = $this->getParam('twitterUsername');
        if (!$username) $username = $this->Wp->getOption('social.twitter.username');
        
        $limit = $this->getParam('tweetsNumber');
        $id = Inflector::camelize(Inflector::slug($this->htmlElementId)) . 'Tweets';
        
        App::uses('GummTwitterTweet', 'Vendor/Twitter');
        
        $Twitter = new GummTwitterTweet();
        $tweets = $Twitter->getLatest($username, $limit);
        
        if (!$tweets) return;
        
        $divAtts = array(
            'class' => array(
                'bluebox-twitter-element',
            ),
        );
        if ($this->getParam('fullWidth') === 'true') {
            $divAtts['class'][] = 'full-width';
        }
?>
        <div<?php echo $this->Html->_constructTagAttributes($divAtts); ?>>
            <div class="twitter-content bluebox-container">
                <!-- <strong class="bluebox-twitter-author">Envato</strong> -->
                
                <?php
                $divSliderAtts = array(
                    'class' => array('slider-placeholder'),
                    'data-animation' => 'fade',
                    'data-control-nav' => '0',
                    'data-direction-nav' => '0',
                    'data-direction-nav-container' => '#' . $id . '-direction-nav-container',
                    'data-smooth-height' => '0',
                    'data-animation-loop' => '1',
                );
                if (count($tweets) > 1) {
                    $divSliderAtts['class'][] = 'flex-slider';
                }
                ?>
                <div<?php echo $this->Html->_constructTagAttributes($divSliderAtts); ?>>
                    <div class="tweets-container slides" style="display:inline;">
                        <?php
                        foreach ($tweets as $tweet) {
                            echo '<div class="gumm-tweet-item slide-item">';
                            echo '<strong class="bluebox-twitter-author">' . $tweet['user']->screen_name . '</strong>';
                            echo $tweet['text'];
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
                <?php if (count($tweets) > 1): ?>
                <div id="<?php echo $id; ?>-direction-nav-container" class="prev-next-links">
                    <a class="icon-chevron-left prev" href="#"></a>
                    <a class="icon-chevron-right next" href="#"></a>
                </div>
                <?php endif; ?>
            </div>
            <div class="twitter-dark-half"></div>
            <div class="twitter-light-half"></div>
        </div>
<?php

    }
}
?>