<?php
class TimeHelper extends GummHelper {
    
/**
 * The format to use when formatting a time using `CakeTime::nice()`
 *
 * The format should use the locale strings as defined in the PHP docs under
 * `strftime` (https://php.net/manual/en/function.strftime.php)
 *
 * @var string
 * @see CakeTime::format()
 */
	public static $niceFormat = '%a, %b %eS %Y, %H:%M';

/**
 * The format to use when formatting a time using `CakeTime::timeAgoInWords()`
 * and the difference is more than `CakeTime::$wordEnd`
 *
 * @var string
 * @see CakeTime::timeAgoInWords()
 */
	public static $wordFormat = 'j/n/y';

/**
 * The format to use when formatting a time using `CakeTime::niceShort()`
 * and the difference is between 3 and 7 days
 *
 * @var string
 * @see CakeTime::niceShort()
 */
	public static $niceShortFormat = '%d/%m, %H:%M';

/**
 * The format to use when formatting a time using `CakeTime::timeAgoInWords()`
 * and the difference is less than `CakeTime::$wordEnd`
 *
 * @var array
 * @see CakeTime::timeAgoInWords()
 */
	public static $wordAccuracy = array(
		'year' => "day",
		'month' => "day",
		'week' => "day",
		'day' => "hour",
		'hour' => "minute",
		'minute' => "minute",
		'second' => "second",
	);

/**
 * The end of relative time telling
 *
 * @var string
 * @see CakeTime::timeAgoInWords()
 */
	public static $wordEnd = '+1 month';
    
/**
 * Returns true if given datetime string is today.
 *
 * @param integer|string|DateTime $dateString UNIX timestamp, strtotime() valid string or DateTime object
 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
 * @return boolean True if datetime string is today
 * @link https://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#testing-time
 */
	public function isToday($dateString, $timezone = null) {
		$date = self::fromString($dateString, $timezone);
		return date('Y-m-d', $date) == date('Y-m-d', time());
	}

/**
 * Returns true if given datetime string is within this week.
 *
 * @param integer|string|DateTime $dateString UNIX timestamp, strtotime() valid string or DateTime object
 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
 * @return boolean True if datetime string is within current week
 * @link https://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#testing-time
 */
	public function isThisWeek($dateString, $timezone = null) {
		$date = self::fromString($dateString, $timezone);
		return date('W o', $date) == date('W o', time());
	}

/**
 * Returns true if given datetime string is within this month
 * @param integer|string|DateTime $dateString UNIX timestamp, strtotime() valid string or DateTime object
 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
 * @return boolean True if datetime string is within current month
 * @link https://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#testing-time
 */
	public function isThisMonth($dateString, $timezone = null) {
		$date = self::fromString($dateString);
		return date('m Y', $date) == date('m Y', time());
	}

/**
 * Returns true if given datetime string is within current year.
 *
 * @param integer|string|DateTime $dateString UNIX timestamp, strtotime() valid string or DateTime object
 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
 * @return boolean True if datetime string is within current year
 * @link https://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#testing-time
 */
	public function isThisYear($dateString, $timezone = null) {
		$date = self::fromString($dateString, $timezone);
		return date('Y', $date) == date('Y', time());
	}

/**
 * Returns true if given datetime string was yesterday.
 *
 * @param integer|string|DateTime $dateString UNIX timestamp, strtotime() valid string or DateTime object
 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
 * @return boolean True if datetime string was yesterday
 * @link https://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#testing-time
 *
 */
	public function wasYesterday($dateString, $timezone = null) {
		$date = self::fromString($dateString, $timezone);
		return date('Y-m-d', $date) == date('Y-m-d', strtotime('yesterday'));
	}

/**
 * Returns true if given datetime string is tomorrow.
 *
 * @param integer|string|DateTime $dateString UNIX timestamp, strtotime() valid string or DateTime object
 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
 * @return boolean True if datetime string was yesterday
 * @link https://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#testing-time
 */
	public function isTomorrow($dateString, $timezone = null) {
		$date = self::fromString($dateString, $timezone);
		return date('Y-m-d', $date) == date('Y-m-d', strtotime('tomorrow'));
	}
	
/**
 * Returns either a relative date or a formatted date depending
 * on the difference between the current time and given datetime.
 * $datetime should be in a *strtotime* - parsable format, like MySQL's datetime datatype.
 *
 * ### Options:
 *
 * - `format` => a fall back format if the relative time is longer than the duration specified by end
 * - `accuracy` => Specifies how accurate the date should be described (array)
 *    - year =>   The format if years > 0   (default "day")
 *    - month =>  The format if months > 0  (default "day")
 *    - week =>   The format if weeks > 0   (default "day")
 *    - day =>    The format if weeks > 0   (default "hour")
 *    - hour =>   The format if hours > 0   (default "minute")
 *    - minute => The format if minutes > 0 (default "minute")
 *    - second => The format if seconds > 0 (default "second")
 * - `end` => The end of relative time telling
 * - `userOffset` => Users offset from GMT (in hours) *Deprecated* use timezone intead.
 * - `timezone` => The user timezone the timestamp should be formatted in.
 *
 * Relative dates look something like this:
 *
 * - 3 weeks, 4 days ago
 * - 15 seconds ago
 *
 * Default date formatting is d/m/yy e.g: on 18/2/09
 *
 * The returned string includes 'ago' or 'on' and assumes you'll properly add a word
 * like 'Posted ' before the function output.
 *
 * NOTE: If the difference is one week or more, the lowest level of accuracy is day
 *
 * @param integer|string|DateTime $dateTime Datetime UNIX timestamp, strtotime() valid string or DateTime object
 * @param array $options Default format if timestamp is used in $dateString
 * @return string Relative time string.
 * @link https://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#formatting
 */
	public function timeAgoInWords($dateTime, $options = array()) {
		$timezone = null;
		$format = self::$wordFormat;
		$end = self::$wordEnd;
		$accuracy = self::$wordAccuracy;

		if (is_array($options)) {
			if (isset($options['timezone'])) {
				$timezone = $options['timezone'];
			} elseif (isset($options['userOffset'])) {
				$timezone = $options['userOffset'];
			}

			if (isset($options['accuracy'])) {
				if (is_array($options['accuracy'])) {
					$accuracy = array_merge($accuracy, $options['accuracy']);
				} else {
					foreach ($accuracy as $key => $level) {
						$accuracy[$key] = $options['accuracy'];
					}
				}
			}

			if (isset($options['format'])) {
				$format = $options['format'];
			}
			if (isset($options['end'])) {
				$end = $options['end'];
			}
			unset($options['end'], $options['format']);
		} else {
			$format = $options;
		}

        // $now = self::fromString(time(), $timezone);
        $now = time();
		$inSeconds = self::fromString($dateTime, $timezone);
		$backwards = ($inSeconds > $now);

		if ($backwards) {
			$futureTime = $inSeconds;
			$pastTime = $now;
		} else {
			$futureTime = $now;
			$pastTime = $inSeconds;
		}
		$diff = $futureTime - $pastTime;

		// If more than a week, then take into account the length of months
		if ($diff >= 604800) {
			list($future['H'], $future['i'], $future['s'], $future['d'], $future['m'], $future['Y']) = explode('/', date('H/i/s/d/m/Y', $futureTime));

			list($past['H'], $past['i'], $past['s'], $past['d'], $past['m'], $past['Y']) = explode('/', date('H/i/s/d/m/Y', $pastTime));
			$years = $months = $weeks = $days = $hours = $minutes = $seconds = 0;

			$years = $future['Y'] - $past['Y'];
			$months = $future['m'] + ((12 * $years) - $past['m']);

			if ($months >= 12) {
				$years = floor($months / 12);
				$months = $months - ($years * 12);
			}
			if ($future['m'] < $past['m'] && $future['Y'] - $past['Y'] == 1) {
				$years--;
			}

			if ($future['d'] >= $past['d']) {
				$days = $future['d'] - $past['d'];
			} else {
				$daysInPastMonth = date('t', $pastTime);
				$daysInFutureMonth = date('t', mktime(0, 0, 0, $future['m'] - 1, 1, $future['Y']));

				if (!$backwards) {
					$days = ($daysInPastMonth - $past['d']) + $future['d'];
				} else {
					$days = ($daysInFutureMonth - $past['d']) + $future['d'];
				}

				if ($future['m'] != $past['m']) {
					$months--;
				}
			}

			if ($months == 0 && $years >= 1 && $diff < ($years * 31536000)) {
				$months = 11;
				$years--;
			}

			if ($months >= 12) {
				$years = $years + 1;
				$months = $months - 12;
			}

			if ($days >= 7) {
				$weeks = floor($days / 7);
				$days = $days - ($weeks * 7);
			}
		} else {
			$years = $months = $weeks = 0;
			$days = floor($diff / 86400);

			$diff = $diff - ($days * 86400);

			$hours = floor($diff / 3600);
			$diff = $diff - ($hours * 3600);

			$minutes = floor($diff / 60);
			$diff = $diff - ($minutes * 60);
			$seconds = $diff;
		}
		$relativeDate = '';
		$diff = $futureTime - $pastTime;

		if ($diff > abs($now - self::fromString($end))) {
			$relativeDate = __('cake', 'on %s', date($format, $inSeconds));
		} else {
			if ($years > 0) {
				$f = $accuracy['year'];
			} elseif (abs($months) > 0) {
				$f = $accuracy['month'];
			} elseif (abs($weeks) > 0) {
				$f = $accuracy['week'];
			} elseif (abs($days) > 0) {
				$f = $accuracy['day'];
			} elseif (abs($hours) > 0) {
				$f = $accuracy['hour'];
			} elseif (abs($minutes) > 0) {
				$f = $accuracy['minute'];
			} else {
				$f = $accuracy['second'];
			}
			$f = str_replace(array('year', 'month', 'week', 'day', 'hour', 'minute', 'second'), array(1, 2, 3, 4, 5, 6, 7), $f);

			$relativeDate .= $f >= 1 && $years > 0 ? ($relativeDate ? ', ' : '') . __difplural(__('%d year', 'gummfw'), __('%d years', 'gummfw'), $years, $years) : '';
			$relativeDate .= $f >= 2 && $months > 0 ? ($relativeDate ? ', ' : '') . __difplural(__('%d month', 'gummfw'), __('%d months', 'gummfw'), $months, $months) : '';
			$relativeDate .= $f >= 3 && $weeks > 0 ? ($relativeDate ? ', ' : '') . __difplural(__('%d week', 'gummfw'), __('%d weeks', 'gummfw'), $weeks, $weeks) : '';
			$relativeDate .= $f >= 4 && $days > 0 ? ($relativeDate ? ', ' : '') . __difplural(__('%d day', 'gummfw'), __('%d days', 'gummfw'), $days, $days) : '';
			$relativeDate .= $f >= 5 && $hours > 0 ? ($relativeDate ? ', ' : '') . __difplural(__('%d hour', 'gummfw'), __('%d hours', 'gummfw'), $hours, $hours) : '';
			$relativeDate .= $f >= 6 && $minutes > 0 ? ($relativeDate ? ', ' : '') . __difplural(__('%d minute', 'gummfw'), __('%d minutes', 'gummfw'), $minutes, $minutes) : '';
			$relativeDate .= $f >= 7 && $seconds > 0 ? ($relativeDate ? ', ' : '') . __difplural(__('%d second', 'gummfw'), __('%d seconds', 'gummfw'), $seconds, $seconds) : '';
			if (!$backwards) {
			    $relativeDate = sprintf(__('%s ago', 'gummfw'), $relativeDate);
			}
		}

		// If now
		if ($diff == 0) {
			$relativeDate = __('just now', 'gummfw');
		}
		return $relativeDate;
	}
	
	public static function fromString($dateString, $timezone=null) {
	    return strtotime($dateString);
	}
	
	public function isFromMonth($dateString, $monthNum) {
	    
	}
	
	public function getTimeZonesAvailable() {
	    App::import('Config', 'Timezones');
	    return Configure::read('Data.timezones');
	}
	
	public function getTimeZonesEnabled($val=null) {
        $result = array();
        if ($val === null) {
            $val = GummRegistry::get('Helper', 'Wp')->getOption('timezones_enabled');
        }
        return ($val && is_array($val)) ? array_intersect_key($this->getTimeZonesAvailable(), array_combine($val, $val)) : array();
	}
}
?>