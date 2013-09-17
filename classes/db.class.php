<?php
class db{

	public function __construct(){
		$this->error = 0;
		$this->getDBConfig();
		$this->connect();
	}

	private function getDBConfig(){
		$this->config = parse_ini_file('/var/db/fcc_worship.ini', false);
	}

	private function connect(){
		$configs = $this->config;
		$this->connection = new mysqli($configs['dbhost'],$configs['dbuser'],$configs['dbpasswd'],$configs['db']);
		if ($this->connection->connect_error){
			$this->setError("Could not connect to " . $configs['db'] . " with error " .$this->connection->connect_error);
		}
	}

	private function setError($message){
		$this->error = 1;
		$this->errormsg = $message;
	}

	private function query($sql){
		if (!$result = $this->connection->query($sql)){
			$this->setError("Could not query database with error " . $this->connection->error);
			return 'error';
		}
		return $result;
		
	}

	//Expects array with keys as column, values as values
	public function insert($table,$data){
		$sql = "INSERT INTO $table (";
		$columns = '';
		$values = '';
		foreach ($data as $key => $val){
			$columns .= "$key,";
			$values .= "'$val',";
		}
		$sql .= rtrim($columns, ',') . ') VALUES (' . rtrim($values, ',') . ')';
		$this->query($sql);
	}

	public function select($sql){
		$result = $this->query($sql);
		if ($result != 'error'){
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$result->free();
			return $row;	
		}
	}

        public function selectAll($sql){
                $result = $this->query($sql);
                if ($result != 'error'){
			while ($row = $result->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			} 
                        $result->free();
                        return $rows;
                }
        }


	public function update($table,$column,$value,$where = NULL){
		$sql = "UPDATE $table set $column = $value";
		if (!is_null($where)){
			$sql .= " WHERE $where";
		}
		$this->query($sql);
	}

	public function close(){
		$this->connection->close();
	}

}



?>
