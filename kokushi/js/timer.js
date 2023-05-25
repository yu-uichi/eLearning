
    var sec = 0;
    var min = 0;
    var hour = 0;
  
    var timer;
  
    // スタート
    function timerStart() 
    {
      // 00:00:00から開始
      sec = 0;
      min = 0;
      hour = 0;
      $('.time').html('00:00:00');
      timer = setInterval(countup, 1000);
  
      
    };
  
    // ストップ
    function timerStop() {
      // 一時停止
      clearInterval(timer);
    }

    //タイマー再開
    function timerRestart(){
        timer = setInterval(countup, 1000);
    }

  
   /**
    * カウントアップ
    */
    function countup()
    {
      sec += 1;
  
      if (sec > 59) {
        sec = 0;
        min += 1;
      }
  
      if (min > 59) {
        min = 0;
        hour += 1;
      }
  
      // 0埋め
      sec_number = ('0' + sec).slice(-2);
      min_number = ('0' + min).slice(-2);
      hour_number = ('0' + hour).slice(-2);
  
      $('.time').html(hour_number + ':' +  min_number + ':' + sec_number);
    }
  