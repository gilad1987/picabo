<?php
class App_Config
{
	
	private static $_instance;
	
	private $config;
	private $timeZone;
	private $currentMode = null;
	
	public static $dbHost;
	public static $dbUser;
	public static $dbPassword;
	public static $dbPort;
	public static $dbSchema;
	
	public static $base_url;
	
	public static $fb_app_id;
	
	
	public static $base_directory;
	
    /**
     * @return App_Config
     */
    public static function getInstance()
	{
		if(self::$_instance === null){
			self::$_instance = new self();
		}
	
		return self::$_instance;
	}
	
	private function __construct()
	{
		$this->config = (object)parse_ini_file("config.ini", INI_SCANNER_RAW);
		$this->parseINI();
		$this->init();
	}
	
	private function init()
	{
		define("BASE_DIRECTORY", str_replace("/","",dirname($_SERVER['PHP_SELF'])));
		self::$base_directory = BASE_DIRECTORY;
		

        if(self::$base_directory  == ''){
            define("BASE_URL", 'http://' . $_SERVER['HTTP_HOST'].'/');
        }else{
            define("BASE_URL",'http://' . $_SERVER['HTTP_HOST']. '/' .BASE_DIRECTORY.'/');
        }

		self::$base_url = BASE_URL;
		$base = BASE_DIRECTORY;
		if(!empty($base)){
			self::$base_url = BASE_URL;
		}
		$this->setTimeZone();
		$this->setCurrentMode();	
		$this->setSetting();

        define("APP_TITLE", $this->currentMode->app->title);
	}
	
	private function setSetting()
	{
		ini_set('display_errors',$this->currentMode->phpSettings->display_errors);
		ini_set('log_errors ',1);
		if($this->currentMode->phpSettings->display_errors){
			ini_set("error_reporting",E_ALL ^ E_NOTICE);
		}
		define("DB_USER", $this->currentMode->db->username);
		define("DB_HOST", $this->currentMode->db->host);
		define("DB_PASS", $this->currentMode->db->password);
		define("DB_SCHEMA", $this->currentMode->db->schema);
		define("DISPLAY_EXCEPTIONS",$this->currentMode->frontController->displayExceptions);
		define("DISPLAY_MYSQL_ERRORS",$this->currentMode->db->mysqlError);
		
		self::$fb_app_id = $this->currentMode->fb->app_id;
	}
	
	private function setCurrentMode()
	{

		if($_SERVER['SERVER_PORT'] == $this->production->port || $_SERVER["SERVER_NAME"] == 'pica.bo'){
			if(isset($_GET[$this->testing->test_query_param])){
				$this->production->frontController->displayExceptions = $this->testing->frontController->displayExceptions;
				$this->production->frontController->mysqlError = $this->testing->frontController->mysqlError;
			}
			return $this->currentMode = $this->production;
		}
		if($_SERVER['SERVER_PORT'] == $this->development->port){
			return $this->currentMode = $this->development;
		}


		throw new Exception("invalid stag");
	}
	
	private function setTimeZone( $timeZone = "Asia/Jerusalem" )
	{
		date_default_timezone_set($timeZone);
		$this->timeZone = $timeZone;
		return $this;
	}
	
	private function parseINI()
	{
		if(!isset($this->config)){
			return;
		}
		foreach ($this->config as $key=>$mode){
			$stag = new stdClass();
			$stagName=null;
			foreach ($mode as $keyMode=>$val){
				if(strpos($keyMode, ".")){
					$arr = explode(".", $keyMode);
					if(isset($stag->$arr[0])){
						$stag->$arr[0]->$arr[1] =$val;
					}else{
						$stag->$arr[0] = new stdClass();
						$stag->$arr[0]->$arr[1] =$val;
					}
				}else{
					$stag->$keyMode = $val;
				}
			}
			$this->$key = $stag;
		}
		unset($this->config);
	}
	
}