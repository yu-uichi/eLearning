'use strict'

/*
    Point.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/14
    Last Updated : 2016/09/03
    [ note ]
       座標を管理するクラス
*/

AKaToolLib.Point = class {
    
    // コンストラクタ
    constructor( x, y ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.x = new AKaToolLib.Value( x );
        this.y = new AKaToolLib.Value( y );
    }
    
    
    
    // 座標を返す
    getX(){ return this.x.getValue(); }
    getY(){ return this.y.getValue(); }
    
    // 距離を返す
    distanceX( x ){ return this.x.distance( x ); }
    distanceY( y ){ return this.y.distance( y ); }
    
    
    
    // 座標を設定
    setPoint( x, y ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.x.setValue( x );
        this.y.setValue( y );
    }
    
    // 座標を増減
    addPoint( x, y ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.x.addValue( x );
        this.y.addValue( y );
    }
    
    // 距離を返す
    distance( x, y ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        return Math.sqrt( this.distanceSq( x, y ) );
    }
    
    // 距離の2乗を返す
    distanceSq( x, y ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var dx = this.distanceX( x );
        var dy = this.distanceY( y );
        return dx * dx + dy * dy;
    }
    
}