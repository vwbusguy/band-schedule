<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db.class.php');

class events{

public function __construct(){
	$this->db = new db;
}

private function chkDBError(){
	if ($this->db->error == 1){
		$this->error = $this->db->errormsg;
		return false;
	}
	return true;
}

public function getAllEvents(){
	$sql = "select * from events";
	$result = $this->db->selectAll($sql);
	$this->chkDBError();
	return $result;
}

public function getEventsForMonth($month,$year){
	if ($month == 12){
		$newMonth = 1;
		$newYear = $year + 1;
	}else{
		$newMonth = $month + 1;
		$newYear = $year;
	}
	$sql = "select * from events where date >= '$year-$month-01' and date < '$newYear-$newMonth-01'";
	$result = $this->db->selectAll($sql);
	$this->chkDBError();
	return $result;
}

public function addEvent($date){
	$data['date'] = $date;
	$data['status'] = 1;
	$this->db->insert('events',$data);
	return $this->chkDBError();
}

public function usrConfirm($uid,$eventid){
	$data['eventid'] = $eventid;
	$data['userid'] = $uid;
	$data['status'] = 1;
	$this->db->insert('event_users',$data);
	return $this->chkDBError();
}

public function chgEventStatus($eventid,$status){
        $this->db->update("events","status","'$status'","eventid = '$eventid'");
        return $this->chkDBError();

}


public function chgUserEvent($uid,$eventid,$status){
	$this->db->update("event_users","status","'$status'","userid = '$uid' and eventid = '$eventid'");
	return $this->chkDBError();

}

public function chkUserEventStatus($eventid,$username){
	$sql = "SELECT status from event_user_status where id = (select status from event_users where eventid = '$eventid' and userid = (SELECT id from users where username = '$username'))";
        $result = $this->db->select($sql);
        $this->chkDBError();
        return $result;
}

public function getEventInfo($eventid){
	$sql = "SELECT * from events where eventid = '$eventid'";
	$result = $this->db->select($sql);
	$this->chkDBError();
	return $result;
}

public function getUserEventStatus($statusId){
	$sql = "SELECT status from event_user_status where id = '$statusId'";
	$result = $this->db->select($sql);
	$this->chkDBError();
	return $result;
}

public function getEventStatus($statusId){
	$sql = "SELECT status from event_status where id = '$statusId'";
        $result = $this->db->select($sql);
        $this->chkDBError();
        return $result;
}

public function getEventStatuses(){
	$sql = "select * from event_status";
	$result = $this->db->selectAll($sql);
	$this->chkDBError();
	return $result;
}

public function getEventUsers($eventid){
        $sql = "select * from event_users where eventid = '$eventid'";
        $result = $this->db->selectAll($sql);
        $this->chkDBError();
        return $result;
}


public function getEventUserStatuses(){
        $sql = "select * from event_user_status";
        $result = $this->db->selectAll($sql);
        $this->chkDBError();
        return $result;
}

public function getEventsForUser($uid){
	$sql = "select u.status,e.eventid,e.date,e.leader from events e inner join event_users u where u.eventid = e.eventid and u.userid = '$uid'";
        $result = $this->db->selectAll($sql);
        $this->chkDBError();
        return $result;
}

public function setLeader($eventid,$uid){
	$this->db->update('events','leader',$uid,"eventid = '$eventid'");
        $this->chkDBError();
        return $result;
}

public function setPractice($eventid,$date){
        $this->db->update('events','practice',$date,"eventid = '$eventid'");
        $this->chkDBError();
        return $result;
}


}
?>
