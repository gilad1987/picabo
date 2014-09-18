<?php
class App_Headers
{
	public static function redirect(array $params,$absolute=false)
	{
		$params['ctrl'] = isset($params['ctrl']) ? $params['ctrl']: "index";
		$params['act'] = isset($params['act']) ? $params['act']: "index";
		$module = App_Http::getInstance()->getModuleName();
		
		$dir = BASE_DIRECTORY;
		$url = '';
		if(!empty($dir)){
			$url = $dir;
		}
		$url .= '/'. strtolower($module);
		if(isset($params['ctrl'])){
			$url .= '/'. $params['ctrl'];
		}
		if(isset($params['act'])){
			$url .= '/'. $params['act'];
		}
		
		header('Location: http://'.App_Config::$base_url.$url);
	}
	
	public static function noCache()
	{
		header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}
	
	public static function send404()
	{
		header("Status: 404 Not Found");
		header('HTTP/1.0 404 Not Found');
	}
	
	public static function send500()
	{
		header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	}
		
	public static function JSON()
	{
		header('Content-type: text/json');
		header('Content-type: application/json');
	}
	
	public static function textPlain()
	{
		header("Content-Type:text/plain");
	}
	
	public static function download($file_name){
		header("Pragma: public", true);
		header("Expires: 0"); // set expiration time
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment; filename=".$file_name);
		header("Content-Transfer-Encoding: binary");
// 		header("Content-Length: ".filesize($src));
	}
}