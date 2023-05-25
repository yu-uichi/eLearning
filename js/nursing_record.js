function cp(){
  var txt = document.getElementById("copy");
  txt.querySelector();
  document.execCommand("Copy");
}



$("#condition_button").click(function () {
    $("#condition").before(
      '<br><div class="input-group mb-3"><div class="input-group-prepend"><input type="text" class="form-control" size="10" id="" name="name[]" value=""placeholder="追記事項"></div><input type="text" class="form-control" id="" name="name[]" value=""></div>'
    );
    console.log("ボタンが押されました");
  });

  //<button id="0" onclick="deleteBtn(this)"> - </button>
  // window.addEventListener("beforeunload", function(e) {
  //   var confirmationMessage = "入力内容を破棄します。";
  //   e.returnValue = confirmationMessage;
  //   return confirmationMessage;
  // });

