<?php
class Configure {
	
/**
 * Returns a singleton instance of the Configure class.
 *
 * @return Configure instance
 * @access public
 */
	public static function &getInstance() {
		static $instance = array();
		if (!$instance) {
		    $_inst = new Configure();
			$instance[0] =& $_inst;
		}
		return $instance[0];
	}
	
/**
 * Used to store a dynamic variable in the Configure instance.
 *
 * Usage:
 * {{{
 * Configure::write('One.key1', 'value of the Configure::One[key1]');
 * Configure::write(array('One.key1' => 'value of the Configure::One[key1]'));
 * Configure::write('One', array(
 *     'key1' => 'value of the Configure::One[key1]',
 *     'key2' => 'value of the Configure::One[key2]'
 * );
 *
 * Configure::write(array(
 *     'One.key1' => 'value of the Configure::One[key1]',
 *     'One.key2' => 'value of the Configure::One[key2]'
 * ));
 * }}}
 *
 * @link https://book.cakephp.org/view/926/write
 * @param array $config Name of var to write
 * @param mixed $value Value to set for var
 * @return boolean True if write was successful
 * @access public
 */
	public static function write($config, $value = null) {
		$_this =& Configure::getInstance();

		if (!is_array($config)) {
			$config = array($config => $value);
		}

		foreach ($config as $name => $value) {
			if (strpos($name, '.') === false) {
				$_this->{$name} = $value;
			} else {
				$names = explode('.', $name, 4);
				switch (count($names)) {
					case 2:
						$_this->{$names[0]}[$names[1]] = $value;
					break;
					case 3:
						$_this->{$names[0]}[$names[1]][$names[2]] = $value;
						break;
					case 4:
						$names = explode('.', $name, 2);
						if (!isset($_this->{$names[0]})) {
							$_this->{$names[0]} = array();
						}
						$_this->{$names[0]} = Set::insert($_this->{$names[0]}, $names[1], $value);
					break;
				}
			}
		}

		return true;
	}

/**
 * Used to read information stored in the Configure instance.
 *
 * Usage:
 * {{{
 * Configure::read('Name'); will return all values for Name
 * Configure::read('Name.key'); will return only the value of Configure::Name[key]
 * }}}
 *
 * @link https://book.cakephp.org/view/927/read
 * @param string $var Variable to obtain.  Use '.' to access array elements.
 * @return string value of Configure::$var
 * @access public
 */
	public static function read($var = 'debug') {
		$_this =& Configure::getInstance();

		if ($var === 'debug') {
			return $_this->debug;
		}

		if (strpos($var, '.') !== false) {
			$names = explode('.', $var, 3);
			$var = $names[0];
		}
		if (!isset($_this->{$var})) {
			return null;
		}
		if (!isset($names[1])) {
			return $_this->{$var};
		}
		switch (count($names)) {
			case 2:
				if (isset($_this->{$var}[$names[1]])) {
					return $_this->{$var}[$names[1]];
				}
			break;
			case 3:
				if (isset($_this->{$var}[$names[1]][$names[2]])) {
					return $_this->{$var}[$names[1]][$names[2]];
				}
				if (!isset($_this->{$var}[$names[1]])) {
					return null;
				}
				return Set::classicExtract($_this->{$var}[$names[1]], $names[2]);
			break;
		}
		return null;
	}
}
?>