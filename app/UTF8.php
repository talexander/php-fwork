<?php

namespace App;

class UTF8 {


	/**
	 * Recursively cleans arrays, objects, and strings. Removes ASCII control
	 * codes and converts to the requested charset while silently discarding
	 * incompatible characters.
	 *
	 *     UTF8::clean($_GET); // Clean GET data
	 *
	 * [!!] This method requires [Iconv](http://php.net/iconv)
	 *
	 * @param   mixed   $var        variable to clean
	 * @param   string  $charset    character set, defaults to Kohana::$charset
	 * @return  mixed
	 * @uses    UTF8::strip_ascii_ctrl
	 * @uses    UTF8::is_ascii
	 */
	public static function clean($var, $charset = 'UTF-8') {

		if (is_array($var) OR is_object($var)) {
			foreach ($var as $key => $val) {
				// Recursion!
				$var[self::clean($key)] = self::clean($val);
			}
		} elseif (is_string($var) AND $var !== '') {
			// Remove control characters
			$var = self::strip_ascii_ctrl($var);

			if ( ! self::is_ascii($var)) {
				// Disable notices
				$error_reporting = error_reporting(~E_NOTICE);

				// iconv is expensive, so it is only used when needed
				$var = iconv($charset, $charset.'//IGNORE', $var);

				// Turn notices back on
				error_reporting($error_reporting);
			}
		}

		return $var;
	}

	/**
	 * Tests whether a string contains only 7-bit ASCII bytes. This is used to
	 * determine when to use native functions or UTF-8 functions.
	 *
	 *     $ascii = UTF8::is_ascii($str);
	 *
	 * @param   mixed   $str    string or array of strings to check
	 * @return  boolean
	 */
	public static function is_ascii($str)
	{
		if (is_array($str)) {
			$str = implode($str);
		}

		return ! preg_match('/[^\x00-\x7F]/S', $str);
	}

	/**
	 * Strips out device control codes in the ASCII range.
	 *
	 *     $str = UTF8::strip_ascii_ctrl($str);
	 *
	 * @param   string  $str    string to clean
	 * @return  string
	 */
	public static function strip_ascii_ctrl($str) {
		return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $str);
	}

	/**
	 * Strips out all non-7bit ASCII bytes.
	 *
	 *     $str = UTF8::strip_non_ascii($str);
	 *
	 * @param   string  $str    string to clean
	 * @return  string
	 */
	public static function strip_non_ascii($str) {
		return preg_replace('/[^\x00-\x7F]+/S', '', $str);
	}
}