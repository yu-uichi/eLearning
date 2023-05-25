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

if (empty($_POST)) { //何も送られてきていない場合
    echo "値が入力されませんでした";
}
// echo '<pre>';
// echo var_dump($_POST);
// echo '</pre>';

if (isset($_POST["family_name"]) && isset($_POST["given_name"]) && isset($_POST["family_name_kana"]) && isset($_POST["given_name_kana"]) && isset($_POST["student_id"]) && isset($_POST["affiliation_id"]) && isset($_POST["email"]) && isset($_POST["password"])){
    $sql = 'INSERT INTO `user_tb` (`id`, `family_name`, `given_name`, `family_name_kana`, `given_name_kana`, `affiliation_id`, `student_id`, `email`, `password`, `teacher`, `admin`, `last_login`, `school_name, `created_at`, `updated_at`) VALUES (NULL, "'. $_POST['family_name'].'", "'. $_POST['given_name'].'", "'. $_POST['family_name_kana'].'", "'. $_POST['given_name_kana'].'", "'. $_POST['affiliation_id'].'", "'. $_POST['student_id'].'", "'. $_POST['email'].'", "'. password_hash($_POST['password'], PASSWORD_DEFAULT).'", NULL, NULL, CURRENT_TIMESTAMP,ait, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)';
    $dbh->exec($sql); //SQLの実行
}
// echo $sql;
echo 'ユーザ情報は登録されました';
echo '<br>';
echo '<a href="login.php" class="alert-link">ログイン画面はこちらをクリック</a>';

?>

