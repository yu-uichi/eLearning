'use strict'

/*
 ArrowPainter.js
 Copyright : Tsubasa Nakano
 Created : 2016/09/03
 Last Updated : 2016/09/09
 [ note ]
 */

class ArrowPainter extends Arrow {
    
    // コンストラクタ
    constructor( canvas, arrowNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super( canvas, arrowNum );
        // 矢印を構成するDOMオブジェクト
        $( INCLUDE.ARROW ).attr( 'id', 'arrow_' + arrowNum ).appendTo( this.getCanvas().getBaseCanvasDom() );
        this.arrowDom = $( '#arrow_' + arrowNum );
        
        // 矢印の始点と終点
        this.basePoint   = new AKaToolLib.Point();
        this.targetPoint = new AKaToolLib.Point();
        
        // 不透明度設定
        this.opacity = 1;
        
        this.highLightFlag = true ;        // 強調表示中ならtrue
        
        this.init();
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // カードの登録先レイヤーを返す
    getCanvasDom(){ return this.getCanvas().getLayerDom(); }
    
    // DOMオブジェクトを返す
    // 矢印
    // 描画領域
    // 出力画像
    getArrowDom     (){ return this.arrowDom; }
    getDrawAreaDom  (){ return this.getArrowDom().children( 'canvas'    ); }
    getImageDom     (){ return this.getArrowDom().children( 'img'       ); }
    
    // 描画領域を返す
    getDrawArea     (){ return this.getDrawAreaDom().get(0).getContext('2d'); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 座標変換
    toViewX     ( localX ){ return this.getCanvas().toViewX( localX ); }
    toViewY     ( localY ){ return this.getCanvas().toViewY( localY ); }
    toLocalX    ( viewX  ){ return this.getCanvas().toLocalX( viewX ); }
    toLocalY    ( viewY  ){ return this.getCanvas().toLocalY( viewY ); }
    
    // 幅と高さを返す
    getWidth        (){ return this.getDrawAreaDom().width (); }
    getHeight       (){ return this.getDrawAreaDom().height(); }
    // 矢印の始点を返す
    getBasePoint    (){ return this.basePoint;                  }
    getBasePointX   (){ return this.getBasePoint().getX();      }
    getBasePointY   (){ return this.getBasePoint().getY();      }
    // 矢印の終点を返す
    getTargetPoint  (){ return this.targetPoint;                }
    getTargetPointX (){ return this.getTargetPoint().getX();    }
    getTargetPointY (){ return this.getTargetPoint().getY();    }
    
    // 不透明度を返す
    getOpacity      (){ return this.opacity; }
    
    
    
    // 矢印の始点を設定
    setBasePoint    ( x, y ){ this.getBasePoint     ().setPoint( x, y ); }
    // 矢印の終点を設定
    setTargetPoint  ( x, y ){ this.getTargetPoint   ().setPoint( x, y ); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 出力画像を設定
    setImage        ( content   ){ this.getImageDom().get(0).src = content; }
    
    // 不透明度を設定
    setOpacity      ( value     ){ this.opacity = value;                    }
    
    // 強調表示フラグを設定
    setHighLightFlag( flag      ){ this.highLightFlag = flag;               }
    
    
    
    // 強調表示中ならtrueを返す
    isHighLighting  (){ return this.highLightFlag; }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 初期化
    init(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getImageDom().hide();
    }
    
    // キャンバスの位置とサイズを設定
    setCanvasSize( x, y, width, height ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getArrowDom    ().css( "left"   , x        + "px" );
        this.getArrowDom    ().css( "top"    , y        + "px" );
        this.getDrawAreaDom ().attr( "width" , width    );
        this.getDrawAreaDom ().attr( "height", height   );
    }
    
    // キャンバスに線を描画
    // x1, y1 : 始点座標
    // x2, y2 : 終点座標
    // size : 線の幅
    drawLine( x1, y1, x2, y2, size ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var drawArea = this.getDrawArea();

        // パスの生成
        drawArea.beginPath();
        drawArea.moveTo( x1, y1 );
        drawArea.lineTo( x2, y2 );
        drawArea.closePath();

        drawArea.lineWidth    = size;                   // 描画する線幅の指定
        drawArea.globalAlpha  = this.getOpacity();      // 不透明度指定

        drawArea.stroke();                              // 線を描画
    }
    
    // キャンバスに点線を描画
    // 矢印側から描画する
    drawDashLine( x1, y1, x2, y2, size, dashSize ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var distanceX = x1 - x2;
        var distanceY = y1 - y2;
        var rad = Math.atan2( distanceY, distanceX );
        var dx = dashSize * Math.cos( rad );
        var dy = dashSize * Math.sin( rad );
        
        var nowX = x2;
        var nowY = y2;
        var distance = Math.abs( distanceX );
        
        // 点線の描画部分の場合はtrue
        var isDot = true;
        // 点線描画のループ回数
        var maxCount = Math.floor( distanceX / dx );
        // ループ
        for( var count = 0; count < maxCount; ++count ){
            if( isDot ){
                var endX = nowX + dx;
                var endY = nowY + dy;
                this.drawLine( nowX, nowY, endX, endY, size );      // 点線描画
            }
            isDot = !isDot;
            nowX += dx;
            nowY += dy;
        }
    }
    
    // 三角形の頂点を指定して描画
    // x1, y1 : 三角系の1つの頂点の座標
    // edgeSize : 三角形の1辺の長さ
    // angle : 三角形を描画する角度( x1, y1 からこの方向に三角形を描画する)
    drawTriangleToVertex( x1, y1, edgeSize, angle ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var drawArea = this.getDrawArea();

        var sideAngle = Math.PI / 6;            // 三角形の内角の半分( 30度 )
        var x2 = x1 + edgeSize * Math.cos( angle + sideAngle );
        var y2 = y1 + edgeSize * Math.sin( angle + sideAngle );
        var x3 = x1 + edgeSize * Math.cos( angle - sideAngle );
        var y3 = y1 + edgeSize * Math.sin( angle - sideAngle );

        // パスの生成
        drawArea.beginPath();
        drawArea.moveTo( x1, y1 );
        drawArea.lineTo( x2, y2 );
        drawArea.lineTo( x3, y3 );
        drawArea.closePath();

        drawArea.globalAlpha  = this.getOpacity();  // 不透明度指定

        drawArea.fill();                          // 塗りつぶし
    }

    // キャンバスに矢印を描画
    // x1, y1 : 始点座標
    // x2, y2 : 終点座標
    // size : 線の幅
    drawArrow( x1, y1, x2, y2, size, isDash ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var edgeSize    = size * 3;                             // 三角形の一辺の長さ( 線の太さの3倍 )
        var angle       = Math.atan2( y1 - y2, x1 - x2 );       // 始点の三角形を描画する方向( 終点から見た始点の方向 )

        this.drawTriangleToVertex( x2, y2, edgeSize, angle );   // 三角形描画

        var centerLineLength = edgeSize * Math.cos( Math.PI / 6 );      // 三角形の重心線の長さ
        x2 += centerLineLength * Math.cos( angle );             // 直線の終点座標を三角形の描画分だけ切り詰める
        y2 += centerLineLength * Math.sin( angle );
        
        if( isDash ){
            this.drawDashLine( x1, y1, x2, y2, size, 8 );      // 直線を描画
        }else{
            this.drawLine( x1, y1, x2, y2, size );              // 直線を描画
        }
    }

    // キャンバスに矢印を描画
    // キャンバスは描画に適したサイズにする
    draw(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var margin = 16;                                        // キャンバスのマージン
        
        var x1 = this.getBasePointX  ();
        var y1 = this.getBasePointY  ();
        var x2 = this.getTargetPointX();
        var y2 = this.getTargetPointY();

        var canvasX     = Math.min( x1,  x2 ) - margin;         // 始点と終点で値が小さい方が左上座標である
        var canvasY     = Math.min( y1,  y2 ) - margin;
        var width       = Math.abs( x2 - x1 ) + margin * 2;     // 差の絶対値で縦横幅を出す
        var height      = Math.abs( y2 - y1 ) + margin * 2;
        this.setCanvasSize( canvasX, canvasY, width, height );  // 位置、サイズ更新

        // キャンバス内のローカル座標に変換
        x1 -= canvasX;
        y1 -= canvasY;
        x2 -= canvasX;
        y2 -= canvasY;
        
        var isBaseDash   = this.getBaseCard  () != null && 'dashed' == this.getBaseCard  ().getCategory().getBorderStyle();
        var isTargetDash = this.getTargetCard() != null && 'dashed' == this.getTargetCard().getCategory().getBorderStyle();
        var isDash = this.getTargetCard() == null ? false : isBaseDash || isTargetDash;
        
        this.drawArrow( x1, y1, x2, y2, 4, isDash );            // 矢印描画
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // キャンバスをクリア
    clearCanvas(){ this.getDrawArea().clearRect( 0, 0, this.getWidth(), this.getHeight() ); }
    
    // 画像として出力
    showImage(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 画像に描画領域の内容を書き込む
        this.setImage( this.getDrawAreaDom().get(0).toDataURL() );
        this.getDrawAreaDom ().hide();      // 描画領域を非表示
        this.getImageDom    ().show();      // 出力画像を表示
    }
    
    // 更新
    update(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // キャンバスをクリア
        this.clearCanvas();
        this.getImageDom    ().hide();      // 出力画像を非表示
        this.getDrawAreaDom ().show();      // 描画領域を表示
        var base    = this.getBaseRect  ();
        var target  = this.getTargetRect();
        var basePoint   = base.edgePoint( target.getCenterX(), target.getCenterY()  );
        var targetPoint = target.edgePoint( base.getCenterX(), base.getCenterY()    );
        // 矩形が重なっている場合は終了
        if( base.isContain( targetPoint.getX(), targetPoint.getY() ) ){ return; }
        
        this.setBasePoint   ( basePoint.getX  (), basePoint.getY  () );
        this.setTargetPoint ( targetPoint.getX(), targetPoint.getY() );
        this.draw();
    }
    
    // 座標指定で更新
    updateToPoint( viewX, viewY ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // キャンバスをクリア
        this.clearCanvas();
        // ローカル座標に変換
        var x = this.toLocalX( viewX );
        var y = this.toLocalY( viewY );
        // 矩形と接触する場合は終了
        if( this.getBaseRect().isContain( x, y ) ){ return; }
        // 始点座標を計算
        var point = this.getBaseRect().edgePoint( x, y );
        
        this.setBasePoint   ( point.getX(), point.getY() );
        this.setTargetPoint ( x, y );
        this.draw();
    }
    
    // 強調表示
    highLight( flag ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 既にフラグと同じ状態なら終了
        if( flag == this.isHighLighting() ){ return; }
        this.setHighLightFlag( flag );
        
        if( flag ){
            this.setOpacity( 1 );
        }else{
            this.setOpacity( 0.4 );
        }
        this.update();
    }
    
    // 削除
    remove(){ this.getArrowDom().remove(); }
    
}