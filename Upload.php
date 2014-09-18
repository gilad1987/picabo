<?php
class Upload
{
    const UPLOADS_DIRECTORY = 'uploads';

    public function image()
    {
        $file_source = $_FILES['file'];
        $file_name = $this->getRandomName();
        $destination = self::UPLOADS_DIRECTORY.'/'.$file_name.'.jpeg';
        move_uploaded_file($file_source['tmp_name'],$destination);
        $time = time();
        $ip = $_SERVER['REMOTE_ADDR'];
        $query = "INSERT INTO `uploads` (`src`,`upload_time`,`token`,`ip`) VALUES ('{$destination}','{$time}','{$file_name}','{$ip}')";
        require_once 'Db.php';
        Db::getInstance()->getConn()->query($query);
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