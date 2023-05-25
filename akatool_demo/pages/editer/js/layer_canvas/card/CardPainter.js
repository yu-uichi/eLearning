'use strict'

/*
    CardPainter.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/15
    Last Updated : 2016/09/04
    [ note ]
*/

class CardPainter extends Card {
    
    // コンストラクタ
    constructor( canvas, cardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super( canvas, cardNum );
        // 矩形を構成するDOMオブジェクト
        $( INCLUDE.CARD ).attr( 'id', 'card_' + cardNum ).appendTo( this.getCanvas().getBaseCanvasDom() );
        this.cardDom            = $( '#card_' + cardNum );
        
        // フラグ
        this.selectingFlag          = false;        // 選択状態ならtrue
        this.editingFlag            = false;        // 編集中ならtrue
        this.highLightFlag          = true ;        // 強調表示中ならtrue
        this.init();
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // ヘルプウィンドウを更新
    updateHelp(){
        this.getHelp().updateCard( this, this.getCardNum() );
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // カードの登録先レイヤーを返す
    getCanvasDom(){ return this.getCanvas().getLayerDom(); }
    
    // DOMオブジェクトを返す
    // カード
    // タイトル
    // 内容
    // 削除ボタン
    // 確定ボタン
    getCardDom          (){ return this.cardDom; }
    getTitleDom         (){ return this.getCardDom().children( '.title'         ); }
    getContentDom       (){ return this.getCardDom().children( '.content'       ); }
    getDeleteButtonDom  (){ return this.getCardDom().children( '.deleteButton'  ); }
    getCancelButtonDom  (){ return this.getCardDom().children( '.cancelButton'  ); }
    getConfirmButtonDom (){ return this.getCardDom().children( '.confirmButton' ); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 座標変換
    toViewX( localX ){ return this.getCanvas().toViewX( localX ); }
    toViewY( localY ){ return this.getCanvas().toViewY( localY ); }
    
    // 拡大率を返す
    getZoomRate         (){ return this.getCanvas().getZoomRate(); }
    
    // カードの幅を返す
    getLocalWidth (){ return this.getRect().getWidth (); }
    getLocalHeight(){ return this.getRect().getHeight(); }
    // 描画幅を返す
    getViewWidth (){ return this.getLocalWidth () * this.getZoomRate(); }
    getViewHeight(){ return this.getLocalHeight() * this.getZoomRate(); }
    
    // 座標を返す
    getLocalX(){ return this.getRect().getX(); }
    getLocalY(){ return this.getRect().getY(); }
    // 描画座標を返す
    getViewX(){ return this.toViewX( this.getLocalX() ); }
    getViewY(){ return this.toViewY( this.getLocalY() ); }
    getViewCenterX(){ return this.getViewX() + this.getCardCenterX(); }
    getViewCenterY(){ return this.getViewY() + this.getCardCenterY(); }
    
    
    
    // 座標を中央座標で指定
    setCenterPoint( centerX, centerY ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var x = centerX - this.getCardDom().outerWidth () / 2;
        var y = centerY - this.getCardDom().outerHeight() / 2;
        this.setPoint( x, y );
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 看護問題可視化状態ならtrueを返す
    isProblemViewMode(){ return this.getEditer().isProblemViewMode(); }
    
    // フラグを変更
    setSelectingFlag( flag ){ this.selectingFlag    = flag; }
    setEditingFlag  ( flag ){ this.editingFlag      = flag; }
    setHighLightFlag( flag ){ this.highLightFlag    = flag; }
    
    // 選択中ならtrueを返す
    isSelecting     (){ return this.selectingFlag;          }
    // 編集中ならtrueを返す
    isEditing       (){ return this.editingFlag;            }
    // このカードが強調表示中ならtrueを返す
    isHighLighting  (){ return this.highLightFlag;          }
    
    // このカードが強調表示の起点となることが可能ならtrueを返す
    isProblemCard   (){ return this.getCategoryNum() == 6;  }
    // ドラッグ可能状態ならtrueを返す
    isMovable       (){ return !this.isProblemViewMode();   }
    // 選択可能状態ならtrueを返す
    isSelectable    (){
        return  !this.isProblemViewMode() ||
                ( this.isProblemViewMode() && this.isProblemCard() );
    }
    
    // 矢印ボタン表示可能状態ならtrueを返す
    isShowButton(){
        return  this.isSelecting() &&
               !this.isEditing  () &&
               !this.getCanvas().isArrowFolowing() &&
               !this.isProblemViewMode() ;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // このカードを選択状態にする
    // このカードを削除する
    // このカードを更新する
    // このカードに矢印を追加する
    toSelect    (){ this.getCanvas().activateCard   ( this.getCardNum() ); }
    toDelete    (){ this.getCanvas().deleteCard     ( this.getCardNum() ); }
    toUpdate    (){ this.getCanvas().updateCard     ( this.getCardNum() ); }
    toAddArrow  (){ this.getCanvas().addArrow       ( this.getCardNum() ); }
    
    // カードをドラッグする
    toDrag( event ){ this.getCanvas().mouseDownInCard( event, this.getCardNum() ); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 初期化
    init(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thisClass = this;
        // カード
        this.getCardDom().on( {
            'mousedown' : function( event ){
                thisClass.toDrag( event );              // ドラッグ状態にする
                event.stopPropagation();
            },
            'dblclick' : function( event ){
                thisClass.edit( true );                 // 編集状態にする
                event.stopPropagation();
            },
        } );
        // 削除ボタン
        this.getDeleteButtonDom().hide();
        this.getDeleteButtonDom().on( {
            'click' : function( event ){
                thisClass.toDelete();
                event.stopPropagation();
            },
            'mousedown' : function( event ){ event.stopPropagation(); }
        } );
        // キャンセルボタン
        this.getCancelButtonDom().hide();
        this.getCancelButtonDom().on( {
            'click' : function( event ){
                thisClass.edit( false );
                event.stopPropagation();
            },
            'mousedown' : function( event ){ event.stopPropagation(); }
        } );
        // 確定ボタン
        this.getConfirmButtonDom().hide();
        this.getConfirmButtonDom().on( {
            'click' : function( event ){
                thisClass.confirm();
                event.stopPropagation();
            },
            'mousedown' : function( event ){ event.stopPropagation(); }
        } );
    }
    
    // 更新
    update(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 内容更新
        this.getTitleDom  ().html( this.getViewTitle  ()     );
        this.getContentDom().html( this.getViewContent()     );
        // 背景と枠線の更新
        this.getCardDom().css( 'background', this.getColor()  );
        this.getCardDom().css( 'border'    , this.getBorder() );
        this.fitRect();
        this.updateRect();              // 位置とサイズを更新
        this.updateHelp();              // ヘルプを更新
    }
    
    // 表示位置とサイズを更新
    updateRect(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
//        var zoom = this.getZoomRate();
        this.getCardDom().css( {
            'transform' : 'translate(' + this.getLocalX() + 'px, ' + this.getLocalY() + 'px )'
        ,   'width'     : this.getLocalWidth () + 'px'
        ,   'height'    : this.getLocalHeight() + 'px'
        } );
    }
    
    // 内容に合わせてカードのサイズを変更
    fitRect(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getCardDom().css( {
            'width'     : ''
        ,   'height'    : ''
        } );
        var width  = Math.max( 160, this.getCardDom().outerWidth () );
        var height = Math.max(  80, this.getCardDom().outerHeight() );
        this.setSize( width, height );
    }
    
    // タイトル更新
    getViewTitle(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 編集状態ではない場合カテゴリ名を返す
        if( !this.isEditing() ){ return this.getCategory().getName(); }
        var selectDom = $('<select>');
        // 選択リストの要素を作成
        for( var list of this.getCategorySet().getList() ){
            var num  = list['num'];
            var name = list['name'];
            var optionDom = $('<option>').attr( 'value', num ).html( name ).appendTo( selectDom );
            // 現在の項目なら選択状態にする
            if( num == this.getCategoryNum() ){ optionDom.attr( 'selected', '' ); }
        }
        selectDom.on( 'mousedown', function( event ){ event.stopPropagation(); } );
        return selectDom;
    }
    
    // 内容更新
    getViewContent(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        if( !this.isEditing() ){
            return this.isEdited() ? this.getContentToHTML() : this.getEditer().getPhrase( 'card_dblclick' );
        }
        var dom = $('<textarea>');
        dom.attr( 'placeholder', this.getEditer().getPhrase( 'input_box' ) );
        dom.html( this.getContent() );
        dom.on( 'mousedown', function( event ){ event.stopPropagation(); } );
        return dom;
    }
    
    // 選択状態を変更
    select( flag ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // フラグと現在の状態が同じであれば終了
        if( flag == this.isSelecting() ){ return; }
        this.setSelectingFlag( flag );      // 選択フラグを変更
        this.updateRect();                  // 位置とサイズを更新
        this.updateArrowButton();           // 矢印ボタンを更新
        if( flag ){
            if( this.isProblemViewMode() ){
                this.getCardDom().addClass( 'highlight_selecting'   );
            }else{
                this.getCardDom().addClass( 'selecting'             );
            }
        }else{
            this.getCardDom().removeClass( 'highlight_selecting'    );
            this.getCardDom().removeClass( 'selecting'              );
        }
        this.updateHelp();              // ヘルプを更新
    }
    
    // 矢印ボタンを更新
    // flagがfalseの場合は削除
    updateArrowButton(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getCardDom().children( '.arrowButton' ).remove();          // 矢印ボタンを消す
        if( !this.isShowButton() ){ return; }
        var margin = 24;
        var width  = this.getCardDom().innerWidth ();
        var height = this.getCardDom().innerHeight();
        var widthHarf  = width  / 2;
        var heightHarf = height / 2;
        var buttonX = [    -margin, widthHarf,     widthHarf, width+margin ];
        var buttonY = [ heightHarf,   -margin, height+margin,   heightHarf ];
        for( var i = 0; i < 4; i++ ){
            var button = $('<div>').addClass( 'arrowButton' ).appendTo( this.getCardDom() );
            button.css( 'left', ( buttonX[i] - button.outerWidth ()/2 ) + 'px' );
            button.css( 'top' , ( buttonY[i] - button.outerHeight()/2 ) + 'px' );
            var thisClass = this;
            button.on( 'mousedown', function( event ){
                thisClass.toAddArrow();                 // 矢印作成
                thisClass.updateArrowButton();          // 矢印ボタン更新
                event.stopPropagation();
            } );
        }
    }
    
    // 編集状態を変更
    edit( flag ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 看護問題可視化状態ならば終了
        if( this.isProblemViewMode() ){ return; }
        // 既にフラグと同じ状態なら終了
        if( flag == this.isEditing() ){ return; }
        this.setEditingFlag( flag );
        
        if( flag ){
            this.getDeleteButtonDom ().show();      // 削除ボタン表示
            this.getCancelButtonDom ().show();      // キャンセルボタン表示
            this.getCancelButtonDom ().attr( 'value', this.getEditer().getPhrase( 'cancel' ) );
            this.getConfirmButtonDom().show();      // 確定ボタン表示
            this.getConfirmButtonDom().attr( 'value', this.getEditer().getPhrase( 'confirm' ) );
        }else{
            this.getDeleteButtonDom ().hide();      // 削除ボタン非表示
            this.getCancelButtonDom ().hide();      // キャンセルボタン非表示
            this.getConfirmButtonDom().hide();      // 確定ボタン非表示
        }
        this.toUpdate();
        this.updateArrowButton();                   // 矢印ボタン更新
    }
    
    // 内容を確定
    confirm(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 内容を設定
        var categoryNum = Number( this.getTitleDom  ().children( 'select'   ).val() );
        var content     = this.getContentDom().children( 'textarea' ).val();
        this.setContent( categoryNum, content );
        
        // 編集状態を解除
        this.edit( false );
    }
    
    // 強調表示
    highLight( flag ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        
        // 看護問題可視化状態の場合
        if( this.isProblemViewMode() ){
            var addClass = this.isProblemCard() ? 'highlight_selectable' : 'inactive_cursor';
            this.getCardDom().addClass( addClass );
        }else{
            this.getCardDom().removeClass('highlight_selectable inactive_cursor');
        }
        
        // 既にフラグと同じ状態なら終了
        if( flag == this.isHighLighting() ){ return; }
        this.setHighLightFlag( flag );
        
        // 強調表示しない場合に透明度を下げる
        if( flag ){
            this.getCardDom().removeClass('lowLight');
        }else{
            this.getCardDom().addClass('lowLight');
        }
    }
    
    // 削除
    remove(){ this.getCardDom().remove(); }
    
}