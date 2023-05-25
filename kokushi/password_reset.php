<?php
//パスワードのリセット処理
//メールから飛んでくる

session_start();
require_once(__DIR__ . "/functions.php");

//クロスサイトリクエストフォージェリ（CSRF）対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

if (empty($_GET)) { //空っぽで，かつ管理者ではない場合
    header('Location: index.php'); //トップページに飛ばす
}

$mail = "";
$error_message = "";
$urltoken = isset($_GET['urltoken']) ? $_GET['urltoken'] : null;
$token_result = tokenConfirmation ($urltoken);

//トークンの確認 (管理者じゃない場合)
function tokenConfirmation ($urltoken) {
    //グローバル変数
    global $mail;
    global $error_message;
    global $affiliation;

    $dbh = connectDB();
    if (!$dbh) {
        echo "データベースの接続に失敗しました．<br>";
        echo "管理者にご連絡ください．";
        return FALSE;
    }
    $sql = 'SELECT `mail` FROM `reset_member_tb` WHERE `urltoken`="' . $urltoken . '" AND `flag`=0 AND `date` > now()-interval 24 hour';
    $sth = $dbh->query($sql); //SQLの実行
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    $_SESSION['reset_mail'] = $result['mail'];
    if ($result == FALSE) { //失敗
        $error_message = "このURLはご利用できません．有効期限が過ぎたなどの問題があります．";
        return FALSE;
    }

    return TRUE;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $APP_TITLE_EXPLAIN . $APP_TITLE; ?> | パスワードのリセット</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">

  <style>
        html, body {
        height: 100%;
    }

    header {
        background-color: #f5f5f5;
    }
    body {
        -ms-flex-align: center;
        align-items: center;
        padding-bottom: 40px;
    }

    .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
    }

    .form-signin .checkbox {
        font-weight: 400;
    }

    .form-signin .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
    }

    .form-signin .form-control:focus {
        z-index: 2;
    }

    .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    </style>
</head>

<body class="text-center">
<?php
  require_once(__DIR__ . "/header.php");
?>
   <img class="mb-4" src="image/injection.png" alt="" height="72">
    <h1 class="h3 mb-3 font-weight-normal">
        <a href="index.php">
<?php
    echo $APP_TITLE_EXPLAIN . "<br>";
    echo $APP_TITLE;
?></a>
    </h1>
    <h2 class="h5 mb-3 font-weight-normal">新しいパスワードを入力してください．</h2>
    <form class="form-signin needs-validation" action="request_password_reset_update.php" method="POST">
        <div class="row">
            <label for="password">パスワード</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
            <div class="invalid-feedback">
                    パスワードを入力してください
            </div>
        </div>
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <button class="btn btn-lg btn-primary btn-block mt-3" type="submit">パスワードのリセット</button>
    </form>


<script type="text/javascript">
// 無効なフィールドがある場合にフォーム送信を無効にするスターターJavaScriptの例
(function() {
  'use strict';

  window.addEventListener('load', function() {
    // Bootstrapカスタム検証スタイルを適用してすべてのフォームを取得
    var forms = document.getElementsByClassName('needs-validation');

    // ループして帰順を防ぐ
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();</script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>