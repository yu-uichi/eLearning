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

if (!(isset($_POST["family_name"]) && isset($_POST["given_name"]) && isset($_POST["affiliation_name"]) && isset($_POST["email"]) && isset($_POST["password"]))){ 
    //POSTで送られてこなかったらログインページに戻る
    header('Location: login.php');
}
// echo '<pre>';
 echo var_dump($_POST);
// echo '</pre>';
    //POSTで送られてくるaffiliation_nameがDBにあるのか検索
    $sql = 'SELECT * FROM `affiliation_tb` WHERE `affiliation` LIKE "%'.$_POST['affiliation_name'].'%"';
    $sth = $dbh->query($sql);
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);


    //IDを生成する関数
    function createId($length = 12){
        return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDWFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
    }
    $aff_id = createId(12); //12文字のクラスIDの生成

    //もしaffiliation_nameがなかったらaffiliation_tbに追加する
    if (count($result) == 0){
        $sql2 = 'INSERT INTO `affiliation_tb` (`id`, `affiliation`, `id_aff`) VALUES (NULL, "'. $_POST['affiliation_name'].'", "'. $aff_id.'")';
        $dbh->exec($sql2); //SQLの実行
        $sth = $dbh->query($sql);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
    }

    // user_tbに情報を登録する
    $sql = 'INSERT INTO `user_tb` (`id`, `family_name`, `given_name`, `family_name_kana`, `given_name_kana`, `affiliation_id`, `student_id`, `email`, `password`, `teacher`, `admin`, `last_login`, `school_name, `created_at`, `updated_at`) VALUES (NULL, "'. $_POST['family_name'].'", "'. $_POST['given_name'].'", "'. $_POST['family_name_kana'].'", "'. $_POST['given_name_kana'].'", "'.$result['id'].'", NULL, "'. $_POST['email'].'", "'. password_hash($_POST['password'], PASSWORD_DEFAULT).'", 1, NULL, CURRENT_TIMESTAMP,ait, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)';
    $dbh->exec($sql); //SQLの実行
//echo $sql;



echo 'ユーザ情報は登録されました';
echo '<br>';
echo '<a href="login.php" class="alert-link">ログイン画面はこちらをクリック</a>';

?>

