<?php
session_start();
require_once('services/chkSession.php');
require_once('includes/head.php');
require_once('includes/globalnav.php');
require_once('classes/users.class.php');
require_once('classes/mail.class.php');
if (($_SESSION['user_level'] > 2) or (!isset($_GET['type']))){
	header("Location: /index.php");
} 
$type = $_GET['type'];
$mail = new mailer;
if ($type == 'event'){
	if (!isset($_GET['id'])){
		header("Location: /index.php");
	}
	require_once('classes/events.class.php');
	$event = new events;
	$e = $event->getEventInfo($_GET['id']);
	if (isset($_POST['submit'])){
		$emails = $event->getEventEmails($_GET['id']);
		$_POST['body'] .= "\n\nSent to users signed up for the event on " .  date("F d, Y", strtotime($e['date']))
		. " by " . ucfirst($_SESSION['username']) . ".";
	}		
}elseif($type == 'all'){
	require_once('classes/users.class.php');
	$user = new users;
	if (isset($_POST['submit'])){
		$emails = $user->getAllEmails();
		$_POST['body'] .= "\n\nMessage sent to all users by " . ucfirst($_SESSSION['username']) . ".";
	}
}
if (isset($emails)){
	foreach ($emails as $email){
		$mail->sendmail($email['email'],$_POST['subject'],$_POST['body']);
		if ($mail->error){
			$success = false;
		}else{
			$success = true;
		}
	}
}
?>
<div class="container">
<h1>Send Email</h1>

<?php
if ($type == 'event'){
	echo "<p>Email all users for event on " . date("F d, Y", strtotime($e['date'])) . "</p>";
}elseif($type == 'all'){
	echo "<p>Email all active users.</p>";
}
?>

<form id="frmEmail" class="frm" method="POST">
<input id='txtMailSubject' name="subject" type="text" placeholder="Subject"
<?php
if (isset($_POST['subject'])){
	echo ' value="' . $_POST['subject'] . '"';
}
?>
>
</input><br/>
<textarea id='txtMailBody' name="body"  placeholder="Message">
<?php
if (isset($_POST['body'])){
	echo $_POST['body'];
}
?>
</textarea><br/>
<?php

if (isset($_POST['submit'])){
        if ($success == true){
                echo 'Email was successfully sent!';
                if ($type == 'event'){
                        echo '<script type="text/javascript">setTimeout(function(){window.location.href = "/event.php?id=' . $_GET['id'] . '";},3000);</script>';
                }
        }else{
                echo 'Could not send email: ' . $mail->errormsg ;
        }
}else{
	echo "<input id='btnMail' name='submit' class='btn btn-primary btn-large' type='submit' value='Send Message'></input>";
}
?>
</form>

<?php

require_once('includes/footer.php');
?>
