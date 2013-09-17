<?php
require_once('chkSession.php');
$result['username'] = $_SESSION['username'];
echo json_encode($result);
?>
