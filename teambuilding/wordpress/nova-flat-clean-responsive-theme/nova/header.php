<!DOCTYPE html>

<!-- BEGIN html -->
<html xmlns="https://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<!-- Antoni Sinote Botev design (https://www.antonibotev.com) - Proudly powered by WordPress (https://wordpress.org) -->

<!-- BEGIN head -->
<head>
    <?php global $gummWpHelper, $gummJsHelper, $gummHtmlHelper, $gummLayoutHelper, $GummTemplateBuilder; ?>
    
    <script type="text/javascript">
        if (navigator.cookieEnabled === true) {
            var redirect = false;
            if (document.cookie.match(/__gumm_device\[pixelRatio\]=(\d+)/) === null) redirect = true;
            document.cookie='__gumm_device[resolution]='+Math.max(screen.width,screen.height)+'; path=/';
            document.cookie='__gumm_device[pixelRatio]='+("devicePixelRatio" in window ? devicePixelRatio : "1")+'; path=/';

            if (redirect) document.location.reload(true);
        }
    </script>
    
	<!-- Meta Tags -->
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	
	<!-- Title -->
	<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
	
	<!-- Favicon -->
    <?php $faviconUrl = ($customFaviconUrl = $gummWpHelper->getOption('favicon')) ? $customFaviconUrl : GUMM_THEME_URL . '/images/' . GUMM_THEME_PREFIX . '-favicon.gif'; ?>
	<link rel="shortcut icon" href="<?php echo $faviconUrl ?>" />	
	
	<!-- RSS & Pingbacks -->
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?> RSS Feed" href="<?php if (get_option(GUMM_THEME_PREFIX . '_feedburner')) { echo get_option(GUMM_THEME_PREFIX . '_feedburner'); } else { bloginfo( 'rss2_url' ); } ?>" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
    <!-- Theme Hook -->
	<?php wp_head(); ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<!-- BEGIN body -->
<body <?php body_class(); ?>>

    <?php
    $divHeaderAtts  = array(
        'class' => array('row-fluid', 'bluebox-header'),
    );

    $post = GummRegistry::get('Model', 'Post')->getQueriedObject();
    if (is_a($post, 'WP_Post') && isset($post->PostMeta['header_settings']) && $post->PostMeta['header_settings'] === 'custom') {
        $headerLayout    = $post->PostMeta['header_layout'];
        $headerMenuStyle = $post->PostMeta['header_menu_style'];
        $dropDownStyle   = $post->PostMeta['main_menu_dropdown_style'];
    } else {
        $headerLayout       = $gummWpHelper->getOption('header_layout');
        $headerMenuStyle    = $gummWpHelper->getOption('header_menu_style');
        $dropDownStyle      = $gummWpHelper->getOption('main_menu_dropdown_style');
    }
    
    $divHeaderAtts['class'][] = 'nav-style-' . $headerMenuStyle;
    $divHeaderAtts['class'][] = 'type-' . $headerLayout;


    if ($dropDownStyle === 'dark') {
        $divHeaderAtts['class'][] = 'dark-dropdown';
    }
    ?>

    <div id="mobile-menu">
        <?php
        $gummHtmlHelper->displayMenu(array(
            'id' => 'prime-nav-mobile',
            'class' => 'prime-nav-mobile-list',
            'walker' => 'GummResponsiveNavMenuWalker',
        ));
        ?>
    </div>
    
    <!-- BEGIN bluebox container -->
    <div id="bluebox-wrap" class="bluebox-wrap">
        
    	<!-- BEGIN header -->
        <div<?php echo $gummHtmlHelper->_constructTagAttributes($divHeaderAtts); ?>>
        	<div class="span12">
        	    
                <?php View::renderElement('header/info-bar'); ?>
        	    
        	    <div class="row-fluid bluebox-head-top-wrap">
                    <div class="bluebox-head-top bluebox-container">
                    
                        <a id="mobile-menu-button" class="mobile-nav-button" href="#">
                            <span></span>
                            <span></span>
                            <span></span>
                        </a>
                    
                    	<div class="bluebox-head-logo">
                            <?php $gummHtmlHelper->displayLogo(); ?>
                        </div>
                    
                        <?php if ($headerLayout === 'one'): ?>
                        <div class="bluebox-head-details">
                            <?php View::renderElement('header/details-bar'); ?>
                        </div>
                        <?php endif; ?>
                    
                        <?php if ($headerLayout === 'two'): ?>
                    	<div class="bluebox-prime-nav bluebox-container">
                            <?php
                            $gummHtmlHelper->displayMenu(array('class' => 'prime-nav'));
                            ?>
                        
                    	</div>
                    	<?php endif; ?>
                	
                        <div class="bluebox-clear"></div>
                    </div>
                </div>
                
                <div class="bluebox-head-bottom">
                    <?php if ($headerLayout !== 'two'): ?>
                	<div class="bluebox-prime-nav bluebox-container">
                        <?php
                        $gummHtmlHelper->displayMenu(array('class' => 'prime-nav'));
                        ?>
                	</div>
                	<?php endif; ?>
                </div>
                
                <?php View::renderElement('header/page-heading'); ?>
            </div>
        </div>
        <!-- END header -->
        
        <?php
        $headerElements = (is_page()) ? $GummTemplateBuilder->getTemplateElementsEnabled('header') : null;
        if ($headerElements) {
            foreach ($headerElements as $headerElement) {
                echo '<div class="row-fluid">';
                $headerElement->render();
                echo '</div>';
            }
        }
        ?>
        
        <!-- BEGIN content area -->
        <div class="bluebox-content-wrap">
        <div class="bluebox-container">
        <div class="row-fluid">
        <?php
		    $gummLayoutHelper->getSidebarForPage('left');
        $gummLayoutHelper->contentTagOpen();
        ?>