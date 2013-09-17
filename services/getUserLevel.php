<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/users.class.php');

class getUserLevel{

public function __construct($type='id'){
	if (!isset($_SESSION['username'])){
		$this->retError('User is not logged in.');
	}
	$this->user = new users;
	if ($type == 'id'){
		$result['level'] = $this->getId();
	}else{
		$result['level'] = $this->getName();
	}
	$result['status'] = 'ok';
	echo json_encode($result);
}

private function retError($message){
	$result['status'] = 'failed';
	$result['message'] = $message;
	die(json_encode($result));
}

private function getId(){
	$username = $_SESSION['username'];
	$result = $this->user->getUserRoleId($username);
	if (isset($this->user->error)){
		$this->retError($this->user->error);
	}
	return $result;
}

private function getName(){
        $username = $_SESSION['username'];
        $result = $this->user->getRoleLevel($username);
        if (isset($this->user->error)){
                $this->retError($this->user->error);
        }
        return $result;
}
}
$foo = new getUserLevel;
?>
