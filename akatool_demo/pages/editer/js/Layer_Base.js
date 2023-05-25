'use strict'

/*
    Layer_Base.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/15
    Last Updated : 2016/09/02
    [ note ]
*/

class Layer_Base {
    
    // コンストラクタ
    constructor( editer, layerDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.editer     = editer;       // エディタクラス
        this.layerDom   = layerDom;     // レイヤーのdomオブジェクト
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // エディタクラスを返す
    getEditer   (){ return this.editer;     }
    // レイヤーのdomオブジェクトを返す
    getLayerDom (){ return this.layerDom;   }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 更新
    update(){}
    
}