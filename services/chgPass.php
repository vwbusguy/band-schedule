<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/users.class.php');
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/chkSession.php');

class chgPass{

function __construct(){
	$this->user = new users;
	$username = $_SESSION['username'];
	$md5 = $_POST['md5'];
	/**if (!usrPermsChk($username)){
		$this->retError('Insufficient Perms');
	}**/
	if ($this->chgUserPass($username,$md5)){
		$result['status'] = 'ok';
		echo json_encode($result);
		return True;
	}
}

private function retError($message){
	$result['status'] = "failed";
	$result['message'] = "$message";
	echo json_encode($result);
	die();
}

private function usrPermsChk($username){
	$usrLevel = $this->user->getUserLevel($username);
	$curLevel = $this->user->getUserLevel($_SESSION['username']);
	if ($curLevel <= $usrLevel){
		return True;
	}
	return False;
}

private function chgUserPass($username,$md5){
	$this->user->chgUserPass($username,$md5);
	if (isset($this->user->error)){
		$this->retError($this->user->error);
	}
	return True;
}

}

$foo = new chgPass;

?>
