<?php
class App_Controller_Site_Index extends App_Controller_Site_Base
{
    protected function preDispatch(){
        $this->_view->js[]='view/js/jquery.js';
        $this->_view->js[]='view/js/dropzone.js';
        $this->_view->js[]='view/js/script.js';

        $this->_view->css[]='view/css/main.css';
        $this->_view->css[]='view/css/dropzone.css';

        parent::preDispatch();
    }

    public function indexAction()
    {

    }

}