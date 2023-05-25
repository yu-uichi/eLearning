'use strict'

/*
    Category.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/15
    Last Updated : 2016/08/15
    [ note ]
*/

class Category {
    
    // コンストラクタ
    constructor( categoryNum, name, color, borderColor, borderStyle, borderSize ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.categoryNum    = categoryNum;
        // カテゴリ情報
        this.name           = name;
        this.color          = color;
        // 線情報
        this.borderColor    = borderColor;
        this.borderStyle    = borderStyle;
        this.borderSize     = borderSize;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 登録番号を返す
    getCategoryNum  (){ return this.categoryNum ; }
    // 表示名を返す
    getName         (){ return this.name        ; }
    // カード色を返す
    getColor        (){ return this.color       ; }
    // 線の色を返す
    getBorderColor  (){ return this.borderColor ; }
    // 線のスタイルを返す
    getBorderStyle  (){ return this.borderStyle ; }
    // 線の太さを返す
    getBorderSize   (){ return this.borderSize  ; }
    // 線の情報を返す
    getBorder       (){
        return this.getBorderColor() + ' '
             + this.getBorderStyle() + ' '
             + this.getBorderSize () + ' ';
    }
    
}