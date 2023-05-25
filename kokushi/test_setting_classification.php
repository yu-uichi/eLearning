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
  <form action="test_classification.php" method="post">
    <div class="input-group form-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="inputGroupSelect01">分類選択</label>
        </div>
        <select class="custom-select form-control" id="inputGroupSelect01" name="classification">
            <option selected value="2">必修問題</option>
            <option value="3">一般問題</option>
            <option value="4">状況設定問題</option>
        </select>
    </div>

    <div class="input-group form-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="inputGroupSelect02">問題数</label>
        </div>
        <select class="custom-select form-control" id="inputGroupSelect02" name="numOfQue">
            <option selected value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
            <option value="50">50</option>
        </select>
    </div>
    <button type="submit" class="btn btn-outline-secondary">問題を解く</button>
  </form>
</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="./js/bootstrap.min.js"></script>


</body>
</html>
