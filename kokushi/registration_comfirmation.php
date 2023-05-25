<?php
session_start();

//登録内容の確認
//参考URL: https://noumenon-th.net/programming/2016/02/28/registration3/


if (empty($_POST)) { //何も送られてきていない場合
    echo "POSTが送られていません";
    header("Location: index.php"); //トップに移動
}

//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){
    echo 'tokenが一致しない';
	header("Location: index.php"); //トップに移動
}

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//前後にある半角全角スペースを削除する関数
function spaceTrim ($str) {
	// 行頭
	$str = preg_replace('/^[ 　]+/u', '', $str);
	// 末尾
	$str = preg_replace('/[ 　]+$/u', '', $str);
	return $str;
}

//POSTで送られるデータの格納
$_SESSION['form_family_name'] = isset($_POST['familyName']) ? $_POST['familyName'] : NULL;
$_SESSION['form_given_name'] = isset($_POST['givenName']) ? $_POST['givenName'] : NULL;
$_SESSION['form_family_name_kana'] = isset($_POST['familyNameKana']) ? $_POST['familyNameKana'] : NULL;
$_SESSION['form_given_name_kana'] = isset($_POST['givenNameKana']) ? $_POST['givenNameKana'] : NULL;
$_SESSION['form_email'] = isset($_POST['email']) ? $_POST['email'] : NULL;
$_SESSION['form_password'] = isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES) : NULL;
if (isset($_POST['affiliation'])) {
    if ($_POST['affiliation'] == "") {
        $_SESSION['form_affiliation'] = "未登録";
    }else{
        $_SESSION['form_affiliation'] = $_POST['affiliation'];
    }
}
$_SESSION['form_student_id'] = isset($_POST['student_id']) ? $_POST['student_id'] : NULL;
$_SESSION['form_teacherCheck'] = 0;
$_SESSION['form_adminCheck'] = 0;

//教員かどうか
if (isset($_POST['teacherCheck'])) {
    $_SESSION['form_teacherCheck'] = 1;
}

//管理者かどうか
if (isset($_POST['adminCheck'])) {
    $_SESSION['form_adminCheck'] = 1;
    $_SESSION['form_teacherCheck'] = 1; //管理者の場合，教員フラグもつける
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
        <div class="col-lg-12">
            <div class="page-header">
                <h1 id="tables">登録内容確認画面</h1>
            </div>
            <table class="table">
            <tr>
                <td scope="col">姓</td>
                <td scope="col"><?php echo $_SESSION['form_family_name']; ?></td>
            </tr>
            <tr>
                <td scope="col">名</td>
                <td scope="col"><?php echo $_SESSION['form_given_name']; ?></td>
            </tr>
            <tr>
                <td scope="col">せい</td>
                <td scope="col"><?php echo $_SESSION['form_family_name_kana']; ?></td>
            </tr>
            <tr>
                <td scope="col">めい</td>
                <td scope="col"><?php echo $_SESSION['form_given_name_kana']; ?></td>
            </tr>
            <tr>
                <td scope="col">Eメール</td>
                <td scope="col"><?php echo $_SESSION['form_email']; ?></td>
            </tr>
            <tr>
                <td scope="col">パスワード</td>
                <td scope="col"><?php echo str_repeat('*', strlen($_SESSION['form_password'])); ?></td>
            </tr>
            <tr>
                <td scope="col">所属</td>
                <td scope="col"><?php echo $_SESSION['form_affiliation']; ?></td>
            </tr>
            <tr>
                <td scope="col">学籍番号</td>
                <td scope="col"><?php echo $_SESSION['form_student_id']; ?></td>
            </tr>
            <tr>
                <td scope="col">オプション</td>
                <td scope="col">
                <?php
                if ($_SESSION['form_adminCheck'] == 1) {
                    echo '<span class="badge badge-pill badge-primary">管理者</span>';
                }
                if ($_SESSION['form_teacherCheck'] == 1) {
                    echo '<span class="badge badge-pill badge-success">教員</span>';
                }
                ?>
                </td>
            </tr>
        </table>
    </div>


    <form action="registration_insert.php" method="post">
        <input type="button" value="戻る" onClick="history.back()">
        <input type="hidden" name="token" value="<?php echo $_POST['token']; ?>">
        <input type="submit" value="登録する">
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>