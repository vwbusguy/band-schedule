<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/users.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/chkSession.php');
class getUserList{

public function __construct(){
	$this->user = new users;
	$result = $this->getUserList();
	$result['status'] = 'ok';
	echo json_encode($result);
}

private function retError($message){
	$result['status'] = 'failed';
	$result['message'] = $message;
	die(json_encode($result));
}

private function getUserList(){
	$result = $this->user->getUserList();
	if (isset($this->user->error)){
		$this->retError($this->user->error);
	}
	return $result;
}

}
$foo = new getUserList;
?>
