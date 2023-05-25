<?php
session_start();

// セッションの変数のクリア
$_SESSION = array();

// セッションクリア
$_SESSION=array();
@session_destroy();
header('Location: login.php');
?>
