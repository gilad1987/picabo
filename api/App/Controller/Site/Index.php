<?php
class App_Controller_Site_Index extends App_Controller_Site_Base
{
    public function indexAction()
    {
        $model = new App_Model_DbTable_Uploads();
        $this->_view->uploads_count = $model->getCount();
    }

}