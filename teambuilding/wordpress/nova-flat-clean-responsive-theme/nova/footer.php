<?php
global $gummWpHelper, $GummTemplateBuilder, $gummLayoutHelper, $gummHtmlHelper;
?>
        <?php $gummLayoutHelper->contentTagClose(); ?> <!-- .main-content end -->
        <?php $gummLayoutHelper->getSidebarForPage('right'); ?>
        </div>
        </div>
        </div>
        <!-- END content area -->
    
        <!-- BEGIN footer -->
        
        <?php
        $sidebarHtml1 = $gummHtmlHelper->getSidebarHtml('gumm-footer-sidebar-1');
        $sidebarHtml2 = $gummHtmlHelper->getSidebarHtml('gumm-footer-sidebar-2');
        $sidebarHtml3 = $gummHtmlHelper->getSidebarHtml('gumm-footer-sidebar-3');
        $sidebarHtml4 = $gummHtmlHelper->getSidebarHtml('gumm-footer-sidebar-4');

        $numColumns = (int) $gummWpHelper->getOption('footer_columns');
        ?>
    
        <div class="footer-wrap">
    		<div class="row-fluid">
    			<div class="span12">

            	    <?php if ($numColumns > 0): ?>
                    <div class="bluebox-footer-content">
                    	<div class="row-fluid bluebox-container">
                            <?php
                            $spanClass = 'span' . 12/$numColumns;
                            for ($i=1; $i<=$numColumns; $i++) {
                                $varName = 'sidebarHtml' . $i;
                                echo '<div class="' . $spanClass . '">' . $$varName . '</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($gummWpHelper->getOption('footer.show_bottom') === 'true'): ?>
    				<div class="bluebox-copyrights-wrap">
                    	<div class="row-fluid bluebox-container">
    						<div class="span6">
    						    <?php echo $gummWpHelper->getOption('footer.bottom_left'); ?>
                            </div>
                            <div class="span6 bluebox-authors-wrap">
                            	<div class="bluebox-authors-content">
                                <?php
                                echo $gummWpHelper->getOption('footer.bottom_right');
                                if ($gummWpHelper->getOption('footer.enable_scroll_top') === 'true') {
                                    echo '<a id="footer-scroll-top-link" class="back-to-top icon-chevron-up" href="#"></a>';
                                }
                                ?>
                                </div>
                        	</div>
                        </div>
    				</div>
    				<?php endif; ?>
                
                    <!-- END footer content -->
                        
    			</div>
    		</div>
    	</div>
    
        <!-- END footer -->
    

    </div>
    <!-- END bluebox container -->

<?php if (Configure::read('build') !== 'release'): ?>
<?php View::renderElement('front-end-preview-panel'); ?>
<?php endif; ?>

<?php echo $gummWpHelper->getOption('google_analytics'); ?>

<?php wp_footer(); ?>
</body>
</html>
