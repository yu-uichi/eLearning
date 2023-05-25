'use strict'

/*
    Layer_Menu.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/15
    Last Updated : 2016/08/15
    [ note ]
*/

class Layer_Menu extends Layer_Base {
    
    // コンストラクタ
    constructor( parent ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super( parent, $('#menu_layer') );
        this.init();
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // Domオブジェクト取得
    getServiceNameDom(){ return $('#serviceName'); }
    getAddDom       (){ return $('#menu_button_add'         ); }
    getProblemDom   (){ return $('#menu_button_problemView' ); }
    getSaveDom      (){ return $('#menu_button_save'        ); }
    getLoadDom      (){ return $('#menu_button_load'        ); }
    getPrintDom     (){ return $('#menu_button_print'       ); }
    getHelpDom      (){ return $('#menu_button_help'        ); }
    getDeleteDom    (){ return $('#menu_button_delete'      ); }
    
    // フッター取得
    getZoomRateDom  (){ return $('#menu_zoom_rate' ); }
    getZoomOutDom   (){ return $('#menu_zoom_out'  ); }
    getZoomResetDom (){ return $('#menu_zoom_reset'); }
    getZoomInDom    (){ return $('#menu_zoom_in'   ); }
    
    
    // メニューの幅
    getWidth        (){ return this.getLayerDom().outerWidth(); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */

    // 各ボタンの機能を設定
    init(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thisClass = this;
        var events = {
            service     : function( event ){ thisClass.returnToTop              (); event.stopPropagation(); }
        ,   add         : function( event ){ thisClass.getEditer().addCard      (); event.stopPropagation(); }
        ,   problemView : function( event ){ thisClass.getEditer().problemView  (); event.stopPropagation(); }
        ,   save        : function( event ){ thisClass.getEditer().save         (); event.stopPropagation(); }
        ,   load        : function( event ){ thisClass.getEditer().load         (); event.stopPropagation(); }
        ,   print       : function( event ){ thisClass.getEditer().print        (); event.stopPropagation(); }
        ,   help        : function( event ){ thisClass.getEditer().help         (); event.stopPropagation(); }
        ,   del         : function( event ){ thisClass.getEditer().allDelete    (); event.stopPropagation(); }
        ,   zoomReset   : function( event ){ thisClass.getEditer().resetView    (); event.stopPropagation(); }
        ,   zoomOut     : function( event ){ thisClass.getEditer().changeView   ( -0.1 ); event.stopPropagation(); }
        ,   zoomIn      : function( event ){ thisClass.getEditer().changeView   (  0.1 ); event.stopPropagation(); }
        };
        this.setButtonEvent( this.getServiceNameDom(), events.service );
        
        this.setButtonEvent( this.getAddDom     (), events.add            );
        this.setButtonEvent( this.getProblemDom (), events.problemView    );
        this.setButtonEvent( this.getSaveDom    (), events.save           );
        this.setButtonEvent( this.getLoadDom    (), events.load           );
        this.setButtonEvent( this.getPrintDom   (), events.print          );
        this.setButtonEvent( this.getHelpDom    (), events.help           );
        this.setButtonEvent( this.getDeleteDom  (), events.del            );
        
        this.setButtonEvent( this.getZoomResetDom   (), events.zoomReset    );
        this.setButtonEvent( this.getZoomOutDom     (), events.zoomOut      );
        this.setButtonEvent( this.getZoomInDom      (), events.zoomIn       );
    }
    
    // ボタンの機能を設定
    setButtonEvent( dom, eventFunction ){ dom.on( 'click', eventFunction ); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 表示を更新
    update(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 表示内容を更新
        this.setButtonContent( this.getAddDom     (), 'menu_add'          );
        this.setButtonContent( this.getProblemDom (), 'menu_problemView'  );
        this.setButtonContent( this.getSaveDom    (), 'menu_save'         );
        this.setButtonContent( this.getLoadDom    (), 'menu_load'         );
        this.setButtonContent( this.getPrintDom   (), 'menu_print'        );
        this.setButtonContent( this.getHelpDom    (), 'menu_help'         );
        this.setButtonContent( this.getDeleteDom  (), 'menu_delete'       );
        
        var isProblemView   = this.getEditer().isProblemViewMode();
        var isHelp          = this.getEditer().isHelpMode       ();
        // トグルボタンの背景色更新
        this.setActive( this.getProblemDom(), isProblemView );
        this.setActive( this.getHelpDom   (), isHelp        );
        // ボタンの使用状態を適用
        this.setInvalid( this.getAddDom     (), isProblemView );
        this.setInvalid( this.getSaveDom    (), isProblemView );
        this.setInvalid( this.getLoadDom    (), isProblemView );
        this.setInvalid( this.getDeleteDom  (), isProblemView );
        
        this.updateFooter();
    }
    
    // フッター更新
    updateFooter(){
        var rate = this.getEditer().getCanvas().getZoomString();
        var phrase = this.getEditer().getPhrase( 'menu_zoom' );
        this.getZoomRateDom ().html( phrase + '&emsp;:&emsp;' + Math.floor( rate ) + '%' );
        this.getZoomOutDom  ().html(  this.getEditer().getPhrase( 'menu_zoom_out'   ) );
        this.getZoomResetDom().html(  this.getEditer().getPhrase( 'menu_zoom_reset' ) );
        this.getZoomInDom   ().html(  this.getEditer().getPhrase( 'menu_zoom_in'    ) );
    }
    
    // ボタンの内容を設定
    setButtonContent( dom, phraseKey ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        dom.html( this.getEditer().getPhrase( phraseKey ) );
    }
    
    // トグルボタンの背景色指定
    setActive( dom, flag ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        flag ? dom.addClass( 'active' ) : dom.removeClass( 'active' );
    }
    
    // flagがtrueの場合ボタンを有効にする
    setInvalid( dom, flag ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        if( flag ){
            dom.addClass( 'inavle' );
            dom.removeClass( 'chooseable' );
        }else{
            dom.removeClass( 'inavle' );
            dom.addClass( 'chooseable' );
        }
    }
    
    /* トップページに戻す */
    returnToTop(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        if( window.confirm('トップページに戻りますか？') ){
            window.location.href = AKaToolLib.SITE_URL;
        }
    }
    
}