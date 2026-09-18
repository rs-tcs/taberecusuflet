<?php
class SingleEventLayoutElement extends GummLayoutElement {
    protected $id = 'BE72BBA2-AD98-4FD7-92B2-BC13FD541A6F';
    
    /**
     * @var string
     */
    public $group = 'single';
    
    protected $supports = array();
    
    protected $gridColumns = 12;
    
    public $editable = false;
    
    public function title() {
        return __('Single Event Layout', 'gummfw');
    }
    
    protected function _fields() {
        return array();
    }
    
    protected function _render($options) {
        global $post;
		
?>
    <div class="bluebox-events-list single-post">
        <div class="event-date-line">
            <div class="date-details-wrap">
                <?php
				
				$eventStartDate = $this->Wp->getPostMeta($post->ID, 'event_start_time');
				
				
                if (isset($_GET['rd'])) {
                    $date = date_i18n('\<\s\t\r\o\n\g\>j\<\/\s\t\r\o\n\g\>\<\s\p\a\n\>M\<\/\s\p\a\n\>', strtotime($_GET['rd']));
                } elseif ($eventStartDate) {
					$eventStartDate = preg_replace("'[a-z]'i", '', $eventStartDate);
					$date = date_i18n('\<\s\t\r\o\n\g\>j\<\/\s\t\r\o\n\g\>\<\s\p\a\n\>M\<\/\s\p\a\n\>', strtotime($eventStartDate));
				} else {
					$date = date_i18n('\<\s\t\r\o\n\g\>j\<\/\s\t\r\o\n\g\>\<\s\p\a\n\>M\<\/\s\p\a\n\>', strtotime($post->post_date));
				}
				
                echo $date;
                ?>
            </div>
        </div>
        <div class="event-details">
            <div class="event-content-wrap">
                <div class="event-content">
                    <div class="event-inner-content-wrap">
                        <?php
                        if ($post->Thumbnail) {
                            echo '<div class="single-event-image">';
                                echo $this->Media->display($post->Thumbnail->guid, null, array('alt' => get_the_title()));
                            echo '</div>';
                        }
                        ?>
                        <div class="single-event-extra-info"><?php the_content(); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    }
}
?>