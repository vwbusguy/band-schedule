<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/chkSession.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/mail.class.php');

class mailUser{

public function __construct(){
	if ((!isset($_POST['recipient'])) or (!isset($_POST['subject'])) or (!isset($_POST['message']))){
		$this->retError('Must specify recipient, subject, and message');
	}
	$to = $_POST['recipient'];
	if (isset($to['email'])){
		$recipient = $to['email'];
	}else{
		$recipient = $to;
	}
	$result = $this->mailUser($recipient,$_POST['subject'],$_POST['message']); 
	$result['recipient'] = $recipient;
	echo json_encode($result);
}

private function retError($message){
	$result['status'] = 'failed';
	$result['message'] = $message;
	die(json_encode($result));
}

public function mailUser($recipient,$subject,$message){
	$umail = new mailer;
	$umail->sendmail($recipient,$subject,$message);
	if (isset($this->mailer->error)){
		$this->retError($this->mailer->error);
	}
	$result['status'] = 'ok';
	return $result;
}


}

$foo = new mailUser;

?>
