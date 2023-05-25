<?php
  //セッションのスタート
session_start();
if(!isset($_SESSION['id'])){
    header('Location: login.php');
}
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/functions.php");

$dbh = connectDB();
if (!$dbh) {
    echo "データベースの接続に失敗しました．<br>";
    echo "管理者にご連絡ください．";
    die;
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta　name="viewport"　content="width=device-width, initial-scale=1, shrink-to-fit=no"　/>
        <title>総合eラーニングアプリ(仮)</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </head>

    <body>

    <?php
        require_once(__DIR__ . "/header.php");
    ?>

    <hr>

    <div class="row">
        <div class="col-6 m-lg-5">
            <div class="container">
                <div class="row">
                <div>
                        <a href="nursingRecord/index.php" ><img src="img/img1.svg" style="height: 200px; width: 200px; padding: 15px; object-fit: contain;"></a>
                        <p style="text-align: center;">実習記録</p>
                    </div>
                    <div>
                        <a href="kokushi/index.php" ><img src="img/img2.svg" style="height: 200px; width: 200px; padding: 15px; object-fit: contain;"></a>
                        <p style="text-align: center;">国家試験対策</p>
                    </div>
                    <div>
                        <a href="akatool_demo/index.php" target="_blank"><img src="img/img3.svg" style="height: 200px; width: 200px; padding: 15px; object-fit: contain;"></a>
                        <p style="text-align: center;">関連図作成</p>
                    </div>
                </div>
            </div>
        </div>

        <!--サイドバー-->
        <div class="col-5">
            <div style="position: sticky; top: 60px;">
                <div class="container my-5 p-lg-4 bg-light">
                    <h1 class="h4 mb-3 text-center font-weight-normal">進捗</h1>
                    <?php
                        if(isset($_SESSION["family_name"])){
                                echo '<ul>';
                                    $sql = 'SELECT *
                                    FROM `record_tb` 
                                    WHERE `user_id`="' . $_SESSION['id'] . '"';
                                    $sth = $dbh->query($sql);
                                    while ($row = $sth->fetch ()) {  
                                        echo '<li>';
                                            echo ''.$row['memo'].' '.$row['class_unit_id'];
                                            if ($row['pass_flag'] == 1){
                                                //echo '<button type="button" class="btn btn-success btn-sm" style="margin-left: 5px">合格</button>';
                                                echo '<a class="text-danger text-nowrap"> 合格</a>';
                                            }
                                        echo '</li>';
                                        $sql2 ='SELECT * FROM `recording_version_tb` WHERE `record_id`="' . $row['id'] . '" ORDER BY `recording_version_tb`.`id` ASC';   
                                        $sth2 = $dbh->query($sql2);
                                        $version=1;
                                        echo '<ul>';
                                        //添削フラグの初期化
                                        $correction_flag = false;
                                        $record_version_id = NULL;

                                        //確認フラグの初期化
                                        $confirmation_id = NULL;
                                        
                                        while ($row2 = $sth2->fetch ()) { 
                                            if ($confirmation_id != NULL){
                                                echo '<img src="img/img4.svg" style="height: 20px; width: 20px; margin: 2px; padding-bottom: 5px; object-fit: contain;">';
                                                //echo '<a href="nursingRecord/recording_result.php?record_version_id='.$record_version_id.'" class="btn btn-success btn-sm" style="margin: 2px 5px">確認</a>';
                                            }
                                            echo '<li>';
                                            echo 'version '.$version.': '.$row2['registration_time'].'';
                                            if ($row2['teacher_time'] == NULL){
                                                echo '<a class="text-primary text-nowrap"> 添削待ち</a>';
                                                //echo '添削待ち';
                                                $correction_flag = false;
                                            }else{
                                                //echo '<a class="text-success text-nowrap"> 添削済み</a>';
                                                //echo '添削済み'.$row2['teacher_time'];
                                                $correction_flag = true;
                                                $record_version_id = $row2['id'];
                                            }
                                            //recording_versionの固有id
                                            $confirmation_id = $row2['id'];
                                            $version = $version + 1;    
                                        }
                                        if($row['pass_flag'] == true){
                                            echo '<img src="img/img4.svg" style="height: 20px; width: 20px; margin: 2px; padding-bottom: 5px; object-fit: contain;">';
                                            //echo '<a href="nursingRecord/recording_result.php?record_version_id='.$record_version_id.'" class="btn btn-success btn-sm" style="margin: 2px 5px">確認</a>';
                                        }else{
                                            if ($correction_flag == true){
                                                echo '<a href="nursingRecord/recording.php?record_version_id='.$record_version_id.'" class="btn btn-primary btn-sm" style="margin: 2px 5px">修正</a>';
                                            }
                                        }
                                        echo '</ul>';
                                        echo '</li>';
                                        //合格なら何もしない
                                        if ($row['pass_flag'] == true){
                                            continue;
                                        }
                                        // echo $correction_flag; 
                                        //添削がまだなら何もしない
                                        if ($correction_flag == false){
                                            continue;
                                        }
                                        echo '</ul>';
                                    }
                                echo '</ul>';
                        }
                    ?>




                    <hr>
                    <iframe src="./kokushi/personal_result2.php" style="width: 100%; height: 410px;"></iframe>
                </div>
            </div>
        </div>

    </div>

<!--<?php
    if(isset($_SESSION["family_name"])){
        
        // echo '<div class="container">';
        //     echo '<div class="row">';
        //     if ($_SESSION['teacher']==TRUE || $_SESSION['admin']==TRUE) {  //教員画面での学生一覧｜｜URLリンク
        //         if ($_SESSION['admin']==TRUE) {
        //             echo '<div class="col-md-3 col-lg-3">';
        //                 echo '<a href="registration_teacher.php" class="btn btn-primary btn-lg">教員作成</a>';
        //             echo '</div>';
        //         }
        //         echo '<div class="col-md-3 col-lg-3">';
        //             echo '<form action=user_list.php method="POST">';
        //                 echo '<div class="form-group">';
        //                     echo '<button type="submit" class="btn btn-primary btn-lg">学生管理</button>';
        //                 echo '</div>';
        //             echo '</form>';
        //         echo '</div>';
        //         $sql = 'SELECT * FROM `user_tb` WHERE `id` = "' . $_SESSION['id'] . '"';
        //         $sth = $dbh->query($sql);
        //         $result = $sth->fetch(PDO::FETCH_ASSOC);
        //         echo '<div class="col-md-3 col-lg-3">';
        //         echo '</div>';
        //     }
        //     echo '</div>';
        // echo '</div>';
        // if ($_SESSION['teacher']==TRUE || $_SESSION['admin']==TRUE){
        //     echo '<hr>';
        // } 
    }
?> -->


                


        <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"
        ></script>
        <script src="js/nursing_record.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="./js/bootstrap.min.js"></script>
    </body>
</html>