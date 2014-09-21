<?php
class App_Dispatcher
{
    private static function initAutoLoad()
    {

        function __autoload($className)
        {
            $classNameArr = explode('_', $className);
            $pathToClass = '';
            $ds = DIRECTORY_SEPARATOR;

            foreach($classNameArr as $partOfPath){
                if($pathToClass){
                    $pathToClass .= $ds;
                }
                $pathToClass .= $partOfPath;
            }
            
            $pathToClass .= '.php';
            $pathToClass = 'Api/'.$pathToClass;
            
            if(!is_file($pathToClass)){
            	throw new Exception("File -- {$pathToClass} -- No found to include");
            }
            
            require_once $pathToClass;
        }
    }


    public static function run()
    {
        self::initAutoLoad();
		App_Config::getInstance();

        try{
           $http = App_Http::getInstance();
//           if($http->isXHR()){
//             if(!App_CSRFUtil::getInstance()->isValid()){
//                   throw new Exception('Invalid token');
//             }
//           }

       		$className = 'App_Controller_'.$http->getModuleName().'_'.$http->getControllerName();
       		
       		if($http->isAdminModule()){
       			$auth = App_Auth::getInstance()->getInstance();
       			if(!$auth->isAuthAction()){
       				$http->setModuleName("Admin")->setControllerName("Auth")->setActionName("login");
       				
       				$className = 'App_Controller_'.$http->getModuleName().'_'.$http->getControllerName();
       			}
       		}



            $ctrl = new $className();
            $ctrl->dispatch($http->getActionName().'Action');
            
        }catch (App_Request_Params_Exceptions $e){
            $message =  $e->getMessage();
            echo json_encode(array('error'=>$message));

        }catch (App_Mysql_Exceptions $e){

            if(DISPLAY_MYSQL_ERRORS){
                $message =  $e->getMessage();
                echo json_encode(array('error'=>$message));
            }

        }catch(Exception $e){
        	if(DISPLAY_EXCEPTIONS){
                echo $e->getMessage();
            }
        }
    }
}