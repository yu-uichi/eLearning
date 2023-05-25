<?php
  //セッションのスタート
session_start();
if(!isset($_SESSION['id'])){
    header('Location: login.php');
}
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/functions.php");

$dbh = connectDB();
if (!$dbh) {
    echo "データベースの接続に失敗しました．<br>";
    echo "管理者にご連絡ください．";
    die;
}

if(isset($_POST['id'])){
    $sql = 'DELETE FROM `user_tb` WHERE `id` = "'. $_POST['id'].'"';
    $dbh->exec($sql); //SQLの実行
}

header('Location: user_list.php');

?>