<?php
    include("data_file/Classes/PHPExcel.php");
    include("data_file/Classes/PHPExcel/IOFactory.php");
    $obj = PHPExcel_IOFactory::load("data_file/過去問.xlsx");
    session_start();

    //クリックジャッキング対策
    header('X-FRAME-OPTIONS: SAMEORIGIN');

    require_once(__DIR__ . "/functions.php");
    $dbh = connectDB(); //DB接続
?>

<?php
// データ格納用の配列の準備
$keyarray=array(); // 最初の行の項目の格納用
$dataarray=array(); // シート内のセルのデータの格納用
$titlearray=array(); // シートのデータの格納用
// データを配列に格納、シートの数だけループ処理を行う
for ($i = 0; $i < $obj->getSheetCount(); $i++) {
	// シートの情報を取得
    $sheet = $obj->setActiveSheetIndex($i);
	// シート内のセルの情報を取得
    $objactive = $obj->getActiveSheet();
	// シート内のセルの情報から、空のセルを除外
    $objarray = $objactive->toArray(null,true,true,true);
	// シート名の取得
    $sheetTitle = $objactive->getTitle();
	// シート名を配列に格納
    $titlearray[] = $sheetTitle;
	// シート内のセルの情報をループ処理
	foreach($objarray as $rowindex=>$rowdata){
		if($rowindex == '1'){
			//1行目にカラム名として連想配列のキーが入っている
			foreach($rowdata as $key=>$value){
				// 最初の行の項目の格納用配列に項目を格納
				$keyarray[$sheetTitle][$key]=nl2br($value);
			}
		} else {
			//2行目以降は、1行目で求めたカラム名を連想配列のキーとする
			foreach($rowdata as $key=>$value){
				// 最初の行の項目の格納用配列と連動させながらセル情報を格納
				$dataarray[$sheetTitle][$rowindex-1][$keyarray[$sheetTitle][$key]] = nl2br($value);
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
// シートのデータ配列が存在する＋空でないならば
if(!empty($titlearray)):
// シートのカウント
$counter = 0;
// シートのループ処理開始
$titlelabel = "Sheet1"
// シートのラベル情報が存在する＋空でないならば
//if(!empty($catarray[$titlelabel])):
?>
<section id="row-num<?php echo $counter; ?>" class="section">
<h2 class="titles"><?php echo $titlelabel; ?></h2>
<?php 
// シート内セルの、行ごとのループ処理開始
foreach($dataarray[$titlelabel] as $row => $item):
    $sql='';
    $number_year_id=(int)$item['時期']-96;
    $region_id = 0;
    $region_id = (int)$item['領域'];
    $classification_id = (int)$item['分類'];
        if($item['1']=='計算'){
            $answer_type=1;
        }else{
            $answer_type=0;
        }
        $sql = 'INSERT INTO `problem_tb` (`number_year_id`, `ampm`, `problem`, `answer_type`, `region_id`, `classification_id`, `image_path`) VALUES("' . $number_year_id .'", "' . $item['ampm'] . '", "' . htmlspecialchars($item['問題文'], ENT_QUOTES) . '", "'. $answer_type . '", "' . $region_id . '", "' . $classification_id . '", ';
        if ($item['問題文画像']!='') {
            $image_path=$item['時期'].$item['ampm'].$item['問題番号'].'pro.png';
            $sql .= '"' . $image_path . '"';
        }else{
            $sql .= 'NULL';
        }
        $sql .= ')';
        $dbh->exec($sql); //SQLの実行
    $problem_id = $dbh->lastInsertID(); //問題ID

        //空白と同一内容の選択肢チェックが必要
        if ($answer_type == 0) { //選択問題
            $answer_problem_option = array(); //配列の初期化

            //選択肢の登録
            for ($i=1; $i<6; $i++) {
                //選択肢挿入
                $sql = 'INSERT INTO `option_tb` (`problem_id`, `option`, `image_path`) VALUES ("' . $problem_id . '", "' . $item[(string)$i] . '", ';
                if ($item['選択肢画像'] != '') {
                    $image_path=$item['時期'].$item['ampm'].$item['問題番号'].'op'. $i .'.png';
                    $sql .= '"' . $image_path . '"';
                }else{
                    $sql .= 'NULL';
                }
                $sql .=')';
                //echo $sql;
                $dbh->exec($sql); //SQLの実行
                $answer_problem_option[] = $dbh->lastInsertID(); //選択肢IDの格納
                if($i==4){
                    if($item[(string)($i+1)]==''){
                        if($item['選択肢画像']=='4'||$item['選択肢画像']==''){
                            break;
                        }
                    }
                }
            }
            $digs=[];
            if((int)$item['正答']>10){
                $num=(int)$item['正答'];
                $k=0;
                while($num){
                    $dig = $num % 10;
                    $digs[$k]=$dig;
                    $num = (int)$num / 10;
                    $k+=1;
                  }
                  //回答の登録
                  for ($k=count($digs)-2; $k>=0; $k--){
                    if((int)$digs[$k]==0){
                        continue;
                    }
                      $sql = 'INSERT INTO `answer_tb` (`problem_id`, `option_id`) VALUES ("' . $problem_id . '", "' . $answer_problem_option[(int)$digs[$k]-1] . '")';
                      $dbh->exec($sql); //SQLの実行
                    }
            }else{
                $sql = 'INSERT INTO `answer_tb` (`problem_id`, `option_id`) VALUES ("' . $problem_id . '", "' . $answer_problem_option[(int)$item['正答']-1] . '")';
                $dbh->exec($sql); //SQLの実行
            }

        }else{ //計算問題
            //数字の登録
            $num=$item['2'];
                $digs=[];
                $k=0;
                while($num){
                    $dig = $num % 10;
                    $digs[$k]=$dig;
                    $num = (int)$num / 10;
                    $k+=1;
                  }
            for ($i=count($digs)-2; $i>=0; $i--) {
                $sql = 'INSERT INTO `answer_tb` (`problem_id`, `number_value`) VALUES ("' . $problem_id . '", "' . $digs[$i] . '")';
                $dbh->exec($sql); //SQLの実行
            }
        }



endforeach;
?>

<?php
// シート内の行ごとのループ処理終了

?>
</section>
<?php
// シートのカウント処理
$counter++;
//endif;
endif;
echo 'successfuly';
?>
</body>
</html>