'use strict'

/*
    Window_Controller.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/16
    Last Updated : 2016/08/16
    [ note ]
*/

class Window_Controller {
    
    // コンストラクタ
    constructor(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        $( INCLUDE.WINDOW ).appendTo( this.getWindowLayerDom() );
        this.pageSet    = {};
        this.showFlag   = false;
        this.init();
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // Domオブジェクトを返す
    // レイヤー
    // 背景
    // ウィンドウ
    getWindowLayerDom   (){ return $('#window_layer'        ); }
    getBackGroundDom    (){ return $('#window_background'   ); }
    getWindowDom        (){ return $('#window'              ); }
    
    // 表示内容を設定
    setContent  ( content ){ this.getWindowDom().html( content ); }
    
    // 表示フラグを変更
    setShowFlag( flag ){ this.showFlag = flag; }
    
    // 表示中ならtrueを返す
    isShowing(){ return this.showFlag; }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // ページを返す
    getPage( pageName ){ return this.pageSet[pageName]; }
    // ページの登録
    addPage( pageName, pageContent ){ this.pageSet[pageName] = pageContent; }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 初期化
    init(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getBackGroundDom().hide();
        // 背景
        var thisClass = this;
        this.getBackGroundDom().on( 'click', function( event ){
            thisClass.hide();
            event.stopPropagation();
        } );
        // ウィンドウ
        this.getWindowDom().on( 'click', function( event ){
            event.stopPropagation();
        } );
    }
    
    // 表示していない場合は表示を行い、表示されていない場合は内容を更新
    show( pageName ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        if( pageName == null ){ console.log( '不明なページ' ); }
        // ウィンドウ内容更新
        this.setContent( this.getPage( pageName ) );
        console.log(pageName);
        // 表示されていない場合は表示
        if( !this.isShowing() ){
            this.showWindow();
            this.setShowFlag( true );
        }
    }
    
    // 表示
    showWindow(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getBackGroundDom().show();
        // 背景を表示
        this.getBackGroundDom().animate(
            { opacity :   1 },
            { duration: 200 }
        );
        // ウィンドウを表示
        this.getWindowDom().animate(
            {   top     : '0px',
                opacity : 1,
            },{ duration  : 400 }
        );
    }
    
    // 非表示
    hide(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        if( !this.isShowing() ){ return; }
        this.hideWindow();
        this.setShowFlag( false );
    }
    
    // 非表示
    hideWindow(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thisClass = this;
        this.getBackGroundDom().animate( {
            opacity : 0
        },{
            duration: 200,
            complete: function(){
                thisClass.getBackGroundDom().hide();
            }
        } );
        this.getWindowDom().animate( {
            top     : '-640px',
            opacity : 0,
        },{
            duration  : 400,
        } );
    }
    
}