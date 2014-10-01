<?php
class App_Utils_Upload
{
    const UPLOADS_DIRECTORY = 'uploads';

    private static $image_types = array("image/gif","image/jpeg","image/jpg","image/png");

    public function image($request_name, $extensions, $size)
    {
        $file_source = isset($_FILES[$request_name]) ? $_FILES[$request_name] : null;

        if($file_source == null){
            return false;
        }

        if(in_array($file_source["type"],self::$image_types) == false){
            return false;
        }

        $ext = explode(".", $file_source["name"]);
        $file_extension = strtolower( end($ext) );

        if(in_array($file_extension,$extensions) == false){
            return false;
        }

        if($file_source['size'] > $size*1024*1024){
            return false;
        }



        $file_name = $this->getRandomName();
        $destination = self::UPLOADS_DIRECTORY.'/'.$file_name.'.'.$file_extension;
        move_uploaded_file($file_source['tmp_name'],$destination);

        $compression_type = Imagick::COMPRESSION_JPEG;
        $thumbnail = new Imagick($destination);
        $thumbnail->setImageCompression($compression_type);
        $thumbnail->setImageCompressionQuality(75);
        $thumbnail->stripImage();
        $image_width = $thumbnail->getImageWidth();
        $width = min($image_width,800);
        $thumbnail->thumbnailImage($width,null);
        App_Controller_Site_Images::delete($destination);
        $thumbnail->writeImage($destination);

        $time = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $query = "INSERT INTO `uploads` (`src`,`upload_time`,`token`,`ip`) VALUES ('{$destination}','{$time}','{$file_name}','{$ip}')";

        App_Db::getInstance()->getConn()->query($query);
        return $file_name;
    }

    public function getRandomName()
    {
        $name = '';
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        for ($i = 0; $i < 7; $i++) {
            $n = rand(0, strlen($alphabet)-1);
            $name .= $alphabet[$n];
        }

        return $name;
    }
}