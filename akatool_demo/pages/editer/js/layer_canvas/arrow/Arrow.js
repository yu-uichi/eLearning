'use strict'

/*
    Arrow.js
    Copyright : Tsubasa Nakano
    Created : 2016/09/03
    Last Updated : 2016/10/04
    [ note ]
*/

class Arrow {
    
    // コンストラクタ
    constructor( canvas, arrowNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.canvas         = canvas;
        
        this.arrowNum       = arrowNum;
        this.baseCardNum    = null;
        this.targetCardNum  = null;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // キャンバスを返す
    getCanvas       (){ return this.canvas;                         }
    // エディタクラスを返す
    getEditer       (){ return this.getCanvas().getEditer();        }
    
    
    
    // 矢印の登録番号を返す
    getArrowNum     (){ return this.arrowNum;       }
    // カード番号を返す
    getBaseCardNum  (){ return this.baseCardNum;    }
    getTargetCardNum(){ return this.targetCardNum;  }
    // カードを返す
    getCard( cardNum ){ return this.getCanvas().getCard( cardNum ); }
    getBaseCard     (){ return this.getCard( this.getBaseCardNum    () ); }
    getTargetCard   (){ return this.getCard( this.getTargetCardNum  () ); }
    // 矩形を返す
    getBaseRect     (){ return this.getBaseCard     ().getRect(); }
    getTargetRect   (){ return this.getTargetCard   ().getRect(); }
    
    
    
    // カード番号を設定
    setBaseCardNum  ( cardNum ){ this.baseCardNum   = cardNum; }
    setTargetCardNum( cardNum ){ this.targetCardNum = cardNum; }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // カードの始端と終端が同じカードを示していればtrueを返す
    isRecursion(){ return this.getBaseCardNum() == this.getTargetCardNum(); }
    
    // 指定されたカード番号がベースカード番号と同じならばtrueを返す
    // 指定されたカード番号がターゲットカード番号と同じならばtrueを返す
    isBaseSame  ( cardNum ){ return cardNum == this.getBaseCardNum  (); }
    isTargetSame( cardNum ){ return cardNum == this.getTargetCardNum(); }
    // cardNumで指定されたカードとの関係があればtrueを返す
    isRelation( cardNum ){ return this.isBaseSame( cardNum ) || this.isTargetSame( cardNum ); }
    // 指定されたカード番号がこの矢印と同じならばtrueを返す
    isSame( baseCardNum, targetCardNum ){ return this.isBaseSame( baseCardNum ) && this.isTargetSame( targetCardNum ); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 矢印を反転させる
    reverse(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var cardNum = this.getTargetCardNum()
        this.setTargetCardNum   ( this.getBaseCardNum() );
        this.setBaseCardNum     ( cardNum               );
    }
    
    // 内容を連想配列で返す
    getStatusToHash(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        return {
            'arrowNum'          : this.getArrowNum      ()
        ,   'baseCardNum'       : this.getBaseCardNum   ()
        ,   'targetCardNum'     : this.getTargetCardNum ()
        };
    }
    
    // 連想配列から内容を作成
    setStatusToHash( hash ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.setBaseCardNum  ( hash['baseCardNum'   ] );
        this.setTargetCardNum( hash['targetCardNum' ] );
    }
    
}