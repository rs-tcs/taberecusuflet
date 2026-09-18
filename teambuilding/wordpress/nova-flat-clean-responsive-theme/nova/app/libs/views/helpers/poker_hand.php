<?php
class PokerHandHelper extends GummHelper {
    
    public function getHandHtml($handString, $options=array()) {
        $options = array_merge(array(
            'size' => 'large',
        ), $options);
        $outputHtml = '';
        $handCards = explode('|', $handString);
        $counter = 1;
        foreach ($handCards as $cardString) {
            $nthClass = date('S', strtotime('1970/1/' . $counter));
            $options['class'] = $nthClass;
            $outputHtml .= $this->getCardHtml($cardString, $options);
            $counter++;
        }
        
        return $outputHtml;
    }
    
    public function getCardHtml($cardString, $options=array()) {
        $options = array_merge(array(
            'size' => 'large',
        ), $options);
        
        switch ($options['size']) {
         case 'large':
            return $this->getCardRichHtml($cardString, $options);
         default:
            return $this->getCardSmallHtml($cardString);
        }

    }
    
    public function getCardRichHtml($cardString, $options=array()) {
        $card   = substr($cardString, 0, 1);
        $symbolLetter = substr($cardString, 1, 1);
        $symbol = $this->getColorSymbolHtml($symbolLetter);
        
        $options = array_merge(array(
            'class' => 'st',
        ), $options);
        
        $handClass = array($options['class']);
        if (in_array($symbolLetter, array('h', 'd'))) $handClass[] = 'red';
        
        ob_start();
?>
        <div class="card-span">
        	<div class="card-wrap">
            	<div class="poker-hand-dummy"></div>
                <div class="poker-hand-large <?php echo implode(' ', $handClass); ?>">
               		<div class="card-symbols">
                        <div class="symbol left"><?php echo $card; ?><span><?php echo $symbol; ?></span></div>
                        <div class="symbol center"><?php echo $symbol; ?></div>
                        <div class="symbol right"><?php echo $card; ?><span><?php echo $symbol; ?></span></div>
                    </div>
                </div>
        	</div>
        </div>
<?php
        return ob_get_clean();
    }
    
    public function getCardSmallHtml($cardString) {
        $card   = substr($cardString, 0, 1);
        $symbolLetter = substr($cardString, 1, 1);
        $symbol = $this->getColorSymbolHtml($symbolLetter);
        
        $handClass = array('poker-hand');
        if (in_array($symbolLetter, array('h', 'd'))) $handClass[] = 'red';
        return '<span class="' . implode(' ', $handClass) . '"><strong>' . strtoupper($card) . '</strong><span>' . $symbol . '</span></span>';
    }
    
    public function getColorSymbolHtml($color) {
        $symbolHtml = '&clubs;';
        // debug($color);
        switch ($color) {
         case 'd':
            $symbolHtml = '&diams;';
            break;
         case 'h':
            $symbolHtml = '&hearts;';
            break;
         case 's':
            $symbolHtml = '&spades;';
            break;
        }
        return $symbolHtml;
    }
}
?>