<?php
class App_Controller_Site_Uploads extends App_Controller_Site_Base
{

    const MAX_IMAGE_SIZE = 10; // Mega

    private static $image_extensions = array("gif", "jpeg", "jpg", "png");

    public function indexAction()
    {
        $upload = new App_Utils_Upload();
        $image_token = $upload->image('file',self::$image_extensions,self::MAX_IMAGE_SIZE);
        $response = new stdClass();
        $response->url = BASE_URL.$image_token;
        $response->success = true;
        $this->_view->setDisableView(false);
        $this->_view->response = $response;
    }
}