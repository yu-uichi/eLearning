<?php
  //セッションのスタート
session_start();
if(!isset($_SESSION['id'])){
    header('Location: login.php');
}
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/functions.php");


if(!isset($_SESSION['admin'])){
    header('Location: login.php');
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta　name="viewport"　content="width=device-width, initial-scale=1, shrink-to-fit=no"　/>
        <title>総合eラーニングアプリ(仮)</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </head>
    <body>

    <?php
        require_once(__DIR__ . "/header.php");
    ?>
    
    <hr>


    <div class="container">
        <form class="needs-validation" novalidate action="registration_comfirmation_teacher.php" method="POST">
            <div class="container mt-5 p-lg-5 bg-light">
                <h1 class="h4 mb-3 text-center font-weight-normal">教員登録</h1>
                <!--氏名-->
                <div class="form-row mb-4">
                    <div class="col-md-6">
                        <label for="firstName">姓</label>
                        <input type="text" class="form-control" name="family_name" placeholder="姓" required>
                        <div class="invalid-feedback">
                            入力してください
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">名</label>
                        <input type="text" class="form-control" name="given_name" placeholder="名" required>
                        <div class="invalid-feedback">
                            入力してください
                        </div>
                    </div>
                </div>

                <!--氏名かな-->
                <div class="form-row mb-4">
                    <div class="col-md-6">
                        <label for="firstName">せい</label>
                        <input type="text" class="form-control" name="family_name_kana" placeholder="せい" required>
                        <div class="invalid-feedback">
                            入力してください
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">めい</label>
                        <input type="text" class="form-control" name="given_name_kana" placeholder="めい" required>
                        <div class="invalid-feedback">
                            入力してください
                        </div>
                    </div>
                </div>

                <!--所属-->
                <div class="form-group row">
                    <label for="affiliation" class="col-sm-2 col-form-label">所属</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="affiliation_name" placeholder="所属" required>
                            <div class="invalid-feedback">入力してください</div>
                        </div>
                    </div>

                <!--Eメール-->
                <div class="form-group row">
                    <label for="inputEmail" class="col-sm-2 col-form-label">メール</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="email" placeholder="メールアドレス" required>
                        <div class="invalid-feedback">入力してください</div>
                    </div>
                </div>

                <!--パスワード-->
                <div class="form-group row mb-5">
                    <label for="inputPassword" class="col-sm-2 col-form-label">パスワード</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="password" placeholder="パスワード" required>
                        <div class="invalid-feedback">入力してください</div>
                        <small id="passwordHelpBlock" class="form-text text-muted">パスワードは、文字と数字を含めて8～20文字で、空白、特殊文字、絵文字を含むことはできません。</small>
                    </div>
                </div>

                <!--ボタンブロック-->
                <div class="form-group row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary btn-block">登録</button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"
        ></script>
        <script src="js/nursing_record.js"></script>
    </body>
</html>