<?php
//パスワードのリセット実行 from password_reset.php
session_start();

if (empty($_POST)) { //何も送られてきていない場合
    header("Location: index.php"); //トップに移動
}

//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){
    //tokenが一致しない
	header("Location: index.php"); //トップに移動
}

if (!isset($_POST['password'])) {
    // $_POST['password']がない
    header("Location: index.php"); //トップに移動
}

require_once(__DIR__ . "/functions.php");

$password = htmlspecialchars($_POST['password'], ENT_QUOTES);

updatePassword($password);

//パスワードの更新
function updatePassword ($password) {
    $dbh = connectDB();
    if (!$dbh) {
        echo "データベースの接続に失敗しました．<br>";
        echo "管理者にご連絡ください．";
        return FALSE;
    }
    //passwordの更新
    $sql = 'UPDATE `user_tb` SET `password`= "' . password_hash($password, PASSWORD_DEFAULT) . '" WHERE `email`="' . $_SESSION['reset_mail'] . '"' ;
    $dbh->query($sql); //SQLの実行

    //不要なreset_memberの削除
    $sql = 'DELETE FROM `reset_member_tb` WHERE `mail`="' . $_SESSION['reset_mail'] . '" OR `date` < now()-interval 24 hour';
    $dbh->query($sql); //SQLの実行

    return TRUE;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $APP_TITLE_EXPLAIN . " " . $APP_TITLE; ?> | 登録内容確認画面</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">

</head>

<body>
<?php
  require_once(__DIR__ . "/header.php");
?>
<div class="container">
    <div class="row">
        パスワードを更新しました．
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>