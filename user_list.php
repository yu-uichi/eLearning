<?php
  //セッションのスタート
    session_start();
if(!isset($_SESSION['id'])){
    header('Location: login.php');
}
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/functions.php");

if(!($_SESSION['admin']==1 || $_SESSION['teacher']==1)) {
    header("Location: index.php"); //トップに移動
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

    <div class="container">
        <form class="needs-validation">
            <div class="container mt-5 p-lg-5 bg-light">
                <div class="col-lg-12 text-center">
                    <h1>学生登録URL</h1><br>
                </div>
                <!-- <h1 class="h4 mb-3 text-center font-weight-normal">ID確認</h1> -->

                <?php
                $dbh = connectDB();
                if (!$dbh) {
                    echo "データベースの接続に失敗しました．<br>";
                    echo "管理者にご連絡ください．";
                    echo "</tbody></table></div></body></html>";
                    die;
                }
                $sql = 'SELECT 
                    `user_tb`.`id`,
                    `affiliation_tb`.`id_aff`,
                    `user_tb`.`student_id`,
                    `user_tb`.`last_login`
                    FROM `user_tb` INNER JOIN `affiliation_tb` ON `user_tb`.`affiliation_id`=`affiliation_tb`.`id`';
                
                $sth = $dbh->query($sql);
                while ($row = $sth->fetch ()) {
                    if($_SESSION['id'] == $row['id']){
                            //echo '<h1 class="h4 mb-3 text-center font-weight-normal">' . $row['id_aff'] . '</h1>';
                            echo '<h1 class="h4 mb-3 text-center font-weight-normal">' . 'http://localhost:8888/new2/registration.php?affiliation_id='. $row['id_aff'] .'</h1>';
                            //echo 'https://nursingrecord.teamdigicom.net/registration.php?affiliation_id='.$row['id_aff'].'';
                    }
                }
                ?>

            </div>
        </form>
    </div>
    <br><br>


<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1>学生一覧</h1>
        </div>
        <div class="bs-componet">
        <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">姓</th>
                    <th scope="col">名</th>
                    <th scope="col">せい</th>
                    <th scope="col">めい</th>
                    <th scope="col">所属</th>
                    <th scope="col">学籍番号</th>
                    <th scope="col">Eメール</th>
                    <!-- <th scope="col">パスワード</th> -->
                    <th scope="col">区分</th>
                    <th scope="col">最終ログイン</th>
                    <th scope="col">削除</th>
                </tr>
                </thead>
            <tbody>
            <?php
            $dbh = connectDB();
            if (!$dbh) {
                echo "データベースの接続に失敗しました．<br>";
                echo "管理者にご連絡ください．";
                echo "</tbody></table></div></body></html>";
                die;
            }
            $sql = 'SELECT 
                `user_tb`.`id`,
                `user_tb`.`family_name`,
                `user_tb`.`given_name`,
                `user_tb`.`family_name_kana`,
                `user_tb`.`given_name_kana`,
                `user_tb`.`affiliation_id`,
                `affiliation_tb`.`affiliation`,
                `user_tb`.`student_id`,
                `user_tb`.`email`,
                `user_tb`.`password`,
                `user_tb`.`teacher`,
                `user_tb`.`admin`,
                `user_tb`.`last_login`
                FROM `user_tb` INNER JOIN `affiliation_tb` ON `user_tb`.`affiliation_id`=`affiliation_tb`.`id`';
                
            $sth = $dbh->query($sql);
            while ($row = $sth->fetch ()) {
                if ($_SESSION['admin']==TRUE) {
                    echo '<tr>';
                    echo '<td>' . $row['family_name'] . '</td>';
                    echo '<td>' . $row['given_name'] . '</td>';
                    echo '<td>' . $row['family_name_kana'] . '</td>';
                    echo '<td>' . $row['given_name_kana'] . '</td>';
                    echo '<td>' . $row['affiliation'] . '</td>'; //所属IDのまま
                    echo '<td>' . $row['student_id'] . '</td>';
                    echo '<td>' . $row['email'] . '</td>';
                    //echo '<td>' . $row['password'] . '</td>'; //パスワード
                    if($row['admin'] == '1'){
                        echo '<td>管理者</td>';
                    }else if($row['teacher'] == '1'){
                        echo '<td>教員</td>';
                    }else{
                        echo '<td>学生</td>';
                    }
                    echo '<td>' . $row['last_login'] . '</td>';
                    echo '<td>';
                    echo '<form action="user_delet.php" method="POST">';
                        echo '<input type="hidden" name="id" value="'.$row['id'].'">';
                        echo '<input type="submit" class="btn btn-primary" value="削除">';
                    echo '</form></td>';
                    echo '</tr>';
                }else if ($_SESSION['teacher']==TRUE) {
                    if($_SESSION['affiliation_id'] == $row['affiliation_id']){
                        echo '<tr>';
                        echo '<td>' . $row['family_name'] . '</td>';
                        echo '<td>' . $row['given_name'] . '</td>';
                        echo '<td>' . $row['family_name_kana'] . '</td>';
                        echo '<td>' . $row['given_name_kana'] . '</td>';
                        echo '<td>' . $row['affiliation'] . '</td>'; //所属IDのまま
                        echo '<td>' . $row['student_id'] . '</td>';
                        echo '<td>' . $row['email'] . '</td>';
                        //echo '<td>' . $row['password'] . '</td>'; //パスワード
                        if($row['admin'] == '1'){
                            echo '<td>管理者</td>';
                        }else if($row['teacher'] == '1'){
                            echo '<td>教員</td>';
                        }else{
                            echo '<td>学生</td>';
                        }
                        echo '<td>' . $row['last_login'] . '</td>';
                        echo '<td>';
                        if($row['admin'] == '1'){
                            echo '<td></td>';
                        }else{
                            echo '<form action="user_delet.php" method="POST">';
                                echo '<input type="hidden" name="id" value="'.$row['id'].'">';
                                echo '<input type="submit" class="btn btn-primary" value="削除">';
                            echo '</form></td>';
                        }
                        echo '</tr>';
                    }
                }
            }
            ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
