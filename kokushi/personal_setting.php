 <?php
//個人設定
session_start();
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/functions.php");

if (!isset($_SESSION['login'])) {
    header("Location: index.php"); //トップに移動
}
if ($_SESSION['login'] != "success") { //ログインしていない場合
    header("Location: index.php"); //トップに移動
}

$error_message = array();
if (!empty($_POST) && isset($_POST['update_flag'])) { //更新の可能性あり
    updatePersonalInfo(); //個人情報の更新
}



//個人情報の更新
function updatePersonalInfo () {
    global $error_message;

    $dbh = connectDB();

    $error_flag = false;
    if (!$dbh) {
        $error_message[] = "データベースの接続に失敗しました．<br>管理者にご連絡ください．";
        $error_flag = true;
    }
    if ($_POST['family_name'] == "") {
        $error_message[] = "姓が入力されていません";
        $error_flag = true;
    }
    if ($_POST['given_name'] == "") {
        $error_message[] = "名が入力されていません";
        $error_flag = true;
    }
    if ($_POST['family_name_kana'] == "") {
        $error_message[] = "せいが入力されていません";
        $error_flag = true;
    }
    if ($_POST['given_name_kana'] == "") {
        $error_message[] = "めいが入力されていません";
        $error_flag = true;
    }
    if ($_POST['email'] == "") {
        $error_message[] = "Eメールアドレスが入力されていません．";
        $error_flag = true;
    }
    if ($error_flag==TRUE) {
        return FALSE;
    }


    //POSTパラメータをいれていく
    $sql = 'UPDATE `user_tb` SET `family_name`="' . $_POST['family_name'] . '", `given_name`="'.$_POST['given_name'] . '", `family_name_kana`="' . $_POST['family_name_kana'] . '", `given_name_kana`="' . $_POST['given_name_kana'] . '", `student_id`="' . $_POST['student_id'] . '"';
    if ($_POST['password']!="") {
        $sql .= ', `password`="' . password_hash($_POST['password'], PASSWORD_DEFAULT) . '"';
    }
    $sql .= ' WHERE `id`=' . $_SESSION['id'];
    $sth = $dbh->query($sql); //SQLの実行

    $_SESSION['family_name'] = $_POST['family_name'];
    $_SESSION['given_name'] = $_POST['given_name'];
    $_SESSION['family_name_kana'] = $_POST['family_name_kana'];
    $_SESSION['given_name_kana'] = $_POST['given_name_kana'];
    $_SESSION['student_id'] = $_POST['student_id'];


    //所属がそのままでEメールが変わっていた場合
    if ($_SESSION['email'] != $_POST['email']) {
        
        //ドメインの取得
        $domain = substr($_POST['email'], strpos($_POST['email'], "@")+1);

        $sql = 'SELECT * FROM `affiliation_tb` WHERE `affiliation`="' . $_POST['affiliation'] . '" AND `domain`="' . $domain . '"';
        $sth = $dbh->query($sql); //SQLの実行
        $result = $sth->fetch(PDO::FETCH_ASSOC); //値の取得

        //登録ドメインに所属の追加
        if ($result==FALSE) {
            $sql = 'INSERT INTO `affiliation_tb` (`affiliation`, `domain`) VALUES ("' . $_POST['affiliation'] . '", "' . $domain . '")';
            $dbh->query($sql); //SQLの実行
        }

        $_SESSION['email'] = $_POST['email'];
    }

    //所属が変更の場合
    if ($_POST['affiliation'] != $_SESSION['affiliation'] && $_POST['affiliation']!=")") {
        //所属の存在確認
        $sql = 'SELECT * FROM `affiliation_tb` WHERE `affiliation`="' . $_POST['affiliation'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
        $result_domain = $sth->fetch(PDO::FETCH_ASSOC); //値の取得

        if ($result_domain == FALSE) {//所属が存在しない場合
            $domain = substr($_POST['email'], strpos($_POST['email'], "@")+1);
            //ドメイン被りがないか確認
            $sql ='SELECT `domain` FROM `affiliation_tb` WHERE `domain`="' . $domain .'"';
            $sth = $dbh->query($sql); //SQLの実行
            $result_domain = $sth->fetch(PDO::FETCH_ASSOC); //値の取得

            //ドメインが被っていない場合
            if ($result_domain == false) {
                //追加
                $sql = 'INSERT INTO `affiliation_tb` (`affiliation`, `domain`) VALUES ("' . $_POST['affiliation'] . '", "' . $domain . '")';
                $dbh->query($sql); //SQLの実行
            }else{
                //実行不可
                $error_message[] = "このドメインには所属が与えられています．";
                return FALSE;
            }
        }

        $sql = 'UPDATE `user_tb` SET `user_tb`.`affiliation_id`=(SELECT `affiliation_tb`.`id` FROM `affiliation_tb` WHERE `affiliation_tb`.`affiliation`="'. $_POST['affiliation'] . '") WHERE `user_tb`.`id`=' . $_SESSION['id'];
        $sth = $dbh->query($sql); //SQLの実行
        if ($sth != FALSE) {
            $_SESSION['affiliation'] = $_POST['affiliation'];
        }
        
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $APP_TITLE_EXPLAIN . " " . $APP_TITLE; ?> | 個人設定</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  
</head>

<body>
<?php
  require_once(__DIR__ . "/header.php");
?>
<div class="container">
    <div class="py-3 text-center">
        <div class="lead">
            個人設定
        </div>
    </div>
    <div class="row">
<?php
    if (!empty($error_message)) {
        echo '<div class="col-md-6 offset-md-3">';
        echo '<div class="alert alert-dismissible alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>';
        foreach($error_message as $value) {
            echo $value . '<br>';
        }
        echo '</div></div>';
    }else if(!empty($_POST) && isset($_POST['update_flag'])){
        echo "<script type='text/javascript'>alert('情報を更新しました');</script>";
    }
?>
        <div class="col-md-8 offset-md-2">
            <form class="needs-validation" novalidate action="" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="familyName">姓</label>
                        <input type="text" class="form-control" name="family_name" id="familyName" placeholder="" value="<?php echo $_SESSION['family_name'];?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="givenName">名</label>
                        <input type="text" class="form-control" name="given_name" id="givenName" placeholder="" value="<?php echo $_SESSION['given_name'];?>" required>
                        <div class="invalid-feedback">
                                名前を入力してください
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="familyNameKana">せい</label>
                            <input type="text" class="form-control" name="family_name_kana" id="familyNameKana" placeholder="" value="<?php echo $_SESSION['family_name_kana'];?>" required>
                            <div class="invalid-feedback">
                                名字をひらがなで入力してください
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="givenNameKana">めい</label>
                            <input type="text" class="form-control" name="given_name_kana" id="givenNameKana" placeholder="" value="<?php echo $_SESSION['given_name_kana'];?>" required>
                            <div class="invalid-feedback">
                                名前をひらがなで入力してください
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email">Eメール</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="you@example.com" value="<?php echo $_SESSION['email'];?>"
                        required >
                        <div class="invalid-feedback">
                            有効なメールアドレスを入力してください
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password">パスワード</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="" required>
                        <input type="checkbox" name="pass-visible" id="toggle-password"><label for="toggle-password">パスワードを表示</label>
                        <div class="invalid-feedback">
                            パスワードを入力してください
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="affiliation">所属 (大学・専門学校名) <span class="text-muted">(任意)</span></label>
                        <input type="text" class="form-control" name="affiliation" id="affiliation" placeholder="所属 (大学・専門学校名)" value="<?php echo $_SESSION['affiliation'];?>"
                        >
                    </div>
                    <div class="mb-3">
                        <label for="student_id">学籍番号 <span class="text-muted">(任意)</span></label>
                        <input type="text" class="form-control" name="student_id" id="student_id" placeholder="学籍番号" value="<?php echo $_SESSION['student_id'];?>">
                    </div>
                    <input type="hidden" name="update_flag" value=1>
                    <hr class="mb-2">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">更新</button>
                </form>
                <div class="mt-4">
                    <a href="index.php">トップに戻る</a>
                </div>
            </div>
        </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
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
</body>
</html>