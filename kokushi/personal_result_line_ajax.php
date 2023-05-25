<?php
  session_start();
  //接続用関数の呼び出し
  require_once(__DIR__.'/functions.php');
?>

<?php
    if(!isset($_POST['lineType'])){
        header('Location: index.php');
    }
    $dbh = connectDB(); //DB接続


    $sql="SELECT `id`,`timestamp` FROM `test_id_tb` WHERE `user_id`='".$_SESSION['id']."'";

    if($_POST['lineType']=='year'){
        $sql .= "AND DATE_FORMAT(timestamp, '%Y') = DATE_FORMAT(now(), '%Y')";
    }else if($_POST['lineType']=='month'){
        $sql .= "AND DATE_FORMAT(timestamp, '%Y-%m') = DATE_FORMAT(now(), '%Y-%m')";
    }else if($_POST['lineType']=='week'){
        $sql .= " AND DATE_FORMAT(timestamp, '%Y-%m-%d') BETWEEN DATE_FORMAT(now() - INTERVAL (DAYOFWEEK(now())-1) day, '%Y-%m-%d') AND DATE_FORMAT(now() + INTERVAL (7-(DAYOFWEEK(now())-1)) day, '%Y-%m-%d');";
    }else if($_POST['lineType']=='today'){
        $sql .= "AND DATE_FORMAT(timestamp, '%Y-%m-%d') = DATE_FORMAT(now(), '%Y-%m-%d')";
    }else if($_POST['lineType']=='custom'){
        $sql .= "";
    }
     $sth = $dbh->query($sql); //SQLの実行
     $data;
     if(isset($sth)){
         while($row = $sth->fetch()){
             $sql="SELECT `problem_id`,`correct` FROM `answer_history_tb` WHERE `test_id`='".$row['id']."'";//テストIDから各テストの問題IDと正誤を取得
             $temp = $dbh->query($sql); //SQLの実行
             while($row2 = $temp->fetch()){
                 $sql="SELECT `region_id` FROM `problem_tb` WHERE `id`='".$row2['problem_id']."'";
                 $temp2 = ($dbh->query($sql))->fetch(); //SQLの実行
                 $data[] = array('problem_id'=>$row2['problem_id'],'region_id'=>$temp2['region_id'],'correct'=>$row2['correct'],'year'=>date('Y', strtotime($row['timestamp'])),'month'=>date('m', strtotime($row['timestamp'])),'day'=>date('d', strtotime($row['timestamp'])),'hour'=>date('H', strtotime($row['timestamp'])));
             }
         }
     }
    
     if(!isset($data)){
         $data[]=array('problem_id'=>-1,'region_id'=>-1,'correct'=>-1,'year'=>'0000','month'=>'00','day'=>'00','hour'=>'00');
     }
     $data = json_encode($data);
     echo $data;

?>