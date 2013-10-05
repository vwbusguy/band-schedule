<?php

$classes = $_SERVER['DOCUMENT_ROOT'] . '/classes/';
require_once($classes . 'db.class.php');
require_once($classes . 'users.class.php');


class mailer{


public function __construct(){
	$this->error = false;

}

private function reterror($message){
	$this->error = true;
	$this->errormsg = $message;
}

public function sendmail($recipient,$subject,$message){
	try{
		$headers = "From: noreply@" . $_SERVER['SERVER_NAME'];
		mail($recipient,$subject,$message,$headers);
	} catch(Exception $e){
		$this->reterror($e);
	}
}

}


?>
