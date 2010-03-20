<?php
/**
* basic session managment in light of inspekt
**/

class Session {
	protected static $_instance;
	protected $input;
	private function __construct()
	{
		session_start();
	}
	
	/**
	 * Implementation of the singleton design pattern
	 * See http://www.talkphp.com/advanced-php-programming/1304-how-use-singleton-design-pattern.html
	 */
	public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
	
	public function getVar($var)
	{
		if(isset($_SESSION[$var]))
		{
			return $_SESSION[$var];
		}
		else
		{
			return null;
		}
	}
	
	public function setVar($var,$value)
	{
		$_SESSION[$var] =$value;
	}
	
	public function destroy()
	{
		session_destroy();
	}
	
	public function regenerate()
	{
		session_regenerate_id();
	}
}