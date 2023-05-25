/*
    Main.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/15
    Last Updated : 2016/08/15
    [ note ]
*/

var language = {};

$( function(){
    var editer = new Editer();
    editer.setPhrase( language );
    editer.setLanguage( 'ja' );
    editer.start();
    
    var checkBrowser = new AKaToolLib.BrowserCheck( [ 'chrome' ] );
    if( !checkBrowser.isSupported() ){ alert('ご使用中のブラウザはAKaToolの動作確認がされていないブラウザです。\nAKaToolの使用にあたり、不具合が発生する可能性があります。\nAKaToolの使用にはGoogle Chromeをご利用ください。'); }
    
} );