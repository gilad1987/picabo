<?php
class Images
{
    public function get($token)
    {
        $query = "SELECT * FROM `uploads` WHERE `token` = '{$token}'" ;
        require_once 'Db.php';
        $result = Db::getInstance()->getConn()->query($query);
        $imageModel = $result->fetchObject();

        if(empty($imageModel) || (!empty($imageModel)  && $imageModel->is_deleted) ){
           return null;
        }
        $query = "UPDATE  `picabo`.`uploads` SET `is_deleted` = '1' WHERE id = '{$imageModel->id}'" ;
        $result = Db::getInstance()->getConn()->query($query);

        $imageData = base64_encode(file_get_contents($imageModel->src));
        $src = 'data: '.mime_content_type($imageModel->src).';base64,'.$imageData;

        $this->delete($imageModel);
        return $src;
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