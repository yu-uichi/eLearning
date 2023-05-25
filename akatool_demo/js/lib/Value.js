'use strict'

/*
    Value.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/14
    Last Updated : 2016/09/04
    [ note ]
       値を管理するクラス
*/

// コンストラクタ
AKaToolLib.Value = class {
    
    
    
    // コンストラクタ
    constructor( value ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.value = value || 0;
    }
    
    
    
    // 値を取得
    getValue(){ return this.value; }
    
    // 値を設定
    setValue( value ){ this.value = value; }
    
    // 値を増減
    addValue( value ){ this.value += value; }
    
    // 距離を取得
    distance( value ){ return value - this.value; }
    
}