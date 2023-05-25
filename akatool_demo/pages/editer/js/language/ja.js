'use strict'

/*
    ja.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/15
    Last Updated : 2016/08/15
    [ note ]
*/

language['ja'] = {
    'language'              : '日本語'
    
,   'menu_add'              : 'カードを追加'
,   'menu_problemView'      : '看護問題の可視化'
,   'menu_save'             : '保存'
,   'menu_load'             : '開く'
,   'menu_print'            : '印刷'
,   'menu_help'             : 'ヘルプ'
,   'menu_config'           : '設定'
,   'menu_delete'           : 'すべて削除'

,   'menu_zoom'             : '拡大率'
,   'menu_zoom_out'         : '縮小'
,   'menu_zoom_reset'       : 'リセット'
,   'menu_zoom_in'          : '拡大'

,   'dblclick_message'      : 'ダブルクリックでカードを追加できます'

,   'card_dblclick'         : 'ダブルクリックで<br>内容を編集'
,   'input_box'             : '内容を入力してください'
,   'card_delete'           : '対象カードを削除しますか?'
,   'confirm'               : '確定'
,   'cancel'                : 'キャンセル'

,   'card_all_delete'       : 'すべてのカードを削除しますか?'

,   'confirm_check'         : '編集中のカードが存在します。\n編集中のカードを全て確定しますか？'

,   'max_card'              : 'これ以上カードを追加できません。\nカードをさらに追加するには利用プランを変更する必要があります．'
,   'max_arrow'             : 'これ以上矢印を追加できません。\n矢印をさらにを追加するには利用プランを変更する必要があります．'

/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */

,   'save_local'            : 'パソコンに保存'
,   'save_server'           : 'サーバに保存'
,   'overwrite_server'      : 'サーバに上書き保存'

,   'load_local'            : 'パソコンから開く'
,   'load_server'           : 'サーバから開く'

,   'label_title'           : '関連図のタイトル'
,   'label_note'            : '説明'
,   'save_confirm'          : '保存'

,   'label_path'            : '開くファイル'
,   'load_confirm'          : '開く'
,    'reference'            : '参照'

,   'drop_file'             : 'ここにファイルをドロップしてください！'
,   'over_write'            : '現在編集中の関連図を保存していない場合は編集内容が消えてしまいます！\n他の関連図を開きますか？'

,   'load_type_error'       : 'akatool形式のファイルを入力してください！'
,   'loca_version_missmatch': '読み込もうとしたデータと現在のAKaToolのバージョンが違います。'

/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */

 ,  'HELP' : {
        'canvas'                : [ '関連図作成スペース'                , '<p>・カードを作成して関連図を作成するスペースです。</p><p>・ダブルクリックすることで、マウスポインタの位置にカードを作成することができます。</p><p>・ドラッグすることで表示位置を変更できます。</p><p>・マウスホイール(画面スクロール)で拡大率を変更できます。</p>' ]
     
    ,   'menu'                  : [ 'メニュー'                          , '<p>・メニューボタンを押すことで機能を実行できます。</p>' ]
    ,   'menu_add'              : [ 'カード追加ボタン'                  , '<p>・関連図作成スペースにカードを追加するボタンです。</p><p>・カードの追加は関連図作成スペースをダブルクリックすることでも行えます。</p>' ]
    ,   'menu_view'             : [ '看護問題の可視化ボタン'            , '<p>・看護問題の可視化を行うボタンです。</p><p>・看護問題の可視化では「看護問題」カテゴリのカードをクリックすることで、看護問題を導き出した過程を強調表示できます。</p>' ]
    ,   'menu_save'             : [ '保存ボタン'                        , '<p>・関連図を保存するボタンです。</p>' ]
    ,   'menu_load'             : [ '開くボタン'                        , '<p>・保存した関連図を開くボタンです。</p>' ]
    ,   'menu_print'            : [ '印刷ボタン'                        , '<p>・印刷を行うボタンです。</p>' ]
    ,   'menu_help'             : [ 'ヘルプボタン'                      , '<p>・ヘルプウィンドウの表示/非表示を切り替えるボタンです。</p>' ]
    ,   'menu_config'           : [ '設定ボタン'                        , '<p>・設定ボタン</p><p>・現在使用不可です。</p>' ]
    ,   'menu_delete'           : [ '全て削除ボタン'                    , '<p>・関連図上のすべてのカードを削除します。</p>' ]
    ,   'menu_zoom_reset'       : [ '表示リセットボタン'                , '<p>・拡大率と表示位置を初期状態に戻します。</p><p>・拡大率の変更はマウスホイール(画面スクロール)で行えます。</p>' ]    
    ,   'menu_zoom_out'         : [ '縮小ボタン'                        , '<p>・関連図の表示サイズを小さくします。</p>' ]
    ,   'menu_zoom_in'          : [ '拡大ボタン'                        , '<p>・関連図の表示サイズを大きくします。</p>' ]

    ,   'card'                  : [ 'カード'                            , '<p>・ドラッグで移動することができます。</p><p>・ダブルクリックで内容を編集することができます。</p>' ]
    ,   'card_editing'          : [ 'カード(編集状態)'                  , '<p>・カード内容を変更することができます。</p><p>・確定ボタンで編集内容を確定できます。</p><p>・右上の✕ボタンでカードを削除できます。</p>' ]
    ,   'arrow_button'          : [ '矢印作成ボタン'                    , '<p>・選択中のカードから矢印を作成することができるボタンです。</p>' ]
    ,   'card_deleteButton'     : [ 'カード削除ボタン'                  , '<p>・編集中のカードを削除します。</p>' ]
    ,   'card_confirm'          : [ 'カード確定ボタン'                  , '<p>・編集中のカードを確定します。</p>' ]
    
    ,   'create_arrow'          : [ '矢印の作成'                        , '<p>・カードをクリックすることで矢印を作成できます。</p><p>・すでにある矢印と同じ矢印を指定すると矢印を削除できます。</p><p>・すでに逆向きの矢印がある場合は指定した矢印で上書きします。</p><p>・何も無いところをクリックすることで作成をキャンセルできます。</p>' ]
    ,   'problem_view'          : [ '看護問題の可視化'                  , '<p>・「看護問題」カテゴリのカードをクリックすることで、看護問題を導き出した過程を強調表示できます。</p><p>・カードが無い場所をドラッグすると表示位置を変更できます。</p><p>・「看護問題の可視化」ボタンを押すことで看護問題の可視化を解除できます。</p>' ]

//    ,   'hoge'  : [ ''                    , '' ]
//    ,   'hoge'  : [ ''                    , '' ]
//    ,   'hoge'  : [ ''                    , '' ]
//    ,   'hoge'  : [ ''                    , '' ]
//    ,   'hoge'  : [ ''                    , '' ]
//    ,   'hoge'  : [ ''                    , '' ]
//    ,   'hoge'  : [ ''                    , '' ]
//    ,   'hoge'  : [ ''                    , '' ]
    }

};