'use strict'

/*
    Rect.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/15
    Last Updated : 2016/09/03
    [ note ]
*/

AKaToolLib.Rect = class {
    
    // コンストラクタ
    constructor( x, y, width, height ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.point  = new AKaToolLib.Point( x, y );
        this.size   = new AKaToolLib.Size( width, height );
    }
    
    
    
    // 位置クラスを返す
    getPoint(){ return this.point;  }
    // サイズクラスを返す
    getSize (){ return this.size;   }
    
    // 座標、サイズを返す
    getX        (){ return this.getPoint().getX     (); }
    getY        (){ return this.getPoint().getY     (); }
    getWidth    (){ return this.getSize ().getWidth (); }
    getHeight   (){ return this.getSize ().getHeight(); }
    // 中央座標を返す
    getCenterX  (){ return this.getX() + this.getWidth () / 2; }
    getCenterY  (){ return this.getY() + this.getHeight() / 2; }
    
    
    
    // 矩形を設定
    setRect( x, y, width, height ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.setPoint( x, y );
        this.setSize( width, height );
    }
    
    
    
    // 座標を設定
    setPoint( x, y ){ this.getPoint().setPoint( x, y ); }
    // 座標を増減
    addPoint( x, y ){ this.getPoint().addPoint( x, y ); }
    
    // サイズを設定
    setSize ( width, height ){ this.getSize().setSize( width, height ); }
    // サイズを増減
    addSize ( width, height ){ this.getSize().addSize( width, height ); }
    
    
    
    // 指定された矩形が矩形範囲内ならばtrueを返す
    isContain( x, y, width, height ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        width  = width  || 0;
        height = height || 0;
        var dx = this.getPoint().distanceX( x );
        var dy = this.getPoint().distanceY( y );
        var isContainX = 0 <= dx && dx + width  <= this.getWidth ();
        var isContainY = 0 <= dy && dy + height <= this.getHeight();
        return isContainX && isContainY; 
    }
    
    // 指定された矩形がこの矩形と重なる場合はtrueを返す
    isOverlap( x, y, width, height ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        width  = width  || 0;
        height = height || 0;
        var dx = this.getPoint().distanceX( x );
        var dy = this.getPoint().distanceY( y );
        var isOverlapX = 0 <= dx + width  && dx <= this.getWidth ();
        var isOverlapY = 0 <= dy + height && dy <= this.getHeight();
        return isContainX && isContainY; 
    }
    
    
    
    copy(){ return new AKaToolLib.Rect( this.getX(), this.getY(), this.getWidth(),this. getHeight() ); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 指定座標 と 矩形の中心座標 からなる直線(直線P) と 矩形枠線との交点座標
    edgePoint( x, y ){
        // 指定座標に近い辺の座標を算出
        // 垂直の辺の式 : y = lx
        // 水平の辺の式 : x = ly
        var lx = this.getX() + ( x < this.getCenterX() ? 0 : this.getWidth () );
        var ly = this.getY() + ( y < this.getCenterY() ? 0 : this.getHeight() );
        
        // 交点座標を格納する変数
        var px = lx;
        var py = ly;
        
        // 中心との距離
        var ax = x - this.getCenterX();
        var ay = y - this.getCenterY();
        
        if     ( 0 == ax ){ px = this.getCenterX(); }
        else if( 0 == ay ){ py = this.getCenterY(); }
        else{
            // 直線Pの傾き
            var a = ay / ax;
            var b = y - ( a * x );
            // 矩形枠線の延長線との交点
            px = ( ly - b ) / a;
            py = a * lx + b;
        }
        
        px = Math.min( Math.max( px, this.getX() ), this.getX() + this.getWidth () );
        py = Math.min( Math.max( py, this.getY() ), this.getY() + this.getHeight() );
        
        return new AKaToolLib.Point( px, py );
    }
    
}