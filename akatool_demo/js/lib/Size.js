'use strict'

/*
    Point.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/14
    Last Updated : 2016/09/04
    [ note ]
       サイズを管理するクラス
*/

AKaToolLib.Size = class {
    
    
    
    // コンストラクタ
    constructor( width, height ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.width  = new AKaToolLib.Value( width  );
        this.height = new AKaToolLib.Value( height );
    }
    
    
    
    // 座標、サイズを返す
    getWidth (){ return this.width.getValue (); }
    getHeight(){ return this.height.getValue(); }
    
    
    
    // サイズを設定
    setSize( width, height ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.width.setValue ( width  );
        this.height.setValue( height );
    }
    
    // サイズを増減
    addSize( width, height ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.width.addValue ( width  );
        this.height.addValue( height );
    }
    
}