<?php
session_start();

if (isset($_SESSION['username'])){
	$username = $_SESSION['username'];
}

foreach ($_COOKIE as $key){
	setcookie('username','',time()-3600);
	setcookie('fccw','',time()-3600);
}

session_destroy();

echo "User $username has been successfully logged out.";

if (isset($username)){
	header("Location: /login.php?logout=$username");
}else{
	header("Location: /login.php");
}
?>
