<?php
class Uploads
{
    public static function getCount()
    {
        require_once 'Db.php';
        return  Db::getInstance()->getConn()->query("SELECT COUNT(`id`) as `counter` FROM `uploads`")->fetchObject()->counter;
    }
}