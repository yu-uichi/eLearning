'use strict'

/*
    DataIO.js
    Copyright : Tsubasa Nakano
    Created : 2016/10/11
    Last Updated : 2016/10/11
    [ note ]
*/

AKaToolLib.DataIO = class {
    
    
    
    // コンストラクタ
    constructor(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.ajax   = new XMLHttpRequest();
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 非同期通信用クラスを返す
    getAjax(){ return this.ajax; }
    
    
    
    // 連想配列をJSON形式に変換
    toJson( data ){ return JSON.stringify( data ); }
    // JSONを連想配列に変換
    toHash( json ){ return JSON.parse( json || 'null' ); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 引数で指定した連想配列を非同期通信でPOSTを送信
    sendAjax( contentHash, endEvent ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var ajax = this.getAjax();
        // 非同期通信を開始( 第三引数がfalseの場合は同期通信 )
        ajax.open( 'post', AKaToolLib.SITE_URL, true );
        // フォームデータを作成
        var form_data = new FormData();
        for( var key in contentHash ){
            form_data.append( key, contentHash[key] );
        }
        // 送信
        ajax.send( form_data );
        // 送信完了後の処理
        ajax.onreadystatechange = endEvent;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // ローカルに保存
    saveToLocal( fileName, content ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // バイナリデータ作成
        var blob    = new Blob( [ content ], { type:'"text/plain' } );
        // aタグにダウンロード内容を入れて、aタグのクリック判定を利用してダウンロード
        var a       = document.createElement( 'a' );
        a.href      = URL.createObjectURL( blob );
        a.target    = '_blank';
        a.download  = fileName;
        a.click();
    }
    
    // ローカルから読み込み
    // ファイルオブジェクト, ファイルの拡張子, 終了時に実行する関数
    loadFromLocal( file, fileType, endEvent ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thisClass = this
        
        // 拡張子の指定がある場合は，指定された拡張子でなければ終了
        if( fileType != null ){
            var regex = new RegExp( '\.' + fileType + '$', 'g');
            if( !file.name.match( regex ) ){
                alert( this.getEditer().getPhrase('load_type_error') );
                return;
            }
        }
        
        var reader = new FileReader();          // ファイル読み込みクラス生成
        reader.readAsText( file );              // テキスト形式で読み込み
        reader.onload = endEvent;               // 読み込み終了後の処理
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // cookieに保存
    // 保存する内容, 保存する時間
    saveToCookie( data, RetentionHour ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // cookieの期限を設定
        var date = new Date();
        date.setTime( date.getTime() + 1000 * 3600 * RetentionHour );
        // URLエンコードして保存
        var content = encodeURIComponent( data );
        document.cookie = 'data=' + data + ';expires=' + date.toUTCString();
    }
    
    // cookieから読み込み
    loadFromCookie(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var cookieName  = 'data=';
        var cookie      = document.cookie;
        var position    = cookie.indexOf( cookieName );
        // Cookieが存在しなければ終了
        if( position == -1 ){ return false; }
        // データ内容の開始地点
        var startIndex  = position + cookieName.length;
        // データ内容の終了地点( 終了地点がなければCookieの末尾 )
        var endIndex = cookie.indexOf( ';', startIndex );    
        if( endIndex == -1 ){ endIndex = cookie.length; }
        // cookieの必要な部分だけ読み込み
        var content = cookie.substring( startIndex, endIndex );
        // URLデコードしたものを返す
        return decodeURIComponent( content );
    }
    
}