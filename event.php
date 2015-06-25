<?php
session_start();
require_once('./services/chkSession.php');
require_once('./includes/head.php');
require_once('./includes/globalnav.php');
require_once('./classes/events.class.php');
require_once('./classes/users.class.php');
?>
<style type="text/css">
.btn-primary {
	width:132px;
}

</style>
<?php
$user = new users();
$event = new events();
$level = $user->getUserRoleId($_SESSION['username']);
if (!isset($_GET['id'])){
	header('Location: /events.php');
}


$ev = $event->getEventInfo($_GET['id']);

if (!isset($ev['date'])){
        header('Location: /events.php');
}
echo '<div class="container">';
echo '<h1>' . date("F d, Y", strtotime($ev['date']));
if ($level = 1){
	echo '  <button type="button" class="btn btn-danger btnDelEv" data-eventid="' . $_GET['id'] . '">Delete Event</button>';
}

echo '</h1><p><a href="/events.php">Back to Events Page</a></p>
<p class=info>The event details page shows the status of the event including practice times and which users have signed up.  Admins and leaders can manage the event details from this page.</p>
';
function drawEvent($ev){
	$event = new events;
	$user = new users;
	$level = $user->getUserRoleId($_SESSION['username']);
	echo "<div class=\"event-detail\">";
	$state =  $event->getEventStatus($ev['status']);
	if ($level != 1){
		echo "Event Status: " . ucfirst($state['status']) . "<br/>";
	}else{
		$statuses = $event->getEventStatuses();
		echo "<h3>Event Status</h3><div id='chgEvent'><select id='selChgEvStat'>";
		foreach ($statuses as $status){
			echo "<option value='" . $status['id'] . "' ";
			if ($status['id'] == $ev['status']){
				echo "selected='selected'";
			}
			echo ">" . ucfirst($status['status']). " </option>";
		}
		echo "</select></div>";
	}
	
	if ($ev['status'] == 2){
		echo "<h4>This event has been cancelled.</h4>";
	}else{
	
	echo "<div id=\"evPractice\" class='divStat'><h4>Practice</h4>";	
	if ($ev['practice'] == Null){
		echo 'There is no practice time set yet for this event.<br/>';
	}else{
		echo "The practice for this event will be on " . date("l, F d, Y, g:i a",strtotime($ev['practice'])) . ".";
	}
	if ($level <= 2){
		echo '<div id="chgEvPractice" class="bootstrap-timepicker"><input id="datPractice" type="text" class="datepicker inp-practice" placeholder="Practice Date"><input id="timPractice" type="text" class="timepicker inp-practice"></div>';
	}

	echo "</div><div id=\"evLeader\" class='divStat'><h4>Leader</h4>";
        if ($ev['leader'] == Null){
        	echo 'There is no leader assigned yet for this event.<br/>';
        }else{
		$leader = $user->getUserNiceName($ev['leader']);
                echo $leader['first_name'] . " " . $leader['last_name'] . " is leading this event.<br/>";
        }
	if ($level <= 2){
		$leaders = $user->getLeaderList();
		echo "<div id='chgEventLeader'><select id='selChgEvLead'>";
                foreach ($leaders as $leader){
			$lname = $user->getUserNiceName($leader['id']);
                        echo "<option value='" . $leader['id'] . "' ";
                        if ($leader['id'] == $ev['leader']){
                                echo "selected='selected'";
                        }
                        echo ">" . $lname['first_name'] . ' ' . $lname['last_name'] . ' (' . $leader['username']. ") </option>";
		}
		echo "</select></div>";
	}
	$status = $event->chkUserEventStatus($ev['eventid'],$_SESSION['username']);
	echo '</div><div id="evStatus" ><h4>Your Status</h4><div id="divstat' . $ev['eventid'] . '">';
	if ($status == Null){
		echo "You are not yet signed up for this date.<br/></div>";
		echo "<button type='button' class=\"btn btn-primary btnEventConfirm\" id=\"btnConfirm" . $ev['eventid'] . "\" value=\"" . $ev['eventid'] . "\">Sign up</button>";
	}else{
		$eventUStatuses = $event->getEventUserStatuses();
		echo "<div id='chgEventStatus'>";
		echo "Your status for this event: " . ucfirst($status['status']) . "<br/>" ;
		echo "<select id='selChgEvStatUsr'>";
                foreach ($eventUStatuses as $eUstatus){
                        echo "<option value='" . $eUstatus['id'] . "' ";
                        if ($eUstatus['status'] == $status['status']){
                                echo "selected='selected'";
                        }
                        echo ">" . ucfirst($eUstatus['status']). " </option>";
                }
                echo "</select></div>";
	}

	echo '</div></div><div id="evUsers"><h3>Event Users</h3>';
	if ($level <= 2){
		echo '<p><a href="/mail.php?type=event&id=' . $ev['eventid'] . '">Email these users</a></p>';
	}
	$eventUsers = $event->getEventUsers($ev['eventid']);
	$eventUStatuses = $event->getEventUserStatuses();
	foreach ($eventUStatuses as $eUstatus){
		echo '<div id="divStat' . $eUstatus['status'] . '" class="divStat"><h4>' . ucfirst($eUstatus['status']) . ' users</h4>';
		$i = 0;
		$euList = array();
		foreach($eventUsers as $eUser){
			if ($eUser['status'] == $eUstatus['id']){
				$name = $user->getUserNiceName($eUser['userid']);
				$euList[] = $name['first_name'] . " " . $name['last_name'] . '<br/>';
				$i = $i+1;
			}
		}
                if ($i == 0){
	                echo 'No users have this status for this event.<br/>';
                }else{
			natcasesort($euList);
			foreach($euList as $line){
				echo $line;
			}
		}
		echo "</div>";
	}
	}
}

drawEvent($ev);

?>

</div>
</div>

<?php require_once('./includes/footer.php'); ?>

