'use strict'

/*
    BrowserCheck.js
    Copyright : Tsubasa Nakano
    Created : 2016/10/14
    Last Updated : 2016/10/14
    [ note ]
*/

AKaToolLib.BrowserCheck = class{
    
    // コンストラクタ
    constructor( list ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.supported = list;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // サポートブラウザを返す
    getSupported(){ return this.supported; }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // ブラウザを返す
    getBrowser(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var userAgent   = window.navigator.userAgent.toLowerCase();
        var ver         = window.navigator.appVersion.toLowerCase();
        if( userAgent.indexOf("msie") != -1 ){
            if( ver.indexOf( "msie 6." ) != -1 ){ return 'ie6'; }
            else if( ver.indexOf( "msie 7." ) != -1 ){ return 'ie7' ; }
            else if( ver.indexOf( "msie 8." ) != -1 ){ return 'ie8' ; }
            else if( ver.indexOf( "msie 9." ) != -1 ){ return 'ie9' ; }
            else if( ver.indexOf( "msie 10.") != -1 ){ return 'ie10'; }
            else{ return  'ie'; }
        }
        else if( userAgent.indexOf( 'trident/7' ) != -1 ){ return 'ie11'   ; }
        else if( userAgent.indexOf( 'chrome'    ) != -1 ){ return 'chrome' ; }
        else if( userAgent.indexOf( 'safari'    ) != -1 ){ return 'safari' ; }
        else if( userAgent.indexOf( 'opera'     ) != -1 ){ return 'opera'  ; }
        else if( userAgent.indexOf( 'firefox'   ) != -1 ){ return 'firefox'; }
    }
    
    // 対応ブラウザでなければfalseを返す
    isSupported(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thusBrowser = this.getBrowser();
        for( var browser of this.getSupported() ){
            if( browser == thusBrowser ){ return true; }
        }
        return false;
    }
    
}