'use strict'

/*
 Help_Controller.js
 Copyright : Tsubasa Nakano
 Created : 2016/09/05
 Last Updated : 2016/09/05
 [ note ]
 */

class Help_Controller {
    
    // コンストラクタ
    constructor(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.windowSize = new AKaToolLib.Size( 248, 400 );
        this.showFlag   = false;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // オブジェクトを返す
    getWindowDom        (){ return $('#help_window'         ); }
    getWindowTitleDom   (){ return $('#help_window_tytle'   ); }
    getWindowContentDom (){ return $('#help_window_content' ); }
    
    // ウィンドウサイズ
    getWindowSize   (){ return this.windowSize; }
    getWindowWidth  (){ return this.getWindowSize().getWidth (); }
    getWindowHeight (){ return this.getWindowSize().getHeight(); }
    
    // タイトルを設定
    setWindowTitle  ( value ){ this.getWindowTitleDom   ().html( value ); }
    setWindowContent( value ){ this.getWindowContentDom ().html( value ); }
    
    // 表示フラグを変更
    setShowFlag( flag ){ this.showFlag = flag; }
    
    // 表示中ならtrueを返す
    isShowing(){ return this.showFlag; }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 表示
    show(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        if( this.isShowing() ){ return; }       // すでに表示されていれば終了
        this.setShowFlag( true );
        
        var width  = this.getWindowWidth ();
        var height = this.getWindowHeight();
        this.getWindowDom().animate( {             // 大きくなる( 大きさは最終的なサイズより少し大きめにする )
            width   : ( width  + 10 ) + 'px',
            height  : ( height + 10 ) + 'px',
            opacity : 1,
        }, 200 );
        this.getWindowDom().animate( {             // 最終的な大きさにする
            width   : ( width  - 10 ) + 'px',
            height  : ( height - 10 ) + 'px',
        }, 100 );
        this.getWindowDom().animate( {             // 最終的な大きさにする
            width   : width           + 'px',
            height  : height          + 'px',
        }, 100 );
    }
    
    // 非表示
    hide(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        if( !this.isShowing() ){ return; }      // ウィンドウがすでに非表示ならば終了
        this.setShowFlag( false );

        this.getWindowDom().animate( {             // 小さくしながら消す
            width   : '0px',
            height  : '0px',
            opacity : 0,
        }, 200 );
    }
    
}