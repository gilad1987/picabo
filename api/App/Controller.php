<?php
class App_Controller
{
    /**
     * @var App_Http
     */
    protected $_http;

    /**
     * @var App_View
     */
    protected $_view;

    public function __construct()
    {
        $this->_http = App_Http::getInstance();
        $this->_view = App_View::getInstance();
        $this->_view->js=array();
        $this->_view->css=array();

    }

    
    public function indexAction()
    {
        
    }

    protected function getModel()
    {
    	$ctrlNameArr = explode('_', get_class($this));
    	$modelName = 'App_Model_DbTable_'.$ctrlNameArr[count($ctrlNameArr)-1];
    	return new $modelName();
    }
    
    protected function preDispatch()
    {
        
    }

    public function dispatch($actionName)
    {
        $this->preDispatch();
        
        if(!method_exists($this, $actionName)){
        	throw new Exception("invalid ActionName --- {$actionName} --- In ---".get_class($this));
        }
        $this->$actionName();
        $this->postDispatch();
    }

    protected function postDispatch()
    {

        if($this->_http->getParam('parse') == 'json' || $this->_http->isXHR() || $this->_view->isViewDisable()){
            $this->_view->returnJson();
        }else{
            $this->_view->render();
        }

    }
    
    public function redirect(array $data,$absolute=false)
    {
    	App_Headers::redirect($data,$absolute);
    }
    

    
}