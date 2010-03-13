<?php
/**
 * Inspekt - main source file
 *
 *
 *
 * @author Chris Shiflett <chris@shiflett.org>
 * @author Ed Finkler <coj@funkatron.com>
 *
 * @package Inspekt
 */



/**
 * Options for isHostname() that specify which types of hostnames
 * to allow.
 *
 * HOST_ALLOW_DNS:   Allows Internet domain names (e.g.,
 *                   example.com).
 */
define ('ISPK_HOST_ALLOW_DNS',   1);

/**
 * Options for isHostname() that specify which types of hostnames
 * to allow.
 *
 * HOST_ALLOW_IP:    Allows IP addresses.
 */
define ('ISPK_HOST_ALLOW_IP',    2);

/**
 * Options for isHostname() that specify which types of hostnames
 * to allow.
 *
 * HOST_ALLOW_LOCAL: Allows local network names (e.g., localhost,
 *                   www.localdomain) and Internet domain names.
 */
define ('ISPK_HOST_ALLOW_LOCAL', 4);

/**
 * Options for isHostname() that specify which types of hostnames
 * to allow.
 *
 * HOST_ALLOW_ALL:   Allows all of the above types of hostnames.
 */
define ('ISPK_HOST_ALLOW_ALL',   7);

/**
 * Options for isUri that specify which types of URIs to allow.
 *
 * URI_ALLOW_COMMON: Allow only "common" hostnames: http, https, ftp
 */
define ('ISPK_URI_ALLOW_COMMON', 1);

/**
 * regex used to define what we're calling a valid domain name
 *
 */
define ('ISPK_DNS_VALID', '/^(?:[^\W_]((?:[^\W_]|-){0,61}[^\W_])?\.)+[a-zA-Z]{2,6}\.?$/');

/**
 * regex used to define what we're calling a valid email
 *
 * we're taking a "match 99%" approach here, rather than a strict
 * interpretation of the RFC.
 *
 * @see http://www.regular-expressions.info/email.html
 */
define ('ISPK_EMAIL_VALID', '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/');


define ('ISPK_ARRAY_PATH_SEPARATOR', '/');

define ('ISPK_RECURSION_MAX', 15);


/**
 * @package    Inspekt
 */
class Inspekt
{
	
	
	protected static $useFilterExtension = true;
	
	

	/**
	 * Returns the $_SERVER data wrapped in an Inspekt_Cage object
	 *
	 * This utilizes a singleton pattern to get around scoping issues
	 *
	 * @param string  $config_file
	 * @param boolean $strict whether or not to nullify the superglobal array
	 * @return Inspekt_Cage
	 * 
	 * @assert()
	 */
	static public function makeServerCage($config_file=NULL, $strict=TRUE) {
		/**
		 * @staticvar $_instance
		 */
		static $_instance;

		if (!isset($_instance)) {
			$_instance = Inspekt_Cage::Factory($_SERVER, $config_file, '_SERVER', $strict);
		}
		$GLOBALS['HTTP_SERVER_VARS'] = NULL;
		return $_instance;
	}


	/**
	 * Returns the $_GET data wrapped in an Inspekt_Cage object
	 *
	 * This utilizes a singleton pattern to get around scoping issues
	 *
	 * @param string  $config_file
	 * @param boolean $strict whether or not to nullify the superglobal array
	 * @return Inspekt_Cage
	 * @static
	 */
	static public function makeGetCage($config_file=NULL, $strict=TRUE) {
		/**
		 * @staticvar $_instance
		 */
		static $_instance;

		if (!isset($_instance)) {
			$_instance = Inspekt_Cage::Factory($_GET, $config_file, '_GET', $strict);
		}
		$GLOBALS['HTTP_GET_VARS'] = NULL;
		return $_instance;
	}


	/**
	 * Returns the $_POST data wrapped in an Inspekt_Cage object
	 *
	 * This utilizes a singleton pattern to get around scoping issues
	 *
	 * @param string  $config_file
	 * @param boolean $strict whether or not to nullify the superglobal array
	 * @return Inspekt_Cage
	 * @static
	 */
	static public function makePostCage($config_file=NULL, $strict=TRUE) {
		/**
		 * @staticvar $_instance
		 */
		static $_instance;

		if (!isset($_instance)) {
			$_instance = Inspekt_Cage::Factory($_POST, $config_file, '_POST', $strict);
		}
		$GLOBALS['HTTP_POST_VARS'] = NULL;
		return $_instance;
	}

	/**
	 * Returns the $_COOKIE data wrapped in an Inspekt_Cage object
	 *
	 * This utilizes a singleton pattern to get around scoping issues
	 *
	 * @param string  $config_file
	 * @param boolean $strict whether or not to nullify the superglobal array
	 * @return Inspekt_Cage
	 * @static
	 */
	static public function makeCookieCage($config_file=NULL, $strict=TRUE) {
		/**
		 * @staticvar $_instance
		 */
		static $_instance;

		if (!isset($_instance)) {
			$_instance = Inspekt_Cage::Factory($_COOKIE, $config_file, '_COOKIE', $strict);
		}
		$GLOBALS['HTTP_COOKIE_VARS'] = NULL;
		return $_instance;
	}


	/**
	 * Returns the $_ENV data wrapped in an Inspekt_Cage object
	 *
	 * This utilizes a singleton pattern to get around scoping issues
	 *
	 * @param string  $config_file
	 * @param boolean $strict whether or not to nullify the superglobal array
	 * @return Inspekt_Cage
	 * @static
	 */
	static public function makeEnvCage($config_file=NULL, $strict=TRUE) {
		/**
		 * @staticvar $_instance
		 */
		static $_instance;

		if (!isset($_instance)) {
			$_instance = Inspekt_Cage::Factory($_ENV, $config_file, '_ENV', $strict);
		}
		$GLOBALS['HTTP_ENV_VARS'] = NULL;
		return $_instance;
	}


	/**
	 * Returns the $_FILES data wrapped in an Inspekt_Cage object
	 *
	 * This utilizes a singleton pattern to get around scoping issues
	 *
	 * @param string  $config_file
	 * @param boolean $strict whether or not to nullify the superglobal array
	 * @return Inspekt_Cage
	 * @static
	 */
	static public function makeFilesCage($config_file=NULL, $strict=TRUE) {
		/**
		 * @staticvar $_instance
		 */
		static $_instance;

		if (!isset($_instance)) {
			$_instance = Inspekt_Cage::Factory($_FILES, $config_file, '_FILES', $strict);
		}
		$GLOBALS['HTTP_POST_FILES'] = NULL;
		return $_instance;
	}


	/**
	 * Returns the $_SESSION data wrapped in an Inspekt_Cage object
	 *
	 * This utilizes a singleton pattern to get around scoping issues
	 *
	 * @param string  $config_file
	 * @param boolean $strict whether or not to nullify the superglobal array
	 * @return Inspekt_Cage
	 * @static
	 * @deprecated
	 */
	static public function makeSessionCage($config_file=NULL, $strict=TRUE) {
		
		trigger_error('makeSessionCage is disabled in this version', E_USER_ERROR);
		
		/**
		 * @staticvar $_instance
		 */
		static $_instance;

		if (!isset($_SESSION)) {
			return NULL;
		}

		if (!isset($_instance)) {
			$_instance = Inspekt_Cage_Session::Factory($_SESSION, $config_file, '_SESSION', $strict);
		}
		$GLOBALS['HTTP_SESSION_VARS'] = NULL;
		return $_instance;
	}


	/**
	 * Returns a Supercage object, which wraps ALL input superglobals
	 *
	 * @param string  $config_file
	 * @param boolean $strict whether or not to nullify the superglobal
	 * @return Inspekt_Supercage
	 * @static
	 */
	static public function makeSuperCage($config_file=NULL, $strict=TRUE) {
		/**
		 * @staticvar $_instance
		 */
		static $_scinstance;

		if (!isset($_scinstance)) {
			$_scinstance = Inspekt_Supercage::Factory($config_file, $strict);
		}
		return $_scinstance;

	}


	/**
	 * Sets and/or retrieves whether we should use the PHP filter extensions where possible
	 * If a param is passed, it will set the state in addition to returning it
	 * 
	 * We use this method of storing in a static class property so that we can access the value outside of class instances
	 * 
	 * @param boolean $state optional
	 * @return boolean
	 */
	static public function useFilterExt($state=null) {
		if (isset($state)) {
			Inspekt::$useFilterExtension = (bool)$state;
		}
		return Inspekt::$useFilterExtension;
	}
	


	/**
	 * Recursively walks an array and applies a given filter method to
	 * every value in the array.
	 *
	 * This should be considered a "protected" method, and not be called
	 * outside of the class
	 * 
	 *
	 * @param array|ArrayObject $input
	 * @param string $inspektor  The name of a static filtering method, like get* or no*
	 * @return array
	 *
	 */
	static protected function _walkArray($input, $method, $classname=NULL) {
				
		if (!isset($classname)) {
			$classname = __CLASS__;
		}
				
		if (!self::isArrayObject($input) && !is_array($input) ) {
			user_error('$input must be an array or ArrayObject', E_USER_ERROR);
			return FALSE;
		}

		if ( !is_callable( array($classname, $method) ) ) {
			user_error('Inspektor '.$classname.'::'.$method.' is invalid', E_USER_ERROR);
			return FALSE;
		}

		foreach($input as $key=>$val) {
			if (is_array($val)) {
				$input[$key]=self::_walkArray($val, $method, $classname);
			} else {
				$val = call_user_func( array($classname, $method), $val);
				$input[$key]=$val;
			}
		}
		return $input;
	}

	
	/**
	 * Checks to see if this is an ArrayObject
	 * @param mixed
	 * @return boolean
	 * @link http://php.net/arrayobject
	 */
	static public function isArrayObject($obj) {
		$is = false;
		$is = (is_object($obj) && get_class($obj) === 'ArrayObject');
		return $is;
	}

	/**
	 * Checks to see if this is an array or an ArrayObject
	 * @param mixed
	 * @return boolean
	 * @link http://php.net/arrayobject
	 * @link http://php.net/array
	 */
	static public function isArrayOrArrayObject($arr) {
		$is = false;
		$is = Inspekt::isArrayObject($arr) || is_array($arr);
		return $is;
	}

	/**
	 * Converts an array into an ArrayObject. We use ArrayObjects when walking arrays in Inspekt
	 * @param array
	 * @return ArrayObject
	 * 
	 */
	static public function convertArrayToArrayObject(&$arr) {
		foreach ($arr as $key => $value) {
			if (is_array($value)) {
				$value = new ArrayObject($value);
				$arr[$key] = $value;
				//echo $key." is an array\n";
				Inspekt::convertArrayToArrayObject($arr[$key]);
			}
		}

		return new ArrayObject($arr);
	}
	

	/**
     * Returns only the alphabetic characters in value.
     *
     * @param mixed $value
     * @return mixed
     *
     * @tag filter
     * @static
     */
	static public function getAlpha($value)
	{
		if (Inspekt::isArrayOrArrayObject($value)) {
			return Inspekt::_walkArray($value, 'getAlpha');
		} else {
			return preg_replace('/[^[:alpha:]]/', '', $value);
		}
	}

	/**
     * Returns only the alphabetic characters and digits in value.
     *
     * @param mixed $value
     * @return mixed
     *
     * @tag filter
     * @static
     * 
     * @assert('1)@*(&UR)HQ)W(*(HG))') === '1URHQWHG'
     */
	static public function getAlnum($value)
	{
		if (Inspekt::isArrayOrArrayObject($value)) {
			return Inspekt::_walkArray($value, 'getAlnum');
		} else {
			return preg_replace('/[^[:alnum:]]/', '', $value);
		}
	}
	 
	/**
     * Returns only the digits in value.
     *
     * @param mixed $value
     * @return mixed
     *
     * @tag filter
     * @static
     * 
     * @assert('1)@*(&UR)HQ)56W(*(HG))') === '156'
     */
	static public function getDigits($value)
	{
		if (Inspekt::isArrayOrArrayObject($value)) {
			return Inspekt::_walkArray($value, 'getDigits');
		} else {
			return preg_replace('/[^[:digit:]]/', '', $value);
		}
	}



	/**
     * Returns dirname(value).
     *
     * @param mixed $value
     * @return mixed
     *
     * @tag filter
     * @static
     * 
     * @assert('/usr/lib/php/Pear.php') === '/usr/lib/php'
     */
	static public function getDir($value)
	{
		if (Inspekt::isArrayOrArrayObject($value)) {
			return Inspekt::_walkArray($value, 'getDir');
		} else {
			return dirname($value);
		}
	}

	/**
     * Returns (int) value.
     *
     * @param mixed $value
     * @return int
     *
     * @tag filter
     * 
     * @assert('1)45@*(&UR)HQ)W.0000(*(HG))') === 1
     * @assert('A1)45@*(&UR)HQ)W.0000(*(HG))') === 0
     */
	static public function getInt($value)
	{
		if (Inspekt::isArrayOrArrayObject($value)) {
			return Inspekt::_walkArray($value, 'getInt');
		} else {
			return (int) $value;
		}
	}

	/**
     * Returns realpath(value).
     *
     * @param mixed $value
     * @return mixed
     *
     * @tag filter
     */
	static public function getPath($value)
	{
		if (Inspekt::isArrayOrArrayObject($value)) {
			return Inspekt::_walkArray($value, 'getPath');
		} else {
			return realpath($value);
		}
	}
	
	
	/**
	 * Returns the value encoded as ROT13 (or decoded, if already was ROT13)
	 * 
	 * @param mixed $value
	 * @return mixed 
	 * 
	 * @link http://php.net/manual/en/function.str-rot13.php
	 */
	static public function getROT13($value)
	{
		if (Inspekt::isArrayOrArrayObject($value)) {
			return Inspekt::_walkArray($value, 'getROT13');
		} else {
			return str_rot13($value);
		}		
	}
	

	/**
     * Returns TRUE if every character is alphabetic or a digit,
     * FALSE otherwise.
     *
     * @param mixed $value
     * @return boolean
     *
     * @tag validator
     * 
     * @assert('NCOFWIERNVOWIEBHV12047057y0650ytg0314') === true
     * @assert('NCOFWIERNVOWIEBHV2@12047057y0650ytg0314') === false
     * @assert('funkatron') === true
     * @assert('funkatron_user') === false
     * @assert('funkatron-user') === false
     * @assert('_funkatronuser') === false
     */
	static public function isAlnum($value)
	{
		return ctype_alnum($value);
	}

	/**
     * Returns TRUE if every character is alphabetic, FALSE
     * otherwise.
     *
     * @param mixed $value
     * @return boolean
     *
     * @tag validator
     * 
     * @assert('NCOFWIERNVOWIEBHV12047057y0650ytg0314') === false
     * @assert('NCOFWIERNVOWIEBHV2@12047057y0650ytg0314') === false
     * @assert('funkatron') === true
     * @assert('funkatron_user') === false
     * @assert('funkatron-user') === false
     * @assert('_funkatronuser') === false
     */
	static public function isAlpha($value)
	{
		return ctype_alpha($value);
	}

	/**
     * Returns TRUE if value is greater than or equal to $min and less
     * than or equal to $max, FALSE otherwise. If $inc is set to
     * FALSE, then the value must be strictly greater than $min and
     * strictly less than $max.
     *
     * @param mixed $value
     * @param mixed $min
     * @param mixed $max
     * @return boolean
     *
     * @tag validator
     * 
     * @assert(12, 0, 12) === TRUE
     * @assert(12, 0, 12, FALSE) === FALSE
     * @assert('f', 'a', 'm', FALSE) === TRUE
     * @assert('p', 'a', 'm', FALSE) === FALSE
     * 
     * 
     */
	static public function isBetween($value, $min, $max, $inc = TRUE)
	{
		if ($value > $min &&
		$value < $max) {
			return TRUE;
		}

		if ($inc &&
		$value >= $min &&
		$value <= $max) {
			return TRUE;
		}

		return FALSE;
	}

	/**
     * Returns TRUE if it is a valid credit card number format. The
     * optional second argument allows developers to indicate the
     * type.
     *
     * @param mixed $value
     * @param mixed $type
     * @return boolean
     *
     * @tag validator
     */
	static public function isCcnum($value, $type = NULL)
	{
		/**
         * @todo Type-specific checks
         */
		if (isset($type)) {
			trigger_error('Type-specific cc checks are not yet supported');
		}


		$value = self::getDigits($value);
		$length = strlen($value);

		if ($length < 13 || $length > 19) {
			return FALSE;
		}

		$sum = 0;
		$weight = 2;

		for ($i = $length - 2; $i >= 0; $i--) {
			$digit = $weight * $value[$i];
			$sum += floor($digit / 10) + $digit % 10;
			$weight = $weight % 2 + 1;
		}

		$mod = (10 - $sum % 10) % 10;

		return ($mod == $value[$length - 1]);
	}

	/**
     * Returns TRUE if value is a valid date, FALSE otherwise. The
     * date is required to be in ISO 8601 format.
     *
     * @param mixed $value
     * @return boolean
     *
     * @tag validator
     * 
     * @assert('2009-06-30') === TRUE
     * @assert('2009-06-31') === FALSE
     * @assert('2009-6-30') === TRUE
     * @assert('2-6-30') === TRUE
     */
	static public function isDate($value)
	{
		list($year, $month, $day) = sscanf($value, '%d-%d-%d');
		
		return checkdate($month, $day, $year);
	}

	/**
     * Returns TRUE if every character is a digit, FALSE otherwise.
     * This is just like isInt(), except there is no upper limit.
     *
     * @param mixed $value
     * @return boolean
     *
     * @tag validator
     * 
     * @assert('1029438750192730t91740987023948') === FALSE
     * @assert('102943875019273091740987023948') === TRUE
     * @assert(102943875019273091740987023948) === FALSE
     */
	static public function isDigits($value)
	{
		return ctype_digit((string) $value);
	}

	/**
     * Returns TRUE if value is a valid email format, FALSE otherwise.
     *
     * @param string $value
     * @return boolean
     * @see http://www.regular-expressions.info/email.html
     * @see ISPK_EMAIL_VALID
     *
     * @tag validator
     * 
     * @assert('coj@poop.com') === TRUE
     * @assert('coj+booboo@poop.com') === TRUE
     * @assert('coj!booboo@poop.com') === FALSE
     * @assert('@poop.com') === FALSE
     * @assert('a@b') === FALSE
     * @assert('webmaster') === FALSE
     */
	static public function isEmail($value)
	{
		return (bool) preg_match(ISPK_EMAIL_VALID, $value);
	}

	/**
     * Returns TRUE if value is a valid float value, FALSE otherwise.
     *
     * @param string $value
     * @return boolean
     *
     * @assert(10244578109.234451) === TRUE
     * @assert('10244578109.234451') === FALSE
     * @assert('10,244,578,109.234451') === FALSE
     * 
     * @tag validator
     */
	static public function isFloat($value)
	{
		$locale = localeconv();
		$value = str_replace($locale['decimal_point'], '.', $value);
		$value = str_replace($locale['thousands_sep'], '', $value);

		return (strval(floatval($value)) == $value);
	}

	/**
     * Returns TRUE if value is greater than $min, FALSE otherwise.
     *
     * @param mixed $value
     * @param mixed $min
     * @return boolean
     *
     * @tag validator
     * @static
     * 
     * @assert(5, 0) === TRUE
     * @assert(2, 10) === FALSE
     * @assert('b', 'a') === TRUE
     * @assert('a', 'b') === FALSE
     */
	static public function isGreaterThan($value, $min)
	{
		return ($value > $min);
	}

	/**
     * Returns TRUE if value is a valid hexadecimal format, FALSE
     * otherwise.
     *
     * @param mixed $value
     * @return boolean
     *
     * @tag validator
     * @static
     * 
     * @assert('6F') === TRUE
     * @assert('F6') === TRUE
     * 
     */
	static public function isHex($value)
	{
		return ctype_xdigit($value);
	}

	/**
     * Returns TRUE if value is a valid hostname, FALSE otherwise.
     * Depending upon the value of $allow, Internet domain names, IP
     * addresses, and/or local network names are considered valid.
     * The default is HOST_ALLOW_ALL, which considers all of the
     * above to be valid.
     *
     * @param mixed $value
     * @param integer $allow bitfield for ISPK_HOST_ALLOW_DNS, ISPK_HOST_ALLOW_IP, ISPK_HOST_ALLOW_LOCAL
     * @return boolean
     *
     * @tag validator
     * @static
     */
	static public function isHostname($value, $allow = ISPK_HOST_ALLOW_ALL)
	{
		if (!is_numeric($allow) || !is_int($allow)) {
			user_error('Illegal value for $allow; expected an integer', E_USER_WARNING);
		}

		if ($allow < ISPK_HOST_ALLOW_DNS || ISPK_HOST_ALLOW_ALL < $allow) {
			user_error('Illegal value for $allow; expected integer between ' . ISPK_HOST_ALLOW_DNS . ' and ' . ISPK_HOST_ALLOW_ALL, E_USER_WARNING);
		}

		// determine whether the input is formed as an IP address
		$status = self::isIp($value);

		// if the input looks like an IP address
		if ($status) {
			// if IP addresses are not allowed, then fail validation
			if (($allow & ISPK_HOST_ALLOW_IP) == 0) {
				return FALSE;
			}

			// IP passed validation
			return TRUE;
		}

		// check input against domain name schema
		$status = @preg_match('/^(?:[^\W_]((?:[^\W_]|-){0,61}[^\W_])?\.)+[a-zA-Z]{2,6}\.?$/', $value);
		if ($status === false) {
			user_error('Internal error: DNS validation failed', E_USER_WARNING);
		}

		// if the input passes as an Internet domain name, and domain names are allowed, then the hostname
		// passes validation
		if ($status == 1 && ($allow & ISPK_HOST_ALLOW_DNS) != 0) {
			return TRUE;
		}

		// if local network names are not allowed, then fail validation
		if (($allow & ISPK_HOST_ALLOW_LOCAL) == 0) {
			return FALSE;
		}

		// check input against local network name schema; last chance to pass validation
		$status = @preg_match('/^(?:[^\W_](?:[^\W_]|-){0,61}[^\W_]\.)*(?:[^\W_](?:[^\W_]|-){0,61}[^\W_])\.?$/',
		$value);
		if ($status === FALSE) {
			user_error('Internal error: local network name validation failed', E_USER_WARNING);
		}

		if ($status == 0) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
     * Returns TRUE if value is a valid integer value, FALSE otherwise.
     *
     * @param string|array $value
     * @return boolean
     *
     * @tag validator
     * @static
     * 
     * @todo better handling of diffs b/t 32-bit and 64-bit
     */
	static public function isInt($value)
	{
		$locale = localeconv();

		$value = str_replace($locale['decimal_point'], '.', $value);
		$value = str_replace($locale['thousands_sep'], '', $value);
				
		$is_valid = (
			is_numeric($value)  // Must be able to be converted to a number
			&& preg_replace("/^-?([0-9]+)$/", "", $value) == ""  // Must be an integer (no floats or e-powers)
			&& bccomp($value, "-9223372036854775807") >= 0  // Must be greater than than min of 64-bit
			&& bccomp($value, "9223372036854775807") <= 0  // Must be less than max of 64-bit
		);
		if (!$is_valid) {
			return false;
		} else {
			return true;
		}
		



		
		// return (strval(intval($value)) === $value);
	}

	/**
     * Returns TRUE if value is a valid IP format, FALSE otherwise.
     *
     * @param mixed $value
     * @return boolean
     *
     * @tag validator
     * @static
     */
	static public function isIp($value)
	{
		return (bool) ip2long($value);
	}

	/**
     * Returns TRUE if value is less than $max, FALSE otherwise.
     *
     * @param mixed $value
     * @param mixed $max
     * @return boolean
     *
     * @tag validator
     * @static
     */
	static public function isLessThan($value, $max)
	{
		return ($value < $max);
	}

	/**
     * Returns TRUE if value is one of $allowed, FALSE otherwise.
     *
     * @param mixed $value
     * @param array|string $allowed
     * @return boolean
     *
     * @tag validator
     * @static
     */
	static public function isOneOf($value, $allowed)
	{
		/**
         * @todo: Consider allowing a string for $allowed, where each
         * character in the string is an allowed character in the
         * value.
         */

		if (is_string($allowed)) {
			$allowed = str_split($allowed);
		}

		return in_array($value, $allowed);
	}

	/**
     * Returns TRUE if value is a valid phone number format, FALSE
     * otherwise. The optional second argument indicates the country.
     * This method requires that the value consist of only digits.
     *
     * @param mixed $value
     * @return boolean
     *
     * @tag validator
     * @static
     */
	static public function isPhone($value, $country = 'US')
	{
		if (!ctype_digit($value)) {
			return FALSE;
		}

		switch ($country)
		{
			case 'US':
				if (strlen($value) != 10) {
					return FALSE;
				}

				$areaCode = substr($value, 0, 3);

				$areaCodes = array(201, 202, 203, 204, 205, 206, 207, 208,
				209, 210, 212, 213, 214, 215, 216, 217,
				218, 219, 224, 225, 226, 228, 229, 231,
				234, 239, 240, 242, 246, 248, 250, 251,
				252, 253, 254, 256, 260, 262, 264, 267,
				268, 269, 270, 276, 281, 284, 289, 301,
				302, 303, 304, 305, 306, 307, 308, 309,
				310, 312, 313, 314, 315, 316, 317, 318,
				319, 320, 321, 323, 325, 330, 334, 336,
				337, 339, 340, 345, 347, 351, 352, 360,
				361, 386, 401, 402, 403, 404, 405, 406,
				407, 408, 409, 410, 412, 413, 414, 415,
				416, 417, 418, 419, 423, 424, 425, 430,
				432, 434, 435, 438, 440, 441, 443, 445,
				450, 469, 470, 473, 475, 478, 479, 480,
				484, 501, 502, 503, 504, 505, 506, 507,
				508, 509, 510, 512, 513, 514, 515, 516,
				517, 518, 519, 520, 530, 540, 541, 555,
				559, 561, 562, 563, 564, 567, 570, 571,
				573, 574, 580, 585, 586, 600, 601, 602,
				603, 604, 605, 606, 607, 608, 609, 610,
				612, 613, 614, 615, 616, 617, 618, 619,
				620, 623, 626, 630, 631, 636, 641, 646,
				647, 649, 650, 651, 660, 661, 662, 664,
				670, 671, 678, 682, 684, 700, 701, 702,
				703, 704, 705, 706, 707, 708, 709, 710,
				712, 713, 714, 715, 716, 717, 718, 719,
				720, 724, 727, 731, 732, 734, 740, 754,
				757, 758, 760, 763, 765, 767, 769, 770,
				772, 773, 774, 775, 778, 780, 781, 784,
				785, 786, 787, 800, 801, 802, 803, 804,
				805, 806, 807, 808, 809, 810, 812, 813,
				814, 815, 816, 817, 818, 819, 822, 828,
				829, 830, 831, 832, 833, 835, 843, 844,
				845, 847, 848, 850, 855, 856, 857, 858,
				859, 860, 863, 864, 865, 866, 867, 868,
				869, 870, 876, 877, 878, 888, 900, 901,
				902, 903, 904, 905, 906, 907, 908, 909,
				910, 912, 913, 914, 915, 916, 917, 918,
				919, 920, 925, 928, 931, 936, 937, 939,
				940, 941, 947, 949, 951, 952, 954, 956,
				959, 970, 971, 972, 973, 978, 979, 980,
				985, 989);

				return in_array($areaCode, $areaCodes);
				break;
			default:
				user_error('isPhone() does not yet support this country.', E_USER_WARNING);
				return FALSE;
				break;
		}
	}

	/**
     * Returns TRUE if value matches $pattern, FALSE otherwise. Uses
     * preg_match() for the matching.
     *
     * @param mixed $value
     * @param mixed $pattern
     * @return mixed
     *
     * @tag validator
     * @static
     */
	static public function isRegex($value, $pattern)
	{
		return (bool) preg_match($pattern, $value);
	}


	/**
	 * Enter description here...
	 *
	 * @param string $value
	 * @param integer $mode
	 * @return boolean
	 *
	 * @link http://www.ietf.org/rfc/rfc2396.txt
	 *
	 * @tag validator
	 * @static
	 */
	static public function isUri($value, $mode=ISPK_URI_ALLOW_COMMON)
	{
		/**
         * @todo
         */
		$regex = '';
		switch ($mode) {

			// a common absolute URI: ftp, http or https
			case ISPK_URI_ALLOW_COMMON:

				$regex .= '&';
				$regex .= '^(ftp|http|https):';					// protocol
				$regex .= '(//)';								// authority-start
				$regex .= '([-a-z0-9/~;:@=+$,.!*()\']+@)?';		// userinfo
				$regex .= '(';
					$regex .= '((?:[^\W_]((?:[^\W_]|-){0,61}[^\W_])?\.)+[a-zA-Z]{2,6}\.?)';		// domain name
				$regex .= '|';
					$regex .= '([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?(\.[0-9]{1,3})?)';	// OR ipv4
				$regex .= ')';
				$regex .= '(:([0-9]*))?';						// port
				$regex .= '(/((%[0-9a-f]{2}|[-_a-z0-9/~;:@=+$,.!*()\'\&]*)*)/?)?';	// path
				$regex .= '(\?[^#]*)?';							// query
				$regex .= '(#([-a-z0-9_]*))?';					// anchor (fragment)
				$regex .= '$&i';
				//echo "<pre>"; echo print_r($regex, true); echo "</pre>\n";

				break;

			case ISPK_URI_ALLOW_ABSOLUTE:

				user_error('isUri() for ISPK_URI_ALLOW_ABSOLUTE has not been implemented.', E_USER_WARNING);
				return FALSE;

//				$regex .= '&';
//				$regex .= '^(ftp|http|https):';					// protocol
//				$regex .= '(//)';								// authority-start
//				$regex .= '([-a-z0-9/~;:@=+$,.!*()\']+@)?';		// userinfo
//				$regex .= '(';
//					$regex .= '((?:[^\W_]((?:[^\W_]|-){0,61}[^\W_])?\.)+[a-zA-Z]{2,6}\.?)';		// domain name
//				$regex .= '|';
//					$regex .= '([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?(\.[0-9]{1,3})?)';	// OR ipv4
//				$regex .= ')';
//				$regex .= '(:([0-9]*))?';						// port
//				$regex .= '(/((%[0-9a-f]{2}|[-a-z0-9/~;:@=+$,.!*()\'\&]*)*)/?)?';	// path
//				$regex .= '(\?[^#]*)?';							// query
//				$regex .= '(#([-a-z0-9_]*))?';					// anchor (fragment)
//				$regex .= '$&i';
				//echo "<pre>"; echo print_r($regex, true); echo "</pre>\n";

				break;

		}
		$result = preg_match($regex, $value, $subpatterns);

		if ($result === 1) {
			return true;
		} else {
			return false;
		}
	}

	/**
     * Returns TRUE if value is a valid US ZIP, FALSE otherwise.
     *
     * @param mixed $value
     * @return boolean
     *
     * @tag validator
     * @static
     */
	static public function isZip($value)
	{
		return (bool) preg_match('/(^\d{5}$)|(^\d{5}-\d{4}$)/', $value);
	}

	/**
     * Returns value with all tags removed.
     * 
     * This will utilize the PHP Filter extension if available
     *
     * @param mixed $value
     * @return mixed
     *
     * @tag filter
     * @static
     */
	static public function noTags($value)
	{
		if (Inspekt::isArrayOrArrayObject($value)) {
			return Inspekt::_walkArray($value, 'noTags');
		} else {
			if (Inspekt::useFilterExt()) {
				return filter_var($value, FILTER_SANITIZE_STRING);
			} else {
				return strip_tags($value);
			}
		}
	}
	
	/**
	 * returns value with tags stripped and the chars '"&<> and all ascii chars under 32 encoded as html entities
	 * 
	 * This will utilize the PHP Filter extension if available
	 * 
	 * @param mixed $value
	 * @return @mixed
	 * 
	 * @tag filter
	 * 
	 */
	static public function noTagsOrSpecial($value)
	{
		if (Inspekt::isArrayOrArrayObject($value)) {
			return Inspekt::_walkArray($value, 'noTagsOrSpecial');
		} else {
			if (Inspekt::useFilterExt()) {
				$newval = filter_var($value, FILTER_SANITIZE_STRING);
				$newval = filter_var($newval, FILTER_SANITIZE_SPECIAL_CHARS);
				return $newval;
			} else {
				$newval = strip_tags($value);
				$newval = htmlspecialchars($newval, ENT_QUOTES, 'UTF-8'); // for sake of simplicity and safety we assume UTF-8

				/*
					convert low ascii chars to entities
				*/
				$newval = str_split($newval);
				for ($i=0; $i < count($newval); $i++) { 
					$ascii_code = ord($newval[$i]);
					if ($ascii_code < 32) {
						$newval[$i] = "&#{$ascii_code};";
					}
				}
				$newval = implode($newval);

				return $newval;
			}
		}
	}

	/**
     * Returns basename(value).
     *
     * @param mixed $value
     * @return mixed
     *
     * @tag filter
     * @static
     */
	static public function noPath($value)
	{
		if (Inspekt::isArrayOrArrayObject($value)) {
			return Inspekt::_walkArray($value, 'noPath');
		} else {
			return basename($value);
		}
	}
	
	
	/**
	 * Escapes the value given with mysql_real_escape_string
	 * 
	 * @param mixed $value
	 * @param resource $conn the mysql connection. If none is given, it will use the last link opened, per behavior of mysql_real_escape_string
	 * @return mixed
	 * 
	 * @link http://php.net/manual/en/function.mysql-real-escape-string.php
	 * 
	 * @tag filter
	 */
	static public function escMySQL($value, $conn=null) {
		
		static $connection;
		$connection = $conn;
		
		if (Inspekt::isArrayOrArrayObject($value)) {
			return Inspekt::_walkArray($value, 'escMySQL');
		} else {
			if (isset($connection)) {
				return mysql_real_escape_string($value, $connection);
			} else {
				return mysql_real_escape_string($value);
			}
			
		}
	}

	/**
	 * Escapes the value given with pg_escape_string
	 * 
	 * If the data is for a column of the type bytea, use Inspekt::escPgSQLBytea()
	 * 
	 * @param mixed $value
	 * @param resource $conn the postgresql connection. If none is given, it will use the last link opened, per behavior of pg_escape_string
	 * @return mixed
	 * 
	 * @link http://php.net/manual/en/function.pg-escape-string.php
	 */
	static public function escPgSQL($value, $conn=null) {
		
		static $connection;
		$connection = $conn;
		
		if (Inspekt::isArrayOrArrayObject($value)) {
			return Inspekt::_walkArray($value, 'escPgSQL');
		} else {
			if (isset($connection)) {
				return pg_escape_string($connection, $value);
			} else {
				return pg_escape_string($value);
			}
		}
	}


	/**
	 * Escapes the value given with pg_escape_bytea
	 * 
	 * @param mixed $value
	 * @param resource $conn the postgresql connection. If none is given, it will use the last link opened, per behavior of pg_escape_bytea
	 * @return mixed
	 * 
	 * @link http://php.net/manual/en/function.pg-escape-bytea.php
	 */
	static public function escPgSQLBytea($value, $conn=null) {
		static $connection;
		$connection = $conn;
		
		if (Inspekt::isArrayOrArrayObject($value)) {
			return Inspekt::_walkArray($value, 'escPgSQL');
		} else {
			if (isset($connection)) {
				return pg_escape_bytea($connection, $value); 
			} else {
				return pg_escape_bytea($value);
			}
		}

	}

}

/**
 * Error handling for Inspekt
 *
 * @package Inspekt
 *
 */
class Inspekt_Error {

	/**
	 * Constructor
	 *
	 * @return Inspekt_Error
	 */
	function Inspekt_Error() {

	}

	/**
	 * Raises an error.  In >= PHP5, this will throw an exception. In PHP4,
	 * this will trigger a user error.
	 *
	 * @param string $msg
	 * @param integer $type  One of the PHP Error Constants (E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE)
	 *
	 * @link http://www.php.net/manual/en/ref.errorfunc.php#errorfunc.constants
	 * @todo support PHP5 exceptions without causing a syntax error.  Probably should use factory pattern and instantiate a different class depending on PHP version
	 *
	 * @static
	 */
	function raiseError($msg, $type=E_USER_WARNING) {
		/*if (version_compare( PHP_VERSION, '5', '<' )) {
			Inspekt_Error::raiseErrorPHP4($msg, $type);
		} else {
			throw new Exception($msg, $type);
		}*/

		Inspekt_Error::raiseErrorPHP4($msg, $type);
	}

	/**
	 * Triggers a user error for PHP4-compatibility
	 *
	 * @param string $msg
	 * @param integer $type  One of the PHP Error Constants (E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE)
	 *
	 * @static
	 */
	function raiseErrorPHP4 ($msg, $type=NULL) {

		if (isset($type)) {
			trigger_error($msg);
		} else {
			trigger_error($msg, $type);
		}
	}
}


/**
 * @package Inspekt
 */
class Inspekt_Cage implements IteratorAggregate, ArrayAccess, Countable {
/**
 * {@internal The raw source data.  Although tempting, NEVER EVER
 * EVER access the data directly using this property!}}
 *
 * Don't try to access this.  ever.  Now that we're safely on PHP5, we'll
 * enforce this with the "protected" keyword.
 *
 * @var array
 */
	protected $_source = NULL;

	/**
	 * where we store user-defined methods
	 *
	 * @var array
	 */
	public $_user_accessors = array();

	/**
	 * the holding property for autofilter config
	 *
	 * @var array
	 */
	public $_autofilter_conf = NULL;

	/**
	 *
	 * @var HTMLPurifer
	 */
	public $purifier = NULL;


	/**
	 *
	 * @return Inspekt_Cage
	 */
	public function Inspekt_Cage() {
		// placeholder -- we're using a factory here
	}



	/**
	 * Takes an array and wraps it inside an object.  If $strict is not set to
	 * FALSE, the original array will be destroyed, and the data can only be
	 * accessed via the object's accessor methods
	 *
	 * @param array $source
	 * @param string $conf_file
	 * @param string $conf_section
	 * @param boolean $strict
	 * @return Inspekt_Cage
	 *
	 * @static
	 */
	static public function Factory(&$source, $conf_file = NULL, $conf_section = NULL, $strict = TRUE) {

		if (!is_array($source)) {
			user_error('$source '.$source.' is not an array', E_USER_WARNING);
		}

		$cage = new Inspekt_Cage();
		$cage->_setSource($source);
		$cage->_parseAndApplyAutoFilters($conf_file, $conf_section);

		if ($strict) {
			$source = NULL;
		}

		return $cage;
	}


	/**
	 * {@internal we use this to set the data array in Factory()}}
	 *
	 * @see Factory()
	 * @param array $newsource
	 */
	private function _setSource(&$newsource) {

		$this->_source = Inspekt::convertArrayToArrayObject($newsource);

	}


	/**
	 * Returns an iterator for looping through an ArrayObject.
	 *
	 * @access public
	 * @return ArrayIterator
	 */
	public function getIterator() {
		return $this->_source->getIterator();
	}


	/**
	 * Sets the value at the specified $offset to value$
	 * in $this->_source.
	 *
	 * @param mixed $offset
	 * @param mixed $value
	 * @access public
	 * @return void
	 */
	public function offsetSet($offset, $value) {
		$this->_source->offsetSet($offset, $value);
	}


	/**
	 * Returns whether the $offset exists in $this->_source.
	 *
	 * @param mixed $offset
	 * @access public
	 * @return bool
	 */
	public function offsetExists($offset) {
		return $this->_source->offsetExists($offset);
	}


	/**
	 * Unsets the value in $this->_source at $offset.
	 *
	 * @param mixed $offset
	 * @access public
	 * @return void
	 */
	public function offsetUnset($offset) {
		$this->_source->offsetUnset($offset);
	}


	/**
	 * Returns the value at $offset from $this->_source.
	 *
	 * @param mixed $offset
	 * @access public
	 * @return void
	 */
	public function offsetGet($offset) {
		return $this->_source->offsetGet($offset);
	}


	/**
	 * Returns the number of elements in $this->_source.
	 *
	 * @access public
	 * @return int
	 */
	public function count() {
		return $this->_source->count();
	}


	/**
	 * Load the HTMLPurifier library and instantiate the object
	 * @param string $path the full path to the HTMLPurifier.auto.php base file. Optional if HTMLPurifier is already in your include_path
	 */
	public function loadHTMLPurifier($path=null, $opts=null) {
		if (isset($path)) {
			include_once($path);
		} else {
			include_once('HTMLPurifier.auto.php');
		}

		if (isset($opts) && is_array($opts)) {
			$config = $this->_buildHTMLPurifierConfig($opts);
		} else {
			$config = null;
		}

		$this->purifier = new HTMLPurifier($config);
	}


	/**
	 *
	 * @param HTMLPurifer $pobj an HTMLPurifer Object
	 */
	public function setHTMLPurifier($pobj) {
		$this->purifier = $pobj;
	}

	/**
	 * @return HTMLPurifier
	 */
	public function getHTMLPurifier() {
		return $this->purifier;
	}


	protected function _buildHTMLPurifierConfig($opts) {
		$config = HTMLPurifier_Config::createDefault();
		foreach ($opts as $key=>$val) {
			$config->set($key, $val);
		}
		return $config;
	}


	protected function _parseAndApplyAutoFilters($conf_file, $conf_section) {
		if (isset($conf_file)) {
			$conf = parse_ini_file($conf_file, true);
			if ($conf_section) {
				if (isset($conf[$conf_section])) {
					$this->_autofilter_conf = $conf[$conf_section];
				}
			} else {
				$this->_autofilter_conf = $conf;
			}

			$this->_applyAutoFilters();
		}
	}


	protected function _applyAutoFilters() {

		if ( isset($this->_autofilter_conf) && is_array($this->_autofilter_conf)) {

			foreach($this->_autofilter_conf as $key=>$filters) {

			// get universal filter key
				if ($key == '*') {

				// get filters for this key
					$uni_filters = explode(',', $this->_autofilter_conf[$key]);
					array_walk($uni_filters, 'trim');

					// apply uni filters
					foreach($uni_filters as $this_filter) {
						foreach($this->_source as $key=>$val) {
							$this->_source[$key] = $this->$this_filter($key);
						}
					}
				//echo "<pre>UNI FILTERS"; echo var_dump($this->_source); echo "</pre>\n";

				} elseif($val == $this->keyExists($key)) {

				// get filters for this key
					$filters = explode(',', $this->_autofilter_conf[$key]);
					array_walk($filters, 'trim');

					// apply filters
					foreach($filters as $this_filter) {
						$this->_setValue($key, $this->$this_filter($key));
					}
				//echo "<pre> Filter $this_filter/$key: "; echo var_dump($this->_source); echo "</pre>\n";

				}
			}
		}
	}



	public function __call($name, $args) {
		if (in_array($name, $this->_user_accessors) ) {

			$acc = new $name($this, $args);
			/*
				this first argument should always be the key we're accessing
			*/
			return $acc->run($args[0]);

		} else {
			trigger_error("The accessor $name does not exist and is not registered", E_USER_ERROR);
			return false;
		}

	}

	/**
	 * This method lets the developer add new accessor methods to a cage object
	 * Note that calling these will be quite a bit slower, because we have to
	 * use call_user_func()
	 *
	 * The dev needs to define a procedural function like so:
	 *
	 * <code>
	 * function foo_bar($cage_object, $arg2, $arg3, $arg4, $arg5...) {
	 *    ...
	 * }
	 * </code>
	 *
	 * @param string $method_name
	 * @return void
	 * @author Ed Finkler
	 */
	public function addAccessor($accessor_name) {
		$this->_user_accessors[] = $accessor_name;
	}


	/**
	 * Returns only the alphabetic characters in value.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag filter
	 */
	public function getAlpha($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		return Inspekt::getAlpha($this->_getValue($key));
	}

	/**
	 * Returns only the alphabetic characters and digits in value.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag filter
	 */
	public function getAlnum($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		return Inspekt::getAlnum($this->_getValue($key));
	}

	/**
	 * Returns only the digits in value. This differs from getInt().
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag filter
	 */
	public function getDigits($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		return Inspekt::getDigits($this->_getValue($key));
	}

	/**
	 * Returns dirname(value).
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag filter
	 */
	public function getDir($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		return Inspekt::getDir($this->_getValue($key));
	}

	/**
	 * Returns (int) value.
	 *
	 * @param mixed $key
	 * @return int
	 *
	 * @tag filter
	 */
	public function getInt($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		return Inspekt::getInt($this->_getValue($key));
	}

	/**
	 * Returns realpath(value).
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag filter
	 */
	public function getPath($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		return Inspekt::getPath($this->_getValue($key));
	}


	/**
	 * Returns ROT13-encoded version
	 *
	 * @param string $key
	 * @return mixed
	 * @tag hash
	 */
	public function getROT13($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		return Inspekt::getROT13($this->_getValue($key));
	}
	

	/**
	 * This returns the value of the given key passed through the HTMLPurifer
	 * object, if it is instantiated with Inspekt_Cage::loadHTMLPurifer
	 *
	 * @param string $key
	 * @return mixed purified HTML version of input
	 * @tag filter
	 */
	public function getPurifiedHTML($key) {
		if (!isset($this->purifier)) {
			trigger_error("HTMLPurifier was not loaded", E_USER_WARNING);
			return false;
		}

		if (!$this->keyExists($key)) {
			return false;
		}
		$val = $this->_getValue($key);
		if (Inspekt::isArrayOrArrayObject($val)) {
			return $this->purifier->purifyArray($val);
		} else {
			return $this->purifier->purify($val);
		}
	}


	/**
	 * Returns value.
	 *
	 * @param string $key
	 * @return mixed
	 *
	 * @tag filter
	 */
	public function getRaw($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		return $this->_getValue($key);
	}

	/**
	 * Returns value if every character is alphabetic or a digit,
	 * FALSE otherwise.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testAlnum($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isAlnum($this->_getValue($key))) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if every character is alphabetic, FALSE
	 * otherwise.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testAlpha($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isAlpha($this->_getValue($key))) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if it is greater than or equal to $min and less
	 * than or equal to $max, FALSE otherwise. If $inc is set to
	 * FALSE, then the value must be strictly greater than $min and
	 * strictly less than $max.
	 *
	 * @param mixed $key
	 * @param mixed $min
	 * @param mixed $max
	 * @param boolean $inc
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testBetween($key, $min, $max, $inc = TRUE) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isBetween($this->_getValue($key), $min, $max, $inc)) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if it is a valid credit card number format. The
	 * optional second argument allows developers to indicate the
	 * type.
	 *
	 * @param mixed $key
	 * @param mixed $type
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testCcnum($key, $type = NULL) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isCcnum($this->_getValue($key), $type)) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns $value if it is a valid date, FALSE otherwise. The
	 * date is required to be in ISO 8601 format.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testDate($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isDate($this->_getValue($key))) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if every character is a digit, FALSE otherwise.
	 * This is just like isInt(), except there is no upper limit.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testDigits($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isDigits($this->_getValue($key))) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if it is a valid email format, FALSE otherwise.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testEmail($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isEmail($this->_getValue($key))) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if it is a valid float value, FALSE otherwise.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testFloat($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isFloat($this->_getValue($key))) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if it is greater than $min, FALSE otherwise.
	 *
	 * @param mixed $key
	 * @param mixed $min
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testGreaterThan($key, $min = NULL) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isGreaterThan($this->_getValue($key), $min)) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if it is a valid hexadecimal format, FALSE
	 * otherwise.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testHex($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isHex($this->_getValue($key))) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if it is a valid hostname, FALSE otherwise.
	 * Depending upon the value of $allow, Internet domain names, IP
	 * addresses, and/or local network names are considered valid.
	 * The default is HOST_ALLOW_ALL, which considers all of the
	 * above to be valid.
	 *
	 * @param mixed $key
	 * @param integer $allow bitfield for HOST_ALLOW_DNS, HOST_ALLOW_IP, HOST_ALLOW_LOCAL
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testHostname($key, $allow = ISPK_HOST_ALLOW_ALL) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isHostname($this->_getValue($key), $allow)) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if it is a valid integer value, FALSE otherwise.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testInt($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isInt($this->_getValue($key))) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if it is a valid IP format, FALSE otherwise.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testIp($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isIp($this->_getValue($key))) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if it is less than $max, FALSE otherwise.
	 *
	 * @param mixed $key
	 * @param mixed $max
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testLessThan($key, $max = NULL) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isLessThan($this->_getValue($key), $max)) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if it is one of $allowed, FALSE otherwise.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testOneOf($key, $allowed = NULL) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isOneOf($this->_getValue($key), $allowed)) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if it is a valid phone number format, FALSE
	 * otherwise. The optional second argument indicates the country.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testPhone($key, $country = 'US') {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isPhone($this->_getValue($key), $country)) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if it matches $pattern, FALSE otherwise. Uses
	 * preg_match() for the matching.
	 *
	 * @param mixed $key
	 * @param mixed $pattern
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testRegex($key, $pattern = NULL) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isRegex($this->_getValue($key), $pattern)) {
			return $this->_getValue($key);
		}

		return FALSE;
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $key
	 * @return unknown
	 *
	 * @tag validator
	 */
	public function testUri($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isUri($this->_getValue($key))) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value if it is a valid US ZIP, FALSE otherwise.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag validator
	 */
	public function testZip($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (Inspekt::isZip($this->_getValue($key))) {
			return $this->_getValue($key);
		}

		return FALSE;
	}

	/**
	 * Returns value with all tags removed.
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag filter
	 */
	public function noTags($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		return Inspekt::noTags($this->_getValue($key));
	}

	/**
	 * Returns basename(value).
	 *
	 * @param mixed $key
	 * @return mixed
	 *
	 * @tag filter
	 */
	public function noPath($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		return Inspekt::noPath($this->_getValue($key));
	}


	public function noTagsOrSpecial($key) {
		if (!$this->keyExists($key)) {
			return false;
		}
		return Inspekt::noTagsOrSpecial($this->_getValue($key));
	}



	public function escMySQL($key, $conn=null) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (isset($conn)) {
			return Inspekt::escMySQL($this->_getValue($key), $conn);
		} else {
			return Inspekt::escMySQL($this->_getValue($key));
		}

	}


	public function escPgSQL($key, $conn=null) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (isset($conn)) {
			return Inspekt::escPgSQL($this->_getValue($key), $conn);
		} else {
			return Inspekt::escPgSQL($this->_getValue($key));
		}

	}


	public function escPgSQLBytea($key, $conn=null) {
		if (!$this->keyExists($key)) {
			return false;
		}
		if (isset($conn)) {
			return Inspekt::escPgSQLBytea($this->_getValue($key), $conn);
		} else {
			return Inspekt::escPgSQLBytea($this->_getValue($key));
		}

	}





	/**
	 * Checks if a key exists
	 *
	 * @param mixed $key
	 * @param boolean $return_value  whether or not to return the value if key exists. defaults to FALSE.
	 * @return mixed
	 *
	 */
	public function keyExists($key, $return_value=false) {
		if (strpos($key, ISPK_ARRAY_PATH_SEPARATOR) !== FALSE) {
			$key = trim($key, ISPK_ARRAY_PATH_SEPARATOR);
			$keys = explode(ISPK_ARRAY_PATH_SEPARATOR, $key);
			return $this->_keyExistsRecursive($keys, $this->_source);
		} else {
			if ($exists = array_key_exists($key, $this->_source)) {
				if ($return_value) {
					return $this->_source[$key];
				} else {
					return $exists;
				}
			} else {
				return FALSE;
			}
			
		}
	}



	protected function _keyExistsRecursive($keys, $data_array) {
		$thiskey = current($keys);

		if (is_numeric($thiskey)) { // force numeric strings to be integers
			$thiskey = (int)$thiskey;
		}

		if (array_key_exists($thiskey, $data_array) ) {
			if (sizeof($keys) == 1) {
				return true;
			} elseif (is_object($data_array[$thiskey]) &&
				get_class($data_array[$thiskey]) === 'ArrayObject') {
				unset($keys[key($keys)]);
				return $this->_keyExistsRecursive($keys, $data_array[$thiskey]);
			}
		} else { // if any key DNE, return false
			return false;
		}
	}

	/**
	 * Retrieves a value from the _source array. This should NOT be called directly, but needs to be public
	 * for use by AccessorAbstract. Maybe a different approach should be considered
	 *
	 * @param string $key
	 * @return mixed
	 * @private
	 */
	public function _getValue($key) {
		if (strpos($key, ISPK_ARRAY_PATH_SEPARATOR)!== FALSE) {
			$key = trim($key, ISPK_ARRAY_PATH_SEPARATOR);
			$keys = explode(ISPK_ARRAY_PATH_SEPARATOR, $key);
			return $this->_getValueRecursive($keys, $this->_source);
		} else {
			return $this->_source[$key];
		}
	}


	
	protected function _getValueRecursive($keys, $data_array, $level=0) {
		$thiskey = current($keys);

		if (is_numeric($thiskey)) { // force numeric strings to be integers
			$thiskey = (int)$thiskey;
		}

		if (array_key_exists($thiskey, $data_array) ) {
			if (sizeof($keys) == 1) {
				return $data_array[$thiskey];
			} elseif (is_object($data_array[$thiskey]) &&
				get_class($data_array[$thiskey]) === 'ArrayObject') {
				if ($level < ISPK_RECURSION_MAX) {
					unset($keys[key($keys)]);
					return $this->_getValueRecursive($keys, $data_array[$thiskey], $level+1);
				} else {
					trigger_error('Inspekt recursion limit met', E_USER_WARNING);
					return false;
				}
			}
		} else { // if any key DNE, return false
			return false;
		}
	}


	/**
	 * Sets a value in the _source array
	 *
	 * @param mixed $key
	 * @param mixed $val
	 * @return mixed
	 */
	protected function _setValue($key, $val) {
		if (strpos($key, ISPK_ARRAY_PATH_SEPARATOR)!== FALSE) {
			$key = trim($key, ISPK_ARRAY_PATH_SEPARATOR);
			$keys = explode(ISPK_ARRAY_PATH_SEPARATOR, $key);
			return $this->_setValueRecursive($keys, $this->_source);
		} else {
			$this->_source[$key] = $val;
			return $this->_source[$key];
		}
	}


	protected function _setValueRecursive($keys, $val, $data_array, $level=0) {
		$thiskey = current($keys);

		if (is_numeric($thiskey)) { // force numeric strings to be integers
			$thiskey = (int)$thiskey;
		}

		if ( array_key_exists($thiskey, $data_array) ) {
			if (sizeof($keys) == 1) {
				$data_array[$thiskey] = $val;
				return $data_array[$thiskey];
			} elseif (is_object($data_array[$thiskey]) &&
				get_class($data_array[$thiskey]) === 'ArrayObject') {
				if ($level < ISPK_RECURSION_MAX) {
					unset($keys[key($keys)]);
					return $this->_setValueRecursive($keys, $val, $data_array[$thiskey], $level+1);
				} else {
					trigger_error('Inspekt recursion limit met', E_USER_WARNING);
					return false;
				}
			}
		} else { // if any key DNE, return false
			return false;
		}
	}
}

/**
 * The Supercage object wraps ALL of the superglobals
 * 
 * @package Inspekt
 *
 */
Class Inspekt_Supercage {

	/**
	 * The get cage
	 *
	 * @var Inspekt_Cage
	 */
	var $get;

	/**
	 * The post cage
	 *
	 * @var Inspekt_Cage
	 */
	var $post;

	/**
	 * The cookie cage
	 *
	 * @var Inspekt_Cage
	 */
	var $cookie;

	/**
	 * The env cage
	 *
	 * @var Inspekt_Cage
	 */
	var $env;

	/**
	 * The files cage
	 *
	 * @var Inspekt_Cage
	 */
	var $files;

	/**
	 * The session cage
	 *
	 * @var Inspekt_Cage
	 */
	var $session;

	var $server;

	/**
	 * Enter description here...
	 *
	 * @return Inspekt_Supercage
	 */
	public function Inspekt_Supercage() {
		// placeholder
	}

	/**
	 * Enter description here...
	 * 
	 * @param string  $config_file
	 * @param boolean $strict
	 * @return Inspekt_Supercage
	 */
	static public function Factory($config_file = NULL, $strict = TRUE) {

		$sc	= new Inspekt_Supercage();
		$sc->_makeCages($config_file, $strict);

		// eliminate the $_REQUEST superglobal
		if ($strict) {
			$_REQUEST = null;
		}

		return $sc;

	}

	/**
	 * Enter description here...
	 *
	 * @see Inspekt_Supercage::Factory()
	 * @param string  $config_file
	 * @param boolean $strict
	 */
	protected function _makeCages($config_file=NULL, $strict=TRUE) {
		$this->get		= Inspekt::makeGetCage($config_file, $strict);
		$this->post		= Inspekt::makePostCage($config_file, $strict);
		$this->cookie	= Inspekt::makeCookieCage($config_file, $strict);
		$this->env		= Inspekt::makeEnvCage($config_file, $strict);
		$this->files	= Inspekt::makeFilesCage($config_file, $strict);
		// $this->session	= Inspekt::makeSessionCage($config_file, $strict);
		$this->server	= Inspekt::makeServerCage($config_file, $strict);
	}
	
	
	public function addAccessor($name) {
		$this->get->addAccessor($name);
		$this->post->addAccessor($name);
		$this->cookie->addAccessor($name);
		$this->env->addAccessor($name);
		$this->files->addAccessor($name);
		// $this->session->addAccessor($name);
		$this->server->addAccessor($name);
	}

}