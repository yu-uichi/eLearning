<?php
    session_start();

    require_once(__DIR__.'/functions.php');
    loginUser();
    function loginUser () {
        //emailとパスワード
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
        $dbh = connectDB();  //データベース接続
        if (!$dbh) {
            echo "データベースの接続に失敗しました．<br>";
            echo "管理者にご連絡ください．";
            return FALSE;
        }
        //$sql = 'SELECT `user_tb`.`id` as `id`,`password`,`family_name`,`given_name`,`family_name_kana`,`given_name_kana`,`student_id`,`email`,`affiliation_id`,`teacher`,`admin`
        //FROM `user_tb` INNER JOIN `affiliation_tb` ON `user_tb`.`affiliation_id`=`affiliation_tb`.`id`
        //WHERE `user_tb`.`email`="' . $email . '"';
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
                FROM `user_tb` INNER JOIN `affiliation_tb` ON `user_tb`.`affiliation_id`=`affiliation_tb`.`id` WHERE `user_tb`.`email`="' . $email . '"';
        //
        $sth = $dbh->query($sql); //SQLの実行

        $result = $sth->fetch(PDO::FETCH_ASSOC); //値の取得
        echo $sql;
        echo "<pre>";
        var_dump($result);
        echo "</pre>";
        if (password_verify($password, $result['password'])) {
            echo "認証成功認証成功";
            //認証成功
            $_SESSION['login'] = "success";
            $_SESSION['id'] = $result['id'];
            $_SESSION['family_name'] = $result['family_name'];
            $_SESSION['given_name'] = $result['given_name'];
            $_SESSION['family_name_kana'] = $result['family_name_kana'];
            $_SESSION['given_name_kana'] = $result['given_name_kana'];
            $_SESSION['student_id'] = $result['student_id'];
            $_SESSION['email'] = $result['email'];
            $_SESSION['affiliation_id'] = $result['affiliation_id'];
            $_SESSION['affiliation'] = $result['affiliation'];
            $_SESSION['teacher'] = $result['teacher'];
            $_SESSION['admin'] = $result['admin'];
            $_SESSION['last_login'] = $result['last_login'];
            //ログイン時間の更新
            $sql = 'UPDATE `user_tb` SET `last_login`=CURRENT_TIMESTAMP WHERE `id`=' . $result['id'];
            $dbh->query($sql); //SQLの実行

            header('Location: index.php');
        }else{
            //認証失敗
            $_SESSION['login'] = "failure";
             //認証失敗
            $_SESSION['log'] = "失敗";
            header('Location: login.php');
        }
    }
?>
