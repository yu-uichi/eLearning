<?php
require_once(__DIR__ . '/config.php');

//データベースに接続
function connectDB() {
    try{
        return new PDO(DSN, DB_USER, DB_PASSWORD);
    }catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}
?>