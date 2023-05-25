'use strict'

/*
    AKaToolDataIO.js
    Copyright : Tsubasa Nakano
    Created : 2016/09/04
    Last Updated : 2016/10/23
    [ note ]
*/

AKaToolLib.AKaToolDataIO = class extends AKaToolLib.DataIO {
    
    // コンストラクタ
    constructor(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super();
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    //日付を取得して2015-12-01のような形式で返す
    getNowTime(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var t       = new Date();
        var year    = t.getFullYear();
        var month   = t.getMonth()+1;
        var day     = t.getDate();
        var hour    = t.getHours();
        var minute  = t.getMinutes();
        return year + '-' + month + '-' + day + '_' + hour + '-' + minute;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // サーバーに保存
    // successEvent = function( response ){}
    saveToServer( successEvent ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var content = {
            'action'        : 'save'
        ,   'page'          : 'databaseIO'
        ,   'diagramData'   : this.getSaveData()
        };
        var thisClass = this;
        super.sendAjax( content, function(){
            switch( this.readyState ){
                case 4 :    // 受信完了
                    var status = this.status;
                    if( ( 200 <= status && status < 300 ) || ( status == 304 ) ){
                        if( this.responseText === '' ){
                            console.log( '保存失敗' );
                        }else{
                            console.log( '保存成功' );
                            successEvent( this.responseText );
                        }
                    }else{
                        console.log( '保存失敗' );
                    }
                    break;
            }
        } );
    }
    
    // サーバから読み込み
    loadFromServer( successEvent, target, optionHash ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var content = $.extend( {
            'action'        : 'load'
        ,   'page'          : 'databaseIO'
        ,   'loadTarget'    : target
        }, optionHash || {} );
        
        var thisClass = this;
        super.sendAjax( content, function(){
            switch( this.readyState ){
                case 4 :    // 受信完了
                    var status = this.status;
                    if( ( 200 <= status && status < 300 ) || ( status == 304 ) ){
                        console.log( '読み込み成功' );
                        successEvent( this.responseText );
                    }else{
                        console.log( '読み込み失敗' );
                    }
                    break;
            }
        } );
    }
    
}