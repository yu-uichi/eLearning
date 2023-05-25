<?php
  session_start();
  //接続用関数の呼び出し
  require_once(__DIR__.'/functions.php');
  
?>
<?php
    if(!isset($_POST['region'])){
        header('Location: test_setting_region.php');
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title><?php echo $APP_TITLE_EXPLAIN . $APP_TITLE; ?></title>


  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/progress-bar.css">
  <link rel="stylesheet" type="text/css" href="css/questionArea.css">
  <link rel="stylesheet" type="text/css" href="css/result.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/timer.js"></script>
</head>
<body>


<?php
    require_once(__DIR__ . "/header.php");
?>
<?php
    global $problem,$optionData,$ansData;
    $dbh = connectDB(); //DB接続
    $region_id=$_POST['region']; //分類指定
    $limit = $_POST['numOfQue'];//問題数
    $sql = "SELECT * FROM `problem_tb` WHERE region_id = " . $region_id . " ORDER BY RAND() LIMIT ". $limit;
    //$sql = "SELECT * FROM `problem_tb` WHERE id BETWEEN 20 AND 27";   //デバッグ用sql
    $sth = $dbh->query($sql); //SQLの実行
    while($row = $sth->fetch()){
        $problem[] = $row['problem'];
        $ansType[] = $row['answer_type'];
        $proId[] = $row['id'];
        $queImage[] = $row['image_path'];
        //-------選択肢の抽出-------
        $sql = "SELECT `id`,`option`,`image_path` FROM `option_tb` WHERE problem_id = " . $row['id'];
        $opSth = $dbh->query($sql); //SQLの実行
        $optionTemp=array();
        while($opRow = $opSth->fetch()){
            $optionTemp[] = array('id'=>$opRow['id'],'option'=>$opRow['option'],'image_path'=>$opRow['image_path']);
        }
        $optionData[] = $optionTemp;
        $optionTemp = array();
        //-------------------------

        //-------正答の抽出----------
        $sql = "SELECT `problem_id`,`option_id`,`number_value` FROM `answer_tb` WHERE problem_id = " . $row['id'];
        $ansSth = $dbh->query($sql); //SQLの実行
        while($ansRow = $ansSth->fetch()){
            $ansTemp[] = array('problem_id'=>$ansRow['problem_id'],'option_id'=>$ansRow['option_id'],'number_value'=>$ansRow['number_value']);
        }
        $ansData[] = $ansTemp;
        $ansTemp = array();
        //--------------------------
    }

    $jsonProblem = json_encode($problem);//js用にjson変換
    $jsonQueImage = json_encode($queImage);
    $jsonAnsType = json_encode($ansType);
    $jsonproId = json_encode($proId);
    $jsonOptionData = json_encode($optionData);
    $jsonAnsData = json_encode($ansData);
?>
<script>
    var problem = <?php echo $jsonProblem;?>;//問題文
    var queImage = <?php echo $jsonQueImage;?>//問題文画像
    var nowPro = 0;//現在何問目
    var ansType = <?php echo $jsonAnsType;?>;
    var proId = <?php echo $jsonproId;?>;
    var optionData = <?php echo $jsonOptionData;?>;
    var ansData = <?php echo $jsonAnsData;?>;
    var user_answer = [];
    var correct = [];
</script>
<div id="modal-content">
    <div class="modalHeader">
        <div class="modalClose" id="modal-close" onclick="modalClose()">✖︎</div>
    </div>
    <div class="modalMiddle">
        <div class="modalProArea">
            <div class="modalQueImage"></div>
            <div class="modalProText questionText">

            </div>
            <div class="modalProOp">
                <table class="table modalOpTable">
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="app">
        <div class="progress">
            <div class="progress-bar"></div>
        </div>
    </div>
    <h2 class="time">00:00:00</h2>
    <input type="button" class="btn btn-primary queStartBtn" onclick="queStart();" value="START">
    <div class="questionArea queHide">
        <div class="queImage queHide"></div>
        <div class="questionText"><?php echo $problem[0];?></div>
        <div class="optionAnswer queHide">
            
        </div>
        <div class="numAnswer queHide">
        </div>
        <input type="button" class="btn btn-primary queAnsBtn" onclick="queNext();" value="回答">
    </div>
    <div class="resultArea queHide">
        <div class="result">
            <table class="table table-hover resultTable">
                <thead>
                    <tr>
                        <th>番号</th>
                        <th>正誤</th>
                    </tr>
                </thead>
                <tbody class="resultTbody">
                </tbody>
                <caption>クリックで問題の詳細を閲覧できます</caption>
            </table>
        </div>
    </div>
    

</div>



<script>
    function queStart(){
        $('.questionArea').removeClass('queHide');//問題エリアの非表示解除
        if(queImage[0]!=null){
            setQuestionImage();
        }
        setQuestionText();
        setAnswer();
        $('.queStartBtn').remove();
        console.log(ansData);
        timerStart();
        $('.progress-bar').css('width','0%');
    }

    function queNext(){
        $('.queImage').addClass('queHide');
        $('.queImage *').remove();//queImage内の全要素削除
        user_answer[nowPro]=[];
        if(ansType[nowPro]==0){
            if($("input[type='radio']").length){//ラジオボタンがあるかどうか
                if($('input:radio[name="optionAns"]:checked').val()!=undefined){
                    user_answer[nowPro][0]=$('input:radio[name="optionAns"]:checked').val();
                }else{
                    user_answer[nowPro][0]=0;
                }
            }else if($("input[type='checkbox']").length){
                var t=0;
                $(':checkbox[name="optionAns"]:checked').each(function (){
                    user_answer[nowPro][t]=$(this).val();
                    t+=1;
                });
            }
        }else if(ansType[nowPro]==1){
            for(j=0;j<ansData[nowPro].filter(value => {
                if(value.problem_id == proId[nowPro]){
                    return true;
                }
            }).length;j++){
                temp='input[name=answerNumber'+j+']:checked';
                user_answer[nowPro][j]=$(temp).val();
            }
        }
        console.log(user_answer);
        if(nowPro+1!=problem.length){
            nowPro+=1;
            progressWidth=String(100*(nowPro/proId.length))+'%';
            $('.progress-bar').css('width',progressWidth);
            if(queImage[nowPro]!=null){
            setQuestionImage();
            }
            setQuestionText();
            setAnswer();
        }else{
            $('.progress-bar').css('width','100%');
            timerStop();
            resetAnswer();
            $('.questionText').text("終了");
            result();
        }
    }
    function setQuestionImage(){
        $('.queImage').removeClass('queHide');
        str='<img src="./problem_image/'+queImage[nowPro]+'" alt="questionImage">';
        $('.queImage').append(str);
    }

    function setQuestionText(){
        $('.questionText').text(problem[nowPro]);

    }
    function setAnswer(){
        resetAnswer();
        //resetChangeCss();
        var ans;
        var imgPath;
        console.log();
        if(ansType[nowPro]==0){ //選択肢問題
            $('.optionAnswer').removeClass('queHide');
            
            option = optionData[nowPro];//option[][0]:optionId, option[][1]:選択肢文,option[][2]:画像path
            option = answerRandom(option);
                if(ansData[nowPro].length==1){//正答がひとつの場合ラジオボタン
                    for(i=0;i<option.length;i++){
                        optionObj = '<input type="radio" class="optionAnsObj" name="optionAns" value="'+option[i].id+'" id="optionAns-'+i+'">';
                        $('.optionAnswer').append(optionObj);
                        var label = '<label for="optionAns-'+i+'">';
                        if(option[i].image_path!=null){
                            label+='<img src="./problem_image/'+option[i].image_path+'" alt="optionImage'+i+'">';
                        }
                        label+=option[i].option+'</label>';
                        $('.optionAnswer').append(label);
                        if(option[i].image_path!=null){
                            changeCss();
                        }
                    }
                }else if(ansData[nowPro].length>1){//正答が複数の場合チェックボックス
                    for(i=0;i<option.length;i++){
                        optionObj = '<input type="checkbox" class="optionAnsObj" name="optionAns" value="'+option[i].id+'" id="optionAns-'+i+'">';
                        $('.optionAnswer').append(optionObj);
                        var label = '<label for="optionAns-'+i+'">';
                        if(option[i].image_path!=null){
                            label+='<img src="./problem_image/'+option[i].image_path+'" alt="optionImage'+i+'">';
                        }
                        label+=option[i].option+'</label>';
                        $('.optionAnswer').append(label);
                        if(option[i].image_path!=null){
                            changeCss();
                        }
                    }
                }
                
            
        }else if(ansType[nowPro]==1){  //計算問題
            $('.numAnswer').removeClass('queHide');
            console.log(ansData[nowPro].filter(item => item.problem_id == proId[nowPro]));
            for(j=0;j<ansData[nowPro].filter(value => {
                if(value.problem_id == proId[nowPro]){
                    return true;
                }
            }).length;j++){
                $('.numAnswer').append('<div class="btn-toolbar mt-2" role="toolbar" aria-label="Toolbar with button groups"><div class="btn-group btn-group-toggle" data-toggle="buttons"><?php
                                    for($i=0; $i<=9; $i++) {
                                        echo '<label class="btn btn-outline-primary';
                                        if ($i==0){
                                            echo ' active';
                                        }
                                        echo '"><input type="radio" autocomplete="off"  name="answerNumber\' + j + \'" value="' . $i . '"';
                                        if ($i==0) {
                                            echo ' checked';
                                        }
                                        echo '>' . $i . '</label>';
                                    }
                                ?></div></div>');
            }
        }

    }
    function resetAnswer(){
        $('.optionAnswer').empty();
        $('.numAnswer').empty();
        $('.optionAnswer').addClass('queHide');
        $('.numAnswer').addClass('queHide');
    }

    function answerRandom(randomOption){ //選択肢問題の選択肢シャッフル
        var n = randomOption.length;
        var temp;
        var s1,s2;
        for(var i=0;i<10;i++){
            s1=Math.floor( Math.random() * n );
            s2=Math.floor( Math.random() * n );
            temp = randomOption[s1];
            randomOption[s1]=randomOption[s2];
            randomOption[s2]=temp;
        }
        return randomOption;
    }

    function result(){
        $('.questionArea').addClass('queHide');
        $('.resultArea').removeClass('queHide');
        $(window).off('beforeunload');
        for(i=0;i<proId.length;i++){
            if(ansType[i]==0){//選択肢問題
                if(user_answer[i].length!=ansData[i].length){
                    correct[i]='✖︎';//不正解
                    continue;
                }
                if(ansData[i].length==1){
                    if(ansData[i][0].option_id==user_answer[i]){
                        correct[i]=true;//正解
                    }else{
                        correct[i]=false;//不正解
                    }
                }else{
                    var flag=1;
                    for(j=0;j<user_answer[i].length;j++){
                        if($.grep(ansData[i],function(obj,index){
                            return(obj.option_id===user_answer[i][j]);
                            }
                            ).length==0){
                            flag=0;
                        }
                    }
                    if(flag==1){
                        correct[i]=true;
                    }else{
                        correct[i]=false;
                    }
                }
            }else if(ansType[i]==1){//計算問題
                flag=1;
                for(j=0;j<ansData[i].length;j++){
                    if(ansData[i][j].number_value!=user_answer[i][j]){
                        flag=0;
                    }
                }
                if(flag==1){
                        correct[i]=true;
                    }else{
                        correct[i]=false;
                    }
            }
        }
        for(i=1;i<=proId.length;i++){
            record="<tr";
            if(correct[i-1]==false){record+=" class='table-danger'";}
            record+=" onclick='modalTemp("+(i-1)+")'><th>"+i+"</th><th>";
            if(correct[i-1]==true){
                record+='○';
            }else{
                record+='✖︎';
            }
            record+="</th><tr>";
            $('table.resultTable tbody').append(record);
        }
        saveResult();


    }
    function changeCss(){
        $('.optionAnswer').css('width','80%');
        $('.optionAnswer').css('float','left');
        $('.optionAnswer label').css('width','40%');
    }

    function resetChangeCss(){
        $('.optionAnswer').css('width','50%');
        $('.optionAnswer').css('float','');
        $('.optionAnswer label').css('width','90%');
    }

</script>

<script>
    
    function modalWindow(){
        //キーボード操作などにより、オーバーレイが多重起動するのを防止する
        $(this).blur() ;	//ボタンからフォーカスを外す
        if($("#modal-overlay")[0]) return false ;		//新しくモーダルウィンドウを起動しない [下とどちらか選択]
        //if($("#modal-overlay")[0]) $("#modal-overlay").remove() ;		//現在のモーダルウィンドウを削除して新しく起動する [上とどちらか選択]

        //オーバーレイ用のHTMLコードを、[body]内の最後に生成する
        $("body").append('<div id="modal-overlay" onclick="modalClose()"></div>');
        //[$modal-overlay]をフェードインさせる
        $("#modal-overlay").fadeIn("slow");
        $("#modal-content").fadeIn("slow");
        $("#modal-content").css({"display":"flex"});
    }

    

    function modalTemp(num){
        $(".modalProText").text(problem[num]);
        $("table.modalOpTable tbody *").remove();
        $(".modal-num").remove();
        $(".modalQueImage *").remove();
        if(queImage[num]!=null){
            str='<img src="./problem_image/'+queImage[num]+'" alt="questionImage">';
            $(".modalQueImage").append(str);
        }

        if(ansType[num]==0){//選択肢問題
            for(i=0;i<optionData[num].length;i++){
            str="<tr";
            if($.inArray(optionData[num][i].id,user_answer[num])!=-1){
                if($.grep(ansData[num],function(obj,index){
                            return(obj.option_id===optionData[num][i].id);
                            }).length!=0){
                                str+=" class='table-success'";
                            }else{
                                str+=" class='table-danger'";
                            }
            }else{
                if($.grep(ansData[num],function(obj,index){
                            return(obj.option_id===optionData[num][i].id);
                            }).length!=0){
                                str+=" class='table-success'";
                            }
            }
            str+="><th>";
            if(optionData[num][i].image_path!=null){
                changeCss();
                str+='<img src="./problem_image/'+optionData[num][i].image_path+'" alt="optionImage'+i+'">';
            }
            str+=optionData[num][i].option+"</th></tr>";
            $("table.modalOpTable tbody").append(str);
            //console.log(str);
            }
        }else{
            $('#modal-content').append('<div class="modal-num"></div>');
            for(j=0;j<ansData[num].length;j++){
                str='<div class="btn-toolbar mt-2" role="toolbar" aria-label="Toolbar with button groups"><div class="btn-group btn-group-toggle" data-toggle="buttons">';
                for(i=0;i<=9;i++){
                    str+='<label class="btn btn-outline-primary';
                    if(Number(user_answer[num][j])==i){
                        str+=' active';
                    }
                    str+='"><input type="radio" autocomplete="off"  name="answerNumber' + j + '" value="' +i+ '"';
                    if(Number(user_answer[num][j])==i){
                        str+= ' checked';
                    }
                    str+= '>'+i+'</label>';
                }
                str+='</div></div>';
                $('.modal-num').append(str);
            }
        }
        modalWindow();
    }
    function modalClose(){
        $("#modal-content,#modal-overlay").fadeOut("slow",function(){
	    //フェードアウト後、[#modal-overlay]をHTML(DOM)上から削除
	    $("#modal-overlay").remove();
        });
    }

    


    $(function(){//問題回答中にページ移動を行おうとするとアラート
  $(window).on('beforeunload', function() {
      timerStop();
    if(!confirm('中断データは保存されません。移動しますか？')){
        /* キャンセルの時の処理 */
        timerRestart();
        return false;
    }else{
        /*　OKの時の処理 */
        return true;
    }
  });
});
</script>
<script>
function saveResult(){
      if('<?php if(isset($_SESSION['login'])){
          echo $_SESSION['login'];
          }else{
              echo 'fail';
              }
        ?>'=='success'){//ログイン中
          userId=<?php if(isset($_SESSION['id'])){
              echo $_SESSION['id'];
              }else{
                  echo 'fail';
                }
                  ?>;//ユーザID
          now = new Date();//現在日時
          date =  now.getFullYear() + "/" + (now.getMonth() + 1) + "/" + now.getDate() + "/" + now.getHours() + "/" + now.getMinutes();

        $.ajax({
            url: 'save_test_result.php',
            type: 'POST',
            data: {
                'userId': userId,
                'sec': sec,
                'min': min,
                'hour': hour,
                'proId': proId,
                //'user_answer': user_answer,
                'date': date,
                'correct': correct
            },
            success: function (data) {
                console.log('OK');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log('NG');
            }
        });

      }else{//未ログイン
          return;
      }
  }
</script>

</body>
</html>
