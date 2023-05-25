<?php
    session_start();
    //クリックジャッキング対策
    header('X-FRAME-OPTIONS: SAMEORIGIN');

    require_once(__DIR__ . "/config.php");
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>総合eラーニングアプリ(仮)(ログイン画面)</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap_org.min.css" />
    <link href="./css/signin.css" rel="stylesheet">
  </head>

  <body class="text-center">
    <main class="form-signin">
      <div class="row">
        <form class="form-signin" action="login_check.php" method="POST">
          <h1 class="h4 mb-3 font-weight-normal">総合eラーニングアプリ(仮)(ログイン)</h1>

          <h2 class="h6 mb-3 font-weight-normal">ログインしてください</h2>

          <?php
          if (isset($_SESSION['login'])) {
              if ($_SESSION['login'] == "failure") {
                  echo '<div class="alert alert-dismissible alert-danger">                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                  Emailアドレスかパスワードが間違っています．
                  </div>';
                  $_SESSION['login'] = NULL;
              }
          }
          ?>

          <label for="inputEmail" class="sr-only">Emailアドレス</label>
          <div class="col-md-12 col-lg-12">
            <input type="email" id="inputEmail" class="form-control" name="email" placeholder="Emailアドレス" required autofocus>
          </div>

          <label for="inputPassword" class="sr-only">パスワード</label>
          <div class="col-md-12 col-lg-12">
            <input type="password" id="inputPassword" name="password" class="form-control" placeholder="パスワード" required>
          </div>

          <button class="btn btn-lg btn-primary btn-block" type="submit">サインイン</button>

          <!-- <div class="mt-4">
            <a href="request_password_reset_form.php">パスワードを忘れた方はこちら</a>
          </div> -->

          <!-- <div class="">
            <button type=“button” class="btn btn-outline-primary" onclick="location.href='guest.php'">guest</button>
          </div> -->

          <p class="mt-5 mb-12 text-muted">&copy; produced by Sawano Lab.</p>
        </form>
      </div>
    </main>  
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="./js/bootstrap.min.js"></script>
  </body>
</html>





