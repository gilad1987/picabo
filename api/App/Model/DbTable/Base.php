<?php

class App_Model_DbTable_Base
{
	protected $tableName;
	protected $fieldTypeByName = array();


	public function __construct()
	{
		$classNameArr = explode('_', get_class($this));
		$this->tableName = strtolower(array_pop($classNameArr));
	}

    /**
     * @return PDO
     */
    protected function getConn()
	{
		return App_Db::getInstance()->getConn();
	}

    public function getCount()
    {
        return  App_Db::getInstance()->getConn()->query("SELECT COUNT(`id`) as `counter` FROM {$this->tableName}")->fetchObject()->counter;
    }

    /**
     * @return mixed|string
     */
    private function getQueryJoin(){
    	$query  = "SELECT tablefileds fileds FROM $this->tableName ";
    	$fields = '';
    	$tablefileds ='';
    	foreach ($this->fieldTypeByName as $prop=>$val){
    		if(strstr($prop, "_id")){
    			$table = str_replace("_id", "", $prop)."s";
    			$modelName = "App_Model_DbTable_".ucfirst($table);
    			$model = new $modelName();
    			foreach ($model->fieldTypeByName as $propModel=>$val){
    				$fields .=" ,`{$table}`.`{$propModel}` as `{$table}_{$propModel}`";
    			}
    			$query .="INNER JOIN `{$table}` ON `{$this->tableName}`.`{$prop}` = `{$table}`.`id` ";
    		}
    		$tablefileds .= " `{$this->tableName}`.`{$prop}` ,";
    	}
    	
    	$tablefileds = rtrim(trim($tablefileds),",");
    	$query = str_replace("tablefileds", $tablefileds, $query);
    	$fields = rtrim(trim($fields),",");
    	$query = str_replace("fileds", $fields, $query);
    	return $query;
    }

    /**
     * @param array $where
     * @param bool $hasJoin
     * @param null $limit
     * @param int $start
     * @param null $order
     * @return array
     * @desc $where = array("table_name"=>array("field_name"=>value))
     * @desc $where = array(
     *                  "main_table_name"=>array("field_name"=>value),
     *                  "join_table_name"=>array("field_name"=>value))
     *
     * @throws Exception
     */
    public function fetchAll(array $where = array(), $hasJoin = false, $limit = null, $start = 0, $order = null){
		$query = '';
		$whereQuery = '';
		if(!$hasJoin){
			$query = "SELECT * FROM $this->tableName";
		}
		if($hasJoin){
			$query = $this->getQueryJoin($this->tableName);
		}
		
		if(!empty($where)){
			$whereQuery = ' WHERE';
			$fileds =array();
			$fileds_ref =array();
			$types= "";
			foreach ($where as $tableName=>$fields){
				$modelName = "App_Model_DbTable_".ucfirst($tableName);
				$model = new $modelName();
				foreach ($fields as $fieldName=>$val){
					$types .= $model->fieldTypeByName[$fieldName];
					$fileds_ref[] = $val;
					$whereQuery .= " `$tableName`.`$fieldName` = ? AND";
				}
			}
			$whereQuery = trim($whereQuery,"AND");
			
// 			for ($i=0; $i<count($fileds_ref); $i++){
// 				$fileds[] = &$fileds_ref[$i];
// 			}
		}
		$query .= $whereQuery;
		
		if($order != null){
			$query .= 'ORDER BY '.$order;	
		}
		
		if($limit != null){
			$query .=" LIMIT {$start},{$limit}";
		}
		
		$conn = $this->getConn();
		
		
		if (!($stmt = $conn->prepare($query))) {
			$error = "error";
			if(DISPLAY_MYSQL_ERRORS){
				$error = "Prepare failed: " .$conn->errorInfo();
			}
			throw new App_Mysql_Exceptions( $error );
		}
		
		if(!empty($where)){
// 			$ref = new ReflectionClass('mysqli_stmt');
// 			$method = $ref->getMethod("bind_param");
// 			$refArr = array_merge(array("0"=>$types),$fileds);
// 			$method->invokeArgs($stmt,$refArr);
			$index =1;
			foreach ($fileds_ref as $key=>&$filed){
				$stmt->bindParam($index, $filed,PDO::PARAM_STR);
				$index++;
			}
		}
		
		if (!$stmt->execute()) {
			$error = "error";
			if(DISPLAY_MYSQL_ERRORS){
				$error = "Execute failed: " .$conn->errorInfo();
			}
			throw new App_Mysql_Exceptions( $error );
		}
		
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
// 		$rows = array();
// 		while($row = $result->fetch_object()){
// 			$rows[] = $row;
// 		}
		return $result;
	}
	
    public function fetchOne(array $where,$withJoin)
    {
    	$rows = $this->fetchAll($where,$withJoin,1);
    	return count($rows) ? $rows[0] : null;
    }

	public function delete($id)
    {
    	$conn = $this->getConn();
    	$id = (int)$id;
    	$sql = 'UPDATE '.$this->tableName.' SET `display` = 0 WHERE `id` = ?';
    	$success = true;
    	
    	if (!($stmt = $conn->prepare($sql))) {
    		$error = "error";
    		if(DISPLAY_MYSQL_ERRORS){
    			$error = "Prepare failed: " .$conn->errorInfo();
    		}
    		throw new App_Mysql_Exceptions( $error );
    	}
    	
    	$stmt->bindValue(1, $id,PDO::PARAM_INT);
    	
    	if (!$stmt->execute()) {
    		$error = "error";
    		if(DISPLAY_MYSQL_ERRORS){
    			$error = "Execute failed: " .$conn->errorInfo();
    		}
    		throw new App_Mysql_Exceptions( $error );
    	}
    	
    	$stmt->closeCursor();
    	return $success;
    }
	
    public function createPlaceholder($text, $count=0, $separator=",",$arr){
    	$result = array();
    	if($count > 0){
    		for($x=0; $x<$count; $x++){
    			$result[] = $text;
    		}
//     		foreach ($arr as $key=>$ar){
//     			$result[] = $text.$key;
//     		}
    	}
    	return implode($separator, $result);
    }
    /**
     * $rows = array(array("date"=>"יט כסלו"),
     * 				 array("date"=>"ח כסלו")
     * 				);
     * @param array $rows
     */
    public function insertOrUpdate(array $rows)
    {
    	$conn = $this->getConn();
    	$types= "";
    	$typePDOArr = array();
    	$question_marks = array();
    	$fileds =array();
    	$fileds_ref =array();
    	foreach ($rows as &$row){
	    	foreach ($row as $filed_name=>$val){
	    		$types .= $this->fieldTypeByName[$filed_name];
	    		if(isset($this->fieldPDOTypeByName[$filed_name])){
	    			$typePDOArr[] =  $this->fieldPDOTypeByName[$filed_name];
	    		}else{
	    			unset($row[$filed_name]);
	    		}
	    	}
    		$question_marks[] = '('  . $this->createPlaceholder('?', sizeof($row),",",$row) . ')';
	    	foreach ($row as $key=>$field){
	    		if(isset($this->fieldPDOTypeByName[$key])){
	    			$fileds_ref[] = $field;
	    		}
	    	}
    	}
    	
//     	for ($i=0; $i<count($fileds_ref); $i++){
//     		$fileds[] = &$fileds_ref[$i];
//     	}
    	$keys = array_keys($rows[0]);
    	$query = 'INSERT '.$this->tableName."  (" . implode(",", $keys ) . ") VALUES  ". implode(',', $question_marks);

    	$query .= " ON DUPLICATE KEY UPDATE ";
    	foreach ($keys as $key){
    		$query .= " $key= VALUES($key) ,"; 
    	}
    	$query = trim(trim($query),",");

    	if (!($stmt = $conn->prepare($query))) {
    		$error = "error";
    		if(DISPLAY_MYSQL_ERRORS){
    			$error = "Prepare failed: " .$conn->errorInfo();
    		}
    		throw new App_Mysql_Exceptions( $error );
    	}
//     	$ref = new ReflectionClass('mysqli_stmt');
//     	$method = $ref->getMethod("bind_param");
//     	$refArr = array_merge(array("0"=>$types),$fileds);
//     	$method->invokeArgs($stmt,$refArr);
		$index = 1;
		foreach ($fileds_ref as $key=>&$filed){
			$stmt->bindValue($index, $filed,$typePDOArr[$key]);
			$index++;
		}
		
    	if (!$stmt->execute()) {
    		$error = "error";
    		if(DISPLAY_MYSQL_ERRORS){
    			$error = "Execute failed: " .$conn->errorInfo();
    		}
    		throw new App_Mysql_Exceptions( $error );
    	}
    	
    	$affected = $stmt->rowCount();
    	return $affected;
    }
}