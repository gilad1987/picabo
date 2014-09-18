<?php

/**
 * Class App_Model_DbTable_Uploads
 */
class App_Model_DbTable_Uploads extends App_Model_DbTable_Base
{
	protected $fieldPDOTypeByName = array(
		'id' 			=> PDO::PARAM_INT,
		'src' 			=> PDO::PARAM_STR,
		'time_open'		=> PDO::PARAM_STR,
        'is_deleted'    => PDO::PARAM_INT,
        'upload_time'   => PDO::PARAM_STR,
        'token' 		=> PDO::PARAM_STR,
        'ip'     		=> PDO::PARAM_STR,
	);


}