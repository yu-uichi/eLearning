'use strict'

/*
    Category_Controller.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/15
    Last Updated : 2016/08/15
    [ note ]
*/

class Category_Controller {
    
    // コンストラクタ
    constructor(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.categorySet    = [];               // カテゴリの配列
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // カテゴリの登録数を返す
    getLength(){ return this.categorySet.length; }
    // カテゴリ取得
    getCategory( categoryNum ){
        for( var category of this.categorySet ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
            if( categoryNum == category.getCategoryNum() ){ return category; }
        }
    }
    
    
    
    // カテゴリを追加
    addCategory( categoryNum, name, color, border ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var category = new Category( categoryNum, name, color, border[0], border[1], border[2] );
        this.categorySet.push( category );
    }
    
    // 登録番号と表示名を連想配列で返す
    getList(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var list = [];
        for( var category of this.categorySet ){
            list.push( {
                'num'  : category.getCategoryNum(),
                'name' : category.getName()
            } );
        }
        return list
    }
    
}