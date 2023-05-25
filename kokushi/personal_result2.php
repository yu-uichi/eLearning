<?php
  session_start();
  //接続用関数の呼び出し
  //require_once(__DIR__.'/functions.php');
  require_once(__DIR__.'/db_setup.php'); //DBの設定
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="./js/bootstrap.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="./js/chosen.jquery.js"></script><!-- dropdown検索用-->
  

  <title><?php echo $APP_TITLE_EXPLAIN . $APP_TITLE; ?></title>

  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

</head>
<body>


<div class="container">
<!-- <div class="btn-group btn-group-toggle" data-toggle="buttons">
  <label class="btn btn-outline-primary active">
    <input type="radio" id="btnradio1" name="periodRadio" value='year' checked><span id="fst-label">全期間</span>
  </label>
  <label class="btn btn-outline-primary">
    <input type="radio" id="btnradio2" name="periodRadio" value='month'> 今月
  </label>
  <label class="btn btn-outline-primary">
    <input type="radio" id="btnradio3" name="periodRadio" value='week'> 今週
  </label>
  <label class="btn btn-outline-primary">
    <input type="radio" id="btnradio4" name="periodRadio" value='today'> 今日
  </label>
</div>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="column-tab" data-toggle="tab" href="#column" role="tab" aria-controls="column" aria-selected="true">column</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="line-tab" data-toggle="tab" href="#line" role="tab" aria-controls="line" aria-selected="false">line</a>
  </li>
</ul> -->
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="column" role="tabpanel" aria-labelledby="column-tab">
  <div id="highchartsArea-column"></div>
  </div>
  <div class="tab-pane fade" id="line" role="tabpanel" aria-labelledby="line-tab">
  <div id="highchartsArea-line"></div>
  </div>
</div>
</div>





<script>

    periodChart('year');
    $('#column-tab').on('click', function() {
      $('#fst-label').text('全期間');
      periodChart($('input[name="periodRadio"]:checked').val());
    });

    $('#line-tab').on('click', function() {
      lineChart($('input[name="periodRadio"]:checked').val());
      $('#fst-label').text('今年');
    });

    $( 'input[name="periodRadio"]:radio' ).change( function() {
      if($('#column').hasClass('active')){
        periodChart($(this).val());
      }else{
        lineChart($(this).val());
      }
      
   });

    $('select').change(function() {
      if($('#column').hasClass('active')){
        periodChart($('input[name="periodRadio"]:checked').val());
      }else{
        lineChart($('input[name="periodRadio"]:checked').val());
      }
    });



    function periodChart(periodType){
        $.ajax({
            type: "POST",
            url: 'personal_result_ajax.php',
            dataType:"json",
            data: {
                'periodType': periodType
            },
        })
            .done(function(data) {
                var paras=[[0,0,0.0]];  //paras[][0]:回答数 paras[][1]:正答数 paras[][2]:正答率
                for(i=1;i<13;i++){  //領域ごとに各数値を入力
                    paras[i-1]=[];
                    paras[i-1][0]=$.grep(data,
                        function (obj, index) {
                            return (obj.region_id == String(i));
                        }
                    ).length;//各領域の回答数
                    paras[i-1][1]=$.grep(data,
                        function (obj, index) {
                            return (obj.region_id == String(i)&&obj.correct === '1');
                        }
                    ).length;//正答数
                    if(paras[i-1][0]==0){
                        paras[i-1][2]=0.0;
                    }else{
                        paras[i-1][2]=(paras[i-1][1]/paras[i-1][0])*100.0;
                    }
                }
                
                setperiodCharts(paras,periodType);
                console.log(paras);
            })//通信が成功した場合の処理
            .fail(function(err) { console.log(err);})
        }

    function lineChart(lineType){
            $.ajax({
            type: "POST",
            url: 'personal_result_line_ajax.php',
            dataType:"json",
            data: {
                'lineType': lineType
            },
        })
            .done(function(data) {
                var paras=[[0,0,0.0]];  //paras[][0]:回答数 paras[][1]:正答数 paras[][2]:正答率 paras[][3] timestamp
                console.log(data);
                for(j=1;j<13;j++){  //領域ループ
                    paras[j-1]=[];
                    paras[j-1][0]=[];//回答数
                    paras[j-1][1]=[];//正答数
                    paras[j-1][2]=[];//正答率
                    if(lineType=='year'){
                      for(i=1;i<=12;i++){ //時系列ループ
                        paras[j-1][0][i-1]=$.grep(data,function (obj, index) {return ((obj.region_id == j)&&obj.year == new Date().getFullYear()&&obj.month==i);}).length;//各領域の回答数
                        paras[j-1][1][i-1]=$.grep(data,function (obj, index) {return ((obj.region_id == String(j))&&obj.year == String(new Date().getFullYear())&&obj.month==String(i)&&obj.correct=='1');}).length;//正答数
                        if(paras[j-1][0][i-1]==0){//回答数が0の場合
                          paras[j-1][2][i-1]='null';
                        }else{
                          paras[j-1][2][i-1]=(paras[j-1][1][i-1]/paras[j-1][0][i-1])*100.0;
                        }
                      }
                    }else if(lineType=='month'){
                      iMax = new Date(data[0].year, data[0].month, 0).getDate();//年月から月の日数を取得
                      for(i=1;i<=iMax;i++){ //時系列ループ
                        paras[j-1][0][i-1]=$.grep(data,function (obj, index) {return ((obj.region_id == j)&&obj.year == new Date().getFullYear()&&obj.month==(new Date().getMonth()+1)&&obj.day==i);}).length;//各領域の回答数
                        paras[j-1][1][i-1]=$.grep(data,function (obj, index) {return ((obj.region_id == j)&&obj.year == new Date().getFullYear()&&obj.month==(new Date().getMonth()+1)&&obj.day==i&&obj.correct=='1');}).length;//正答数
                        if(paras[j-1][0][i-1]==0){//回答数が0の場合
                          paras[j-1][2][i-1]='null';
                        }else{
                          paras[j-1][2][i-1]=(paras[j-1][1][i-1]/paras[j-1][0][i-1])*100.0;
                        }
                      }
                    }else if(lineType=='week'){
                      fdt=new Date();//今日の日付取得
                      fdt.setDate(fdt.getDate()-fdt.getDay());//今週日曜の日付
                      for(i=1;i<=7;i++){ //時系列ループ
                        paras[j-1][0][i-1]=$.grep(data,function (obj, index) {return ((obj.region_id == j)&&obj.year == fdt.getFullYear()&&obj.month==(fdt.getMonth()+1)&&obj.day==fdt.getDate());}).length;//各領域の回答数
                        paras[j-1][1][i-1]=$.grep(data,function (obj, index) {return ((obj.region_id == j)&&obj.year == fdt.getFullYear()&&obj.month==(fdt.getMonth()+1)&&obj.day==fdt.getDate()&&obj.correct=='1');}).length;//正答数
                        if(paras[j-1][0][i-1]==0){//回答数が0の場合
                          paras[j-1][2][i-1]='null';
                        }else{
                          paras[j-1][2][i-1]=(paras[j-1][1][i-1]/paras[j-1][0][i-1])*100.0;
                        }
                        fdt.setDate(fdt.getDate()+1);//日付加算
                      }
                    }else if(lineType=='today'){
                      for(i=0;i<=23;i++){ //時系列ループ
                        paras[j-1][0][i]=$.grep(data,function (obj, index) {return ((obj.region_id == j)&&obj.year == new Date().getFullYear()&&obj.month==(new Date().getMonth()+1)&&obj.day==new Date().getDate()&&obj.hour==i);}).length;//各領域の回答数
                        paras[j-1][1][i]=$.grep(data,function (obj, index) {return ((obj.region_id == j)&&obj.year == new Date().getFullYear()&&obj.month==(new Date().getMonth()+1)&&obj.day==new Date().getDate()&&obj.hour==i&&obj.correct=='1');}).length;//正答数
                        if(paras[j-1][0][i]==0){//回答数が0の場合
                          paras[j-1][2][i]='null';
                        }else{
                          paras[j-1][2][i]=(paras[j-1][1][i]/paras[j-1][0][i])*100.0;
                        }
                      }
                    }
                }
                console.log(paras);
                if(lineType=='week'){
                  setLineChartsOfWeek(paras,lineType);
                }else{
                  setLineCharts(paras,lineType);
                }
                
            })//通信が成功した場合の処理
            .fail(function(err) { console.log(err);})
        }

</script>

<script>
    var title;
  function setperiodCharts(paras,periodType){
        
        if(periodType=='year'){
            title='（全期間）';
        }else if(periodType=='month'){
            title='（今月）';
        }else if(periodType=='week'){
            title='（今週）';
        }else if(periodType=='today'){
            title='（今日）';
        }else if(periodType=='custom'){
            title='（カスタム）';
        }
        // Create the chart
    Highcharts.chart('highchartsArea-column', {
      chart: {
        type: 'column'
      },
      title: {
        text: '領域別正答率'+title
      },
      subtitle: {
        text: ''
      },
      accessibility: {
        announceNewData: {
          enabled: true
        }
      },
      xAxis: {
        type: 'category'
      },
      yAxis: {
        title: {
          text: '正答率'
        },
        max: 100

      },
      legend: {
        enabled: false
      },
      plotOptions: {
        series: {
          borderWidth: 0,
          dataLabels: {
            enabled: true,
            format: '{point.y:.1f}%'
          }
        }
      },

      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b><br/> <span style="font-size:11px">回答数: {point.total}</span><br> <span style="font-size:11px">正答数: {point.correct}</span><br>',
      },

      series: [
        {
          name: "領域",
          colorByPoint: true,
          data: [
            {
              name: "必修問題",
              y: paras[0][2],       //正答率
              total: paras[0][0],   //回答数
              correct: paras[0][1]  //正答数
            },
            {
              name: "人体の構造と機能",
              y: paras[1][2],
              total: paras[1][0],
              correct: paras[1][1]
            },
            {
              name: "疾病の成り立ちと回復の促進",
              y: paras[2][2],
              total: paras[2][0],
              correct: paras[2][1]
            },
            {
              name: "健康支援と社会保障制度",
              y: paras[3][2],
              total: paras[3][0],
              correct: paras[3][1]
            },
            {
              name: "基礎看護学",
              y: paras[4][2],
              total: paras[4][0],
              correct: paras[4][1]
            },
            {
              name: "系統別成人看護学",
              y: paras[5][2],
              total: paras[5][0],
              correct: paras[5][1]
            },
            {
              name: "老年看護学",
              y: paras[6][2],
              total: paras[6][0],
              correct: paras[6][1]
            },
            {
              name: "小児看護学",
              y: paras[7][2],
              total: paras[7][0],
              correct: paras[7][1]
            },
            {
              name: "母性看護学",
              y: paras[8][2],
              total: paras[8][0],
              correct: paras[8][1]
            },
            {
              name: "精神看護学",
              y: paras[9][2],
              total: paras[9][0],
              correct: paras[9][1]
            },
            {
              name: "在宅看護論",
              y: paras[10][2],
              total: paras[10][0],
              correct: paras[10][1]
            },
            {
              name: "看護の統合と実践",
              y: paras[11][2],
              total: paras[11][0],
              correct: paras[11][1]
            }
          ]
        }
      ]
    });
}

function setLineCharts(paras,lineType){
  if(lineType=='year'){
            title='（今年）';
            range='1 to 12';
            pointStart=1;
        }else if(lineType=='month'){
            title='（今月）';
            range='1 to '+new Date(new Date().getFullYear(), (new Date().getMonth)+1, 0).getDate();//年月から月の日数を取得
            pointStart=1;
        }else if(lineType=='today'){
            title='（今日）';
            range='0 to 23';
            pointStart=0;
        }else if(lineType=='custom'){
            title='（カスタム）';
            range='カスタム';
        }

  Highcharts.chart('highchartsArea-line', {

    title: {
      text: '時系列領域別正答率'+title
    },

    subtitle: {
      text: ''
    },

    yAxis: {
      title: {
        text: '正答率'
      },
      max: 100
    },

    xAxis: {
      accessibility: {
        rangeDescription: range
      }
    },

    legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'middle'
    },

    plotOptions: {
      series: {
        label: {
          connectorAllowed: false
        },
        pointStart: pointStart
      }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">領域</span><br>',
        pointFormat: '<span style="color:{point.color}">{series.name}</span>: <b>{point.y:.2f}%</b><br/>',
      },

    series: [{
      name: '必修問題',
      data: paras[0][2],
      total: paras[0][0],
      correct: paras[0][1]
    }, {
      name: '人体の構造と機能',
      data: paras[1][2],
      total: paras[1][0],
      correct: paras[1][1]
    }, {
      name: '疾病の成り立ちと回復の促進',
      data: paras[2][2],
      total: paras[2][0],
      correct: paras[2][1]
    }, {
      name: '健康支援と社会保障制度',
      data: paras[3][2],
      total: paras[3][0],
      correct: paras[3][1]
    }, {
      name: '基礎看護学',
      data: paras[4][2],
      total: paras[4][0],
      correct: paras[4][1]
    }, {
      name: '系統別成人看護学',
      data: paras[5][2],
      total: paras[5][0],
      correct: paras[5][1]
    }, {
      name: '老年看護学',
      data: paras[6][2],
      total: paras[6][0],
      correct: paras[6][1]
    }, {
      name: '小児看護学',
      data: paras[7][2],
      total: paras[7][0],
      correct: paras[7][1]
    }, {
      name: '母性看護学',
      data: paras[8][2],
      total: paras[8][0],
      correct: paras[8][1]
    }, {
      name: '精神看護学',
      data: paras[9][2],
      total: paras[9][0],
      correct: paras[9][1]
    }, {
      name: '在宅看護論',
      data: paras[10][2],
      total: paras[10][0],
      correct: paras[10][1]
    }, {
      name: '看護の統合と実践',
      data: paras[11][2],
      total: paras[11][0],
      correct: paras[11][1]
    }],

    responsive: {
      rules: [{
        condition: {
          maxWidth: 500
        },
        chartOptions: {
          legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom'
          }
        }
      }]
    }

  });
}

function setLineChartsOfWeek(paras,lineType){
  title='（今週）';
  range='SUNDAY to SATURDAY';

  Highcharts.chart('highchartsArea-line', {

    title: {
      text: '時系列領域別正答率'+title
    },

    subtitle: {
      text: ''
    },

    yAxis: {
      title: {
        text: '正答率'
      },
      max: 100
    },

    xAxis: {
      categories: ['日', '月', '火', '水', '木', '金', '土'], //ラベル
    },

    legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'middle'
    },

    plotOptions: {
      series: {
        label: {
          connectorAllowed: false
        },
      }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">領域</span><br>',
        pointFormat: '<span style="color:{point.color}">{series.name}</span>: <b>{point.y:.2f}%</b><br/>',
      },

    series: [{
      name: '必修問題',
      data: paras[0][2]
    }, {
      name: '人体の構造と機能',
      data: paras[1][2]
    }, {
      name: '疾病の成り立ちと回復の促進',
      data: paras[2][2]
    }, {
      name: '健康支援と社会保障制度',
      data: paras[3][2]
    }, {
      name: '基礎看護学',
      data: paras[4][2]
    }, {
      name: '系統別成人看護学',
      data: paras[5][2]
    }, {
      name: '老年看護学',
      data: paras[6][2]
    }, {
      name: '小児看護学',
      data: paras[7][2]
    }, {
      name: '母性看護学',
      data: paras[8][2]
    }, {
      name: '精神看護学',
      data: paras[9][2]
    }, {
      name: '在宅看護論',
      data: paras[10][2]
    }, {
      name: '看護の統合と実践',
      data: paras[11][2]
    }],

    responsive: {
      rules: [{
        condition: {
          maxWidth: 500
        },
        chartOptions: {
          legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom'
          }
        }
      }]
    }

  });
}
    
</script>
</body>
</html>
