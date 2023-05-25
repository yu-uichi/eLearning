<!DOCTYPE html>

<!--
    Page_Base.html
    Copyright : Sawano Laboratogy
    Created : 2016/08/12
    Last Updated : 2022/09/19
    [ note ]
-->

<html>

<head>
    <title>AKaTool</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ▼▼▼ HEAD ▼▼▼ -->
    <!-- エディタヘッダー -->

    <!-- css -->
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/load_diagram.css">
    <link rel="stylesheet" href="pages/editer/css/page_editer.css">
    <link rel="stylesheet" href="pages/editer/css/layer_canvas.css">
    <link rel="stylesheet" href="pages/editer/css/card.css">
    <link rel="stylesheet" href="pages/editer/css/layer_menu.css">
    <link rel="stylesheet" href="pages/editer/css/layer_subview.css">
    <link rel="stylesheet" href="pages/editer/css/layer_help.css">
    <link rel="stylesheet" href="pages/editer/css/layer_window.css">



    <!-- jquery -->
    <script src="js/jquery/jquery-2.2.1.min.js"></script>
    <script src="js/jquery/jPrintArea.js"></script> <!-- 印刷用プラグイン -->
    <script src="js/jquery/jquery.mousewheel.min.js"></script> <!-- マウスホイール用プラグイン -->

    <!-- AKaToolLib -->
    <script src="js/lib/AKaToolLib.js"></script>
    <script src="js/lib/Value.js"></script>
    <script src="js/lib/Point.js"></script>
    <script src="js/lib/Size.js"></script>
    <script src="js/lib/Rect.js"></script>
    <script src="js/lib/Phrase.js"></script>
    <script src="js/lib/DataIO.js"></script>
    <script src="js/lib/AKaToolDataIO.js"></script>
    <script src="js/lib/BrowserCheck.js"></script>

    <script src="js/LoadList.js"></script>
    <script src="js/LoadDiagram.js"></script>

    <!-- HTMLテンプレート読み込み -->
    <script>
    var INCLUDE = {};
    INCLUDE.ARROW = `<!-- 矢印 -->
<div class="arrowCanvas">
    <canvas></canvas>
    <img>
</div>`;
    INCLUDE.CARD = `
<!-- カード -->
<div class='card'>
    <div class='deleteButton'></div>
    <div class='title'>
        <!-- タイトル -->
    </div>
    <div class='content'>
        <!-- 内容 -->
    </div>
    <!-- キャンセルボタン -->
    <input class='footerButton cancelButton' type='button'>
    <!-- 確定ボタン -->
    <input class='footerButton confirmButton' type='button'>
</div>
`;
    INCLUDE.WINDOW = `
<!-- ウィンドウベース -->
<div id="window_background" class="layer">
    <div class="center">

        <div id="window"></div>

    </div>
</div>
`;
    INCLUDE.WINDOW_LOAD = `
<!-- 読み込み先指定ウィンドウ -->
<div id="window_load">
    <div>
        <!--
        <button type="submit" id="loadServer" class="targetSelectButton"></button>
        -->
        <button type="submit" id="loadLocal"  class="targetSelectButton"></button>
    </div>

    <button type="submit" class="footerButton cancel"></button>

</div>
`;
    INCLUDE.WINDOW_LOAD_LOCAL = `
<!-- ローカルへ保存ウィンドウ -->
<div class="title"></div>

<div class="content">
    <span class="loadPath"></span>:<input type="text" class="loadPath" disabled="disabled">
    <button type="submit" class="reference"></button>
</div>


<button type="submit" class="footerButton cancel "     ></button>
<button type="submit" class="footerButton loadConfirm" ></button>`;
    INCLUDE.WINDOW_LOAD_SERVER = `
<!-- ローカルへ保存ウィンドウ -->
<div class="title"></div>

<div id="loadDiagramArea"></div>

<button type="submit" class="footerButton cancel "     ></button>
<button type="submit" class="footerButton loadConfirm" ></button>`;
    INCLUDE.WINDOW_SAVE = `
<!-- 保存先指定ウィンドウ -->
<div id="window_save">
<!--
    <div>
        <button type="submit" id="saveServer"       class="targetSelectButton"></button>
        <button type="submit" id="overwriteServer"  class="targetSelectButton"></button>
    </div>
-->

    <button type="submit" id="saveLocal"        class="targetSelectButton"></button>
</div>

<button type="submit" class="footerButton cancel"></button>`;
    INCLUDE.WINDOW_SAVE_PROPERTY = `
<!-- 保存情報の設定ウィンドウ -->
<div class="title"></div>

<div id="window_property">
    <table>
        <tr><td>
            <span class="saveTitle"></span>
        </td><td>
            <input type="text" class="propertyInput">
        </td></tr><tr><td>
            <span class="saveNote"></span>
        </td><td>
            <textarea class="propertyInput"></textarea>
        </td></tr>
    </table>
</div>

<button type="submit" class="footerButton cancel "      ></button>
<button type="submit" class="footerButton saveConfirm"  ></button>`;
    </script>

    <!-- 定数読み込み -->
    <script>
    AKaToolLib.SITE_URL = 'https://sawanolab.aitech.ac.jp/akatool/akatool4.0/index.php';
    AKaToolLib.LOAD_DIAGRAM_DATA = '%LOAD_DIAGRAM_DATA%';
    AKaToolLib.MAX_CARD = '20';
    AKaToolLib.MAX_ARROW = '30';
    </script>

    <!-- JavaScript -->
    <script src="pages/editer/js/Main.js"></script>
    <script src="pages/editer/js/DataIO.js"></script>
    <script src="pages/editer/js/Editer.js"></script>
    <script src="pages/editer/js/Layer_Base.js"></script>
    <script src="pages/editer/js/layer_canvas/category/Category.js"></script>
    <script src="pages/editer/js/layer_canvas/category/Category_Controller.js"></script>
    <script src="pages/editer/js/layer_canvas/card/Card.js"></script>
    <script src="pages/editer/js/layer_canvas/card/CardPainter.js"></script>
    <script src="pages/editer/js/layer_canvas/card/CardPainter_Controller.js"></script>
    <script src="pages/editer/js/layer_canvas/arrow/Arrow.js"></script>
    <script src="pages/editer/js/layer_canvas/arrow/ArrowPainter.js"></script>
    <script src="pages/editer/js/layer_canvas/arrow/ArrowPainter_Controller.js"></script>
    <script src="pages/editer/js/layer_canvas/Layer_Canvas.js"></script>
    <script src="pages/editer/js/layer_subview/Layer_Subview.js"></script>
    <script src="pages/editer/js/layer_help/Help_Controller.js"></script>
    <script src="pages/editer/js/layer_help/Layer_Help.js"></script>
    <script src="pages/editer/js/layer_menu/Layer_Menu.js"></script>
    <script src="pages/editer/js/layer_window/Window_Controller.js"></script>
    <script src="pages/editer/js/layer_window/Layer_Window.js"></script>

    <!-- Language -->
    <script src="pages/editer/js/language/ja.js"></script>

    <!-- ▲▲▲ HEAD ▲▲▲ -->
</head>

<body>
    <!-- ▼▼▼ BODY ▼▼▼ -->
    <!--
    [ note ]
        [ レイヤー構造 ]
        ↑画面背面
            body
            ├edit canvas_layer
            │├first_message //edit base layer
            │├card layer
            │└arrow layer
            ├menu layer
            ├help layer
            └window layer
        ↓画面前面
-->



    <!-- 編集エリアレイヤー( マウスイベントはこのレイヤーで受け取る ) -->
    <div class="layer" id="canvas_layer">
        <!-- 背景メッセージ -->
        <div class="layer">
            <div id="dblclick_message" class="center"></div>
        </div>
        <!-- 描画領域レイヤー -->
        <div class="layer" id="canvas"></div>
    </div>

    <!-- メニューレイヤー -->
    <div class="layer" id="menu_layer">


        <div id="serviceName">AKaTool</div>

        <div id="menu_body">
            <div class="menuButton chooseable" id="menu_button_add"> 追加 </div>
            <div class="menuButton chooseable" id="menu_button_problemView"> 看護問題の可視化 </div>
            <div class="menuButton chooseable" id="menu_button_save"> 保存 </div>
            <div class="menuButton chooseable" id="menu_button_load"> 読み込み </div>
            <div class="menuButton chooseable" id="menu_button_print"> 印刷 </div>
            <div class="menuButton chooseable" id="menu_button_help"> ヘルプ </div>
            <div class="menuButton chooseable" id="menu_button_delete"> すべて削除 </div>
        </div>

        <!-- [ 調整中 ] -->
        <div id="menu_footer">
            <table>
                <tr>
                    <td colspan="3" id="menu_zoom_rate"></td>
                </tr>
                <tr>
                    <td id="menu_zoom_out" class="menu_zoom_control">
                    </td>
                    <td id="menu_zoom_reset" class="menu_zoom_control">
                    </td>
                    <td id="menu_zoom_in" class="menu_zoom_control">
                    </td>
                </tr>
            </table>
        </div>
    </div>


    <!-- サブビューレイヤー -->
    <div class="layer" id="subview_layer">
        <canvas id='subview_diagram' class="subview_canvas"></canvas>
        <canvas id='subview_viewPort' class="subview_canvas"></canvas>
    </div>


    <!-- ヘルプレイヤー -->
    <div class="layer" id="help_layer">

        <div id="help_window_area">
            <div id="help_window">
                <div id="help_window_tytle"></div>
                <div id="help_window_content"></div>
            </div>
        </div>

    </div>



    <!-- ウィンドウレイヤー -->
    <div class="layer" id="window_layer">
    </div>
    <!-- ▲▲▲ BODY ▲▲▲ -->
</body>

</html>