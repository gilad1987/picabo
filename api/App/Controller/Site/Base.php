<?php
class App_Controller_Site_Base extends App_Controller
{
    protected function preDispatch()
    {
        $this->_view->pageTitle = APP_TITLE;
        $this->_view->contentTitle = 'עמוד ראשי';
        parent::preDispatch();
    }

    protected function postDispatch()
    {
        $this->_view->pageContent = 'עמוד ללא תוכן';
        parent::postDispatch();
    }
    
    protected function getModel()
    {
    	$ctrlNameArr = explode('_', get_class($this));
    	$modelName = 'App_Model_DbTable_'.$ctrlNameArr[count($ctrlNameArr)-1];
    	return new $modelName();
    }
    

    
}