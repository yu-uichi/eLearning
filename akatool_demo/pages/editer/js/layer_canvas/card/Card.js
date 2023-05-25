'use strict'

/*
    Card.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/15
    Last Updated : 2016/10/05
    [ note ]
*/

class Card {
    
    // コンストラクタ
    constructor( canvas, cardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.canvas         = canvas;
        
        this.cardNum        = cardNum;
        this.categoryNum    = 1;
        this.content        = '';
        this.rect           = new AKaToolLib.Rect( 0, 0, 400, 300 );
        
        this.editedFlag     = false;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */

    // キャンバスを返す
    getCanvas       (){ return this.canvas;                         }
    // エディタクラスを返す
    getEditer       (){ return this.getCanvas().getEditer();        }
    // ヘルプウィンドウクラスを返す
    getHelp         (){ return this.getEditer().getHelpWindow();    }
    // カテゴリー管理クラスを返す
    getCategorySet  (){ return this.getEditer().getCategorySet();   }
    
    // 登録番号を返す
    getCardNum      (){ return this.cardNum;        }
    // カテゴリー番号を返す
    getCategoryNum  (){ return this.categoryNum;    }
    
    // カテゴリーを返す
    getCategory     (){ return this.getCategorySet().getCategory( this.getCategoryNum() ); }
    // 背景色を返す
    getColor        (){ return this.getCategory().getColor (); }
    // 枠線を返す
    getBorder       (){ return this.getCategory().getBorder(); }
    
    // 内容を返す( ▲平文で返すのでXSSに注意 )
    getRawContent   (){ return this.content; }
    // 内容を返す
    getContent      (){ return this.sanitize( this.getRawContent() );  }
    // HTML形式で内容を返す
    getContentToHTML(){ return this.getContent().replace( /\n/g, '<br>' ); }
    // 矩形情報を返す
    getRect         (){ return this.rect; }
    
    
    
    // 内容を設定
    setContent( categoryNum, content ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.categoryNum    = categoryNum;
        this.content        = content;
        this.editedFlag     = true;
    }
    
    // 矩形情報設定
    setPoint(     x,      y ){ this.getRect().setPoint   (     x,      y );   }
    setSize ( width, height ){ this.getRect().setSize    ( width, height );  }
    setRect( x, y, width, height ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.setPoint   (     x,      y );
        this.setSize    ( width, height );
    }
    
    
    
    // すでに1回でも内容を編集していればtrueを返す
    isEdited(){ return this.editedFlag; }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // サニタイズ
    sanitize( str ){
        // XSSに使用されるとなりやすい記号の変換リスト
        // (){}は
        var escapeMap = {
            '&' : '&amp;'
        ,   '\'': '&#x27;'
        ,   '`' : '&#x60;'
        ,   '"' : '&quot;'
        ,   '<' : '&lt;'
        ,   '>' : '&gt;'
        };
        for( var key in escapeMap ){
            str = str.replace( new RegExp( key , 'g' ), escapeMap[key] );
        }
        return str;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 内容を連想配列で返す
    getStatusToHash(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        return {
            'cardNum'       : this.getCardNum       ()
        ,   'categoryNum'   : this.getCategoryNum   ()
        ,   'content'       : this.getRawContent    ()
        ,   'editedFlag'    : this.isEdited         ()
        ,   'rect'          : this.getRectToHash    ()
        };
    }
    
    // 座標を連想配列で返す
    getRectToHash(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        return {
            'x'         : this.rect.getX        ()
        ,   'y'         : this.rect.getY        ()
        ,   'width'     : this.rect.getWidth    ()
        ,   'height'    : this.rect.getHeight   ()
        };
    }
    
    // 連想配列から内容を作成
    setStatusToHash( hash ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        if( hash['editedFlag'] == 1 ){
            this.setContent( hash['categoryNum'], hash['content'] );
        }
        var rect = hash['rect'];
        this.setRect( rect['x'], rect['y'], rect['width'], rect['height'] );
    }
    
}