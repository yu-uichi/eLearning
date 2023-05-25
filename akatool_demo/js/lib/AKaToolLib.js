/*
    AKaToolLib.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/15
    Last Updated : 2016/08/15
    [ note ]
 */

// ライブラリの名前空間を宣言
var AKaToolLib = {};



// フォルダがドラッグされた際に内容を表示してしまうことを防ぐために
// documentのイベントをブロック
$(document).on( {
    'dragenter' : function( event ){
        event.stopPropagation();                // バブリング停止
        event.preventDefault();                 // イベント停止
    },

    'dragover' : function( event ){
        event.stopPropagation();                // バブリング停止
        event.preventDefault();                 // イベント停止
    },

    'drop' : function( event ){
        event.stopPropagation();                // バブリング停止
        event.preventDefault();                 // イベント停止
    }
} );