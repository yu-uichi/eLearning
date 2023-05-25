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
        $sql = "CREATE TABLE `teamdigicom_mixdb`.`user_tb` 
        (`id` INT(11) NOT NULL AUTO_INCREMENT , 
        `family_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , 
        `given_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , 
        `family_name_kana` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , 
        `given_name_kana` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , 
        `affiliation_id` INT(11) NOT NULL , 
        `student_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , 
        `email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , 
        `password` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , 
        `teacher` TINYINT(4) NULL , 
        `admin` TINYINT(4) NULL , 
        `last_login` DATETIME NULL , 
        `created_at` DATETIME NULL , 
        `updated_at` DATETIME NULL , PRIMARY KEY (`id`)) 
        ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行
        //---------------------------------------------------------------------------
        //所属テーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS `affiliation_tb` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `affiliation` VARCHAR(30) NULL,
            `id_aff` VARCHAR(30) NULL,
            PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行
        //---------------------------------------------------------------------------
        //ユーザの作成
        //所属
        $sql = "INSERT INTO `affiliation_tb` VALUES (NULL, '未登録', NULL)";
        $dbh->exec($sql); //SQLの実行

        $sql = "INSERT INTO `affiliation_tb` VALUES (NULL, '愛知工業大学', '7BINdPRBLojs')";
        $dbh->exec($sql); //SQLの実行

        $sql = "INSERT INTO `affiliation_tb` VALUES (NULL, '名城大学', 'z1ecL0On6aFW')";
        $dbh->exec($sql); //SQLの実行
        
        $sql = "INSERT INTO `affiliation_tb` VALUES (NULL, '修文大学', 'CWpt4z7VQ458')";
        $dbh->exec($sql); //SQLの実行

        //管理者
        $sql = "INSERT INTO `user_tb` (`id`, `family_name`, `given_name`, `family_name_kana`, `given_name_kana`, `affiliation_id`, `student_id`, `email`, `password`, `teacher`, `admin`, `last_login`, `created_at`, `updated_at`) VALUES (NULL, '岡田', '響', 'おかだ', 'ひびき', '2', 'x19019xx', 'x19019xx@aitech.ac.jp', '". password_hash('abcd', PASSWORD_DEFAULT)."', '1', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $dbh->exec($sql); //SQLの実行
        //管理者
        $sql = "INSERT INTO `user_tb` (`id`, `family_name`, `given_name`, `family_name_kana`, `given_name_kana`, `affiliation_id`, `student_id`, `email`, `password`, `teacher`, `admin`, `last_login`, `created_at`, `updated_at`) VALUES (NULL, '川口', '雄一朗', 'かわぐち', 'ゆういちろう', '2', 'x20022xx', 'x20022xx@aitech.ac.jp', '". password_hash('abcd', PASSWORD_DEFAULT)."', '1', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $dbh->exec($sql); //SQLの実行

          //教員
        $sql = "INSERT INTO `user_tb` (`id`, `family_name`, `given_name`, `family_name_kana`, `given_name_kana`, `affiliation_id`, `student_id`, `email`, `password`, `teacher`, `admin`, `last_login`, `created_at`, `updated_at`) VALUES (NULL, '教員', '太郎', 'きょういん', 'たろう', '2', '', 'abc@aitech.ac.jp', '". password_hash('abcd', PASSWORD_DEFAULT)."', '1', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $dbh->exec($sql); //SQLの実行
        //相撲佐希子
        $sql = "INSERT INTO `user_tb` (`id`, `family_name`, `given_name`, `family_name_kana`, `given_name_kana`, `affiliation_id`, `student_id`, `email`, `password`, `teacher`, `admin`, `last_login`, `created_at`, `updated_at`) VALUES (NULL, '相撲', '佐希子', 'すまい', 'さきこ', '4', '', 'sumai.s@shubun.ac.jp', '". password_hash('abcd', PASSWORD_DEFAULT)."', '1', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $dbh->exec($sql); //SQLの実行

         //学生1
        $sql = "INSERT INTO `user_tb` (`id`, `family_name`, `given_name`, `family_name_kana`, `given_name_kana`, `affiliation_id`, `student_id`, `email`, `password`, `teacher`, `admin`, `last_login`, `created_at`, `updated_at`) VALUES (NULL, '花子', '学生', 'はなこ', 'がくせい', '2', 'bbb', 'aaa@aitech.ac.jp', '". password_hash('abcd', PASSWORD_DEFAULT)."', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $dbh->exec($sql); //SQLの実行

        //---------------------------------------------------------------------------
        //実習記録テーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS `record_tb` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `user_id` INT NOT NULL,
            `memo` VARCHAR(255) NOT NULL,
            `creation_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `class_unit_id` VARCHAR(30) DEFAULT NULL,
            `pass_flag` BOOLEAN NULL DEFAULT NULL, 
            PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行

        //---------------------------------------------------------------------------
        //実習記録記載情報テーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS `recording_version_tb` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `record_id` INT NOT NULL,
            `registration_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `teacher_time` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行

        //---------------------------------------------------------------------------
        //詳細テーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS `record_detail_tb` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `record_version_id` INT(12) NOT NULL COMMENT '実習記録のversionがわかるid',
            `hospital_name` VARCHAR(255) NULL DEFAULT NULL COMMENT '病院名の入力欄',
            `full_name` VARCHAR(255) NULL DEFAULT NULL COMMENT '患者名の入力欄',
            `gender` VARCHAR(10) NULL DEFAULT NULL COMMENT '性別の入力欄',
            `age` INT(150) NULL DEFAULT NULL COMMENT '年齢の入力欄',
            `birthday` DATE NULL DEFAULT NULL COMMENT '生年月日の入力欄',
            `physical` VARCHAR(255) NULL DEFAULT NULL COMMENT '身体的・心理的・社会的側面の特徴の入力欄',
            `family` VARCHAR(255) NULL DEFAULT NULL COMMENT '家族構成の入力欄',
            `job` VARCHAR(255) NULL DEFAULT NULL COMMENT '職歴の入力欄',
            `temperament` VARCHAR(255) NULL DEFAULT NULL COMMENT '気質・情動状態・一過性の気分(情報源)',
            `height` FLOAT(20) NULL DEFAULT NULL COMMENT '身長の入力欄',
            `weight` FLOAT(20) NULL DEFAULT NULL COMMENT '体重の入力欄',
            `dailyweightchanges` VARCHAR(10) NULL DEFAULT NULL COMMENT '体重の変化ありかなしか',
            `menstrual` VARCHAR(50) NULL DEFAULT NULL COMMENT '月経状態の入力欄',
            `drinking` VARCHAR(50) NULL DEFAULT NULL COMMENT '飲酒の入力欄',
            `allergies` VARCHAR(50) NULL DEFAULT NULL COMMENT 'アレルギーの入力欄',
            `smoking` VARCHAR(50) NULL DEFAULT NULL COMMENT '喫煙の入力欄',
            `hearing` VARCHAR(50) NULL DEFAULT NULL COMMENT '聴力の入力欄',
            `sight` VARCHAR(50) NULL DEFAULT NULL COMMENT '視覚の入力欄',
            `perception` VARCHAR(50) NULL DEFAULT NULL COMMENT '知覚の入力欄',
            `language` VARCHAR(50) NULL DEFAULT NULL COMMENT '言語能力の入力欄',
            `exercise` VARCHAR(50) NULL DEFAULT NULL COMMENT '運動能力の入力欄',
            `additional` VARCHAR(50) NULL DEFAULT NULL COMMENT '追加の入力欄',
            `hearing_aid` VARCHAR(10) NULL DEFAULT NULL COMMENT '補聴器の入力欄',
            `glasses` VARCHAR(20) NULL DEFAULT NULL COMMENT '眼鏡の入力欄',
            `denture` VARCHAR(10) NULL DEFAULT NULL COMMENT '義歯の入力欄',
            `intellectual` VARCHAR(255) NULL DEFAULT NULL COMMENT '知的能力の入力欄',
            `diagnosis` VARCHAR(50) NULL DEFAULT NULL COMMENT '診断名の入力欄',
            `chiefComplaints` VARCHAR(50) NULL DEFAULT NULL COMMENT '主訴の入力欄',
            `medicalHistory` VARCHAR(255) NULL DEFAULT NULL COMMENT '既住歴の入力欄',
            `progress` VARCHAR(255) NULL DEFAULT NULL COMMENT '入院までの経過の入力欄',
            `progress_from_hospitalization` VARCHAR(255) NULL DEFAULT NULL COMMENT '病状の入力欄',
            `infoSub` VARCHAR(255) NULL DEFAULT NULL COMMENT '主観的情報の入力欄',
            `infoObj` VARCHAR(255) NULL DEFAULT NULL COMMENT '客観的情報の入力欄',
            `resident` VARCHAR(255) NULL DEFAULT NULL COMMENT '常在条件の入力欄',
            `breath` VARCHAR(255) NULL DEFAULT NULL COMMENT '呼吸の入力欄',
            `posture` VARCHAR(255) NULL DEFAULT NULL COMMENT '姿勢活動の入力欄',
            `meal` VARCHAR(255) NULL DEFAULT NULL COMMENT '食事の入力欄',
            `rest` VARCHAR(255) NULL DEFAULT NULL COMMENT '安静度の入力欄',
            `treatment_policy` VARCHAR(255) NULL DEFAULT NULL COMMENT '治療方針、治療内容の入力欄',
            `AI_step1` VARCHAR(500) NULL DEFAULT NULL COMMENT '分析・解釈step1',
            `AI_step2` VARCHAR(500) NULL DEFAULT NULL COMMENT '分析・解釈step2',
            `AI_step3` VARCHAR(500) NULL DEFAULT NULL COMMENT '分析・解釈step3',
            `AI_step4` VARCHAR(500) NULL DEFAULT NULL COMMENT '分析・解釈step4',
            PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行

        //---------------------------------------------------------------------------
        //先生_授業IDテーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS `teacher_tb` ( 
            `id` INT NOT NULL AUTO_INCREMENT ,
            `user_id` BOOLEAN DEFAULT NULL ,
            `class_id` VARCHAR(30) DEFAULT NULL ,
            `t_memo` VARCHAR(255) NOT NULL ,
            `time` TIMESTAMP NULL DEFAULT NULL ,
            PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行

        //---------------------------------------------------------------------------
        //添削詳細テーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS `record_t_detail_tb` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `record_version_id` INT(12) NOT NULL COMMENT '実習記録の添削versionがわかるid',
            `t_hospital_name` VARCHAR(255) NULL DEFAULT NULL COMMENT '病院名の入力欄',
            `t_full_name` VARCHAR(255) NULL DEFAULT NULL COMMENT '患者名の入力欄',
            `t_gender` VARCHAR(15) NULL DEFAULT NULL COMMENT '性別の入力欄',
            `t_age` VARCHAR(15) NULL DEFAULT NULL COMMENT '年齢の入力欄',
            `t_birthday` VARCHAR(10) NULL DEFAULT NULL COMMENT '生年月日の入力欄',
            `t_physical` VARCHAR(255) NULL DEFAULT NULL COMMENT '身体的・心理的・社会的側面の特徴の入力欄',
            `t_family` VARCHAR(255) NULL DEFAULT NULL COMMENT '家族構成の入力欄',
            `t_job` VARCHAR(255) NULL DEFAULT NULL COMMENT '職歴の入力欄',
            `t_temperament` VARCHAR(255) NULL DEFAULT NULL COMMENT '気質・情動状態・一過性の気分(情報源)',
            `t_height` VARCHAR(15) NULL DEFAULT NULL COMMENT '身長の入力欄',
            `t_weight` VARCHAR(15) NULL DEFAULT NULL COMMENT '体重の入力欄',
            `t_dailyweightchanges` VARCHAR(10) NULL DEFAULT NULL COMMENT '体重の変化ありかなしか',
            `t_menstrual` VARCHAR(50) NULL DEFAULT NULL COMMENT '月経状態の入力欄',
            `t_drinking` VARCHAR(50) NULL DEFAULT NULL COMMENT '飲酒の入力欄',
            `t_allergies` VARCHAR(50) NULL DEFAULT NULL COMMENT 'アレルギーの入力欄',
            `t_smoking` VARCHAR(50) NULL DEFAULT NULL COMMENT '喫煙の入力欄',
            `t_hearing` VARCHAR(50) NULL DEFAULT NULL COMMENT '聴力の入力欄',
            `t_sight` VARCHAR(50) NULL DEFAULT NULL COMMENT '視覚の入力欄',
            `t_perception` VARCHAR(50) NULL DEFAULT NULL COMMENT '知覚の入力欄',
            `t_language` VARCHAR(50) NULL DEFAULT NULL COMMENT '言語能力の入力欄',
            `t_exercise` VARCHAR(50) NULL DEFAULT NULL COMMENT '運動能力の入力欄',
            `t_additional` VARCHAR(50) NULL DEFAULT NULL COMMENT '追加の入力欄',
            `t_hearing_aid` VARCHAR(10) NULL DEFAULT NULL COMMENT '補聴器の入力欄',
            `t_glasses` VARCHAR(20) NULL DEFAULT NULL COMMENT '眼鏡の入力欄',
            `t_denture` VARCHAR(10) NULL DEFAULT NULL COMMENT '義歯の入力欄',
            `t_intellectual` VARCHAR(255) NULL DEFAULT NULL COMMENT '知的能力の入力欄',
            `t_diagnosis` VARCHAR(50) NULL DEFAULT NULL COMMENT '診断名の入力欄',
            `t_chiefComplaints` VARCHAR(50) NULL DEFAULT NULL COMMENT '主訴の入力欄',
            `t_medicalHistory` VARCHAR(255) NULL DEFAULT NULL COMMENT '既住歴の入力欄',
            `t_progress` VARCHAR(255) NULL DEFAULT NULL COMMENT '入院までの経過の入力欄',
            `t_progress_from_hospitalization` VARCHAR(255) NULL DEFAULT NULL COMMENT '病状の入力欄',
            `t_infoSub` VARCHAR(255) NULL DEFAULT NULL COMMENT '主観的情報の入力欄',
            `t_infoObj` VARCHAR(255) NULL DEFAULT NULL COMMENT '客観的情報の入力欄',
            `t_resident` VARCHAR(255) NULL DEFAULT NULL COMMENT '常在条件の入力欄',
            `t_breath` VARCHAR(255) NULL DEFAULT NULL COMMENT '呼吸の入力欄',
            `t_posture` VARCHAR(255) NULL DEFAULT NULL COMMENT '姿勢活動の入力欄',
            `t_meal` VARCHAR(255) NULL DEFAULT NULL COMMENT '食事の入力欄',
            `t_rest` VARCHAR(255) NULL DEFAULT NULL COMMENT '安静度の入力欄',
            `t_treatment_policy` VARCHAR(255) NULL DEFAULT NULL COMMENT '治療方針、治療内容の入力欄',
            `t_AI_step1` VARCHAR(500) NULL DEFAULT NULL COMMENT '分析・解釈step1',
            `t_AI_step2` VARCHAR(500) NULL DEFAULT NULL COMMENT '分析・解釈step2',
            `t_AI_step3` VARCHAR(500) NULL DEFAULT NULL COMMENT '分析・解釈step3',
            `t_AI_step4` VARCHAR(500) NULL DEFAULT NULL COMMENT '分析・解釈step4',
            PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $dbh->exec($sql); //SQLの実行
        
        //--------------------------------------------------------------------------------
        //--------------------------------------------------------------------------------
        //--------------------------------------------------------------------------------
        //--------------------------------------------------------------------------------
        //----------ここから国試------------------------------------------------------------

        //----------これはいらない気もする----------------------------------------------------
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

        //----------------------------------------------------------------------
        //---------------------------------------------------------------------------
        //回答履歴テーブルの作成
        $sql = "CREATE TABLE `teamdigicom_mixdb`.`answer_history_tb` ( `id` INT NOT NULL AUTO_INCREMENT , `test_id` INT NOT NULL , `problem_id` INT NOT NULL , `correct` BOOLEAN NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
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
        $sql = "CREATE TABLE `teamdigicom_mixdb`.`test_id_tb` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , `time` TIME NOT NULL , `timestamp` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
        $dbh->exec($sql); //SQLの実行
        //---------------------------------------------------------------------------
       


    }
?>

