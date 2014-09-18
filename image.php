<?php
if(!isset($_GET['file_token'])){
    die('No Access Permission');
}

require_once 'Images.php';
$image = new Images();
$image->get($_GET['file_token']);
