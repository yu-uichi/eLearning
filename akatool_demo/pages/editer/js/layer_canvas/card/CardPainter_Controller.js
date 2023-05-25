'use strict'

/*
    CardPainter_Controller.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/15
    Last Updated : 2016/09/04
    [ note ]
*/

class CardPainter_Controller {
    
    // コンストラクタ
    constructor( canvas ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.canvas         = canvas;           // キャンバスクラス
        this.cardSet        = [];               // カード全体を保持する配列
        
        this.nowCardNum     = 0;                // カード登録番号
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // キャンバスクラスを返す
    getCanvas   (){ return this.canvas;     }
    // エディタクラスを返す
    getEditer   (){ return this.getCanvas().getEditer(); }
    // カード配列を返す
    getCardSet  (){ return this.cardSet;    }
    // カードの登録数を返す
    getLength(){ return this.getCardSet().length; }
    
    // 指定番号のカードを返す
    getCard( cardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var card of this.getCardSet() ){
            if( cardNum == card.getCardNum() ){ return card; }
        }
    }
    
    
    
    // 現在の登録番号を返す
    getNowCardNum()         { return this.nowCardNum;   }
    // 現在の登録番号を設定
    setNowCardNum( value )  { this.nowCardNum = value;  }
    // 登録番号を加算
    addNowCardNum()         { ++this.nowCardNum;        }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // カードを追加
    addCard( centerX, centerY ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.addNowCardNum();                       // 登録番号加算
        var num = this.getNowCardNum();
        var card = new CardPainter( this.getCanvas(), num );
        card.update();                              // 内容を更新
        card.setCenterPoint( centerX, centerY );    // 位置を設定
        this.getCardSet().push( card );             // 配列に追加
        this.activateCard( num );                   // 選択状態にする
    }
    
    // カードを更新
    // cardNumにnullを指定すると全て更新
    updateCard( cardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var card of this.getCardSet() ){
            if( cardNum != null && cardNum != card.getCardNum() ){ continue; }
            card.update();
        }
    }
    
    // カードを更新
    // cardNumにnullを指定すると全て更新
    updateCard( cardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var card of this.getCardSet() ){
            if( cardNum != null && cardNum != card.getCardNum() ){ continue; }
            card.update();
        }
    }
    
    // カードを削除
    // cardNumにnullを指定すると全て削除
    deleteCard( cardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var i = 0; i < this.getLength(); ++i ){
            var card = this.getCardSet()[i];
            if( cardNum != null && cardNum != card.getCardNum() ){ continue; }
            card.remove();
            this.getCardSet().splice( i, 1 );
            --i;
        }
    }
    
    // カードを選択状態にする
    activateCard( cardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var card of this.getCardSet() ){
            card.select( cardNum == card.getCardNum() );
        }
    }
    
    // 編集状態のカードが1つでもある場合はtrue
    isEditingCard(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var card of this.getCardSet() ){ if( card.isEditing() ){ return true; } }
        return false 
    }
    
    // 編集状態のカードを確定
    confirmEditingCard(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var card of this.getCardSet() ){
            if( !card.isEditing() ){ continue; }
            card.confirm();
        }
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 強調表示するカードを指定
    // 指定が無ければ解除
    highLightCard( cardNumSet ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var card of this.getCardSet() ){
            var flag = cardNumSet != null && cardNumSet.indexOf( card.getCardNum() ) >= 0;
            card.highLight( flag );
        }
    }
    
    // 全てのカードを強調表示
    highLightAllCard(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var card of this.getCardSet() ){ card.highLight( true ); }
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // カード情報を連想配列で返す
    getStatusToHash(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var hash = [];
        for( var card of this.getCardSet() ){
            hash.push( card.getStatusToHash() );
        }
        return hash;
    }
    
    // 連想配列からカードを作成
    setStatusToHash( hashSet ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.setNowCardNum( 0 );
        // ハッシュデータの分ループ
        for( var hash of hashSet ){
            var num = hash['cardNum'];
            var card = new CardPainter( this.getCanvas(), num );
            card.setStatusToHash( hash );
            card.update();                              // 内容を更新
            this.getCardSet().push( card );             // 配列に追加
            this.setNowCardNum( Math.max( this.getNowCardNum(), num ) );
        }
    }
    
}