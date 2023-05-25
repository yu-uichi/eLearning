<?php
  session_start();
  //接続用関数の呼び出し
  //require_once(__DIR__.'/functions.php');
  require_once(__DIR__.'/db_setup.php'); //DBの設定
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

<div class="container">
  <div class="col-lg-5 mx-auto mt-5">
    <a href="test_setting_classification.php"><button type="button" class="btn btn-primary btn-block">分類別</button></a>
    <a href="test_setting_region.php"><button type="button" class="btn btn-primary btn-block mt-2">領域別</button></a>
    <a href="test_setting_random.php"><button type="button" class="btn btn-primary btn-block mt-2">ランダム</button></a>
    <a href="test_setting_pastQue.php"><button type="button" class="btn btn-primary btn-block mt-2">過去問</button></a>
    <hr>
<?php
    if (isset($_SESSION['family_name'])) {
      echo '<a href="personal_result.php"><button type="button" class="btn btn-info btn-block">成績</button></a>';
      if ($_SESSION['teacher']==TRUE || $_SESSION['admin']==TRUE) {
        echo '<a href="class_result.php"><button type="button" class="btn btn-success btn-block">クラス成績 (教員メニュー)</button></a>';
        if ($_SESSION['admin']==TRUE) {
          echo '<hr>
          <a href="problem_setting.php"><button type="button" class="btn btn-warning btn-block">問題作成</button></a>';
          echo '<a href="problem_list.php"><button type="button" class="btn btn-success btn-block mt-2">問題のリスト</button></a>';
        }
      }

    }
?>

  </div>

</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="./js/bootstrap.min.js"></script>


</body>
</html>
