'use strict'

/*
 Layer_Subview.js
 Copyright : Tsubasa Nakano
 Created : 2016/09/09
 Last Updated : 2016/09/09
 [ note ]
 */

class Layer_Subview extends Layer_Base {
    
    // コンストラクタ
    constructor( editer ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super( editer, $('#subview_layer') );
        this.subviewZoomRate = 0.05;
        this.init();
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // キャンバスのDOMオブジェクトを返す
    getCanvasDom(){ return $('#subview_diagram'); }
    // 描画領域表示キャンバスのDOMオブジェクトを返す
    getViewCanvasDom(){ return $('#subview_viewPort'); }
    // 描画領域を返す
    getCanvasDrawArea(){ return this.getCanvasDom().get(0).getContext('2d'); }
    // 描画領域を返す
    getViewCanvasDrawArea(){ return this.getViewCanvasDom().get(0).getContext('2d'); }
    
    
    
    // 背景色を返す
    getBackGroundColor(){ return 'rgba(255, 255, 255, 0.8)'; }
    
    // 幅を返す
    getWidth (){ return 200; }
    getHeight(){ return 200; }
    
    // サブビューの拡大率
    getZoomRate(){ return this.subviewZoomRate; }
    // サブビュー座標に変換
    toSubviewX( localX ){ return localX * this.getZoomRate(); }
    toSubviewY( localY ){ return localY * this.getZoomRate(); }
    
    
    
    // キャンバスを返す
    getCanvas   (){ return this.getEditer().getCanvas(); }
    // カード配列を返す
    getCardSet  (){ return this.getCanvas().getCardSet().getCardSet(); }
    // 矢印配列を返す
    getArrowSet (){ return this.getCanvas().getArrowSet().getArrowSet(); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // カードを描画
    drawCard(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 画面中央の座標
        var viewPointWidth = this.getCanvas().getViewPointWidth() + this.getEditer().getMenu().getWidth();
        var viewCenterX = this.getCanvas().toLocalX( viewPointWidth / 2 );
        var viewCenterY = this.getCanvas().toLocalY( this.getCanvas().getViewPointHeight() / 2 );
        var centerX = this.getWidth () / 2;
        var centerY = this.getHeight() / 2;
        
        var drawArea = this.getCanvasDrawArea();
        for( var card of this.getCardSet() ){
            var subviewZoom = this.getZoomRate();
            var x =  centerX + this.toSubviewX( card.getLocalX() - viewCenterX );
            var y =  centerY + this.toSubviewY( card.getLocalY() - viewCenterY );
            var width  = card.getLocalWidth () * subviewZoom;
            var height = card.getLocalHeight() * subviewZoom;
            drawArea.fillStyle = card.getColor();
            drawArea.fillRect( x, y, width, height );
        }
    }
    
    // 線を描画
    drawLine( x1, y1, x2, y2 ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var drawArea = this.getCanvasDrawArea();

        // パスの生成
        drawArea.beginPath();
        drawArea.moveTo( x1, y1 );
        drawArea.lineTo( x2, y2 );
        drawArea.closePath();

        drawArea.strokeStyle  = 'rgb( 0, 0, 0 )';       // 線の色
        drawArea.lineWidth    = 1;                      // 描画する線幅の指定
        drawArea.globalAlpha  = 1.0;                    // 不透明度指定

        drawArea.stroke();                              // 線を描画
    }
    
    // 矢印を描画
    drawArrow(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 画面中央の座標
        var viewPointWidth = this.getCanvas().getViewPointWidth() + this.getEditer().getMenu().getWidth();
        var viewCenterX = this.getCanvas().toLocalX( viewPointWidth / 2 );
        var viewCenterY = this.getCanvas().toLocalY( this.getCanvas().getViewPointHeight() / 2 );
        var centerX = this.getWidth () / 2;
        var centerY = this.getHeight() / 2;
        
        for( var arrow of this.getArrowSet() ){
            var zoom = this.getZoomRate();
            var x1 = centerX + this.toSubviewX( arrow.getBasePointX   () - viewCenterX );
            var y1 = centerY + this.toSubviewY( arrow.getBasePointY   () - viewCenterY );
            var x2 = centerX + this.toSubviewX( arrow.getTargetPointX () - viewCenterX );
            var y2 = centerY + this.toSubviewY( arrow.getTargetPointY () - viewCenterY );
            this.drawLine( x1, y1, x2, y2 );
        }
    }
    
    // 現在位置を描画
    drawNowRect(){
        var subviewZoom = this.getZoomRate();
        var canvaszoom  = this.getCanvas().getZoomValue();
        var viewPointWidth = this.getCanvas().getViewPointWidth() - this.getEditer().getMenu().getWidth();
        var width  = viewPointWidth / canvaszoom * subviewZoom;
        var height = this.getCanvas().getViewPointHeight() / canvaszoom * subviewZoom;
        var x = this.getWidth () / 2 - width  / 2;
        var y = this.getHeight() / 2 - height / 2;
        
        var drawArea = this.getViewCanvasDrawArea();
        drawArea.strokeStyle  = 'rgb( 255, 0, 0 )';       // 線の色
        drawArea.strokeRect( x, y, width, height );
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 初期化
    init(){
        this.getCanvasDom().attr( {
            "width"  : this.getWidth ()
        ,   "height" : this.getHeight()
        } );
        this.getViewCanvasDom().attr( {
            "width"  : this.getWidth ()
        ,   "height" : this.getHeight()
        } );
    }
    
    // キャンバスをクリア
    clearDiagram(){
        var drawArea = this.getCanvasDrawArea();
        drawArea.clearRect( 0, 0, this.getWidth(), this.getHeight() );
        drawArea.fillStyle = this.getBackGroundColor();
        drawArea.fillRect( 0, 0, this.getWidth(), this.getHeight() );
    }
    
    // キャンバスをクリア
    clearViewPoint(){
        var drawArea = this.getViewCanvasDrawArea();
        drawArea.clearRect( 0, 0, this.getWidth(), this.getHeight() );
        drawArea.fillStyle = 'rgba(255, 255, 255, 0)';
        drawArea.fillRect( 0, 0, this.getWidth(), this.getHeight() );
    }
    
    // 更新
    update(){
        this.clearDiagram();
        this.drawCard();
        this.drawArrow();
    }
    
    // 表示範囲キャンバスを更新
    updateViewPoint(){
        this.clearViewPoint();
        this.drawNowRect();
    }
    
    // キャンバスを画像で返す
    getDiagramCanvasImage(){ return this.getCanvasDom().get(0).toDataURL(); }
}