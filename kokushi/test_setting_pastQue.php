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
  <form action="test_pastQue.php" method="post">
    <div class="input-group form-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="inputGroupSelect01">開催時期</label>
        </div>
        <select class="custom-select form-control" id="inputGroupSelect01" name="pastNum">
            <option selected value="1">第97回</option>
            <option value="2">第98回</option>
            <option value="3">第99回</option>
            <option value="4">第100回</option>
            <option value="5">第101回</option>
            <option value="6">第102回</option>
            <option value="7">第103回</option>
            <option value="8">第104回</option>
            <option value="9">第105回</option>
            <option value="10">第106回</option>
            <option value="11">第107回</option>
            <option value="12">第108回</option>
            <option value="13">第109回</option>
        </select>
    </div>

    <div class="input-group form-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="inputGroupSelect02">午前午後</label>
        </div>
        <select class="custom-select form-control" id="inputGroupSelect02" name="ampm">
            <option selected value="am">午前</option>
            <option value="pm">午後</option>
        </select>
    </div>
    <button type="submit" class="btn btn-outline-secondary">問題を解く</button>
  </form>
</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="./js/bootstrap.min.js"></script>


</body>
</html>
