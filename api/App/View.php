<?php
class App_View
{
    const ROOT_VIEW = 'Api/App/View/';

    private static $_instance;

    private $_http;

    private $controllerName;
    private $actionName;
    private $layoutName;
    private $disableView;
	private $isErrorPage = false;
    
	public $tempaleName;
    public $pageContent;
    public $paramsResponse;
    public $csrf_token;

    public static function getInstance()
    {
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    public function isViewDisable()
    {
        return $this->disableView;
    }

    public function setDisableView($disableView)
    {
    	$this->disableView = (bool)$disableView;
    	return $this;
    }

    public function __set($key,$val){
        $this->$key = $val;
    }

    public function __get($key){
        if(!isset($this->$key)){
            return null;
        }
        return $this->$key;
    }
    
    private function __construct()
    {
        $this->_http = App_Http::getInstance();
        $this->response = new stdClass();

//        $this->csrf_token = App_CSRFUtil::getInstance()->getToken(true);
    }

    public function setLayoutName($layout){
    	$this->layoutName = $layout;
    }
    
    private function initParams()
    {
    	$this->disableView = false;
        $this->controllerName = $this->_http->getControllerName();
        $this->actionName = $this->_http->getActionName();
        $this->layoutName = $this->_http->getLayoutName();

        if(empty($this->tempaleName)){
	        $this->tempaleName = $this->actionName;
        }
    }

    public function showErrorPage($errorNum,$massage=''){
    	$this->initParams();
    	$this->controllerName =$errorNum;
    	$this->tempaleName = "index";
    	$this->errorMsg = $massage;
    	$param = "is".$errorNum;
    	$this->isErrorPage = true;
    	$funcName = 'send'.$errorNum;
    	App_Headers::$funcName();
    	$this->render();
    }
    
    private function setPageContent()
    {
        $filePath = self::ROOT_VIEW.'Html/'.$this->layoutName.'/'.$this->controllerName.'/'.$this->tempaleName.'.phtml';
        if(!file_exists($filePath)){
        	return;
            $filePath = self::ROOT_VIEW.'View\Error\error.phtml';
        }
        ob_start();
        require_once $filePath;
        $partialHtml = ob_get_contents();
        ob_get_clean();
        
        $this->pageContent = $partialHtml;
    }

    public function isXmlHttpRequest()
    {
    	return $this->_http->isXHR();
    }
    
    public function escape($string)
    {
    	return htmlspecialchars($string);
    }

    public function returnJson(){
        $this->response->token = App_CSRFUtil::getInstance()->getToken(true);
        App_Headers::JSON();
        echo json_encode($this->response);
    }

    public function render()
    {
    	if($this->disableView){
    		return;
    	}
    	
    	if(!$this->isErrorPage){
	    	header('Content-Type: text/html; charset=utf-8');
	    	$this->initParams();
    	}

        $this->csrf_token = App_CSRFUtil::getInstance()->getToken(true);

        $this->setPageContent();
        if(!$this->isXmlHttpRequest()){
        	$a = self::ROOT_VIEW.'Layout/'.strtolower($this->layoutName).'.phtml';
        	require_once self::ROOT_VIEW.'Layout/'.strtolower($this->layoutName).'.phtml';
//        	die();
        }else{
        	echo $this->pageContent;
        }
    }

    /**
     * @param array $params
     * @param bool $absolute
     * @example $this->url(
     *              array("ctrl"=>"lessons",
     *                    "act"=>"add",
     *                    "module"=>"index"))
     *
     * @return string
     */
    public function url(array $params,$absolute=false)
    {
    	$exclude = array('c','a','page','ctrl', 'act','lesson');
    	$dir = BASE_DIRECTORY;
    	$url = '';
    	if(!empty($dir)){
    		$url = '/'.$dir;
    	}
    	
    	if(isset($params['module'])){
    		$url .= '/'.$params['module'];
    	}elseif($this->layoutName != App_Http::DEFAULT_LAYOUT){
	        $url .= '/'. strtolower($this->layoutName);
    	}else{
    		$url .= '/index';
    	}
		
		if(isset($params['ctrl'])){
			$url .= '/'. $params['ctrl'];
		}
		if(isset($params['ctrl']) && isset($params['act'])){
			$url .= '/'. $params['act'];
		}
		
		$first = true;
		$params = explode("&", $_SERVER['QUERY_STRING']);
        foreach ($params as $k => $v){
        	$current = explode("=", $v);
        	if(in_array($current[0],$exclude)){
        		continue;
        	}
        	if(!empty($current[0])){
	        	$url .= $first ? "?".$current[0] :"&".$current[0];
        	}
        	if(!empty($current[0]) && !empty($current[1])){
        		$url .="=".$current[1];
        	}
        	$first = false;
        }
        return $url;
    }
}