<?php

class App_CSRFUtil
{
    private static $_instance;

    private $_token;
    private $_session_token_name = 'ASD34fsd_sdf';

    /**
     * @var App_Session
     */
    private $_session;

    /**
     * @var App_Http
     */
    private $_http;


    public static function getInstance()
    {
        if(self::$_instance === null){
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private function __construct()
    {
        $this->_session = App_Session::getInstance();
        $this->_http = App_Http::getInstance();
    }

    private function set()
	{
        $session_name = $this->_session_token_name;
        $token =  md5(time().uniqid().rand(1,10));
        return $this->_token = $this->_session->$session_name = $token;
    }

    public function getToken($set = false){
       if($set){
           $this->set();
       }
       return $this->_token;
    }

    public function isValid(){
        $session_name = $this->_session_token_name;
        $t = $this->_http->token;
        $st = $this->_session->$session_name;
        return ($t == $st) && preg_match('/([a-zA-Z0-9]){32}/i', "{$st}", $result);
    }
}