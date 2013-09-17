<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/users.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/chkSession.php');

class getUserInfo{

public function __construct(){
	if (isset($_GET['username'])){
		$username = $_GET['username'];
	}
	elseif (isset($_SESSION['username'])){
		$username = $_SESSION['username'];
	}else{
		$this->retError('User is not logged in.');
	}
	$this->user = new users;
	$result = $this->getUserInfo($username);
	echo json_encode($result);
}

private function retError($message){
	$result['status'] = 'failed';
	$result['message'] = $message;
	die(json_encode($result));
}

private function getUserInfo($username){
	$result = $this->user->getUserData($username);
	if (isset($this->user->error)){
		$this->retError($this->user->error);
	}
	return $result;
}

}
$foo = new getUserInfo;
?>
