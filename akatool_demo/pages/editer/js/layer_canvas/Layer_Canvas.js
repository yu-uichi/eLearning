'use strict'

/*
    Layer_Canvas.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/15
    Last Updated : 2016/10/12
    [ note ]
*/

class Layer_Canvas extends Layer_Base {
    
    // コンストラクタ
    constructor( editer ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super( editer, $('#canvas_layer') );
        
        // コントローラー
        // カード
        // 矢印
        this.cardSet            = new CardPainter_Controller ( this );
        this.arrowSet           = new ArrowPainter_Controller( this );
        
        // マウス追随矢印
        this.followArrow        = new ArrowPainter( this, 0 );
        this.followArrow.setOpacity( 0.5 );
        
        this.viewPoint  = new AKaToolLib.Point();   // 現在の表示座標
        this.dragPoint  = new AKaToolLib.Point();   // ドラッグ開始座標
        this.zoomValue  = 1.0;                      // 拡大率
        
        this.dragCardNum        = null;             // ドラッグ中のカード番号
        
        this.dragFlag           = false;            // マウス押下後ドラッグを行った場合true
        this.dragCanvasFlag     = false;            // キャンバスをドラッグ中ならtrue
        this.followArrowFlag    = false;            // 矢印がマウスに追随中ならtrue
        
        // 初期化
        this.init();
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // ヘルプウィンドウを返す
    getHelpWindow   (){ return this.getEditer().getHelpWindow(); }
    
    // 背景メッセージを返す
    getMessage      (){ return $('#dblclick_message' ); }
    
    // キャンバスを返す
    getBaseCanvasDom(){ return $('#canvas'); }
    
    // コントローラーを返す
    getCardSet  (){ return this.cardSet;    }   // カード
    getArrowSet (){ return this.arrowSet;   }   // 矢印
    
    // カード，矢印を返す
    getCard     ( cardNum  ){ return this.getCardSet ().getCard ( cardNum  ); }
    getArrow    ( arrowNum ){ return this.getArrowSet().getArrow( arrowNum ); }
    
    // マウス追随矢印を返す
    getFollowArrow(){ return this.followArrow; }
    
    
    
    // カードが1つでも存在すればtrueを返す
    isExistCard(){ return this.getCardSet().getLength() != 0; }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */

    // キャンバスを初期値点からずらす距離を返す
    getViewPoint (){ return this.viewPoint;             }
    getViewPointX(){ return this.getViewPoint().getX(); }
    getViewPointY(){ return this.getViewPoint().getY(); }
    // ドラッグ開始座標を返す
    getDragPoint (){ return this.dragPoint;             }
    getDragPointX(){ return this.getDragPoint().getX(); }
    getDragPointY(){ return this.getDragPoint().getY(); }
    
    // 表示領域のサイズを返す
    getViewPointWidth (){ return this.getLayerDom().innerWidth (); }
    getViewPointHeight(){ return this.getLayerDom().innerHeight(); }
    
    // 拡大率を返す
    getZoomValue (){ return this.zoomValue;             }
    getZoomString(){ return this.getZoomValue() * 100;  }
    
    // 表示座標を実座標に変換
    toLocalX( viewX ){ return ( viewX - this.getViewPointX() ) / this.getZoomValue(); }
    toLocalY( viewY ){ return ( viewY - this.getViewPointY() ) / this.getZoomValue(); }
    // 実座標を表示座標に変換
    toViewX( localX ){ return this.getViewPointX() + localX * this.getZoomValue(); }
    toViewY( localY ){ return this.getViewPointY() + localY * this.getZoomValue(); }
    
    
    
    
    // ドラッグ開始位置を設定
    setDragPoint( x, y ){ this.getDragPoint().setPoint( x, y ); }
    
    // 表示座標の記録値を設定
    setViewPoint( x, y ){ this.getViewPoint().setPoint( x, y ); }
    
    // 拡大率の記録値を変更
    setZoomValue( rate ){ this.zoomValue  = rate; }
    addZoomValue( rate ){ this.setZoomValue( this.zoomValue + rate ); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // ドラッグ中のカード番号を返す
    getDragCardNum  (){ return this.dragCardNum;    }
    
    // ドラッグするカードを設定．ドラッグしない場合はnull
    setDragCardNum  ( cardNum ){ this.dragCardNum     = cardNum;  }
    
    // カードドラッグ中の場合trueを返す
    isCardDraging   (){ return this.getDragCardNum  () != null; }
    
    
    
    // ドラッグフラグを設定
    setDragFlag         ( flag ){ this.dragFlag         = flag; }
    // キャンバスドラッグフラグを設定
    setDragCanvasFlag   ( flag ){ this.dragCanvasFlag   = flag; }
    // 矢印のマウスに追随フラグを設定
    setFollowArrowFlag  ( flag ){ this.followArrowFlag  = flag; }
    
    // ドラッグフラグを返す
    isDraged        (){ return this.dragFlag;           }
    // キャンバスをドラッグ中の場合trueを返す
    isDragCanvas    (){ return this.dragCanvasFlag;     }
    // 矢印がマウスに追随中の場合trueを返す
    isArrowFolowing (){ return this.followArrowFlag;    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 初期化
    init(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thisClass = this;
        this.getLayerDom().on( {
            'dblclick'      : function( event ){
                thisClass.doubleClick( event );
                event.stopPropagation();
            },
            'mousedown'     : function( event ){
                thisClass.mouseDownInCanvas( event );
                event.stopPropagation();
            },
            'mousemove'     : function( event ){
                thisClass.mouseMove( event );
                event.stopPropagation();
            },
            'mouseup'       : function( event ){
                thisClass.mouseUp( event );
                event.stopPropagation();
            },
            'mousewheel'    : function( event, delta ){
                thisClass.wheel( event, delta );
                event.stopPropagation();
            }
        } );
    }
    
    // 更新
    update(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        if( this.getCardSet().getLength() > 0 ){
            this.getMessage().hide();
        }else{
            this.getMessage().show();
            this.getMessage().html( this.getEditer().getPhrase('dblclick_message') );
        }
        // サブビューの更新
        this.getEditer().getSubview().update();
        this.getEditer().getSubview().updateViewPoint();
    }
    
    // ダブルクリック
    doubleClick( event ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 看護問題可視化状態ならば終了
        if( this.getEditer().isProblemViewMode() ){ return; }
        var x = this.toLocalX( event.clientX );
        var y = this.toLocalY( event.clientY );
        this.addCard( x, y );                           // カード追加処理
    }
    
    // キャンバスドラッグ開始
    mouseDownInCanvas( event ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // キャンバスドラッグフラグを変更
        this.setDragCanvasFlag( true );
        // ドラッグフラグ変更
        this.setDragFlag( false );
        // ドラッグ中の表示位置は
        //         開始時の表示位置 + ( ドラッグ時のマウス位置 - 開始時のマウス位置 )
        // で求められるので，開始時の表示位置 - 開始時のマウス位置 を保存
        var x = this.getViewPointX() - event.clientX;
        var y = this.getViewPointY() - event.clientY;
        this.setDragPoint( x, y );
    }
    
    // カードドラッグ開始
    mouseDownInCard( event, cardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // カードが選択不可の場合終了
        if( !this.getCard( cardNum ).isSelectable() ){ return; }
        // 看護問題可視化状態ならば
        if( this.getEditer().isProblemViewMode() ){
            this.highLightPath( cardNum );
        }
        
        // 矢印追随中なら矢印を確定
        if( this.isArrowFolowing() ){
            // 矢印が確定しなかった場合は終了
            if( !this.confirmArrow( cardNum ) ){
                this.activateCard( null );
                return;
            }
        }
        
        // カードを選択状態にする
        this.activateCard( cardNum );
        // ドラッグするカード番号を設定
        this.setDragCardNum( cardNum );
        // ドラッグ中の表示位置は
        //         開始時の表示位置 + ( ドラッグ時のマウス位置 - 開始時のマウス位置 )
        // で求められるので，開始時の表示位置 - 開始時のマウス位置 を保存
        var x = this.getCard( cardNum ).getViewX() - event.clientX;
        var y = this.getCard( cardNum ).getViewY() - event.clientY;
        this.setDragPoint( x, y );
    }
    
    // マウス移動
    mouseMove( event ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var x = event.clientX;
        var y = event.clientY;
        // 矢印作成中
        if( this.isArrowFolowing() ){
            this.getFollowArrow().updateToPoint( x, y );
        }
        // 開始時の表示位置 + ( ドラッグ時のマウス位置 - 開始時のマウス位置 )
        // より、+ ドラッグ時のマウス位置 を行う
        x += this.getDragPointX();
        y += this.getDragPointY();
        
        // キャンバスドラッグ中
        if( this.isDragCanvas() ){
            // ドラッグかクリックかの判定
            if( this.getDragPoint().distance( x + this.getViewPointX(), y + this.getViewPointY() ) ){
                this.setDragFlag( true );
            }
            this.setViewPoint( x, y );      // キャンバスの表示位置を設定
            this.updateCanvasDimention();   // キャンバスの表示位置とサイズを更新
            return;
        }
        // カードドラッグ中
        if( this.isCardDraging() ){
            var cardNum = this.getDragCardNum();
            if( !this.getCard( cardNum ).isMovable() ){ return; }
            // 位置更新
            this.updateCardPoint( cardNum, this.toLocalX( x ), this.toLocalY( y ) );
            return;
        }
    }
    
    // ドラッグ終了
    mouseUp( event ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // キャンバスのクリック判定
        if( this.isDragCanvas() && !this.isDraged() ){
            this.click( event );
        }
        this.getHelpWindow().updateContent();           // ヘルプの更新
        // ドラッグフラグを変更
        this.setDragCanvasFlag( false );
        this.setDragCardNum( null );
        // キャンバス更新
        this.update();
    }
    
    // クリック処理
    click( event ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 看護問題可視化状態ならば
        if( this.getEditer().isProblemViewMode() ){
            this.highLightPath( null );
        }
        this.cancelArrow();
    }
    
    // ホイール処理
    wheel( event, delta ){
        var moveValue = 0.015 * ( 0 < delta ? 1 : -1 );
        this.zoom( moveValue, event.clientX, event.clientY );
        // サブビューの更新
        this.getEditer().getSubview().updateViewPoint();
    }
    
    // ズームのイン/アウト処理
    zoom( moveValue, baseX, baseY ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        baseX = baseX || ( this.getViewPointWidth ()/2 );
        baseY = baseY || ( this.getViewPointHeight()/2 );
        // 新しい拡大率
        var zoom = moveValue + this.getZoomValue();
        if( 1.5 <= zoom ){
            zoom = 1.5;
            moveValue = 1.5 - this.getZoomValue();
        }else if( 0.1 >= zoom ){
            zoom = 0.1;
            moveValue = 0.1 - this.getZoomValue();
        }
        // 表示原点からの距離 = 現在の拡大率でのマウスのローカル位置 * 新しい拡大率
        // 新しい表示原点の位置 = マウスの位置 - 表示原点からの距離
        var x = baseX - this.toLocalX( baseX ) * zoom;
        var y = baseY - this.toLocalY( baseY ) * zoom;
        this.setZoomValue( zoom );
        this.setViewPoint( x, y );          // キャンバスの表示位置を変更
        this.updateCanvasDimention();       // キャンバスの表示を更新
        // メニューのフッター表示を更新
        this.getEditer().getMenu().updateFooter();
        // 矢印編集中の場合
        if( this.isArrowFolowing() ){
            this.getFollowArrow().updateToPoint( mouseX, mouseY );
        }
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 設定された数値を基にキャンバスの位置とサイズを更新
    updateCanvasDimention(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var x   = this.getViewPointX();
        var y   = this.getViewPointY();
        var zoomRate    = this.getZoomValue();
        this.getBaseCanvasDom().css( {
            'transform-origin'  : '0px 0px'
        ,   'transform'         : 'translate(' + x + 'px, ' + y + 'px ) scale(' + zoomRate + ', ' + zoomRate + ')'
        } );
    }
    
    // カードを追加
    addCard( centerX, centerY ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // カードが最大数ならば警告を出して終了
        if( this.getCardSet().getLength() >= AKaToolLib.MAX_CARD ){
            alert( this.getEditer().getPhrase( 'max_card' ) );
            return;
        }
        
        this.getCardSet().addCard( centerX, centerY );
        this.update();                          // キャンバス更新
        this.getEditer().saveToCookie();        // Cookieに保存
    }
    
    // カードを更新
    // nullを指定すると全て更新
    updateCard( cardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getCardSet ().updateCard ( cardNum );
        this.getArrowSet().updateArrow( cardNum );
        
        this.getEditer().saveToCookie();        // Cookieに保存
    }
    
    // カードの位置を更新
    updateCardPoint( cardNum, x, y ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getCard( cardNum ).setPoint( x, y );
        this.getCard( cardNum ).updateRect();
        this.getArrowSet().updateArrow( cardNum );
        
        this.getEditer().saveToCookie();        // Cookieに保存
    }
    
    // カードを選択
    // nullを指定すると全て選択解除
    activateCard( cardNum ){
        this.getCardSet().activateCard( cardNum );
    }
    
    // カードを削除
    // nullを指定すると全て削除
    // messageFlagがfalseの場合は警告なし
    deleteCard( cardNum, messageFlag ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        if( messageFlag != false ){
            // 確認ポップアップ
            var message = this.getEditer().getPhrase( cardNum == null ? 'card_all_delete' : 'card_delete' );
            if( !confirm( message ) ){ return; }
        }
        this.getCardSet ().deleteCard ( cardNum );              // カード削除
        this.getArrowSet().deleteArrowToCard( cardNum );        // 矢印を削除
        this.update();                                          // キャンバス更新
        
        this.getEditer().saveToCookie();                        // Cookieに保存
    }
    
    // 矢印を追加
    addArrow( cardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getFollowArrow().setBaseCardNum( cardNum );
        this.setFollowArrowFlag( true );
        this.getHelpWindow().updateContent();                       // ヘルプの更新
    }
    
    // 矢印を確定
    // targetCardNumがnullの場合はキャンセル
    confirmArrow( targetCardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var baseCardNum = this.getFollowArrow().getBaseCardNum();
        // 再帰になる場合は終了
        if( baseCardNum == targetCardNum ){ return false; }
        
        this.getFollowArrow().clearCanvas();
        this.setFollowArrowFlag( false );
        if( targetCardNum == null ){ return false; }          // カードが指定されていなければ終了
        
        var reverseArrow    = this.getArrowSet().getArrowToCard( targetCardNum, baseCardNum );
        var sameArrow       = this.getArrowSet().getArrowToCard( baseCardNum, targetCardNum );
        // 逆の矢印がある場合は反転
        if( reverseArrow != null ){
            this.getArrowSet().reverseArrow( reverseArrow );
        }else{
            // 同じ矢印がある場合は削除
            if( sameArrow != null ){
                this.getArrowSet().deleteArrow( sameArrow );
            }else{
                // カードが最大数ならば警告を出して終了
                if( this.getArrowSet().getLength() >= AKaToolLib.MAX_ARROW ){
                    alert( this.getEditer().getPhrase( 'max_arrow' ) );
                    return false;
                }
        
                this.getArrowSet().addArrow( baseCardNum, targetCardNum );  // 矢印を追加
            }
        }
        
        this.getHelpWindow().updateContent();                       // ヘルプの更新
        this.getEditer().saveToCookie();                            // Cookieに保存
        
        return true;
    }
    
    // 矢印作成をキャンセル
    cancelArrow(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.activateCard( null );
        if( !this.isArrowFolowing() ){ return; }
        this.confirmArrow();
    }
    
    // 強調表示
    // targetCardNumにnullが指定された場合は解除
    highLightPath( targetCardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // ハイライト表示するカード番号リストを作成
        var cardNumSet = targetCardNum == null ? null : this.getReachableCardNum( targetCardNum );
        this.getCardSet ().highLightCard ( cardNumSet );        // カードを強調表示
        this.getArrowSet().highLightArrow( cardNumSet );        // 矢印を強調表示
    }
    
    // CardNumに到達可能なカードを配列で返す
    // CardNumから矢印を逆方向に辿って探索
    // pathSetは再帰呼び出しで利用する
    getReachableCardNum( cardNum, pathSet ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 再帰呼び出し以外で呼び出された際に配列を初期化
        pathSet = pathSet || [ cardNum ];
        // cardNumのカードに向けて矢印を向けているカードのカード番号配列
        var followerCardNum = this.getArrowSet().getCardNumSetToTarget( cardNum );
        // 再帰呼び出しでカード番号配列に同じ処理を繰り返す
        // 結果をpathSetに追加する。(pathSetは配列なので参照渡し)
        // すでにpathSetに同じカード番号が追加されている場合は追加せず、
        // 再帰呼び出しも行わない
        for( var cardNum of followerCardNum ){
            if( pathSet.indexOf( cardNum ) >= 0 ){ continue; }
            pathSet.push( cardNum );
            this.getReachableCardNum( cardNum, pathSet );
        }
        return pathSet;
    }
    
    // 全て強調表示( 通常状態に戻す )
    lowLight(){
        this.getCardSet ().highLightAllCard ();
        this.getArrowSet().highLightAllArrow();
    }
    
    // カード内容確定確認
    // 確定されなかった場合はfalseを返す
    confirmCheck(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 編集中のカードがある場合
        if( this.getCardSet().isEditingCard() ){
            if( !confirm( this.getEditer().getPhrase( 'confirm_check' ) ) ){ return false; }
            this.getCardSet().confirmEditingCard();         // 編集中のカードを確定
        }
        return true;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // キャンバス情報を連想配列で返す
    getStatusToHash(){
        return {
            'version'   : this.getEditer    ().getVersion       ()
        ,   'diagramID' : this.getEditer    ().getDiagramID     ()
        ,   'name'      : this.getEditer    ().getDiagramName   ()
        ,   'note'      : this.getEditer    ().getDiagramNote   ()
        ,   'cards'     : this.getCardSet   ().getStatusToHash  ()
        ,   'arrows'    : this.getArrowSet  ().getStatusToHash  ()
        ,   'thumbnail' : this.getEditer    ().getSubview().getDiagramCanvasImage()
        };
    }
    
    // キャンバス情報を連想配列で返す
    setStatusToHash( hash ){
        if( hash['version'] != this.getEditer().getVersion() ){
            alert( this.getEditer().getPhrase('loca_version_missmatch') );
        }
        // 関連図の情報を設定
        this.getEditer().setDiagramProperty( hash['diagramID'], hash['name'], hash['note'] );
        // カードと矢印を全て削除
        this.deleteCard( null, false );
        // カードと矢印を読み込み
        this.getCardSet ().setStatusToHash( hash['cards' ] );
        this.getArrowSet().setStatusToHash( hash['arrows'] );
        this.update();                          // キャンバス更新
        
        this.getEditer().saveToCookie();        // Cookieに保存
    }
    
}