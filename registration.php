<?php
  //セッションのスタート
session_start();
// if(!isset($_SESSION['id'])){
//     header('Location: login.php');
// }
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/functions.php");


//変更
$dbh = connectDB();
if (!$dbh) {
    echo "データベースの接続に失敗しました．<br>";
    echo "管理者にご連絡ください．";
    die;
}
    
$sql = 'SELECT * FROM `affiliation_tb`';

$sth = $dbh->query($sql);
$flag_reg = 0;
while ($row = $sth->fetch ()) {
    if($row['id_aff'] == $_GET['affiliation_id']){
        $flag_reg = 1;
        $id = $row['id'];
        break;
    }else {
        $flag_reg = 0;
    }
}

if($flag_reg == 0){
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

    <div class="container">
        <form class="needs-validation" novalidate action="registration_comfirmation.php" method="POST">
            <div class="container mt-5 p-lg-5 bg-light">
                <h1 class="h4 mb-3 text-center font-weight-normal">看護記録Web(登録画面)</h1>
                <!--氏名-->
                <div class="form-row mb-4">
                    <div class="col-md-6">
                        <label for="lastName">姓</label>
                        <input type="text" class="form-control" name="family_name" placeholder="姓" required>
                        <div class="invalid-feedback">
                            入力してください
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="firstName">名</label>
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

                <!--学籍番号-->
                <div class="form-group row">
                    <label for="gakusekibangou" class="col-sm-2 col-form-label">学籍番号</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="student_id" placeholder="学籍番号" required>
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
                        <input type="hidden" name="affiliation_id" value="<?php echo $id;?>">
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