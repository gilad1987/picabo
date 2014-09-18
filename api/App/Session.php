<?php

class App_Session
{
	private static $_instance = null;

	public static function getInstance()
	{
		if(self::$_instance === null){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct()
	{
		session_start();
	}

	public function __get($paramName)
	{
		return isset($_SESSION[$paramName]) ? $_SESSION[$paramName] : null;
	}

	public function __set($paramName, $paramVal)
	{
		$_SESSION[$paramName] = $paramVal;
	}

	public function destroy()
	{
		session_destroy();
		unset($_SESSION);
	}	
}