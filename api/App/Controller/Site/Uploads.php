<?php
class App_Controller_Site_Uploads extends App_Controller_Site_Base
{
    public function indexAction()
    {
        $upload = new App_Utils_Upload();
        $image_token = $upload->image();
        $response = new stdClass();
        $response->url = BASE_URL.$image_token;
        $response->success = true;
        $this->_view->setDisableView(false);
        $this->_view->response = $response;
    }
}