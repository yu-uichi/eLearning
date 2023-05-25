'use strict'

/*
 ArrowPainter_Controller.js
 Copyright : Tsubasa Nakano
 Created : 2016/09/03
 Last Updated : 2016/09/03
 [ note ]
 */

class ArrowPainter_Controller {
    
    // コンストラクタ
    constructor( canvas ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.canvas             = canvas;       // キャンバスクラス
        this.arrowSet           = [];           // 矢印全体を保持する配列
        
        this.nowArrowNum        = 0;            // 矢印登録番号
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // キャンバスクラスを返す
    getCanvas   (){ return this.canvas;     }
    // 矢印配列を返す
    getArrowSet (){ return this.arrowSet;   }
    // 矢印の登録数を返す
    getLength(){ return this.getArrowSet().length; }
    
    // 指定番号の矢印を返す
    getArrow( arrowNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var arrow of this.getArrowSet() ){
            if( arrowNum == arrow.getArrowNum() ){ return arrow; }
        }
    }
    
    // 指定のカード番号の矢印を返す
    getArrowToCard( baseCardnum, targetCardNum ){
        for( var arrow of this.getArrowSet() ){
            if( arrow.isSame( baseCardnum, targetCardNum ) ){ return arrow.getArrowNum(); }
        }
    }
    
    // 現在の登録番号を返す
    getNowArrowNum()        { return this.nowArrowNum;  }
    // 現在の登録番号を設定
    setNowArrowNum( value ) { this.nowArrowNum = value; }
    // 登録番号を加算
    addNowArrowNum()        { ++this.nowArrowNum;       }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 指定されたカード番号に対して矢印を向けているカード番号を配列で返す
    getCardNumSetToTarget( targetCardNum ){
        var cardNumSet = [];
        for( var arrow of this.getArrowSet() ){
            var cardNum = arrow.getBaseCardNum();
            if( !arrow.isTargetSame( targetCardNum ) ){ continue; }
            cardNumSet.push( arrow.getBaseCardNum() );
        }
        return cardNumSet;
    }
    
    // 強調表示するカードを指定
    // 指定が無ければ解除
    highLightArrow( cardNumSet ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var arrow of this.getArrowSet() ){
            var flag = cardNumSet != null && cardNumSet.indexOf( arrow.getTargetCardNum() ) >= 0;
            arrow.highLight( flag );
        }
    }
    
    // 全ての矢印を強調表示
    highLightAllArrow(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var arrow of this.getArrowSet() ){ arrow.highLight( true ); }
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 矢印追加
    addArrow( baseCardNum, targetCardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.addNowArrowNum();                      // 登録番号加算
        var num = this.getNowArrowNum();
        var arrow = new ArrowPainter( this.getCanvas(), num );
        arrow.setBaseCardNum    ( baseCardNum   );
        arrow.setTargetCardNum  ( targetCardNum );
        arrow.update();
        this.getArrowSet().push( arrow );           // 配列に追加
    }
    
    // 更新
    // cardNumで指定したカード番号に関する矢印を更新
    // nullが指定された場合は全て更新
    updateArrow( cardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var arrow of this.getArrowSet() ){
            if( cardNum != null && !arrow.isRelation( cardNum ) ){ continue; }
            arrow.update();
        }
    }
    
    // 画像として出力
    updateArrowImage(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var arrow of this.getArrowSet() ){ arrow.showImage(); }
    }
    
    // 指定矢印を削除
    // nullを指定すると全て削除
    deleteArrow( arrowNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var i = 0; i < this.getLength(); ++i ){
            var arrow = this.getArrowSet()[i];
            if( arrowNum != null && arrowNum != arrow.getArrowNum() ){ continue; }
            arrow.remove();
            this.getArrowSet().splice( i, 1 );
            --i;
        }
    }
    
    // 指定されたカード番号に関する矢印を削除
    // cardNumにnullを指定すると全て削除
    deleteArrowToCard( cardNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        for( var i = 0; i < this.getLength(); ++i ){
            var arrow = this.getArrowSet()[i];
            if( cardNum != null && !arrow.isRelation( cardNum ) ){ continue; }
            arrow.remove();
            this.getArrowSet().splice( i, 1 );
            --i;
        }
    }
    
    // 矢印を反転
    reverseArrow( arrowNum ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getArrow( arrowNum ).reverse();
        this.getArrow( arrowNum ).update();
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 矢印情報を連想配列で返す
    getStatusToHash(){
        var hash = [];
        for( var arrow of this.getArrowSet() ){
            hash.push( arrow.getStatusToHash() );
        }
        return hash;
    }
    
    // 連想配列からカードを作成
    setStatusToHash( hashSet ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.setNowArrowNum( 0 );
        // ハッシュデータの分ループ
        for( var hash of hashSet ){
            var arrowNum = hash['arrowNum'];
            var arrow = new ArrowPainter( this.getCanvas(), arrowNum );
            arrow.setStatusToHash( hash );
            arrow.update();                             // 内容を更新
            this.getArrowSet().push( arrow );           // 配列に追加
            this.setNowArrowNum( Math.max( this.getNowArrowNum(), arrowNum ) );
        }
    }
    
}