<?php
include_once 'Upload.php';
$upload = new Upload();
$image_token = $upload->image();
$response = new stdClass();
$response->url = 'http://giladt.com/exit/'.$image_token;
$response->success = true;
echo json_encode($response);

