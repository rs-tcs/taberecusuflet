<?php
class EventLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = '31EFB436-2000-4C9B-8329-2CBB0871067B';
    
    /**
     * @var string
     */
    public $group = 'posts';
    
    /**
     * @var int
     */
    protected $gridColumns = 1;
    
    /**
     * @var array
     */
    protected $supports = array();
    
    /**
     * @var string
     */
    // protected $htmlClass = 'three-cols-news';
    
    /**
     * @var int
     */
    private $visibleNum = 3;
    
    /**
     * @var string
     */
    private $_currentDate;
    
    /**
     * @var array
     */
    private $_currentEvents = null;
    
    /**
     * @return string
     */
    public function title() {
        return __('Events', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        return array(
            'layout' => array(
                'name' => __('Display as', 'gummfw'),
                'type' => 'select',
                'inputOptions' => array(
                    'list' => __('List', 'gummfw'),
                    'calendar' => __('Calendar', 'gummfw'),
					'listall' => __('List of all upcoming events', 'gummfw'), 
                ),
                'value' => 'list',
            ),
        );
    }
    
    /**
     * @return void
     */
    protected function _render($options) {

        switch ($this->getParam('layout')) {
         case 'list':
            $this->_renderEventsList($options);
            break;
         case 'calendar':
            $this->_renderEventsCalendar($options);
            break;
		case 'listall':
			$this->_renderEventsListall($options);
			break;
        }
        
        wp_reset_query();
    }
    
    public function _renderEventsList($events) {
        $events = $this->getEvents();
        
        $this->_renderCalendarHeader();

        $currentDate = date('Y/m/d', strtotime($this->getCurrentDate()));
        $currentDateParts = explode('/', $currentDate);

        $firstDOM = date('Y/m/d', strtotime($currentDateParts[0] . '/' . $currentDateParts[1] . '/' . 01));
        $numDaysForMonth = date('t', strtotime($this->getCurrentDate()));
        
        global $post;
        if ($this->getEvents()):
        for ($i=0; $i<$numDaysForMonth; $i++) {
            $dayOfMonth = date('Y/m/d', strtotime($firstDOM . ' +' . $i . ' days'));
            $events = $this->getEventsForDate($dayOfMonth);
            
            foreach ($events as $post) :
                setup_postdata($post);
                GummRegistry::get('Model', 'Post')->bindPostModels($post);
                
                $postMeta = $post->PostMeta;
                $rating = (isset($postMeta['event_rating'])) ? (int) $postMeta['event_rating'] : 0;
                $organizer = (isset($postMeta['event_organizer_name'])) ? $postMeta['event_organizer_name'] : null;
                $organizerLink = (isset($postMeta['event_organizer_link'])) ? $postMeta['event_organizer_link'] : null;
                // $eventStartTime = $this->Wp->getPostMeta($post->ID, 'event_start_time');
                $eventStartTime = $post->event_start_time;
                
                $eventPermalink = $this->getPermalink($post, $dayOfMonth);
                
                
                $divAtts = array(
                    'class' => array(
                        'bluebox-events-list'
                    ),
                );
                if (!$post->Thumbnail) {
                    $divAtts['class'][] = 'no-image';
                }
?>
                <div<?php echo $this->Html->_constructTagAttributes($divAtts); ?>>
                    <div class="event-date-line">
                        <div class="date-details-wrap">
                            <?php
                            echo date_i18n('\<\s\t\r\o\n\g\>j\<\/\s\t\r\o\n\g\>\<\s\p\a\n\>M\<\/\s\p\a\n\>', strtotime($post->start_date));
                            ?>
                        </div>
                    </div>
                    <div class="event-details">
                        <div class="event-content-wrap">
                            <div class="image-wrap">
                                <div class="image-details">
                                    <?php
                                    if ($post->Thumbnail) {
                                        echo '<a href="' . get_permalink() . '" class="image-details">';
                                        echo $this->Media->display($post->Thumbnail->guid, array(
                                            'ar' => 1,
                                            'context' => 'span4'
                                        ), array(
                                            'alt' => get_the_title(),
                                        ));
                                        echo '</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="event-content">
                                <div class="page-heading-wrap">
                                    <h2><?php the_title(); ?></h2>
                                    <?php
                                    if ($post->GummOption['display_rating'] !== 'false') {
                                        View::renderElement('layout-components-parts/event/rating', array(
                                            'rating' => (int) $post->PostMeta['event_rating'],
                                        ));
                                    }
                                    ?>
                                </div>
                                <div class="event-inner-content-wrap">
                                    <?php the_excerpt(); ?>
                                    <a href="<?php echo $this->getPermalink($post, $dayOfMonth); ?>" class="event-more-link icon-plus"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<?php
            endforeach;
        }
        else:
        echo '<p class="no-posts">' . __('No events found for this month', 'gummfw') . '</p>';
        endif;
    }
    
	public function _renderEventsListall($events) {
		
		$args = array(
			'post_type'=> 'event',
			'numberposts' => '-1',
            'meta_key' => GUMM_THEME_PREFIX . '_event_start_time',
			'orderby' => GUMM_THEME_PREFIX . '_event_start_time',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => GUMM_THEME_PREFIX . '_event_start_time',
					'value' => date_i18n('Y/m/d H:i'),
					'compare' => '>=',
		        ),
		    ),
		);
			
		
		query_posts($args);
		
		while (have_posts()) : the_post();
			global $post;
			
			
		$divAtts = array(
					'class' => array(
						'bluebox-events-list'
					),
				);
				if (!$post->Thumbnail) {
					$divAtts['class'][] = 'no-image';
				}
				
		
?>
		<div<?php echo $this->Html->_constructTagAttributes($divAtts); ?>>
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
                            <div class="image-wrap">
                                <div class="image-details">
                                    <?php
                                    if ($post->Thumbnail) {
                                        echo '<a href="' . get_permalink() . '" class="image-details">';
                                        echo $this->Media->display($post->Thumbnail->guid, array(
                                            'ar' => 1,
                                            'context' => 'span4'
                                        ), array(
                                            'alt' => get_the_title(),
                                        ));
                                        echo '</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="event-content">
                                <div class="page-heading-wrap">
                                    <h2><?php the_title(); ?></h2>
                                    <?php
                                    if ($post->GummOption['display_rating'] !== 'false') {
                                        View::renderElement('layout-components-parts/event/rating', array(
                                            'rating' => (int) $post->PostMeta['event_rating'],
                                        ));
                                    }
                                    ?>
                                </div>
                                <div class="event-inner-content-wrap">
                                    <?php the_excerpt(); ?>
                                    <a href="<?php echo $this->getPermalink($post); ?>" class="event-more-link icon-plus"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

       
<?php
		endwhile;
		wp_reset_query();

	}
    public function _renderEventsCalendar($options=array()) {
        $options = array_merge(array(
            'headerStyle' => 'default',
            'wrapClass' => 'calendar-page',
            'afterContent' => '',
        ), $options);
        
        $currentDate = date('Y/m/d', strtotime($this->getCurrentDate()));
        $currentDateParts = explode('/', $currentDate);

        $firstDOM = date('Y/m/d', strtotime($currentDateParts[0] . '/' . $currentDateParts[1] . '/' . 01));
        $lastDOM = date('Y/m/d', strtotime($currentDateParts[0] . '/' . $currentDateParts[1] . '/' . date('t', strtotime($currentDate))));
        $numDays = date('t', strtotime($this->getCurrentDate()));
        
        $firstDoW = date('N', strtotime($firstDOM));
        $lastDoW = date('N', strtotime($lastDOM));
        
        $firstDoWDeviation = $firstDoWDeviationHelper = $firstDoW - 1;
        $lastDoWDeviation = $lastDoWDeviationHelper = 7-$lastDoW;
        $numIterations = $numDays + $firstDoWDeviation + $lastDoWDeviation;
        
        $weekChunks = array_chunk(range(1, 42), 7);
        
        $ymDate = date('Y/m/', strtotime($this->getCurrentDate()));
        
?>
        <div class="note-design-wrap <?php echo $options['wrapClass']; ?>">
<?php
        switch ($options['headerStyle']) {
         case 'note':
            $this->_renderCalendarNoteHeader();
            break;
         default:
            $this->_renderCalendarHeader();
        }
?>
        <div class="calendar-sheet-container content">
            <div class="sheet-item">
                <table class="gumm-events-calendar">
                    <thead>
                        <tr>
                        <?php
                        for ($i=1; $i<=7; $i++) {
                            $w = str_pad($i, 0, STR_PAD_LEFT);
                            echo '<th>' . date_i18n('D', strtotime('2012/10/' . $w)) . '</th>';
                        }
                        ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        foreach ($weekChunks as $weekChunk) {
                            // if ($counter > $numIterations) {
                                // echo '<tr style="visibility: hidden;">';
                            // } else {
                                echo '<tr>';
                            // }
                            foreach ($weekChunk as $dayChunk) {
                                $tdClass = array();
                                $displayDoM = $dayChunk - $firstDoWDeviation;
                                $eventsForToday = null;
                                if ($firstDoWDeviationHelper > 0) {
                                    $displayDate = date('Y/m/d', strtotime($firstDOM . '-' . $firstDoWDeviationHelper . ' days'));
                                    $displayDoM = date('j', strtotime($displayDate));
                                    $firstDoWDeviationHelper--;
                                    
                                    $tdClass[] = 'off';
                                } elseif ($displayDoM > $numDays && $lastDoWDeviationHelper > 0) {
                                    $displayDate = date('Y/m/d', strtotime($displayDate . '+1 days'));
                                    $displayDoM = date('j', strtotime($displayDate));
                                    $lastDoWDeviationHelper--;
                                    
                                    $tdClass[] = 'off';
                                } else {
                                    $displayDate = $ymDate . str_pad($displayDoM, 0, STR_PAD_LEFT);
                                    $eventsForToday = $this->getEventsForDate($displayDate);
                                    if ($eventsForToday) {
                                        $tdClass[] = 'event';
                                    }
                                }
                                if ($this->Time->isToday($displayDate)) {
                                    $tdClass[] = 'active';
                                }
                                
                                echo '<td class="' . implode(' ', $tdClass) . '">';
                                if ($counter > $numIterations) {
                                    echo '<a style="visibility: hidden !important;"><span>0</span></a>';
                                } elseif ($eventsForToday) {
                                    $popupTitle = __('Events for', 'gummfw') . ' ' . date_i18n('jS M, Y', strtotime($displayDate));
                                    $popupContentHtml = $this->getDayEventsPopupHtml($eventsForToday, array('date' => $displayDate));
                                    echo '<a href="#" class="b-popover" data-content="' . $popupContentHtml . '" data-original-title="' . $popupTitle . '"><span>' . $displayDoM . '</span></a>';
                                } else {
                                    echo '<a>' . $displayDoM . '</a>';
                                }
                                echo '</td>';
                                $counter++;
                            }
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-sheet-1"></div>
        <div class="bg-sheet-2"></div>
        <div class="bg-sheet-3"></div>
        <?php echo $options['afterContent']; ?>
        </div>
<?php
        // d($firstDoW);
    }
    
    private function _renderCalendarHeader() {;
        $dateFormat = 'F';
        if (!$this->Time->isThisYear($this->getCurrentDate())) {
            $dateFormat = 'F \<\s\p\a\n\>(Y)\<\s\p\a\n\>';
        }
?>
        <div class="cal-page-month-wrap">
            <h3><?php echo date_i18n($dateFormat, strtotime($this->getCurrentDate())); ?></h3>
            <a href="<?php echo $this->getPrevMonthLink(); ?>" class="nav-arrow icon-chevron-left"><span></span></a>
            <a href="<?php echo $this->getNextMonthLink(); ?>" class="nav-arrow icon-chevron-right"><span></span></a>
        </div>
<?php
    }
    
    private function _renderCalendarNoteHeader() {
        $dateFormat = 'F';
        if (!$this->Time->isThisYear($this->getCurrentDate())) {
            $dateFormat = 'F \<\s\p\a\n\>(Y)\<\s\p\a\n\>';
        }
?>
        <div class="month-heading">
            <h4><?php echo date_i18n($dateFormat, strtotime($this->getCurrentDate())); ?></h4>
            <?php
            echo $this->Html->link('', array(
                'ajax' => true,
                'controller' => 'layout_elements',
                'action' => 'display',
                'Event',
                'settings' => array(
                    'date' => $this->getNextDate(),
                    'layout' => 'calendar',
                ),
                'renderOptions' => array(
        	        'headerStyle' => 'note',
                    'wrapClass' => '',
                ),
            ), array(
                'class' => 'arrow next icon-chevron-right',
            ));
            echo $this->Html->link('', array(
                'ajax' => true,
                'controller' => 'layout_elements',
                'action' => 'display',
                'Event',
                'settings' => array(
                    'date' => $this->getPrevDate(),
                    'layout' => 'calendar',
                ),
                'renderOptions' => array(
        	        'headerStyle' => 'note',
                    'wrapClass' => '',
                ),
            ), array(
                'class' => 'arrow prev icon-chevron-left',
            ));
            ?>
        	<div class="cal-detail left"></div>
            <div class="cal-detail right"></div>
        </div>
<?php
        $this->scriptBlockStart();
?>
        // $('#<?php echo $this->htmlElementId; ?>').gummNoteFlip({
        //    ajaxItems: true,
        //    container: '.calendar-sheet-container',
        //    items: '.content'
        //});
<?php
        $this->scriptBlockEnd();
    }
    
    /**
     * A dirty function to get all events - recurring and non recurring.
     * 
     * @return array
     */
    public function getEvents() {
        global $wpdb;
        $prefix = GUMM_THEME_PREFIX;
        
        $currentDate = date('Y/m/d', strtotime($this->getCurrentDate()));
        $currentDateParts = explode('/', $currentDate);

        $firstDOM = date('Y/m/d', strtotime($currentDateParts[0] . '/' . $currentDateParts[1] . '/' . 01));
        $lastDOM = date('Y/m/d', strtotime($currentDateParts[0] . '/' . $currentDateParts[1] . '/' . date('t', strtotime($currentDate))));
        
        
        $sql = "
            SELECT $wpdb->posts.*, recurring.meta_value as recurrence, startdate.meta_value as event_start_time, postmeta.meta_value as postmeta
            FROM $wpdb->posts
            INNER JOIN $wpdb->postmeta recurring ON (
            	$wpdb->posts.ID = recurring.post_id
            	AND recurring.meta_key = '{$prefix}_recurrence'
            )
            INNER JOIN $wpdb->postmeta startdate ON (
            	$wpdb->posts.ID = startdate.post_id
            	AND startdate.meta_key = '{$prefix}_event_start_time'
            )
            LEFT JOIN $wpdb->postmeta postmeta ON (
                $wpdb->posts.ID = postmeta.post_id
    	        AND postmeta.meta_key = '{$prefix}_postmeta'
            )
            WHERE $wpdb->posts.post_status='publish'
            AND (
            	STR_TO_DATE( startdate.meta_value, '%Y/%m/%d') BETWEEN STR_TO_DATE('$firstDOM', '%Y/%m/%d') AND STR_TO_DATE('$lastDOM', '%Y/%m/%d')
            )
            OR (
            	recurring.meta_value IN ('daily', 'weekly', 'monthly')
            	AND '$firstDOM' >= STR_TO_DATE( startdate.meta_value, '%Y/%m/%d')
            )
            OR (
            	recurring.meta_value = 'yearly'
            	AND 
            		MONTH(STR_TO_DATE( startdate.meta_value, '%Y/%m/%d'))
            		BETWEEN MONTH(STR_TO_DATE('$firstDOM', '%Y/%m/%d'))
            		AND MONTH(STR_TO_DATE('$lastDOM', '%Y/%m/%d'))
                AND
                    '$firstDOM' >= STR_TO_DATE( startdate.meta_value, '%Y/%m/%d')
            )
            ORDER BY startdate.meta_value ASC
        ";
                
        $events = $wpdb->get_results($sql, OBJECT);
        
        foreach ($events as &$event) {
            $postMeta = @unserialize($event->postmeta);
            if ($postMeta !== false) {
                $event->PostMeta = $postMeta;
            } else {
                $event->PostMeta = array();
            }
            $datetimeParts = explode(' ', $event->event_start_time);
            if (count($datetimeParts) == 3) {
                $event->start_date = date('Y/m/d', strtotime($datetimeParts[0]));
            } else {
                $event->start_date = date('Y/m/d', strtotime($event->event_start_time));
            }
        }
        return $events;
    }
    
    public function getMonthLink($type) {
        global $wp_rewrite;
    	$url = GummRouter::url();
    	$date = $this->getCurrentDate();

        switch ($type) {
         case 'next':
            $date = date( 'Ym', strtotime(date('Ymd', strtotime($date)). ' +1 month') );
            break;
         case 'prev':
         case 'previous':
            $date = date( 'Ym', strtotime(date('Ymd', strtotime($date)). ' -1 month') );
            break;
        }
        
        if (strpos($url, '?') === false) {
             $url .= '?eventsdate=' . $date;
        } else {
            if (strpos($url, 'eventsdate=') !== false) {
                $url = preg_replace('|(eventsdate=)\d{6}|im', '${1}' . $date, $url);
            } else {
                $url .= '&eventsdate=' . $date;
            }
        }

        return $url;
    }
    
    public function getPrevMonthLink() {
        return $this->getMonthLink('prev');
    }
    
    public function getNextMonthLink() {
        return $this->getMonthLink('next');
    }
    
    public function getCurrentDate() {
        if (!$this->_currentDate) {
            $date = date('Ymd');
            if (isset($_REQUEST['eventsdate'])) {
                $date = date('Ymd', strtotime((int) $_REQUEST['eventsdate'] . '01'));
            } elseif ($requestedDate = $this->getParam('date')) {
                $date = date('Ymd', strtotime((int) $requestedDate));
            }
            $this->_currentDate = $date;
        }
        
        return $this->_currentDate;
    }
    
    private function getNextDate() {
        return date('Ymd', strtotime($this->getCurrentDate() . ' +1 month'));
    }
    
    private function getPrevDate() {
        return date('Ymd', strtotime($this->getCurrentDate() . ' -1 month'));
    }
    
    private function getEventsForDate($date) {
        $date = date('Y/m/d', strtotime($date));
        $currentDateParts = explode('/', $date);

        $firstDayOfMonth = date('Y/m/d', strtotime($currentDateParts[0] . '/' . $currentDateParts[1] . '/' . 01));
        if ($this->_currentEvents === null) {
            $this->_currentEvents = $this->getEvents();
        }
        $date = date('Y/m/d', strtotime($date));

        if (empty($this->_currentEvents)) return array();
        $events = array();
        foreach ($this->_currentEvents as $event) {
            $_event = clone $event;
            switch ($event->recurrence) {
             case 'yearly':
                $y = date('Y', strtotime($date));
                $_event->start_date = $y . '/' . date('m/d', strtotime($event->start_date));
                break;
             case 'monthly':
                $ym = date('Y/m', strtotime($date));
                $_event->start_date = $ym . '/' . date('d', strtotime($event->start_date));
                break;
             case 'weekly':
                if ($this->weeklyEventBelongsToDate($event, $date)) {
                    $_event->start_date = $date;
                }
                break;
             case 'daily':
                if ($event->start_date <= $date) {
                    $_event->start_date = $date;
                }
                break;
             default:
                $_event->start_date = date('Y/m/d', strtotime($_event->start_date));
                break;
            }

            if ($date == $_event->start_date) {
                $events[] = $_event;
            }

        }
        
        return $events;
    }
    
    /**
     * Determines if event with weekly recurrence belongs to specific date.
     * 
     * @param object $event
     * @param string $date
     * @return bool
     */
    private function weeklyEventBelongsToDate($event, $date) {
        if ($event->start_date > $date) return false;
        
        $dow = date('w', strtotime($date));
        $eventDoW = date('w', strtotime($event->start_date));
        
        return $dow == $eventDoW;
    }
    
    private function getDayEventsPopupHtml($events, $options=array()) {
        if (!$events) return;
        
        $options = array_merge(array(
            'date' => false,
            'sanitize' => true,
        ), $options);
        
        $outputHtml = '<ul>';
        foreach ($events as $event) {
            $outputHtml .= '<li>
                <a href="' . $this->getPermalink($event, $options['date']) . '">' . get_the_title($event->ID) . '</a>
                <span>@ ' . $this->getEventTime($event->event_start_time) . '</span>
            </li>';
        }
        $outputHtml .= '</ul>';
        
        if ($options['sanitize']) {
            $outputHtml = GummSanitize::html($outputHtml);
        }
        return $outputHtml;
    }
    
    private function getEventTime($datetimeString) {
        $datetimeParts = explode(' ', $datetimeString);

        return date_i18n(get_option('time_format'), strtotime($datetimeParts[1])) . ' ' . $datetimeParts[2];
    }
    
    public function getPermalink($event, $date=null) {
        $permalink = get_permalink($event->ID);
        if ($date) {
            $date = date('Ymd', strtotime($date));
            if (strpos($permalink, '?') === false) $permalink .= '?rd=' . $date;
            else $permalink .= '&rd=' . $date;
        }

        return $permalink;
    }
}
?>