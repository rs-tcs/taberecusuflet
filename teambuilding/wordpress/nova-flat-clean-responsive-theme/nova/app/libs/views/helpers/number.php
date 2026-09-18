<?php
class NumberHelper extends GummHelper {
    
/**
 * If native number_format() should be used. If >= PHP5.4
 *
 * @var boolean
 */
    protected static $_numberFormatSupport = null;

/**
 * Formats a number into a currency format.
 *
 * @param float $number A floating point number
 * @param integer $options if int then places, if string then before, if (,.-) then use it
 *   or array with places and before keys
 * @return string formatted number
 * @link https://book.cakephp.org/2.0/en/core-libraries/helpers/number.html#NumberHelper::format
 */
    public static function format($number, $options = false) {
        $places = 0;
        if (is_int($options)) {
            $places = $options;
        }

        $separators = array(',', '.', '-', ':');

        $before = $after = null;
        if (is_string($options) && !in_array($options, $separators)) {
            $before = $options;
        }
        $thousands = ',';
        if (!is_array($options) && in_array($options, $separators)) {
            $thousands = $options;
        }
        $decimals = '.';
        if (!is_array($options) && in_array($options, $separators)) {
            $decimals = $options;
        }

        $escape = true;
        if (is_array($options)) {
            $options = array_merge(array('before' => '$', 'places' => 2, 'thousands' => ',', 'decimals' => '.'), $options);
            extract($options);
        }

        $out = $before . self::_numberFormat($number, $places, $decimals, $thousands) . $after;

        if ($escape) {
            return htmlspecialchars($out);
        }
        return $out;
    }

/**
 * Alternative number_format() to accommodate multibyte decimals and thousands < PHP 5.4
 *
 * @param float $number
 * @param integer $places
 * @param string $decimals
 * @param string $thousands
 * @return string
 */
    protected static function _numberFormat($number, $places = 0, $decimals = '.', $thousands = ',') {
        if (!isset(self::$_numberFormatSupport)) {
            self::$_numberFormatSupport = version_compare(PHP_VERSION, '5.4.0', '>=');
        }
        if (self::$_numberFormatSupport) {
            return number_format($number, $places, $decimals, $thousands);
        }
        $number = number_format($number, $places, '.', '');
        $after = '';
        $foundDecimal = strpos($number, '.');
        if ($foundDecimal !== false) {
            $after = substr($number, $foundDecimal);
            $number = substr($number, 0, $foundDecimal);
        }
        while (($foundThousand = preg_replace('/(\d+)(\d\d\d)/', '\1 \2', $number)) != $number) {
            $number = $foundThousand;
        }
        $number .= $after;
        return strtr($number, array(' ' => $thousands, '.' => $decimals));
    }
    
    public function hex2rgb($hex) {
       $hex = str_replace("#", "", $hex);

       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }
       $rgb = array($r, $g, $b);
       
       return $rgb; // returns an array with the rgb values
    }
    
    public function rgb2Hex($r, $g, $b) {
        //String padding bug found and the solution put forth by Pete Williams (https://snipplr.com/users/PeteW)
        $hex = "#";
        $hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
        $hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
        $hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

        return $hex;
    }
}
?>