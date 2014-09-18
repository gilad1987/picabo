<?php
class Images
{
    public function get($token)
    {
        $query = "SELECT * FROM `uploads` WHERE `token` = '{$token}'" ;
        require_once 'Db.php';
        $result = Db::getInstance()->getConn()->query($query);
        $image = $result->fetchObject();

        if($image == false){
            header('Location: 404.php');
        }

//        $time = time();
//        $query = "DELETE FROM `exit`.`images` WHERE id = '{$image->id}'" ;
//        $result = Db::getInstance()->getConn()->query($query);

        header("Content-Type: image/jpeg");
        echo file_get_contents($image->src);
        $this->delete($image);
    }

    private function delete($image)
    {
        $handle = fopen($image->src,'r+');
        $file_size = filesize($image->src);
        $string = str_repeat("0",$file_size);
        fwrite($handle,$string,$file_size);
        unlink($image->src);
    }
}