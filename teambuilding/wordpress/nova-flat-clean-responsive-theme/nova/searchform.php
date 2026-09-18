<?php
    $inputValue = __('Search...', 'gummfw');
	$inactiveClassName = 'bluebox-search-input';
	$activeClassName = $inactiveClassName . ' active';
?>
	<form method="get" action="<?php echo home_url(); ?>/" class="search-form">
        <i class="icon-search searchform-icon"></i>
		<input class="<?php echo $inactiveClassName; ?>" type="text" name="s" value="<?php echo $inputValue ?>" onfocus="if(this.value=='<?php echo esc_js($inputValue); ?>'){this.value=''; this.className='<?php echo esc_js($activeClassName); ?>';}" onblur="if(this.value==''){this.value='<?php echo esc_js($inputValue); ?>'; this.className='<?php echo esc_js($inactiveClassName); ?>';}" autocomplete="off" data-view-all-title="<?php _e('Show All Results', 'gummfw'); ?>" />
		<input type="submit" class="submit" value="Search" />
		<div class="search-form-autocomplete active">
		    <div class="search-results-autocomplete">
		        
		    </div>
		</div>
	</form>
