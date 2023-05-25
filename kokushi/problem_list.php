<?php
//問題のリスト

session_start();

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

require_once(__DIR__ . "/functions.php");

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
}
$dbh = connectDB();

//右側の表示に関するパラメータ
if (!(isset($_GET['showing_number_year_id']) && 
      isset($_GET['showing_ampm']))) { //GETパラメータがない場合は，一番最新の問題を表示させる

    $sql = 'SELECT `id` FROM `number_year_tb` ORDER BY `id` DESC';
    $sth = $dbh->query($sql);
    $showing_number_year_id = $sth->fetch()['id'];
    $showing_ampm = "am";

}else{
    $showing_number_year_id = $_GET['showing_number_year_id']; //ID
    $showing_ampm = $_GET['showing_ampm']; //午前か午後か
}

//削除処理
if (isset($_GET['deleting_problem_id'])) {
    //削除処理 (全体)
    $sql = 'DELETE FROM `problem_tb` WHERE `id`="' . $_GET['deleting_problem_id'] . '"';
    $sth = $dbh->query($sql); //実行
    //削除処理 (選択肢)
    $sql = 'DELETE FROM `option_tb` WHERE `problem_id`="' . $_GET['deleting_problem_id'] . '"';
    $sth = $dbh->query($sql); //実行
    //削除処理 (回答)
    $sql = 'DELETE FROM `answer_tb` WHERE `problem_id`="' . $_GET['deleting_problem_id'] . '"';
    $sth = $dbh->query($sql); //実行
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
        <h3>問題のリスト</h3>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="list-group">
<?php
$dbh = connectDB();
//試験の年のリスト
$sql = 'SELECT * FROM `number_year_tb` ORDER by `number` DESC';
$sth = $dbh->query($sql);
if ($sth == FALSE) {
    echo '</body></html>';
    die;
}

$ampm_array = array('am', 'pm');
while ($row = $sth->fetch()) {
    echo '<div class="list-group-item mt-3">第'. $row['number'] . '回 (' . $row['year'] . '年'. $row['month'] . '月' . $row['date'] . '日)</div>';
    echo '<div class="list-group">';
    foreach ($ampm_array as $ampm) {
        echo '<a href="problem_list.php?showing_number_year_id=' . $row['id'] .'';
        if ($ampm=='am') {
            echo '&showing_ampm=am';
        }else{
            echo '&showing_ampm=pm';
        }
        echo '" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">';
        if ($ampm=='am') {
            echo '午前';
        }else{
            echo '午後';
        }
        echo '<span class="badge badge-primary badge-pill">';
        $sql_ampm = 'SELECT count(*) as `problem_num` FROM `problem_tb` WHERE `ampm`="' . $ampm . '" AND `number_year_id`="' . $row['id'] . '"';
        $sth_ampm = $dbh->query($sql_ampm);
        $problem_num = $sth_ampm->fetch();

        //数字ここから
        echo $problem_num['problem_num'];
        //ここまで
        echo '</span>';
        echo '</a>';
    }
    echo '</div>';
}
?>
            </div> <!-- list-group -->
        </div> <!-- col-md-4 -->
        <div class="col-md-8">
            <div class="container">
<?php
$sql = 'SELECT * FROM `number_year_tb` WHERE `id`="' . $showing_number_year_id . '"';
$sth = $dbh->query($sql);
$row = $sth->fetch();
echo '<h3>第'. $row['number'] . '回 (' . $row['year'] . '年'. $row['month'] . '月' . $row['date'] . '日) ';
if ($showing_ampm=="am") {
    echo '午前';
}else{
    echo '午後';
}
echo '</h3>';
echo '<small class="text-muted">問題番号は登録順です．</small>';

$sql = 'SELECT * FROM `problem_tb` WHERE `number_year_id`="' . $showing_number_year_id . '" AND `ampm`="' . $showing_ampm . '"';
$sth = $dbh->query($sql);
$problem_num = 1;

while ($row = $sth->fetch()) {
    echo '<hr>';
    echo '<p>問' . $problem_num . '. ' . $row['problem'] . '</p>';
    if ($row['answer_type'] == 0) { //選択肢の場合
        //回答の取得
        $sql_answer = 'SELECT * FROM `answer_tb` WHERE `problem_id`="' . $row['id'] . '"';
        $sth_answer = $dbh->query($sql_answer);
        $answer_array = array();
        while($answer = $sth_answer->fetch()) {
            $answer_array[] = $answer['option_id'];
        }

        //選択肢の取得
        $sql_option = 'SELECT * FROM `option_tb` WHERE `problem_id`="' . $row['id'] . '"';
        $sth_option = $dbh->query($sql_option);
        $option_sign = 'a'; //回答記号
        while ($row_option = $sth_option->fetch()) {
            $right_answer = FALSE;

            foreach ($answer_array as $answer) {
                if ($answer == $row_option['id']) {
                    $right_answer = TRUE;
                    break;
                }
            }

            if ($right_answer == TRUE) {
                echo '<p class="text-primary"><strong>';
            }else{
                echo '<p>';
            }
            echo '(' . $option_sign . ') ' . $row_option['option'];
            if ($right_answer == true) {
                echo '</strong>';
            }
            echo '</p>';
            $option_sign++;
        }
    }else if ($row['answer_type'] == 1) { //数値回答の場合
        $sql_number = 'SELECT * FROM `answer_tb` WHERE `problem_id`="' . $row['id'] . '" ORDER BY `id` ASC';
        $sth_number = $dbh->query($sql_number);
        while ($row_number = $sth_number->fetch()) {
            echo '<u class="text-primary">' . $row_number['number_value'] . '</u> ';
        }
        echo '<div class="mb-3"></div>';
    }
    //編集ボタン (現時点では未実装)
    echo '<a href="problem_editing.php?id='.$row['id'].'" role="button" class="btn btn-secondary mr-3">編集</a>';
    //削除ボタン
    echo '<a href="problem_list.php?showing_number_year_id=' . $showing_number_year_id . '&showing_ampm=' . $showing_ampm . '&deleting_problem_id=' . $row['id'] . '" role="button" class="btn btn-danger"  onclick="return confirm(\'問題を削除します．よろしいですか?\')">削除</a>';
    $problem_num++;
} //while loop end

?>
            </div> <!-- container -->
        </div> <!-- col-md-8 -->
    </div> <!-- row -->
</div> <!-- container -->

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>