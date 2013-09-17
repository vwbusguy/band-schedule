<?php
session_start();
require_once('../services/chkSession.php');
require_once('../includes/head.php');
require_once('../includes/globalnav.php');
?>
<div class="container">
<h1>Add an Event</h1>
<p>
<?php
if (isset($_POST['date'])){
        $date = $_POST['date'];
        require_once('../services/addEvent.php');
        $event = new addEvent($date);
        $result = $event->getResult();
        if ($result['status'] == 'ok'){
                echo "Successfully added event for $date";
        }else{
		$message = $result['message'];
		echo "Could not add event.  $message";
	}
}
?>
</p>
<h3>Choose the date:</h3>

<form id="frmAddEvent" action="addEvent.php" method="post">
<input class="datepicker" id="date" name="date" value ="Click to Set Date" type="text"  size="16"><br/>
<button class="btn btn-large btn-primary" type="submit">Add Event</button>
</form>

<p id="result"> </p>

</div>

<?php 
require_once('../includes/footer.php'); ?>
