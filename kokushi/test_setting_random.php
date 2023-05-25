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
  <form action="test_random.php" method="post">
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
            <option value="60">60</option>
            <option value="70">70</option>
            <option value="80">80</option>
            <option value="90">90</option>
            <option value="100">100</option>
        </select>
    </div>
    <button type="submit" class="btn btn-outline-secondary">問題を解く</button>
  </form>
</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="./js/bootstrap.min.js"></script>


</body>
</html>
