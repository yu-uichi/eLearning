'use strict'

/*
    LoadList.js
    Copyright : Tsubasa Nakano
    Created : 2016/10/23
    Last Updated : 2016/10/23
    [ note ]
*/

class LoadList extends AKaToolLib.AKaToolDataIO {
    
    // コンストラクタ
    constructor( drawTargetDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super();
        this.drawTargetDom = drawTargetDom;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 描画対象DOMを返す
    getDrawTargetDom(){ return this.drawTargetDom; }
    
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // サーバからリスト情報を読み込んでリストを構成して描画
    loadFromServer( loadTarget ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thisClass = this;
        super.loadFromServer(
            function( response ){ thisClass.drawList( thisClass.toHash( response ) ); }
        ,   loadTarget
        );
    }
    
    // リストを描画
    // 関連図の一覧, サムネイルの表示幅，一覧中の行が選択された際に実行される関数
    drawList( list, titleList ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getDrawTargetDom().empty();
        this.createRowTable( this.getTableTitle() ).appendTo( this.getDrawTargetDom() );
        $('<hr>').appendTo( this.getDrawTargetDom() );
        for( var row of list ){ this.getRow( row ); }
    }
    
    // リストの1行を構成して返す( 抽象クラス )
    getRow( row ){}
    
    // テーブルのタイトルを返す
    getTableTitle(){ return []; }
    
    // 配列で渡された項目を1行のtable要素にして返す
    createRowTable( list ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var row = $('<tr>');
        for( var column of list ){ $('<td>').html( column ).appendTo( row ); }
        return $('<table>').append( row );
    }
    
}