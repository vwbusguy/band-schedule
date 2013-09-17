<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/users.class.php');

class setSession{

public function __construct(){
	if (!isset($_POST['username'])){
		$this->retError('Go away troll.');
	}else{
		$username = $_POST['username'];
	}
	$this->user = new users;
	$this->setSession($username);
	$this->result['status'] = 'ok';
	echo json_encode($this->result);
}

private function getUserData($username){
	return $this->user->getUserData($username);
}

private function setSession($username){
	if (($_COOKIE['username'] != $username) or ($_COOKIE['fccw'] != 'auth')){
		$this->retError('Failed to set sesstion');
	}
	session_start();
	session_regenerate_id(true);
	$userData = $this->getUserData($username);
	if (isset($this->user->error)){
		$this->retError($this->user->error);
	}
	foreach ($userData as $key=>$val){
		$_SESSION[$key] = $val;
	}
	$this->user->setLastLogin($username);
	return true;
}

private function retError($message){
	$result['status'] = 'failed';
	$result['message'] = $message;
	echo json_encode($result);
	die();
}

}

$foo = new setSession;

?>
