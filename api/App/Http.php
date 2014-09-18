<?php

class App_Http
{
	const DEFAULT_LAYOUT = 'Site';
	
    private static $_instance;
    private $controllerName;
    private $actionName;
    private $layoutName;

    public static function getInstance()
    {
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    private function __construct()
    {
        $this->initParams();
    }

    public function __get($paramName)
    {
        return $this->getParam($paramName);
    }

    public function __set($paramName, $paramVal)
    {
        $this->$paramName = $paramVal;
    }
    
    public function getParam($paramName)
    {
    	return isset($_REQUEST[$paramName]) ? $_REQUEST[$paramName] : null;
    }
	
    public function isPost(){
    	
    }
    
    public function isGet(){
    	
    }
    
    public function isXHR(){
    	return array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }
    
    public function getModuleName()
    {
        return $this->getLayoutName();
    }

    public function isAdminModule(){
        return $this->getModuleName() == "Admin";
    }

    public function setModuleName($moduleName)
    {
    	$this->layoutName = $moduleName;
    	return $this;
    }
    
    public function setControllerName($controllerName)
    {
    	$this->controllerName = $controllerName;
    	return $this;
    } 
    
    public function setActionName($actionName)
    {
    	$this->actionName = $actionName;
    	return $this;
    } 
       
    private function initParams()
    {
  		$this->actionName = "index";
  		$this->controllerName = "Index";
  		
  		$controllerName = $this->c;
  		$actionName = $this->a;
  		$layoutName = $this->l;
  		
  		if($actionName !=null && is_string($actionName)){
  			$this->actionName = $actionName;
  		}
  		
  		if($controllerName !=null && is_string($controllerName)){
  			$this->controllerName = ucFirst($controllerName);
  		}
  		
  		$this->layoutName = str_replace('Index', self::DEFAULT_LAYOUT, ucFirst(basename($_SERVER['SCRIPT_NAME'], '.php')));
    }

    public function setLayoutName($layout){
    	$this->layoutName = $layout;
    }
    
    public function getBaseUrl()
    {
        var_dump($_SERVER);
    }

    public function getControllerName()
    {
       return $this->controllerName;
    }

    public function getActionName()
    {
        return $this->actionName;
    }

    public function getLayoutName()
    {
        return $this->layoutName;
    }
}