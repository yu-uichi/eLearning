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
        //---------------------------------------------------------------------------
        //利用者テーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS `user_tb` ( `id` INT NOT NULL AUTO_INCREMENT, `family_name` VARCHAR(30) NOT NULL, `given_name` VARCHAR(30) NOT NULL, `family_name_kana` VARCHAR(30) NOT NULL, `given_name_kana` VARCHAR(30) NOT NULL , `affiliation_id` INT,  `student_id` VARCHAR(30), `email` VARCHAR(30) NOT NULL , `password` VARCHAR(200) NOT NULL ,`teacher` BOOLEAN NOT NULL ,
        `admin` BOOLEAN NOT NULL ,
        `active_flag` TINYINT(1) NOT NULL DEFAULT 1,
        `last_login` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行
        //---------------------------------------------------------------------------
        //所属テーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS `affiliation_tb` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `affiliation` VARCHAR(30) NULL,
            `domain` VARCHAR(30) NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行

        //仮登録用テーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS `pre_member_tb` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `urltoken` VARCHAR(128) NOT NULL,
            `mail` VARCHAR(50) NOT NULL,
            `date` DATETIME NOT NULL,
            `flag` TINYINT(1) NOT NULL DEFAULT 0
            )ENGINE=InnoDB DEFAULT CHARACTER SET=utf8";
        $dbh->exec($sql); //SQLの実行

        //パスワードリセット用テーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS `reset_member_tb` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `urltoken` VARCHAR(128) NOT NULL,
            `mail` VARCHAR(50) NOT NULL,
            `date` DATETIME NOT NULL,
            `flag` TINYINT(1) NOT NULL DEFAULT 0
            )ENGINE=InnoDB DEFAULT CHARACTER SET=utf8";
        $dbh->exec($sql); //SQLの実行

        //---------------------------------------------------------------------------
        //ユーザの作成
        //所属
        $sql = "INSERT INTO `affiliation_tb` VALUES (NULL, '未登録', NULL)";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `affiliation_tb` VALUES (NULL, '愛知工業大学', 'aitech.ac.jp')";
        $dbh->exec($sql); //SQLの実行

        $sql = 'SELECT `id` FROM `affiliation_tb` WHERE `affiliation`="' . '愛知工業大学' . '"';
        $sth = $dbh->query($sql); //SQLの実行
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        //システム管理者
        $sql = "INSERT INTO `user_tb` VALUES (NULL, '岡田', '響', 'おかだ', 'ひびき'," . $result['id'] . ", NULL ,'x19019xx@aitech.ac.jp','" . password_hash('abcd', PASSWORD_DEFAULT) . "', 1, 1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $dbh->exec($sql); //SQLの実行
        //教員
        $sql = "INSERT INTO `user_tb` VALUES (NULL, '教員', '太郎', 'きょういん', 'たろう'," . $result['id'] . ", NULL ,'abc@aitech.ac.jp', '" . password_hash('abcd', PASSWORD_DEFAULT) . "', 1, 0, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $dbh->exec($sql); //SQLの実行
        //教員
        $sql = "INSERT INTO `user_tb` VALUES (NULL, '相撲', '佐希子', 'すまい', 'さきこ'," . $result['id'] . ", NULL ,'sumai.s@shubun.ac.jp', '" . password_hash('abcd', PASSWORD_DEFAULT) . "', 1, 0, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $dbh->exec($sql); //SQLの実行
        //学生
        $sql = "INSERT INTO `user_tb` VALUES (NULL, '学生', '花子', 'がくせい', 'はなこ'," . $result['id'] . ", 'bbb' ,'aaa@aitech.ac.jp', '" . password_hash('abcd', PASSWORD_DEFAULT) . "', 0, 0, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $dbh->exec($sql); //SQLの実行

        //所属
        $sql = "INSERT INTO `affiliation_tb` VALUES (NULL, '修文大学', 'shubun.ac.jp')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `affiliation_tb` VALUES (NULL, '一宮研伸大学', 'ikc.ac.jp')";
        $dbh->exec($sql); //SQLの実行

        //---------------------------------------------------------------------------
        //回答履歴テーブルの作成
        $sql = "CREATE TABLE `teamdigicom_kokushidb`.`answer_history_tb` ( `id` INT NOT NULL AUTO_INCREMENT , `test_id` INT NOT NULL , `problem_id` INT NOT NULL , `correct` BOOLEAN NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
        $dbh->exec($sql); //SQLの実行

        //成績タイプのテーブルの作成
        /*
        $sql = "CREATE TABLE `score_type_tb` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `user_id` INT NOT NULL ,
            `exam_type_id` INT NOT NULL ,
            `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行
        */
        //---------------------------------------------------------------------------
        //領域テーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS `region_tb` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `region` VARCHAR(30) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行

        //領域データの挿入
        $sql = "INSERT INTO `region_tb` VALUES (1, '必修問題')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `region_tb` VALUES (2, '人体の構造と機能')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `region_tb` VALUES (3, '疾病の成り立ちと回復の促進')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `region_tb` VALUES (4, '健康支援と社会保障制度')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `region_tb` VALUES (5, '基礎看護学')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `region_tb` VALUES (6, '系統別成人看護学')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `region_tb` VALUES (7, '老年看護学')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `region_tb` VALUES (8, '小児看護学')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `region_tb` VALUES (9, '母性看護学')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `region_tb` VALUES (10, '精神看護学')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `region_tb` VALUES (11, '在宅看護論')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `region_tb` VALUES (12, '看護の統合と実践')";
        $dbh->exec($sql); //SQLの実行
        

        //---------------------------------------------------------------------------
        //分類テーブルの作成
        $sql = "CREATE TABLE `classification_tb` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `classification` VARCHAR(30) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行

        //分類データの挿入
        $sql = "INSERT INTO `classification_tb` VALUES (NULL, '未分類')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `classification_tb` VALUES (NULL, '必修問題')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `classification_tb` VALUES (NULL, '一般問題')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `classification_tb` VALUES (NULL, '状況設定問題')";
        $dbh->exec($sql); //SQLの実行

        //---------------------------------------------------------------------------

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
        //問題番号_日付テーブルの作成
        $sql = "CREATE TABLE `number_year_tb` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `number` INT NOT NULL ,
            `year` INT NOT NULL ,
            `era_name` VARCHAR(30) NOT NULL ,
            `month` INT NOT NULL ,
            `date` INT NOT NULL,
            PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行

        //問題番号_日付データの挿入
        $sql = "INSERT INTO `number_year_tb` VALUES (NULL, '97', '2008', '平成20', '2', '24')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `number_year_tb` VALUES (NULL, '98', '2009', '平成21', '2', '22')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `number_year_tb` VALUES (NULL, '99', '2010', '平成22', '2', '21')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `number_year_tb` VALUES (NULL, '100', '2011', '平成23', '2', '20')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `number_year_tb` VALUES (NULL, '101', '2012', '平成24', '2', '19')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `number_year_tb` VALUES (NULL, '102', '2013', '平成25', '2', '17')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `number_year_tb` VALUES (NULL, '103', '2014', '平成26', '3', '19')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `number_year_tb` VALUES (NULL, '104', '2015', '平成27', '2', '22')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `number_year_tb` VALUES (NULL, '105', '2016', '平成28', '2', '14')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `number_year_tb` VALUES (NULL, '106', '2017', '平成29', '2', '19')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `number_year_tb` VALUES (NULL, '107', '2018', '平成30', '2', '18')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `number_year_tb` VALUES (NULL, '108', '2019', '平成31', '2', '17')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `number_year_tb` VALUES (NULL, '109', '2020', '令和2', '2', '16')";
        $dbh->exec($sql); //SQLの実行

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
        //テストタイプのテーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS `exam_type_tb` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `exam_type` VARCHAR(30) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行

        //領域データの挿入
        $sql = "INSERT INTO `exam_type_tb` VALUES (NULL, '分類別')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `exam_type_tb` VALUES (NULL, '領域別')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `exam_type_tb` VALUES (NULL, 'システム予想')";
        $dbh->exec($sql); //SQLの実行
        $sql = "INSERT INTO `exam_type_tb` VALUES (NULL, '過去問')";
        $dbh->exec($sql); //SQLの実行

        //---------------------------------------------------------------------------
        //テストidテーブルの作成
        $sql = "CREATE TABLE `teamdigicom_kokushidb`.`test_id_tb` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , `time` TIME NOT NULL , `timestamp` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
        $dbh->exec($sql); //SQLの実行
    }
?>