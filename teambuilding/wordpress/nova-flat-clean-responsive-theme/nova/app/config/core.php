<?php

/**
 * Define build for the theme.
 * 
 * Possible values are 'release' | 'production' | 'development'
 */
Configure::write('build', 'release');

Configure::write('dynamicStylesOptionsMap', array(
    'color_option_1' => array(
        'color' => array(
            'body a:hover',
            '.nav-style-two ul.prime-nav > li > a:hover',
            'ul.prime-nav li > ul.bluebox-dropdown li a:hover',
            'ul.prime-nav li > ul.bluebox-dropdown li.current-menu-item > a',
            '.bluebox-header.dark-dropdown ul.prime-nav li > ul.bluebox-dropdown li a:hover',
            '.bluebox-header.dark-dropdown ul.prime-nav li > ul.bluebox-dropdown li.current-menu-item > a',
            '.roki-rev-slide-vl-opt2-opt1',
            '.roki-rev-slide-vl-opt4-opt1',
            '.roki-rev-slide-vl-no-opt1',
            '.roki-rev-slide-l-opt2-opt1',
            '.roki-rev-slide-l-opt4-opt1',
            '.roki-rev-slide-l-no-opt1',
            '.roki-rev-slide-n-opt2-opt1',
            '.roki-rev-slide-n-opt4-opt1',
            '.roki-rev-slide-n-no-opt1',
            '.bluebox-accordion .accordion-heading a.accordion-button:hover',
            '.bluebox-accordion .accordion-heading:hover',
            '.nav-tabs > .active > a',
            '.nav-tabs > .active > a:hover',
            '.nav-tabs > .active > a > span',
            '.nav-tabs > .active > a:hover > span',
            '.nav-tabs > li > a:hover > span',
            'ol.comment-list li.comment div.comment-author cite a:hover',
            'ol.comment-list li.comment div.comment-meta a:hover',
            '.sidebar ul.menu li > a:hover',
            '.sidebar ul.menu li.current-menu-item > a',
            array(
                'selectors' => array(
                    '.sidebar ul.menu li.current-menu-item > a',
                    '.hover-light:hover',
                    '.hover-light:hover span',
                    '.bluebox-new-tabs-element > .nav-left ul li.active > a',
                ),
                'params' => array(
                    'important' => true,
                ),
            ),
            '.sidebar ul.menu .sub-menu li a:hover',
            '.widget-wrap ul.bluebox-widget-menu a:hover span',
            '.bluebox-footer-content .widget-wrap ul.bluebox-widget-menu a:hover span',
            '.bluebox-custom-social-link',
            '.bluebox-custom-social-link:hover',
            '.bluebox-events-list:hover .event-details .page-heading-wrap ul.event-rate-list li',
            '.bluebox-new-tabs-element > .nav-left ul li > a:hover',
            '.bluebox-new-tabs-element > .nav-left ul .sub-menu li a:hover',
        ),
        'background' => array(
            '::selection',
            '::-moz-selection',
            
        ),
        'background-color' => array(
            '.bluebox-button',
            '.roki-button-link',
            '.bluebox-button:hover',
            '.roki-button-link:hover',
            '.bluebox-info-bar',
            '.bluebox-prime-nav #prime-nav-searchform',
            '.nova-slider-bottom-nav .bluebox-slider-content ul li.current a',
            '.nova-slider-bottom-nav .bluebox-slider-content ul li.current a:hover',
            '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li.current a',
            '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li.current a:hover',
            '.roki-rev-slide-vl-opt1-opt2',
            '.roki-rev-slide-vl-opt1-opt4',
            '.roki-rev-slide-l-opt1-opt2',
            '.roki-rev-slide-l-opt1-opt4',
            '.roki-rev-slide-n-opt1-opt2',
            '.roki-rev-slide-n-opt1-opt4',
            '.roki-rev-slide-extra-text-bullets:after',
            '.bluebox-heading-wrap .prev-next-heading-links a:hover',
            'ul.bluebox-pagination li.current a',
            '.tagline a:hover',
            '.load-more-link:hover',
            '.tagline a.current',
            '.tagline li.current a',
            '.bluebox-accordion .accordion-heading.ui-state-active',
            '.progress .bar',
            '.bluebox-fancy-list li span',
            '.bluebox-partners ul.partners-slide li a:hover:before',
            '.bluebox-contact input[type="submit"]',
            '.bluebox-staff .content-details:before',
            '.bluebox-icon-container:hover',
            '.text-box-style-two .head-link:hover span',
            '.bluebox-fancy-text.fancy-colorful',
            '.bluebox-fancy-text.full-width.fancy-colorful .element-background',
            '.bluebox-quotes.quote-colorful',
            '.bluebox-quotes.quote-colorful.full-width',
            '.span12 .bluebox-quotes.full-width.quote-colorful .element-background',
            '.bluebox-quotes.quote-fancy.quote-colorful',
            '.blog-loop-standard .line-details .line-date',
            '.sidebar ul.menu li.current-menu-item > a:before',
            '.tagcloud a:hover',
            '.bluebox-footer-content .tagcloud a:hover ',
            '.bluebox-footer-content a.social-link:hover',
            '.bluebox-authors-wrap .bluebox-authors-content a.back-to-top:hover',
            '.prime-nav-searchform-button-active',
            '.bluebox-single-staff-wrap  .top-staff-wrap .single-staff-left-wrap:before',
            '.bluebox-single-staff-wrap .bottom-staff-wrap .single-staff-left-wrap .single-social-link:hover span',
            '.bluebox-single-staff-wrap.new-builder-element .single-staff-right-wrap .page-heading-wrap .staff-more-link:hover',
            '.cal-page-month-wrap a.nav-arrow:hover',
            '.bluebox-events-list:hover .event-date-line',
            '.bluebox-events-list:hover .event-date-line:before',
            '.bluebox-events-list .event-details .event-inner-content-wrap .event-more-link:hover',
            '.bluebox-new-tabs-element > .nav-left ul li.active > a > span',
            '.bluebox-new-tabs-element > .nav-left ul li.active > a:before',
            '.bluebox-new-tabs-element.large > .nav-left ul li.active > a > span',
            '.popover-title',
            'div.note-design-wrap td.event a:hover',
            'div.note-design-wrap td.active a',
            'div.note-design-wrap td.event.active a',
            'div.note-design-wrap td.active a:hover',
            'div.note-design-wrap td.event.active a:hover',
            '.sidebar div.note-design-wrap .month-heading a.arrow:hover',
            'ul.terms-alphabet li.selected a',
            
            array(
                'selectors' => array(
                    '.hover-colorful:hover',
                    '.quotes-arrows li a:hover',
                    '.bluebox-button.light:hover',
                    '.tparrows.default:hover', // @TOCHECK
                    '.bluebox-heading-arrows a.bluebox-shadows:hover',
                    'ul.bluebox-pagination li a:hover',
                ),
                'params' => array(
                    'important' => true,
                ),
            ),
            'a.mobile-nav-button span',
            '.image-wrap:hover .image-details-link.image-wrap-mask' => array(
                'alpha' => .8
            ),
            '.bluebox-single-staff-wrap:hover .image-details-link.image-wrap-mask' => array(
                'alpha' => .8
            ),

            
        ),
        'box-shadow' => array(
            array(
                'selectors' => array(
                    '.nav-style-one ul.prime-nav > li.page_item > a:hover',
                    '.nav-style-one ul.prime-nav > li.menu-item > a:hover',
                    '.nav-style-two ul.prime-nav > li.page_item > a:hover',
                    '.nav-style-two ul.prime-nav > li.menu-item > a:hover',                    
                    '.nav-style-three ul.prime-nav > li.page_item > a:hover',
                    '.nav-style-three ul.prime-nav > li.menu-item > a:hover',
                    '.nav-style-four ul.prime-nav > li.page_item > a:hover',
                    '.nav-style-four ul.prime-nav > li.menu-item > a:hover',
                ),
                'params' => array(
                    'declaration' => 'inset 0px -4px 0px 0px %s'
                ),
            ),
            // '.nav-style-four ul.prime-nav > li > a:hover' => array(
            //     'declaration' => 'inset 0px -4px 0px 0px %s'
            // ),
            '.nova-slider-bottom-nav .bluebox-slider-content ul li a:hover' => array(
                'declaration' => 'inset 0px 0px 0px 1px rgba(0, 0, 0, 0.06), inset 0px -4px 0px 0px %s'
            ),
            // '.nav-style-one ul.prime-nav > li > a:hover' => array(
            //     'declaration' => 'inset 0px -4px 0px 0px %s'
            // ),
            
        ),
        'border-top' => array(
            array(
                'selectors' => array(
                    'ul.prime-nav li > ul.bluebox-dropdown',
                    '.bluebox-quotes.quote-fancy p',
                ),
                'params' => array(
                    'declaration' => '4px solid %s'
                ),
            ),
        ),
        'border-bottom' => array(
            array(
                'selectors' => array(
                    'ul.bluebox-pricing-table li > div ul li.price-row div:after',
                    '.bluebox-partners ul.partners-slide li a:hover:after',
                    '.nova-slider-bottom-nav .bluebox-slider-content ul li a:hover:after',
                    '.bluebox-staff .content-details:after',
                    '.bluebox-quotes.quote-fancy p:after',
                    
                ),
                'params' => array(
                    'declaration' => '6px solid %s',
                ),
            ),
            'ul.bluebox-pricing-table li > div ul li.price-row div' => array(
                'declaration' => '4px solid %s',
            ),
        ),
        'border-right' => array(
            array(
                'selectors' => array(
                    '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li a:hover span',
                    '.sidebar.right-sidebar ul.menu li.current-menu-item:before',
                ),
                'params' => array(
                    'declaration' => '4px solid %s',
                ),
            ),
            '.nova-slider-four .bluebox-slider-content .heading-container a.slide-link:hover:after' => array(
                'declaration' => '6px solid %s',
            ),
        ),
        'border-left' => array(
            array(
                'selectors' => array(
                    '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li a:hover:after',
                    '.nova-slider-four .bluebox-slider-content .slide-details.extra-info .details-wrap:after',
                    '.bluebox-single-staff-wrap  .top-staff-wrap .single-staff-left-wrap:after',
                    '.bluebox-events-list:hover .event-date-line:after',
                ),
                'params' => array(
                    'declaration' => '6px solid %s'
                ),
            ),
            
            array(
                'selectors' => array(
                    '.nova-slider-four .bluebox-slider-content .slide-details.extra-info .details-wrap',
                    '.nova-slider-four .bluebox-slider-content .heading-container a.slide-link:hover',
                    '.sidebar ul.menu li.current-menu-item:before',
                    '.bluebox-new-tabs-element > .nav-left ul li.active:before',
                ),
                'params' => array(
                    'declaration' => '4px solid %s',
                ),
            ),
        ),
    ),
    'color_option_2' => array(
        'color' => array(
            array(
                'selectors' => array(
                    '.bluebox-button',
                    '.roki-button-link',
                    '.tp-caption a',
                    '.hover-dark:hover',
                    '.bluebox-slider-content .slide-details .details-wrap a.bluebox-button:hover',
                    '.bluebox-fancy-text .bluebox-button:hover',
                    '.quote-colorful .quotes-arrows li a:hover',
                    '.text-box-style-three .bluebox-button:hover',
                    '.bluebox-pricing-table .bluebox-button:hover',
                    '.roki-button-link:hover',
                    '.hover-dark:hover span',
                    '.bluebox-slider-content .slide-details .details-wrap a.bluebox-button:hover span',
                    '.bluebox-fancy-text .bluebox-button:hover span',
                    '.quote-colorful .quotes-arrows li a:hover span',
                    '.text-box-style-three .bluebox-button:hover span',
                    '.bluebox-pricing-table .bluebox-button:hover span',
                    '.roki-button-link:hover span',
                    '.hover-colorful:hover',
                    '.quotes-arrows li a:hover',
                    '.bluebox-button.light:hover',
                    '.hover-colorful:hover span',
                    '.quotes-arrows li a:hover span',
                    '.bluebox-button.light:hover span',
                    '.tparrows.default:hover',
                    '.bluebox-heading-arrows a.bluebox-shadows:hover',
                    'ul.bluebox-pagination li a:hover',
                    '.portfolio-loop .project-half > .half-content .bluebox-button:hover',
                    '.portfolio-loop .project-half > .half-content .bluebox-button:hover span',
                ),
                'params' => array(
                    'important' => true,
                ),
            ),
            '.prime-nav-searchform-button-active',
            '.bluebox-button:hover',
            '.roki-button-link:hover',
            '.bluebox-button.extra span',
            '.roki-button-link span',
            '.image-wrap a.icon-search',
            array(
                'selectors' => array(
                    '.nova-slider-four .bluebox-slider-content .heading-container a.slide-link',
                    '.bluebox-authors-wrap .bluebox-authors-content a.back-to-top',
                    '.bluebox-footer-content a.social-link',
                    
                ),
                'params' => array(
                    'alpha' => .26,
                ),
            ),
            '.bluebox-info-bar',
            '.bluebox-prime-nav #prime-nav-searchform',
            array(
                'selectors' => array(
                    '.bluebox-prime-nav #prime-nav-searchform i',
                    '.bluebox-slider-content .slide-details .details-wrap .details-content p',
                    '.nova-slider-four .bluebox-slider-content .slide-details.extra-info .details-wrap',
                    '.bluebox-accordion .accordion-heading.ui-state-active a.accordion-button',
                    '.bluebox-fancy-text.fancy-colorful .fancy-content p',
                    '.bluebox-quotes.quote-colorful p',
                    '.bluebox-footer-content',
                    '.bluebox-copyrights-wrap',
                    '.bluebox-footer-content a',
                    '.bluebox-copyrights-wrap a',
                    '.bluebox-footer-content .bluebox-contact input[type="text"]',
                    '.bluebox-footer-content .bluebox-contact textarea',
                    '.bluebox-footer-content .widget-wrap form.search-form input.text-input',
                    
                    
                ),
                'params' => array(
                    'alpha' => .5,
                ),
            ),
            array(
                'selectors' => array(
                    '#mobile-menu .prime-nav-mobile-list li > i'
                ),
                'params' => array(
                    'alpha' => .3
                ),
            ),
            array(
                'selectors' => array(
                    '#mobile-menu .prime-nav-mobile-list li a',
                ),
                'params' => array(
                    'alpha' => .9
                ),
            ),
            array(
                'selectors' => array(
                    '#mobile-menu .prime-nav-mobile-list li a.dropdown-link',
                    '#mobile-menu .prime-nav-mobile-list li a.dropdown-link:hover',
                ),
                'params' => array(
                    'alpha' => .6,
                ),
            ),
            '.bluebox-prime-nav #prime-nav-searchform form input[type="text"].bluebox-search-input',
            '.nav-style-two ul.prime-nav > li > a',
            '.bluebox-header.dark-dropdown ul.prime-nav li > ul.bluebox-dropdown li a',
            '.bluebox-slider-content .slide-details .details-wrap .details-content',
            '.nova-slider-bottom-nav .bluebox-slider-content ul li a',
            '.nova-slider-bottom-nav .bluebox-slider-content ul li a span ',
            '.nova-slider-bottom-nav .bluebox-slider-content ul li a:hover',
            '.nova-slider-bottom-nav .bluebox-slider-content ul li.current a',
            '.nova-slider-bottom-nav .bluebox-slider-content ul li.current a:hover',
            '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li a',
            '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li a span',
            '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li a:hover',
            '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li.current a',
            '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li.current a:hover',
            '.nova-slider-four .bluebox-slider-content .heading-container',
            '.nova-slider-four .bluebox-slider-content .heading-container a.slide-link:hover',
            '.roki-rev-slide-vl-opt1-opt2',
            '.roki-rev-slide-vl-opt4-opt2',
            '.roki-rev-slide-vl-no-opt2',
            '.roki-rev-slide-l-opt1-opt2',
            '.roki-rev-slide-l-opt4-opt2',
            '.roki-rev-slide-l-no-opt2',
            '.roki-rev-slide-n-opt1-opt2',
            '.roki-rev-slide-n-opt4-opt2',
            '.roki-rev-slide-n-no-opt2',
            '.roki-rev-slide-extra-text-bullets:after',
            '.bluebox-heading-wrap .prev-next-heading-links a:hover',
            'ul.bluebox-pagination li.current a',
            '.tagline a:hover',
            '.load-more-link:hover',
            '.tagline a.current',
            '.tagline li.current a',
            '.bluebox-accordion .accordion-heading.ui-state-active',
            '.bluebox-accordion .accordion-heading.ui-state-active a.accordion-button:hover',
            '.progress .bar > p',
            '.bluebox-fancy-list li span',
            '.prev-next-links a',
            '.prev-next-links a:hover',
            '.tooltip-inner',
            '.bluebox-contact input[type="submit"]',
            '.bluebox-contact input[type="submit"]:hover',
            '.bluebox-icon-container',
            '.bluebox-icon-container span',
            '.text-box-style-two .head-link:hover span',
            '.bluebox-fancy-text.fancy-colorful h4',
            '.bluebox-quotes.quote-colorful em',
            '.bluebox-quotes.quote-colorful .quotes-arrows li a',
            '.bluebox-quotes.quote-fancy.quote-colorful p strong',
            '.bluebox-twitter-element',
            '.blog-loop-standard .line-details div',
            '.portfolio-loop .line-details div',
            '.bluebox-footer-content .widget-wrap ul.bluebox-widget-menu a span' => array(
                'alpha' => .16,
            ),
            '.tagcloud a:hover',
            '.bluebox-footer-content h3.bluebox-heading',
            '.bluebox-footer-content .widget-wrap .heading-wrap h3',
            '.bluebox-footer-content .tagcloud a:hover',
            '.bluebox-footer-content a.social-link:hover',
            '.bluebox-authors-wrap .bluebox-authors-content a.back-to-top:hover',
            '.bluebox-custom-social-link',
            '.bluebox-single-staff-wrap .bottom-staff-wrap .single-staff-left-wrap .single-social-link:hover span',
            '.bluebox-single-staff-wrap.new-builder-element:hover .single-staff-right-wrap .page-heading-wrap .staff-more-link',
            '.bluebox-single-staff-wrap.new-builder-element .single-staff-right-wrap .page-heading-wrap .staff-more-link:hover',
            '.cal-page-month-wrap a.nav-arrow:hover',
            '.bluebox-events-list:hover .event-date-line',
            '.bluebox-events-list:hover .event-date-line .date-details-wrap strong',
            '.top-staff-wrap .image-wrap ul.social-links li a',
            '.bluebox-events-list:hover .event-details .event-inner-content-wrap .event-more-link',
            '.bluebox-events-list .event-details .event-inner-content-wrap .event-more-link:hover',
            '.bluebox-new-blog-element .blog-new-heading-wrap a.head-link',
            '.bluebox-new-blog-element .blog-new-heading-wrap > .inner-wrap .new-blog-date',
            '.bluebox-new-blog-element .blog-new-post-format-icon',
            '.bluebox-new-blog-element .image-wrap a > i',
            '.bluebox-new-tabs-element > .nav-left ul li.active > a > span',
            '.bluebox-new-tabs-element.large > .nav-left ul li.active > a > span ',
            '.popover-title',
            'div.note-design-wrap td.event a:hover',
            'div.note-design-wrap td.active a',
            'div.note-design-wrap td.event.active a',
            'div.note-design-wrap td.active a:hover',
            'div.note-design-wrap td.event.active a:hover',
            '.sidebar div.note-design-wrap .month-heading a.arrow:hover',
            'ul.terms-alphabet li.selected a',
            '.bluebox-new-tabs-element > .nav-left ul li.active > a:hover > span'
        ),
        'background-color' => array(
            '.roki-rev-slide-vl-opt2-opt1',
            '.roki-rev-slide-vl-opt2-opt4',
            '.roki-rev-slide-l-opt2-opt1',
            '.roki-rev-slide-l-opt2-opt4',
            '.roki-rev-slide-n-opt2-opt1',
            '.roki-rev-slide-n-opt2-opt4',
            array(
                'selectors' => array(
                    '.bluebox-icon-container',
                ),
                'params' => array(
                    'alpha' => .74,
                ),
            ),
            array(
                'selectors' => array(
                    '.hover-light:hover',
                ),
                'params' => array(
                    'important' => true,
                ),
            ),

            
            
        ),
        'border-top' => array(
            '.bluebox-quotes.quote-fancy.quote-colorful p' => array(
                'declaration' => '4px solid %s',
            ),
        ),
        'border-bottom' => array(
            array(
                'selectors' => array(
                    '.bluebox-slider-wrap:before',
                ),
                'params' => array(
                    'declaration' => '8px solid %s',
                    'alpha' => .06,
                ),
            ),
            array(
                'selectors' => array(
                    '.bluebox-quotes.quote-fancy.quote-colorful p:after',
                ),
                'params' => array(
                    'declaration' => '6px solid %s'
                ),
            ),
        ),
        'border-right' => array(
            array(
                'selectors' => array(
                    '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li.current a span',
                    '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li.current a:hover span',
                ),
                'params' => array(
                    'declaration' => '4px solid %s',
                ),
            ),
            array(
                'selectors' => array(
                    '.nova-slider-four .bluebox-slider-content .heading-container a.slide-link:after',
                ),
                'params' => array(
                    'declaration' => '6px solid %s',
                    'alpha' => .20,
                ),
            ),
            array(
                'selectors' => array(
                    '.blog-3-cols .image-wrap:before',
                    
                ),
                'params' => array(
                    'declaration' => '8px solid %s',
                    'alpha' => .06,
                ),
            ),
        ),
        'border-left' => array(
            array(
                'selectors' => array(
                    '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li.current a:after',
                    '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li.current a:hover:after',
                ),
                'params' => array(
                    'declaration' => '6px solid %s',
                ),
            ),
            array(
                'selectors' => array(
                    '.nova-slider-four .bluebox-slider-content .heading-container a.slide-link',
                ),
                'params' => array(
                    'declaration' => '4px solid',
                    'alpha' => .26,
                ),
            ),
        ),
        
    ),
    'color_option_3' => array(
        'color' => array(
            'body a',
            array(
                'selectors' => array(
                    '.bluebox-button.light',
                ),
                'params' => array(
                    'important' => true,
                ),
            ),
            array(
                'selectors' => array(
                    '.nav-style-three ul.prime-nav > li:after',
                    '.table-striped.a-to-z-terms tbody tr td.first-letter',
                    '.widget-wrap ul.bluebox-widget-menu a span',
                ),
                'params' => array(
                    'alpha' => .16
                ),
            ),
            array(
                'selectors' => array(
                    '.bluebox-heading-arrows a.bluebox-shadows',
                    '.bluebox-heading-wrap .prev-next-heading-links a',
                    '.bluebox-accordion .accordion-heading a.accordion-button',
                    '.nav-tabs > li > a > span',
                    '.text-box-style-two .head-link span',
                    '.quotes-arrows li a',
                    '.bluebox-button.light.extra span',
                    '.bluebox-single-staff-wrap .bottom-staff-wrap .single-staff-left-wrap .single-social-link span',
                    '.cal-page-month-wrap a.nav-arrow',
                    '.bluebox-events-list .event-details .page-heading-wrap ul.event-rate-list li',
                    '.bluebox-new-tabs-element > .nav-left ul li > a > span',
                    '.sidebar div.note-design-wrap .month-heading a.arrow',
                    
                ),
                'params' => array(
                    'alpha' => .26
                ),
            ),
            
            array(
                'selectors' => array(
                    '.bluebox-info-bar.light',
                    '.page-heading-wrap h2 span',
                    '.page-heading-wrap .bluebox-heading-details .bluebox-heading-search > i',
                    'ul.bluebox-pagination li a',
                    '.portfolio-cols .project-post-details p',
                    '.tagline a',
                    '.load-more-link',
                    '.bluebox-accordion .accordion-content',
                    'ul.bluebox-pricing-table',
                    '.bluebox-staff .content-details span.position',
                    '.bluebox-textboxes p',
                    '.bluebox-fancy-text .fancy-content p',
                    '.bluebox-quotes p',
                    '.line-meta-details',
                    '.blog-loop-standard p',
                    '.portfolio-loop em.tags a',
                    '.portfolio-loop p',
                    'ol.comment-list li.comment div.comment-meta a',
                    '.sidebar ul.menu .sub-menu li a',
                    '.tagcloud a',
                    '.bluebox-events-list .event-date-line',
                    '.bluebox-events-list.single-post .event-date-line',
                    '.bluebox-events-list.single-post:hover .event-date-line',
                    '.bluebox-new-tabs-element > .nav-left ul .sub-menu li a',
                    'div.note-design-wrap td a',
                    'div.note-design-wrap td.off a',
                    'ul.terms-alphabet li.no-terms a',
                    '.table-striped.a-to-z-terms tbody tr td.term-meaning p',
                    
                ),
                'params' => array(
                    'alpha' => .5
                ),
            ),
            '.tparrows.default' => array(
                'alpha' => .26,
                'important' => true
            ),
            '.roki-rev-slide-vl-opt1-opt4',
            '.roki-rev-slide-vl-opt2-opt4',
            '.roki-rev-slide-vl-no-opt4',
            '.roki-rev-slide-l-opt1-opt4',
            '.roki-rev-slide-l-opt2-opt4',
            '.roki-rev-slide-l-no-opt4',
            '.roki-rev-slide-n-opt1-opt4',
            '.roki-rev-slide-n-opt2-opt4',
            '.roki-rev-slide-n-no-opt4',
            'h3.bluebox-heading',
            '.bluebox-contact label',
            'ul.bluebox-pricing-table strong',
            '.bluebox-quotes.quote-fancy p strong',
            'ol.comment-list li.comment div.comment-author cite a',
            '.bluebox-events-list .event-date-line:before',
            '.bluebox-events-list .event-date-line .date-details-wrap strong',
            '.top-staff-wrap .image-wrap ul.social-links li a:hover',
            '.bluebox-events-list.single-post:hover .event-date-line .date-details-wrap strong',
            '.bluebox-new-tabs-element > .nav-left ul li > a:hover > span',
            
            
        ),
        'background-color' => array(
            array(
                'selectors' => array(
                    '.nav-style-two .bluebox-head-bottom',
                    '.bluebox-header.type-two.nav-style-two ul.prime-nav', // @TOCHECK
                    '.bluebox-header.dark-dropdown ul.prime-nav li > ul.bluebox-dropdown', // @TOCHECK
                    '.bluebox-slider-content .slide-details',
                    '.nova-slider-bottom-nav .bluebox-slider-content ul li a',
                    '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li a',
                    '.roki-rev-slide-vl-opt4-opt1',
                    '.roki-rev-slide-vl-opt4-opt2',
                    '.roki-rev-slide-l-opt4-opt1',
                    '.roki-rev-slide-l-opt4-opt2',
                    '.roki-rev-slide-n-opt4-opt1',
                    '.roki-rev-slide-n-opt4-opt2',
                    '.tooltip-inner',
                    '.bluebox-contact input[type="submit"]:hover',
                    '.bluebox-icon-container',
                    '.blog-loop-standard .line-details .line-post-format', // @TOCHECK
                    '.bluebox-footer-content',
                    
                    
                ),
                'params' => array(
                    'alpha' => .74,
                ),
            ),
            array(
                'selectors' => array(
                    '.tp-bullets.simplebullets .bullet:hover',
                    '.tp-bullets.simplebullets .bullet.selected',
                    '.hover-dark:hover',
                    '.bluebox-slider-content .slide-details .details-wrap a.bluebox-button:hover',
                    '.bluebox-fancy-text .bluebox-button:hover',
                    '.quote-colorful .quotes-arrows li a:hover',
                    '.text-box-style-three .bluebox-button:hover',
                    '.bluebox-pricing-table .bluebox-button:hover',
                    '.roki-button-link:hover',
                    '.portfolio-loop .project-half > .half-content .bluebox-button:hover',
                ),
                'params' => array(
                    'alpha' => .74,
                    'important' => true,
                ),
            ),
            array(
                'selectors' => array(
                    '.nova-slider-bottom-nav .bluebox-slider-content ul li a span',
                    '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li a span',
                    
                ),
                'params' => array(
                    'alpha' => .16
                ),
            ),
            array(
                'selectors' => array(
                    '.nova-slider-bottom-nav .bluebox-slider-content ul li a:hover',
                    '.nova-right-tabs-slider .bluebox-slider-content .slide-details ul li a:hover',
                    
                ),
                'params' => array(
                    'alpha' => .86
                ),
            ),
            array(
                'selectors' => array(
                    '.bluebox-copyrights-wrap',
                ),
                'params' => array(
                    'alpha' => .80,
                ),
            ),
            '.tp-bullets.simplebullets .bullet' => array(
                'alpha' => .26,
                'important' => true,
            ),
            '.tparrows.default' => array(
                'alpha' => .06,
                'important' => true,
            ),
            
        ),
        'box-shadow' => array(
            array(
                'selectors' => array(
                    '.tparrows.default',
                    '.tparrows.default:hover',
                ),
                'params' => array(
                    'declaration' => 'inset 0px 0px 0px 1px %s',
                    'alpha' => .06,
                    'important' => true,
                ),
            ),
        ),
        'border-bottom' => array(
            array(
                'selectors' => array(
                    '.bluebox-twitter-element:after',
                ),
                'params' => array(
                    'declaration' => '6px solid %s',
                ),
            ),
            '.bluebox-footer-content:after' => array(
                'declaration' => '6px solid %s',
                'alpha' => .74,
            ),
        ),
        'border-top-color' => array(
            '.tooltip.top .tooltip-arrow' => array(
                'alpha' => .74,
            ),
        ),
        'border-right-color' => array(
            '.tooltip.right .tooltip-arrow' => array(
                'alpha' => .74,
            ),
        ),
        'border-bottom-color' => array(
            '.tooltip.bottom .tooltip-arrow' => array(
                'alpha' => .74,
            ),
        ),
        'border-left-color' => array(
            '.tooltip.left .tooltip-arrow' => array(
                'alpha' => .74,
            ),
        ),
        'border-left' => array(
            array(
                'selectors' => array(
                    '.bluebox-events-list .event-date-line:after'
                ),
                'params' => array(
                    'declaration' => '6px solid %s',
                ),
            ),
        ),
        
    ),
    'color_option_4' => array(
        
    ),
    
));

Configure::write('styleGroups', array(
    'heading_fonts' => array('h1', 'h2', 'h3', 'h4', 'h5'),
    'content_wrap' => array('.bluebox-wrap'),
    'page-heading-wrap' => array('.fancy-text-bar.page-heading-wrap'),
));

Configure::write('themeDefaultColors', array(
    'color_option_1' => 'ed7721',
    'color_option_2' => 'ffffff',
    'color_option_3' => '000000',
    // 'color_option_4' => '000000',
));

Configure::write('themeSupport', array(
    'skins' => true,
));

Configure::write('customPostTypes', array(
    'portfolio' => array(
        'args' => array(
            'labels' => array(
                'name' => _x('Portfolio', 'portfolio admin labels', 'gummfw'),
                'singular_name' => _x('Portfolio', 'portfolio admin labels', 'gummfw'),
                'all_items' => _x('All Portfolio Items', 'portfolio admin labels', 'gummfw'),
                'edit_item' => _x('Edit Portfolio', 'portfolio admin labels', 'gummfw'),
                'new_item' => _x('New Portfolio', 'portfolio admin labels', 'gummfw'),
                'view_item' => _x('View Portfolio', 'portfolio admin labels', 'gummfw'),
                'search_items' => _x('Search Portfolio Items', 'portfolio admin labels', 'gummfw'),
            ),
            'public' => true,
            'show_ui' => true, 
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => array('with_front' => false),
            'supports' => array('title', 'editor', 'author', 'excerpt', 'revisions', 'thumbnail', 'page-attributes', 'comments'),
            'taxonomies' => array('post_tag', 'portfolio_category'),
            'menu_position' => 5,
        ),
        'columns' => array(
            "cb" => "<input type=\"checkbox\" />",
            'thumbnail' => '',
            "title" => _x("Title", "portfolio title column", 'gummfw'),
            "author" => _x("Author", "portfolio author column", 'gummfw'),
            "category" => _x("Category", "portfolio types column", 'gummfw'),
            "date" => _x("Date", "portfolio date column", 'gummfw')
        ),
    ),
    'staff' => array(
        'args' => array(
            'labels' => array(
                'name' => _x('Staff', 'staff admin labels', 'gummfw'),
                'singular_name' => _x('Staff Member', 'staff admin labels', 'gummfw'),
                'all_items' => _x('All Staff Members', 'staff admin labels', 'gummfw'),
                'edit_item' => _x('Edit Staff Member', 'staff admin labels', 'gummfw'),
                'new_item' => _x('New Staff Member', 'staff admin labels', 'gummfw'),
                'view_item' => _x('View Staff Member', 'staff admin labels', 'gummfw'),
                'search_items' => _x('Search Staff Members', 'staff admin labels', 'gummfw'),
            ),
            
            'public' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'show_ui' => true, 
            'show_in_nav_menus' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => array('with_front' => false),
            'supports' => array('title', 'editor', 'author', 'excerpt', 'revisions', 'thumbnail', 'page-attributes', 'comments'),
            'taxonomies' => array('staff_category'),
            'menu_position' => 5,
        ),
        'columns' => array(
            "cb" => "<input type=\"checkbox\" />",
            'thumbnail' => '',
            "title" => _x("Title", "staff title column", 'gummfw'),
            "author" => _x("Author", "staff author column", 'gummfw'),
            "category" => _x("Category", "staff types column", 'gummfw'),
            "date" => _x("Date", "staff date column", 'gummfw')
        ),
    ),
    'testimonial' => array(
        'args' => array(
            'labels' => array(
                'name' => _x('Testimonials', 'testimonial', 'gummfw'),
                'singular_name' => _x('Testimonial', 'testimonial', 'gummfw'),
                'all_items' => _x('All Testimonials', 'testimonial', 'gummfw'),
                'edit_item' => _x('Edit Testimonial', 'testimonial', 'gummfw'),
                'new_item' => _x('New Testimonial', 'testimonial', 'gummfw'),
                'view_item' => _x('View Testimonial', 'testimonial', 'gummfw'),
                'search_items' => _x('Search Testimonials', 'testimonial', 'gummfw'),
            ),
            'label' => __('Testimonials', 'gummfw'),
            'singular_label' => __('Testimonial', 'gummfw'),
            'public' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'show_ui' => true, 
            'show_in_nav_menus' => false,
            'show_in_menu' => true,
            '_builtin' => false, 
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => false, 
            'supports' => array('excerpt'),
            'menu_position' => 5,
            // 'taxonomies' => array('post_tag'),
            // 'menu_icon' => get_bloginfo('template_directory') . '/functions/img/icon.png',
        ),
        'columns' => array( 
            'cb' => "<input type=\"checkbox\" />",
            'testimonial_excerpt' => _x('Testimonial', 'testimonial excerpt column', 'gummfw'),
            'testimonial_author' => _x('Author', 'testimonial author column', 'gummfw'),
            'date' => _x('Date', 'testimonial date column', 'gummfw'),
        ),
    ),
    'partner' => array(
        'args' => array(
            'labels' => array(
                'name' => _x('Partners', 'partner', 'gummfw'),
                'singular_name' => _x('Partners', 'partner', 'gummfw'),
                'all_items' => _x('All Partners', 'partner', 'gummfw'),
                'edit_item' => _x('Edit Partner', 'partner', 'gummfw'),
                'new_item' => _x('New Partner', 'partner', 'gummfw'),
                'view_item' => _x('View Partner', 'partner', 'gummfw'),
                'search_items' => _x('Search Partners', 'partner', 'gummfw'),
            ),
            'label' => __('Parnters', 'gummfw'),
            'singular_label' => __('Parnter', 'gummfw'),
            'public' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'show_ui' => true, 
            'show_in_nav_menus' => false,
            'show_in_menu' => true,
            '_builtin' => false, 
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => false, 
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
            'taxonomies' => array('partner_category'),
            'menu_position' => 5,
        ),
        'columns' => array( 
            "cb" => "<input type=\"checkbox\" />",
            'thumbnail' => '',
            "title" => _x("Title", "partner title column", 'gummfw'),
            "author" => _x("Author", "partner author column", 'gummfw'),
            "date" => _x("Date", "partner date column", 'gummfw'),
        ),
    ),
    'event' => array(
        'args' => array(
            'labels' => array(
                'name' => _x('Events', 'gallery admin labels', 'gummfw'),
                'singular_name' => _x('Event', 'gallery admin labels', 'gummfw'),
                'all_items' => _x('All Events', 'gallery admin labels', 'gummfw'),
                'edit_item' => _x('Edit Event', 'gallery admin labels', 'gummfw'),
                'new_item' => _x('New Event', 'gallery admin labels', 'gummfw'),
                'view_item' => _x('View Event', 'gallery admin labels', 'gummfw'),
                'search_items' => _x('Search Events', 'gallery admin labels', 'gummfw'),
            ),
            'public' => true,
            'show_ui' => true, 
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => array('with_front' => false),
            // 'rewrite' => false,
            'supports' => array('title', 'editor', 'author', 'excerpt', 'revisions', 'thumbnail', 'comments'),
            'taxonomies' => array('post_tag'),
            'menu_position' => 5,
            // 'menu_icon' => get_bloginfo('template_directory') . '/functions/img/icon.png',
        ),
    ),
    'term' => array(
        'args' => array(
            'labels' => array(
                'name' => _x('Terms', 'gallery admin labels', 'gummfw'),
                'singular_name' => _x('Term', 'gallery admin labels', 'gummfw'),
                'all_items' => _x('All Terms', 'gallery admin labels', 'gummfw'),
                'edit_item' => _x('Edit Term', 'gallery admin labels', 'gummfw'),
                'new_item' => _x('New Term', 'gallery admin labels', 'gummfw'),
                'view_item' => _x('View Term', 'gallery admin labels', 'gummfw'),
                'search_items' => _x('Search Term', 'gallery admin labels', 'gummfw'),
            ),
            'public' => false,
            'show_ui' => true, 
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => array('with_front' => false),
            // 'rewrite' => false,
            'supports' => array('title', 'editor', 'excerpt', 'revisions'),
            'taxonomies' => array('post_tag'),
            'menu_position' => 5,
            // 'menu_icon' => get_bloginfo('template_directory') . '/functions/img/icon.png',
        ),
    ),
));

Configure::write('customTaxonomies', array(

));

Configure::write('imageSizesMap', array(
    'noSidebars' => array(
        
    ),
    'oneSidebar' => array(
        
    ),
    'twoSidebars' => array(

    ),
));

Configure::write('excerptLengthMap', array(
    'noSidebars' => array(

    ),
    'oneSidebar' => array(

    ),
    'twoSidebars' => array(
        
    ),
));

Configure::write('layoutStructureMap', array(

));

Configure::write('optionIdStructureMap', array(
    'sidebars' => 'layout.%s.sidebars',
    'layoutSchema' => 'layout.%s.schema',
    'layoutType' => 'layout.%s.type',
    'layoutComponents' => 'layout.%s.layout_components',
));

/**
 * Configuration for default sidebars
 * 
 * This config write should contain the default sidebars by orientation
 * If the theme does support layout manipulation (which it should if it is proper GUMM),
 * use keys for the default orientations, as fall back layout pages will default to these
 * 
 */
Configure::write('sidebars', array(
    'left' => array(
        array(
            'name'          => 'Main Left Sidebar',
            'id'            => 'gumm-default-sidebar-left-1',
            'description'   => __('Left Sidebar on all pages', 'gummfw'),
            'before_widget' => '<div class="widget-wrap">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="bluebox-heading-wrap"><h3 class="bluebox-heading">',
            'after_title'   => '</h3></div>',
        ),
    ),
    'right' => array(
        array(
            'name'          => 'Main Right Sidebar',
            'id'            => 'gumm-default-sidebar-right-1',
            'description'   => __('Right Sidebar on all pages', 'gummfw'),
            'before_widget' => '<div class="widget-wrap">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="bluebox-heading-wrap"><h3 class="bluebox-heading">',
            'after_title'   => '</h3></div>',
        ),
    ),
    'footer' => array(
        array(
            'name'          => 'Footer 1',
            'id'            => 'gumm-footer-sidebar-1',
            'description'   => __('Footer sidebar first column', 'gummfw'),
            'before_widget' => '<div class="widget-wrap">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="heading-wrap"><h3><span></span>',
            'after_title'   => '</h3></div>',
        ),
        array(
            'name'          => 'Footer 2',
            'id'            => 'gumm-footer-sidebar-2',
            'description'   => __('Footer sidebar second column', 'gummfw'),
            'before_widget' => '<div class="widget-wrap">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="heading-wrap"><h3><span></span>',
            'after_title'   => '</h3></div>',
        ),
        array(
            'name'          => 'Footer 3',
            'id'            => 'gumm-footer-sidebar-3',
            'description'   => __('Footer sidebar third column', 'gummfw'),
            'before_widget' => '<div class="widget-wrap">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="heading-wrap"><h3><span></span>',
            'after_title'   => '</h3></div>',
        ),
        array(
            'name'          => 'Footer 4',
            'id'            => 'gumm-footer-sidebar-4',
            'description'   => __('Footer sidebar fourth column', 'gummfw'),
            'before_widget' => '<div class="widget-wrap">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="heading-wrap"><h3><span></span>',
            'after_title'   => '</h3></div>',
        ),
    ),
    
));

Configure::write('widgets', array(
    'GummPostsWidget',
    'GummTabsWidget',
    // 'GummTwitterWidget',
    'GummEventsCalendarWidget',
    'GummContactFormWidget',
    'GummSocialNetworksWidget',
));

Configure::write('Settings.maxNumCategories', 6);

Configure::write('Skin.customUserSkinId', 'user-custom-skin');

Configure::write('Security.cipherSeed', '598981478357997151979011865');

?>