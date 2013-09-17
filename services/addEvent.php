<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/chkSession.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/events.class.php');

class addEvent{

public function __construct($date){
	$this-> event = new events;
	$result = $this->insEvent($date);
	$this->setResult($result);
}

public function getResult(){
	return $this->result;
}

private function retError($message){
	$result['status'] = 'failed';
	$result['message'] = $message;
	die(json_encode($result));
}

private function setResult($result){
	$this->result = $result;
}

public function insEvent($date){
	$this->event->addEvent($date);
	if (isset($this->event->error)){
		$this->retError($this->event->error);
	}
	$result['status'] = 'ok';
	return $result;
}


}



?>
