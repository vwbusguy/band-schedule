<?php
session_start();
require_once('./services/chkSession.php');
require_once('./includes/head.php');
require_once('./includes/globalnav.php');
require_once('./classes/events.class.php');
require_once('./classes/users.class.php');
$user = new users();
$event = new events();
?>
<style type="text/css">
select{
	width: 6em;
}

</style>

<div class="container">
<h1>Scheduled Events</h1>
<form id="selMonth" method="POST" action="/events.php">
<select name="month">
<option value='' disabled>Month</option>
<?php 
if (isset($_POST['month'])){
	$mon = $_POST['month'];
}else{
	$mon = date('m');
}
$i = 1;
while ($i <= 12){
	echo "<option value='$i'";
	if ($i == $mon){
		echo " selected='selected'";
	}
	echo ">$i</option>";
	$i= $i + 1;
}
?>
</select>
<select name="year">
<option value='' disabled>Year</option>
<?php
if (isset($_POST['year'])){
        $yr = $_POST['year'];
}else{
	$yr = date('Y');
}
$year = $yr + 1;
$i = 1;
while ($i <= 3){
	echo "<option value='$year'";
	if ($year == $yr){
		echo " selected='selected'";
	}
	echo ">$year</option>";
	$year = $year - 1;
	$i = $i + 1;
}
?>
</select> <br/>
<input type="submit" class="button btnSubmit btn btn-small" value="Change month">
<p>
<?php
$level = $user->getUserRoleId($_SESSION['username']);
if ($level <= 2){
	echo '<br/><a href="/utils/addEvent.php">Add a new event</a>';
}
?>
</p>

<?php
if (isset($_POST['month']) and isset($_POST['year'])){
	$month = $_POST['month'];
	$year = $_POST['year'];
	$txtMonth = date("F",mktime(0,0,0,$month,10));
}else{
	$month = date('m');
	$year = date('Y');
	$txtMonth = date('F');
}


echo "<h3 id=\"events-h3\">Events for $txtMonth $year</h3>";



function drawEvent($ev){
	$event = new events();
	$user = new users;
	echo "<div class=\"event";
	echo "\">";
        echo "<h4 class=\"event-h4\" id='h4-event" . $ev['eventid'] . "'><a href='/event.php?id=" . $ev['eventid'] . "'>" . date("F d, Y", strtotime($ev['date'])) . "</a></h4>";
	$state =  $event->getEventStatus($ev['status']);
	echo "Event Status: " . ucfirst($state['status']) . "<br/>";
        if ($ev['leader'] == Null){
        	echo 'There is no leader assigned yet for this event.<br/>';
        }else{
                $leader = $user->getUserNiceName($ev['leader']);
                echo $leader['first_name'] . " " . $leader['last_name'] . " is leading this event.<br/>";
        }
	$status = $event->chkUserEventStatus($ev['eventid'],$_SESSION['username']);
	echo '<div id="divstat' . $ev['eventid'] . '">';
	if ($status == Null){
		echo "You are not yet signed up for this date.<br/></div>";
		echo "<button type='button' class=\"btn btn-primary btnEventConfirm\" id=\"btnConfirm" . $ev['eventid'] . "\" value=\"" . $ev['eventid'] . "\">Sign up</button>";
	}else{
		echo "Your status for this event: " . ucfirst($status['status']) . "</div>" ;
		echo "<button type='button' class=\"btn btn-primary btnEventConfirm\" id=\"btnConfirm" . $ev['eventid'] . "\" value=\"" . $ev['eventid'] . "\" disabled>Sign up</button>";
	}
	echo "</div>";
}
function date_compare($a, $b)
{
    $t1 = strtotime($a['date']);
    $t2 = strtotime($b['date']);
    return $t1 - $t2;
}   
$curEvents = $event->getEventsForMonth($month,$year);
usort($curEvents,'date_compare');
if ($curEvents == Null){
	echo "There are no events scheduled yet for this month.";
}else{
	foreach ($curEvents as $ev){
		drawEvent($ev);
	}
}

?>


</div>

<?php require_once('./includes/footer.php'); ?>

