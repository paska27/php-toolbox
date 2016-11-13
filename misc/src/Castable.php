<?php
namespace Paska\Toolbox;

/**
 * Class Castable
 *
 * Used for converting some values from/into different types.
 *
 * Common usage:
 * <code>
 *  $value;// type of value is unknown
 *
 *  echo (string) new Paska\Castable($value);
 * </code>
 */
class Castable {

	/**
	 * @var mixed
	 */
	private $value;

	/**
	 * @param mixed $value
	 *
	 * @return string
	 */
	public static function toString($value) {
		if (is_object($value)) {
			return sprintf('Object(%s)', get_class($value));
		}

		if (is_array($value)) {
			$a = array();
			foreach ($value as $k => $v) {
				$a[] = sprintf('%s => %s', $k, self::toString($v));
			}

			return sprintf("Array(%s)", implode(', ', $a));
		}

		if (is_resource($value)) {
			return sprintf('Resource(%s)', get_resource_type($value));
		}

		if (null === $value) {
			return 'null';
		}

		if (false === $value) {
			return 'false';
		}

		if (true === $value) {
			return 'true';
		}

		return (string)$value;
	}

	/**
	 * @param mixed $value
	 *
	 * @return array
	 */
	public static function toMap($value) {
		$map = array();

		$objKey = function ($object) {
			$key = substr($class = get_class($object), $pos = ((int) strrpos($class, '\\') + (int) strrpos($class, '_') + 1));

			return strtolower(1 != $pos ? $key : $class);
		};

		// objects' collection
		if (is_array($value)
			&& is_numeric($cur = current(array_keys($value)))
			&& is_object($first = reset($value))
			&& isset($value[$next = ($cur+1)])
			&& $value[$next] == $first
		) {
			$map[$objKey($first) . 's'] = $value;

			return $map;
		}

		$value = !is_array($value) ? array($value) : $value;
		// loop through mixed values
		foreach ($value as $key => $val) {
			if (is_numeric($key)) {
				if (is_object($val)) {
					// object name to key conversion
					$map[$objKey($val)] = $val;
				}
			} else {
				$map[$key] = $val;
			}
		}

		return $map;
	}

	/**
	 * @param mixed $value
	 * @param string $wrap
	 *
	 * @return string
	 */
	public static function toCsv($value, $wrap = null) {
		return self::toList($value, ', ', $wrap);
	}

	/**
	 * @param mixed $value
	 * @param string $separator
	 * @param string $wrap
	 *
	 * @return string
	 */
	public static function toList($value, $separator, $wrap = null) {
		if ($wrap) {
			$wrap = !is_array($wrap) ? array($wrap, $wrap) : $wrap;
			$value = array_map(function ($v) use ($wrap) {return $wrap[0] . $v . $wrap[1];}, (array) $value);
		}

		return join($separator, (array)$value);
	}

	/**
	 * @param mixed $value
	 *
	 * @return boolean
	 */
	public static function isMap($value) {
		if (!is_array($value)) {
			return false;
		}

		foreach ($value as $key => $dummy) {
			if (is_integer($key)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param mixed $value
	 */
	public function __construct($value) {
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return self::toString($this->value);
	}
}
