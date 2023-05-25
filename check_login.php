<?php
    //接続用関数の呼び出し
    require_once(__DIR__.'/functions.php');
    //セッションの生成
    session_start();
    if(!(isset($_POST['user'])&&isset($_POST['pass']))){
        header('Location:login.php');
    }
    

    //ユーザー名/パスワード
    $email = htmlspecialchars($_POST['email'],ENT_QUOTES);
    $password = htmlspecialchars($_POST['password'],ENT_QUOTES);


    //DBへの接続
    $dbh = connectDB();
    // if($dbh){
    //     //データベースへの問い合わせSQL文（文字列）
    //     $sql = 'SELECT user_id FROM user_tb WHERE email = "'.$email.'" 
    //     AND password = "'.$password.'"';
        
    //     $sth = $dbh->query($sql);   //SQLの実行
    //     //データの取得
    //     $result = $sth->fetchALL(PDO::FETCH_ASSOC);
    //     // $_SESSION['user_id'] = $result[0]['user_id'];
        
    // }
    if($dbh){
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'SELECT user_name FROM user_tb WHERE email = "'.$email.'" 
        AND password = "'.$password.'"';
        
        $sth = $dbh->query($sql);   //SQLの実行
        //データの取得
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $result['password'])) {
            //認証成功
            $_SESSION['login'] = "success";
            $_SESSION['id'] = $result['id'];
            $_SESSION['family_name'] = $result['family_name'];
            $_SESSION['given_name'] = $result['given_name'];
            $_SESSION['family_name_kana'] = $result['family_name_kana'];
            $_SESSION['given_name_kana'] = $result['given_name_kana'];
            $_SESSION['email'] = $result['email'];
            $_SESSION['student_id'] = $result['student_id'];
            $_SESSION['affiliation'] = $result['affiliation'];
            $_SESSION['teacher'] = $result['teacher'];
            $_SESSION['admin'] = $result['admin'];
            //ログイン時間の更新
            $sql = 'UPDATE `user_tb` SET `last_login`=CURRENT_TIMESTAMP WHERE `id`=' . $result['id'];
            $dbh->query($sql); //SQLの実行

            header('Location: index.php');
        }else{
            //認証失敗
            $_SESSION['login'] = "failure";
            header('Location: login.php');
        }
    }
    
    //認証
    if(count($result)==1){//配列数が１の時
        //ログイン成功
        $login = 'OK';
        //表示用ユーザー名をセッション変数に保存
        $_SESSION['name'] = $result[0]['user_name'];
    }else{
        //ログイン失敗
        $login = 'Error';
    }
    $sth = null; // データの削除
    $dbh = null; //DBを閉じる

    $_SESSION['login'] = $login;
    //セッションの変数に代入
    $_SESSION['login'] = $login;
    //移動
    if($login == 'OK'){
        //ログイン成功:掲示板メニューへ
        header('Location:index.php');
    }else{
        //ログイン失敗：ログインフォーム画面へ
        header('Location:login.html');
    }

    echo "<pre>";
    print_r($result);   //デバッグ
    echo "</pre>";
?>
