<?php
if (!isset($_SESSION)){
	session_start();
}

if ((!isset($_SESSION['username'])) or ($_COOKIE['username'] != $_SESSION['username'])){
        header("Location: /logout.php");
        die();
}

?>
