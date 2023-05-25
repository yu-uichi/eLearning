<?php
    //接続用関数の呼び出し
    require_once(__DIR__.'/functions.php');
    createTable(); //テーブルの生成

    //テーブルの生成
    function createTable() {
        //DBへの接続
        $dbh = connectDB();
        //データベースの接続確認
        if (!$dbh) {  //接続できていない場合
            echo 'DBに接続できていません．';
            return;
        }

        //テーブルが存在するかを確認するSQL文
        $sql = "show tables";
        $sth = $dbh->query($sql); //SQLの実行
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (0 < count($result)) {
            //データベース構築済み
            return;
        }
       
        //問題テーブルの作成
        $sql = "CREATE TABLE `problem_tb` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `number_year_id` INT NOT NULL ,
            `ampm` VARCHAR(10) NOT NULL ,
            `problem` VARCHAR(1000) NOT NULL ,
            `answer_type` INT NOT NULL ,
            `region_id` INT NOT NULL ,
            `classification_id` INT NOT NULL ,
            `image_path` VARCHAR(300),
            `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
            PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行
        //---------------------------------------------------------------------------
        
        //---------------------------------------------------------------------------
        //問題選択肢のテーブル作成
        $sql = "CREATE TABLE `option_tb` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `problem_id` INT NOT NULL ,
            `option` VARCHAR(300) NOT NULL ,
            `image_path` VARCHAR(300), PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行
        //---------------------------------------------------------------------------
        //回答のテーブル作成
        $sql = "CREATE TABLE `answer_tb` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `problem_id` INT NOT NULL ,
            `option_id` INT ,
            `number_value` INT,
            PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行
        //---------------------------------------------------------------------------

    }
?>