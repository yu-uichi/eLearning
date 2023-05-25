<?php
//登録フォーム
//一般ユーザはメールから飛んでくる
//管理者は直接設定できる

session_start();
require_once(__DIR__ . "/functions.php");

//参考URL: https://noumenon-th.net/programming/2016/02/28/registration3/

//クロスサイトリクエストフォージェリ（CSRF）対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//管理者確認
$admin_flag = FALSE; //デフォルト: 管理者ではない状態
if (isset($_SESSION['admin'])) {
    if ($_SESSION['admin'] == TRUE) {
        $admin_flag = TRUE; //管理者
    }
}

if (empty($_GET) && $admin_flag != TRUE) { //空っぽで，かつ管理者ではない場合
    header('Location: index.php'); //トップページに飛ばす
}

$mail = "";
$error_message = "";
$urltoken = "";
$affiliation = "";

if ($admin_flag == FALSE) { //管理者ではない場合
    //GETデータを変数に格納．GETデータは，URLの後ろについてくる値
    $urltoken = isset($_GET['urltoken']) ? $_GET['urltoken'] : null;
    tokenConfirmation ($urltoken);
}


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
    $sql = 'SELECT `mail` FROM `pre_member_tb` WHERE `urltoken`="' . $urltoken . '" AND `flag`=0 AND `date` > now()-interval 24 hour';
    $sth = $dbh->query($sql); //SQLの実行
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    if ($result == FALSE) { //失敗
        $error_message = "このURLはご利用できません．有効期限が過ぎたなどの問題があります．";
        return;
    }
    $mail = $result['mail'];
    $domain = substr($mail, strpos($mail, "@")+1);
    if (strpos($domain, 'ac.jp') != false) { //ac.jpだけは所属を固定させる
        $sql = 'SELECT `affiliation` FROM `affiliation_tb` WHERE `domain`="' . $domain . '"';
        $sth = $dbh->query($sql); //SQLの実行
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if ($result != false) { //失敗ではない場合
            $affiliation = $result['affiliation']; //所属先の取得
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $APP_TITLE_EXPLAIN . " " . $APP_TITLE; ?> | ユーザ登録</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
</head>

<body>
<?php
  require_once(__DIR__ . "/header.php");
?>

    <div class="container">
<?php
    if ($error_message != "") { //エラーが存在する場合
        echo '<p>' . $error_message . '</p>';
        echo '</div>';
        echo '</body>';
        echo '</html>';
        die;
    }
?>
        <div class="py-3 text-center">
            <div class="lead">
                ユーザ登録
                <?php
                    //管理者のみ
                    if (isset($_SESSION['admin'])) {
                        if ($_SESSION['admin'] == TRUE) {
                            echo '<div class="text-danger">(管理者向け) </div>';
                        }
                    }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form class="needs-validation" novalidate action="registration_comfirmation.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="familyName">姓</label>
                            <input type="text" class="form-control" name="familyName" id="familyName" placeholder="" value="" required>
                            <div class="invalid-feedback">
                                名字を入力してください
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="givenName">名</label>
                            <input type="text" class="form-control" name="givenName" id="givenName" placeholder="" value="" required>
                            <div class="invalid-feedback">
                                名前を入力してください
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="familyNameKana">せい</label>
                            <input type="text" class="form-control" name="familyNameKana" id="familyNameKana" placeholder="" value="" required>
                            <div class="invalid-feedback">
                                名字をひらがなで入力してください
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="givenNameKana">めい</label>
                            <input type="text" class="form-control" name="givenNameKana" id="givenNameKana" placeholder="" value="" required>
                            <div class="invalid-feedback">
                                名前をひらがなで入力してください
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email">Eメール</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="you@example.com"
                        <?php
                            //メールが登録されている場合
                            if ($mail != "") {
                                echo ' value="' . $mail . '" readonly';
                            }else{
                                echo 'required';
                            }
                        ?>
                        >
                        <div class="invalid-feedback">
                            有効なメールアドレスを入力してください
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password">パスワード</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required><span><i class="fa-regular fa-eye"></i></span>
                        <div class="invalid-feedback">
                            パスワードを入力してください
                        </div>
                        <input type="checkbox" name="pass-visible" id="toggle-password"><label for="toggle-password">パスワードを表示</label>
                    </div>

                    <div class="mb-3">
                        <label for="affiliation">所属 (大学・専門学校名) <span class="text-muted">(任意)</span></label>
                        <input type="text" class="form-control" name="affiliation" id="affiliation" placeholder="所属 (大学・専門学校名)"
                        <?php
                            echo ' value="' . $affiliation . '"';
                        ?>
                        >
                    </div>
                    <div class="mb-3">
                        <label for="student_id">学籍番号 <span class="text-muted">(任意)</span></label>
                        <input type="text" class="form-control" name="student_id" id="student_id" placeholder="学籍番号">
                    </div>
<?php
                    if ($admin_flag == true) { //管理者の場合
                        echo '<div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="teacherCheck" id="teacherCheck">
                            <label class="custom-control-label" for="teacherCheck">教員</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="adminCheck" id="adminCheck">
                            <label class="custom-control-label" for="adminCheck">管理者</label>
                        </div>';
                    }
?>
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <hr class="mb-2">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">登録</button>
                </form>
                <div class="mt-4">
                    <a href="index.php">トップに戻る</a>
                </div>
            </div>
        </div>
    </div>
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
<script>
    $("#toggle-password").click(function () {
    // 入力フォームの取得
    let input = $('#password');
    // type切替
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});
</script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>