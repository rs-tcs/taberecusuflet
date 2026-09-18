<?php
/*
PaginationHelper class - based on Plugin WP-PageNavi by Lester 'GaMerZ' Chan

Plugin Name: WP-PageNavi
Plugin URI: https://lesterchan.net/portfolio/programming/php/
Description: Adds a more advanced paging navigation to your WordPress blog.
Version: 2.50
Author: Lester 'GaMerZ' Chan
Author URI: https://lesterchan.net
*/


/*  
	Copyright 2009  Lester Chan  (email : lesterchan@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class PaginationHelper extends GummHelper {
    private $options = array();
    
    public function __construct($options=array()) {
        parent::__construct();
        
        $this->options = array_merge(array(
            'class' => 'bluebox-pagination',
            'pages_text' => '',
            'current_text' => '%PAGE_NUMBER%',
        	'page_text' => '%PAGE_NUMBER%',
        	'first_text' => '&laquo; ' . __('First', 'gummfw'),
            'last_text' => __('Last', 'gummfw') . ' &raquo;',
        	'next_text' => __('Next', 'gummfw') . ' <i class="icon-angle-right"></i>',
        	'prev_text' => '<i class="icon-angle-left"></i> ' . __('Prev', 'gummfw'),
        	'dotright_text' => '...',
        	'dotleft_text' => '...',
        	'style' => 1,
        	'num_pages' => 5,
        	'always_show' => 0,
        	'num_larger_page_numbers' => 3,
        	'larger_page_numbers_multiple' => 10,
        ), $options);
    }
    
    public function paginate($pagenavi_options=array()) {
        $pagenavi_options = array_merge($this->options, $pagenavi_options);
        global $wpdb, $wp_query;
    	if (!is_single()) {
    		$request = $wp_query->request;
    		$posts_per_page = intval(get_query_var('posts_per_page'));
    		$paged = intval(get_query_var('paged'));
    		$numposts = $wp_query->found_posts;
    		$max_page = $wp_query->max_num_pages;
    		if(empty($paged) || $paged == 0) {
    			$paged = 1;
    		}
    		$pages_to_show = intval($pagenavi_options['num_pages']);
    		$larger_page_to_show = intval($pagenavi_options['num_larger_page_numbers']);
    		$larger_page_multiple = intval($pagenavi_options['larger_page_numbers_multiple']);
    		$pages_to_show_minus_1 = $pages_to_show - 1;
    		$half_page_start = floor($pages_to_show_minus_1/2);
    		$half_page_end = ceil($pages_to_show_minus_1/2);
    		$start_page = $paged - $half_page_start;
    		if($start_page <= 0) {
    			$start_page = 1;
    		}
    		$end_page = $paged + $half_page_end;
    		if(($end_page - $start_page) != $pages_to_show_minus_1) {
    			$end_page = $start_page + $pages_to_show_minus_1;
    		}
    		if($end_page > $max_page) {
    			$start_page = $max_page - $pages_to_show_minus_1;
    			$end_page = $max_page;
    		}
    		if($start_page <= 0) {
    			$start_page = 1;
    		}
    		$larger_per_page = $larger_page_to_show*$larger_page_multiple;
    		$larger_start_page_start = ($this->n_round($start_page, 10) + $larger_page_multiple) - $larger_per_page;
    		$larger_start_page_end = $this->n_round($start_page, 10) + $larger_page_multiple;
    		$larger_end_page_start = $this->n_round($end_page, 10) + $larger_page_multiple;
    		$larger_end_page_end = $this->n_round($end_page, 10) + ($larger_per_page);
    		if($larger_start_page_end - $larger_page_multiple == $start_page) {
    			$larger_start_page_start = $larger_start_page_start - $larger_page_multiple;
    			$larger_start_page_end = $larger_start_page_end - $larger_page_multiple;
    		}
    		if($larger_start_page_start <= 0) {
    			$larger_start_page_start = $larger_page_multiple;
    		}
    		if($larger_start_page_end > $max_page) {
    			$larger_start_page_end = $max_page;
    		}
    		if($larger_end_page_end > $max_page) {
    			$larger_end_page_end = $max_page;
    		}
    		if($max_page > 1 || intval($pagenavi_options['always_show']) == 1) {
    		    echo '<ul class="' . $pagenavi_options['class'] . '">';
    		    
    			$pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($paged), $pagenavi_options['pages_text']);
    			$pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
    			switch(intval($pagenavi_options['style'])) {
    				case 1:

    					if ($start_page >= 2 && $pages_to_show < $max_page) {
    						$first_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['first_text']);						
    						/*if(!empty($pagenavi_options['dotleft_text'])) {
    							echo '<span class="extend">'.$pagenavi_options['dotleft_text'].'</span>';
    						}*/
    					}
    					if($larger_page_to_show > 0 && $larger_start_page_start > 0 && $larger_start_page_end <= $max_page) {
    						for($i = $larger_start_page_start; $i < $larger_start_page_end; $i+=$larger_page_multiple) {
    							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
    							echo ' <li><a href="'.esc_url(get_pagenum_link($i)).'" class="page" title="'.$page_text.'">'.$page_text.'</a></li>';
    						}
    					}
    					ob_start();
                        previous_posts_link($pagenavi_options['prev_text']);
                        $prevLink = ob_get_clean();
                        if ($prevLink) {
        					echo '<li>' . $prevLink . '</li>';
                        }
    					for($i = $start_page; $i  <= $end_page; $i++) {						
    						if($i == $paged) {
    							$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
    							echo ' <li class="current"><a>'.$current_page_text.'</a></li>';
    						} else {
    							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
    							echo ' <li><a href="'.esc_url(get_pagenum_link($i)).'" class="page" title="'.$page_text.'">'.$page_text.'</a></li>';
    						}
    					}
    					ob_start();
                        next_posts_link($pagenavi_options['next_text'], $max_page);
                        $nextLink = ob_get_clean();
                        if ($nextLink) {
        					echo '<li>' . $nextLink . '</li>';
                        }
    					/*if($larger_page_to_show > 0 && $larger_end_page_start < $max_page) {
    						for($i = $larger_end_page_start; $i <= $larger_end_page_end; $i+=$larger_page_multiple) {
    							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
    							echo '<a href="'.esc_url(get_pagenum_link($i)).'" class="page" title="'.$page_text.'">'.$page_text.'</a>';
    						}
    					}*/
    					/*if ($end_page < $max_page) {
    						if(!empty($pagenavi_options['dotright_text'])) {
    							echo '<span class="extend">'.$pagenavi_options['dotright_text'].'</span>';
    						}
    						$last_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['last_text']);
    						echo '<a href="'.esc_url(get_pagenum_link($max_page)).'" class="last" title="'.$last_page_text.'">'.$last_page_text.'</a>';
    					}*/
    					break;
    				case 2;

    					break;
    			}
    			
    			echo '</ul>';
    		}
    	}
    }
    
    ### Function: Round To The Nearest Value
    private function n_round($num, $tonearest) {
       return floor($num/$tonearest)*$tonearest;
    }
    
    public function wpLinkPages() {
        return wp_link_pages(array(
            'before' => '<div class="wp-page-links">',
            'after' => '</div>',
            'next_or_number' => 'number',
            'link_before' => '<span>',
            'link_after' => '</span>',
            'echo' => 0
        ));
    }
}
?>