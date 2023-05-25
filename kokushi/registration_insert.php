<?php
session_start();

//会員登録完了ページ
//参考URL: https://noumenon-th.net/programming/2016/02/28/registration3/

if (empty($_POST)) { //何も送られてきていない場合
    header("Location: index.php"); //トップに移動
}

//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){
	echo "不正アクセスの可能性あり";
	exit();
}

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

require_once(__DIR__ . "/functions.php");
if(empty($_POST)) {
	header("Location: index.php");
	exit();
}

insertMember();

//メンバーの登録
function insertMember() {
	$dbh = connectDB();
    if (!$dbh) {
        echo "データベースの接続に失敗しました．<br>";
        echo "管理者にご連絡ください．";
        return NULL;
    }
    $affiliation_id = NULL;
    if ($_SESSION['form_affiliation'] != null) {
        $sql = 'SELECT `id` FROM `affiliation_tb` WHERE `affiliation`="' . $_SESSION['form_affiliation'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if (isset($result['id'])) {
            $affiliation_id = $result['id'];
        } else {
            //ドメインの取得
            $domain = substr($_SESSION['form_email'], strpos($_SESSION['form_email'], "@")+1);
            $sql = 'INSERT INTO `affiliation_tb` (`affiliation`, `domain`) VALUES ("' . $_SESSION['form_affiliation'] . '", "' . $domain . '")';
            $dbh->exec($sql); //SQLの実行

            $affiliation_id = $dbh->lastInsertId();
        }
    }
    $sql = "INSERT INTO `user_tb` (`family_name`, `given_name`, `family_name_kana`, `given_name_kana`, `affiliation_id`, `student_id`, `email`, `password`, `teacher`, `admin`) VALUES ('". $_SESSION['form_family_name'] . "', '" . $_SESSION['form_given_name'] . "', '" . $_SESSION['form_family_name_kana'] . "', '" . $_SESSION['form_given_name_kana'] . "'," . $affiliation_id .", '" . $_SESSION['form_student_id'] . "', '" . $_SESSION['form_email'] . "' , '" . password_hash($_SESSION['form_password'], PASSWORD_DEFAULT) . "', '" . $_SESSION['form_teacherCheck'] . "', '" . $_SESSION['form_adminCheck'] . "')";
    $dbh->exec($sql); //SQLの実行

    //一時的なメンバから削除
    $sql = 'DELETE FROM `pre_member_tb` WHERE `mail`="' . $_SESSION['form_email'] . '"';
    $dbh->exec($sql); //SQLの実行

}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $APP_TITLE; ?> | ユーザ登録完了</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">

</head>

<body>
<?php
  require_once(__DIR__ . "/header.php");
?>
<div class="container">
	<p>登録完了いたしました．</p>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>