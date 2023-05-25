<?php
//メール登録フォームのアドレスに登録ページをユーザに送信する．
session_start();

//参考URL: https://noumenon-th.net/programming/2016/02/27/registration2/

if(empty($_POST)) {
    //不正アクセス
    header('Location: index.php');
}

//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){
    //不正アクセス
    header('Location: index.php');
}

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

require_once(__DIR__ . "/functions.php");

//メールの入力
$mail = isset($_POST['mail']) ? $_POST['mail']: NULL;

if ($mail == NULL) {
    //不正アクセス
    header('Location: index.php');
}

//トークンの発行
$urltoken = hash('sha256',uniqid(rand(),1));
//GETでトークンの埋め込み
//URLは変更されます．
$url = "https://kokushi.teamdigicom.net/registration.php"."?urltoken=".$urltoken;


//メンバーの仮登録
$insert_result = insertPreMember($mail, $urltoken);
$message = '';

if ($insert_result == TRUE) {
    //メールの送信
    $mail_result = sendMail($mail, $url);
}else{
    $mail_result = "同一のメールアドレスが登録されています．";
}

//メンバーの仮登録
function insertPreMember($mail, $urltoken) {
    $dbh = connectDB();
    if (!$dbh) {
        echo "データベースの接続に失敗しました．<br>";
        echo "管理者にご連絡ください．";
        return NULL;
    }
    //存在確認
    $sql = 'SELECT * FROM `user_tb` WHERE `email`="' . $mail . '"';
    $sth = $dbh->query($sql); //SQLの実行
    $result = $sth->fetch(PDO::FETCH_ASSOC); //値の取得

    if ($result != FALSE) { //すでに登録済み
        return FALSE;
    }


    $sql = 'INSERT INTO `pre_member_tb` (`urltoken`, `mail`, `date`)
        VALUES ("' . $urltoken . '","'. $mail . '", now() )';
    $sth = $dbh->query($sql); //SQLの実行

    return TRUE;
}

//メールの送信
function sendMail($mail, $url) {
    global $APP_TITLE_EXPLAIN;
    global $APP_TITLE;
    //メールの情報
    $mail_to = $mail;
    $return_mail = 'kokushi@teamdigicom.net';
    $web_name = $APP_TITLE_EXPLAIN . " " . $APP_TITLE;
    $subject = '[' . $web_name . '] 会員登録URLのお知らせ';

    $mail_body = $web_name .'アプリです．
    24時間以内に下記のURLからご登録ください．
    '. $url;

    mb_language('ja');
    mb_internal_encoding('UTF-8');

    //From ヘッダの作成
    $header = "From: " . mb_encode_mimeheader($web_name) . ' <' . $return_mail . ">\n";
    $header .= "Reply-To: " . $return_mail . "\n";

    if (mb_send_mail($mail_to, $subject, $mail_body, $header)) {

        //セッション変数をすべて解除
        $_SESSION = array();

        //クッキーの削除
        if (isset($_COOKIE["PHPSESSID"])) {
            setcookie("PHPSESSID", '', time() - 1800, '/');
        }

        //セッションを破棄する
        session_destroy();
        global $message; //グローバル変数
        $message = "メールをお送りしました．24時間以内にメールに記載されたURLからご登録下さい．";

        return NULL;
    }

    return "メールの送信に失敗しました";
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $APP_TITLE_EXPLAIN . $APP_TITLE; ?> | メール確認画面</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">

</head>

<body>
<?php
  require_once(__DIR__ . "/header.php");
?>
<div class="container">
<h1>メール確認画面</h1>
<?php
if ($mail_result == NULL) {
    echo $message;
    echo '<p>' . 'URLが記載されたメールが届きますので，クリックして手続きしてください．</p>';
}else{
    echo $mail_result;
}
?>
</div>
</body>
</html>