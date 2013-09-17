<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/users.class.php');

class valUser{

public function __construct($method = Null,$username = Null,$data = Null){
	if (is_null($method)){
		$method = $_GET['method'];
		$username = $_GET['username'];
		$data = $_GET['udata'];
	}
	$this->user = new users();
	if ($method == 'pass'){
		$result = $this->chkPass($username,$data);
	}
	echo json_encode($result);
}

private function chkPass($username,$pass){
	if (!$this->user->chkLogin($username,$pass)){
		if (isset($this->user->error)){
			$this->retError($this->user->error);
		}
		else{
			$message['status'] = 'ok';
        		$message['login'] = 'failed';
		}
	}else{
		$message['status'] = 'ok';
		$message['login'] = 'verified';
	}
	return $message;		
}

private function retError($message){
	$result['status'] == 'failed';
	$result['message'] == $message;
	die(json_encode($result));
}

}

$foo = new valUser;

?>
