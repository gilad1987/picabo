<?php
class App_Controller_Site_Images extends App_Controller_Site_Base
{
    public function indexAction()
    {
        $token = $_GET['file_token'];

        if(preg_match('/^([A-Z0-9a-z]{7})$/i', $token) == false){
            App_Headers::redirect(array());
        }
        $query = "SELECT * FROM `uploads` WHERE `token` = '{$token}'" ;

        $result = App_Db::getInstance()->getConn()->query($query);
        $imageModel = $result->fetchObject();

        if(empty($imageModel) || (!empty($imageModel)  && $imageModel->is_deleted) ){
            return null;
        }
        $query = "UPDATE `uploads` SET `is_deleted` = '1' WHERE id = '{$imageModel->id}'" ;
        $result = App_Db::getInstance()->getConn()->query($query);

        $imageData = base64_encode(file_get_contents($imageModel->src));
        $src = 'data: '.$this->getMimeType($imageModel->src).';base64,'.$imageData;

        $this->delete($imageModel);
        $this->_view->image_src = $src;
    }

    public function getMimeType($src)
    {
        if(!is_file($src)){
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileMimeType = finfo_file($finfo, $src);
        finfo_close($finfo);
        return $fileMimeType;
    }

    private function delete($image)
    {
        $handle = fopen($image->src,'r+');
        $file_size = filesize($image->src);
        $string = str_repeat("0",$file_size);
        fwrite($handle,$string,$file_size);
        fclose($handle);
        unlink($image->src);
    }
}