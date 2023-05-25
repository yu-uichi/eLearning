<?php
//問題作成フォーム

session_start();

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

require_once(__DIR__ . "/functions.php");

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
}

//初期値設定
if (!isset($_SESSION['problem_year'])) { //年
    $dbh = connectDB(); //DB接続
    $sql = "SELECT MAX(`year`) `max_year` FROM `number_year_tb`";
    $sth = $dbh->query($sql); //SQLの実行
    $result = $sth->fetch(PDO::FETCH_ASSOC); //値の取得
    $_SESSION['problem_year'] = $result['max_year']; //登録されている最新の年
}
if (!isset($_SESSION['problem_ampm'])) { //午前午後
    $_SESSION['problem_ampm'] = 'am';
}

//-------------------------------------
//FORMが送られてきた内容を次でも利用
if (isset($_POST['year'])) { //年
    $_SESSION['problem_year'] = $_POST['year']; //登録したページの値を使用する
}

if (isset($_POST['ampmRadios'])) { //午前午後
    $_SESSION['problem_ampm'] = $_POST['ampmRadios'];
}
//-------------------------------------

problemRegistration();

//問題の登録
function problemRegistration() {
    //登録処理
    if (isset($_POST['year']) && //年
        isset($_POST['ampmRadios']) && //午前午後
        isset($_POST['classificationSelect']) && //分類
        isset($_POST['regionSelect']) && //領域
        isset($_POST['problemTextarea']) && //問題文
        $_POST['problemTextarea'] != "" && //問題文が空白でない
        isset($_POST['optionRadios']) ) { //問題選択
        if ($_POST['optionRadios'] == 'optionProblem') {
            if (!isset($_POST['answerProblemOption'])) {
                echo "<script type='text/javascript'>window.alert('選択肢問題で，正答がありません');</script>";
                return;
            }
        }

        $dbh = connectDB(); //DB接続

        //年の取得
        $sql = 'SELECT `id` FROM `number_year_tb` WHERE `year`="' . $_POST['year'] . '"';
        $sth = $dbh->query($sql);
        $year_id = $sth->fetch()['id'];
        //分類の取得
        $sql = 'SELECT `id` FROM `classification_tb` WHERE `classification`="' . $_POST['classificationSelect'] . '"';
        $sth = $dbh->query($sql);
        $classification_id = $sth->fetch()['id'];
        //領域の取得
        $sql = 'SELECT `id` FROM `region_tb` WHERE `region`="' . $_POST['regionSelect'] . '"';
        $sth = $dbh->query($sql);
        $region_id = $sth->fetch()['id'];
        $answer_type = 0; //選択問題
        if ($_POST['optionRadios'] == 'numberProblem') { //計算問題の場合
            $answer_type = 1;
        }

        $upload_file_name = NULL;

        //画像ファイルが存在しているか
        if(isset($_FILES)&&
            isset($_FILES['problemImgInputFile']) &&
            is_uploaded_file($_FILES['problemImgInputFile']['tmp_name'])){

            if(!file_exists('problem_image')){ //フォルダの存在確認
                mkdir('problem_image');
            }
            date_default_timezone_set('Asia/Tokyo'); //タイムゾーンの設定

            $upload_file_name = 'problem_image/' . date("YmdHis") . basename($_FILES['problemImgInputFile']['name']);
            //$upload_file_name = 'problem_image/' . date("YmdHis") . "jpg";

            if(!move_uploaded_file($_FILES['problemImgInputFile']['tmp_name'], $upload_file_name)){
                //アップロード失敗
                $upload_file_name = NULL;
            }
        }
        //問題文の登録
        $sql = 'INSERT INTO `problem_tb` (`number_year_id`, `ampm`, `problem`, `answer_type`, `region_id`, `classification_id`, `image_path`) VALUES("' . $year_id .'", "' . $_POST['ampmRadios'] . '", "' . htmlspecialchars($_POST['problemTextarea'], ENT_QUOTES) . '", "'. $answer_type . '", "' . $region_id . '", "' . $classification_id . '", ';
        if ($upload_file_name != NULL) {
            $sql .= '"' . $upload_file_name . '"';
        }else{
            $sql .= 'NULL';
        }
        $sql .= ')';
        //echo $sql;
        $dbh->exec($sql); //SQLの実行
        $problem_id = $dbh->lastInsertID(); //問題ID

        //空白と同一内容の選択肢チェックが必要
        if ($_POST['optionRadios'] == 'optionProblem') { //選択問題
            $answer_problem_option = array(); //配列の初期化

            //選択肢の登録
            for ($i=0; $i<count($_POST['problemOption']); $i++) {
                $upload_option_image_file_name = NULL;

                //画像ファイルが存在しているか
                if(isset($_FILES)&&
                    isset($_FILES['optionImgInputFile']) &&
                    is_uploaded_file($_FILES['optionImgInputFile']['tmp_name'][$i])){

                    if(!file_exists('problem_image')){ //フォルダの存在確認
                        mkdir('problem_image');
                    }
                    date_default_timezone_set('Asia/Tokyo'); //タイムゾーンの設定

                    $upload_option_image_file_name = 'problem_image/' . date("YmdHis") . basename($_FILES['optionImgInputFile']['name'][$i]);
                    //$upload_file_name = 'problem_image/' . date("YmdHis") . "jpg";

                    if(!move_uploaded_file($_FILES['optionImgInputFile']['tmp_name'][$i], $upload_option_image_file_name)){
                        //アップロード失敗
                        $upload_option_image_file_name = NULL;
                    }
                }

                //選択肢挿入
                $sql = 'INSERT INTO `option_tb` (`problem_id`, `option`, `image_path`) VALUES ("' . $problem_id . '", "' . $_POST['problemOption'][$i] . '", ';
                if ($upload_option_image_file_name != NULL) {
                    $sql .= '"' . $upload_option_image_file_name . '"';
                }else{
                    $sql .= 'NULL';
                }
                $sql .=')';
                $dbh->exec($sql); //SQLの実行
                $answer_problem_option[] = $dbh->lastInsertID(); //選択肢IDの格納
            }

            //回答の登録
            for ($i=0; $i<count($_POST['answerProblemOption']); $i++) {
                $sql = 'INSERT INTO `answer_tb` (`problem_id`, `option_id`) VALUES ("' . $problem_id . '", "' . $answer_problem_option[$_POST['answerProblemOption'][$i]] . '")';
                $dbh->exec($sql); //SQLの実行
            }

        }else{ //計算問題
            //数字の登録
            for ($i=0; $i<count($_POST['answerNumber']); $i++) {
                $sql = 'INSERT INTO `answer_tb` (`problem_id`, `number_value`) VALUES ("' . $problem_id . '", "' . $_POST['answerNumber'][$i] . '")';
                $dbh->exec($sql); //SQLの実行
            }
            return;
        }
    }else{
        return;
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $APP_TITLE_EXPLAIN . $APP_TITLE; ?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>

<?php
    require_once(__DIR__ . "/header.php");
?>

<div class="container mt-3">
    <div class="row">
        <div class="mx-auto">
            <h3>
                問題の作成
            </h3>
        </div>
        <div class="container mt-3">
            <div class="col-md-6 mx-auto">
                <form action="#" method="POST" onSubmit="return check()" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="form-inline mb-3">
                            <div class="input-group">
                                <input type="number" class="form-control" name="year" value=<?php
                                 echo '"' . $_SESSION['problem_year'] . '"';
                                 $dbh = connectDB(); //DB接続
                                 $sql = "SELECT MIN(`year`) as `min_year`, MAX(`year`) `max_year` FROM `number_year_tb`";
                                 $sth = $dbh->query($sql); //SQLの実行
                                 $result = $sth->fetch(PDO::FETCH_ASSOC); //値の取得
                                 echo ' min="' . $result['min_year'] . '" max="' . $result['max_year'] . '"';
                                 ?>>
                                <div class="input-group-append">
                                    <span class="input-group-text">年</span>
                                </div>
                            </div>
                            <div class="form-check form-check-inline ml-2">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="ampmRadios" id="amRadio" value="am" 
                                    <?php
                                        if($_SESSION['problem_ampm']=='am') {
                                            echo 'checked';
                                        }
                                    ?>>
                                    午前
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="ampmRadios" id="pmRadio" value="pm"
                                    <?php
                                        if($_SESSION['problem_ampm']=='pm') {
                                            echo 'checked';
                                        }
                                    ?>>
                                    午後
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="classification">分類</label>
                        <select class="form-control" name="classificationSelect">
                        <?php
                            $dbh = connectDB();
                            $sql = 'SELECT * FROM `classification_tb`';
                            $sth = $dbh->query($sql);
                            $selectec_flag = TRUE;
                            while ($row = $sth->fetch()) {
                                echo '<option';
                                if ($selectec_flag == TRUE) {
                                    echo ' selected';
                                    $selectec_flag = FALSE;
                                }
                                echo '>' . $row['classification'] . '</option>';
                            }
                        ?>
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="classification">領域</label>
                        <select class="form-control" name="regionSelect">
                        <?php
                            $dbh = connectDB();
                            $sql = 'SELECT * FROM `region_tb`';
                            $sth = $dbh->query($sql);
                            $selectec_flag = TRUE;
                            while ($row = $sth->fetch()) {
                                echo '<option';
                                if ($selectec_flag == TRUE) {
                                    echo ' selected';
                                    $selectec_flag = FALSE;
                                }
                                echo '>' . $row['region'] . '</option>';
                            }
                        ?>
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="problemTextarea">問題文</label>
                        <textarea class="form-control" name="problemTextarea" id="problemTextarea" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                    <input type="file" class="form-control-file" name="problemImgInputFile" aria-describedby="fileHelp" accept="image/png,image/jpeg,image/gif" id="problemImgInputFile">
                    <small id="fileHelp" class="form-text text-muted">画像があればアップロードしてください．</small>
                </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="optionRadios" id="optionProblem" value="optionProblem" checked>
                        選択問題
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="optionRadios" id="numberProblem" value="numberProblem">
                        計算問題
                        </label>
                    </div>
                    <fieldset class="form-group" id="problemList">
                        <label class="mt-2">選択肢の文 (正解にチェックしてください)</label>
                        <div id='check-alert'><label class="mt-2">正解となるチェックがありません</label></div>
                        <?php
                            for ($i=0; $i<4; $i++) {
                                echo '<div class="form-check mt-2">
                                <input class="form-check-input form-inline mt-3" type="checkbox" name="answerProblemOption[]" value="' . $i . '">
                                <input type="text" class="form-control form-inline" placeholder="選択肢の文" name="problemOption[]">
                                <input type="file" class="form-control-file form-inline" name="optionImgInputFile[]" aria-describedby="fileHelp" accept="image/png,image/jpeg,image/gif" id="optionImgInputFile' . $i . '">
                                </div>';
                            }
                        ?>
                    </fieldset>
                    <button type="button" class="btn btn-secondary" id="problemAdditionBt">+</button>
                    <button type="button" class="btn btn-secondary" id="problemSubtractionBt">-</button>
                    <fieldset class="form-group"  id="numberList">
                        <label class="mt-2">計算問題 (数字を選択してください)</label>
                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <?php
                                    for($i=0; $i<=9; $i++) {
                                        echo '<label class="btn btn-outline-primary';
                                        if ($i==0){
                                            echo ' active';
                                        }
                                        echo '">
                                        <input type="radio" name="answerNumber[0]" autocomplete="off" value="' . $i . '"';
                                        if ($i==0) {
                                            echo ' checked';
                                        }
                                        echo '>' . $i . '
                                        </label>
                                        ';
                                    }
                                ?>
                            </div>
                        </div>
                    </fieldset>
                    <button type="button" class="btn btn-secondary" id="numbersAdditionBt">+</button>
                    <button type="button" class="btn btn-secondary" id="numbersSubtractionBt">-</button>
                    <fieldset class="mt-3">
                    <button type="submit" class="btn btn-primary">問題の登録</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>

<script>

var option_list_num = 4; //選択肢の番号
var number_lists_num = 1; //数字の番号
$('#numberList').hide();
$('#numbersAdditionBt').hide();
$('#numbersSubtractionBt').hide();
$('#check-alert').hide();

//選択肢追加ボタン
$('#problemAdditionBt').click(function(){
    $('#problemList').append('<div class="form-check mt-2"><input class="form-check-input form-inline mt-2" type="checkbox" value="' + option_list_num + '" name="answerProblemOption[]"><input type="text" class="form-control form-inline" placeholder="選択肢の文" name="problemOption[]"><input type="file" class="form-control-file form-inline" name="optionImgInputFile[]" aria-describedby="fileHelp" accept="image/png,image/jpeg,image/gif" id="optionImgInputFile' + option_list_num + '"></div>');
    option_list_num++;
});

//選択肢削除ボタン
$('#problemSubtractionBt').click(function(){
    $('#problemList').find('div:last').remove();
    option_list_num--;
});

//数字追加ボタン
$('#numbersAdditionBt').click(function(){
    $('#numberList').append('<div class="btn-toolbar mt-2" role="toolbar" aria-label="Toolbar with button groups"><div class="btn-group btn-group-toggle" data-toggle="buttons"><?php
                                    for($i=0; $i<=9; $i++) {
                                        echo '<label class="btn btn-outline-primary';
                                        if ($i==0){
                                            echo ' active';
                                        }
                                        echo '"><input type="radio" autocomplete="off"  name="answerNumber[\' + number_lists_num + \']" value="' . $i . '"';
                                        if ($i==0) {
                                            echo ' checked';
                                        }
                                        echo '>' . $i . '</label>';
                                    }
                                ?></div></div>');
    number_lists_num++;
});

//数字の削除
$('#numbersSubtractionBt').click(function(){
    //数字のリストは二つのdivで構成されているため，二つ削除
    $('#numberList').find('div:last').remove();
    $('#numberList').find('div:last').remove();
    number_lists_num--;
});

//問題の選択肢を変更したら反応する関数
$('input[name="optionRadios"]').change(function() {
    $('#problemList').toggle();
    $('#problemAdditionBt').toggle();
    $('#problemSubtractionBt').toggle();
    $('#numberList').toggle();
    $('#numbersAdditionBt').toggle();
    $('#numbersSubtractionBt').toggle();
})
//投稿確認
function check(){
    if($('input[name="optionRadios"]').value=='optionProblem'){
        if($('input[type="checkbox"]:checked').length==0){
            $('#check-alert').show();
            return false;
        }else{
            $('#check-alert').hide();
            if(window.confirm('送信してよろしいですか?')){ // 確認ダイアログを表示
                return true; // 「OK」時は送信を実行
            }else{ // 「キャンセル」時の処理
                window.alert('キャンセルされました'); // 警告ダイアログを表示
                return false; // 送信を中止
            }
        }
    }else{
        if(window.confirm('送信してよろしいですか?')){ // 確認ダイアログを表示
                return true; // 「OK」時は送信を実行
            }else{ // 「キャンセル」時の処理
                window.alert('キャンセルされました'); // 警告ダイアログを表示
                return false; // 送信を中止
            }
    }
    
}
</script>

</body>
</html>