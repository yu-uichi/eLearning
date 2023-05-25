<?php
//メール登録フォーム
session_start();
require_once(__DIR__ . "/functions.php");
//参考URL: https://noumenon-th.net/programming/2016/02/27/registration2/

//クロスサイトリクエストフォージェリ（CSRF）対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $APP_TITLE_EXPLAIN . " " . $APP_TITLE; ?> | メールの登録</title>
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
<form class="form-signin needs-validation" action="registration_mail_check.php" method="POST">
    <img class="mb-4" src="image/injection.png" alt="" height="72">
    <h1 class="h3 mb-3 font-weight-normal"><a href="index.php">
        <?php 
            echo $APP_TITLE_EXPLAIN . "<br>";
            echo $APP_TITLE; 
        ?></a></h1>
    <h2 class="h5 mb-3 font-weight-normal">Eメールの登録画面</h2>


    <div class="row">
        <input type="email" id="inputEmail" class="form-control" name="mail" placeholder="you@example.com" required autofocus>
        <div class="invalid-feedback">
            有効なメールアドレスを入力してください
        </div>
        <label for="inputEmail" class="text-success">所属先 (大学・専門学校) のEメールがお勧め</label>
    </div>
    <input type="hidden" name="token" value="<?php echo $token; ?>">
    <button class="btn btn-lg btn-primary btn-block mt-3" type="submit">サインアップ</button>
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