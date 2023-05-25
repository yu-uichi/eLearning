<?php
  session_start();
  //接続用関数の呼び出し
  require_once(__DIR__.'/functions.php');
  
?>
<?php
    if(!isset($_POST['userId'])||!isset($_SESSION['id'])){
        //header('Location: index.php');
    }
?>
<?php 
    $dbh = connectDB(); //DB接続
    $time = $_POST['hour'].':'.$_POST['min'].':'.$_POST['sec'];
    $proId = $_POST['proId'];
    $correct = $_POST['correct'];
    $sql = "INSERT INTO `test_id_tb` (`user_id`,`time`,`timestamp`) VALUES('".$_POST['userId']."','".$time."','".$_POST['date']."')";
    $dbh->exec($sql); //SQLの実行
    $testId = $dbh->lastInsertID();
    for($i=0;$i<count($proId);$i++){
        $sql = "INSERT INTO `answer_history_tb` (`test_id`,`problem_id`,`correct`) VALUES('".$testId."','".$proId[$i]."',".$correct[$i].")";   //sql
        $dbh->exec($sql); //SQLの実行
    }
    
?>


