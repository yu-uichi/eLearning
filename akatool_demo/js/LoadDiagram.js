'use strict'

/*
    LoadDiagram.js
    Copyright : Tsubasa Nakano
    Created : 2016/10/11
    Last Updated : 2016/10/23
    [ note ]
*/

class LoadDiagram extends LoadList {
    
    // コンストラクタ
    constructor( drawTargetDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super( drawTargetDom );
        this.listType       = 0;
        this.selectingID    = null;
        this.rowClickEvent  = null;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 表示するリストの種類を返す
    getListType(){ return this.listType; }
    // 表示するリストの種類を設定
    setListType( type ){ this.listType = type; }
    
    
    
    // 選択中の関連図のIDを返す
    getSelectingID(){ return this.selectingID; }
    // 選択中の関連図のIDを設定
    setSelectingID( id ){ this.selectingID = id; }
    
    
    
    // 選択時のイベントを返す
    getRowClickEvent(){ return this.rowClickEvent; }
    // 選択時のイベントを設定
    setRowClickEvent( event ){ this.rowClickEvent = event ; }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 引数で指定された種類の関連図リストの読み込みを行う
    loadDiagramList( listType ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.setListType( listType );
        this.loadFromServer( 'ownDiagramList' );
    }
    
    // リストを描画
    drawList( list ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var title = '';
        switch( this.getListType() ){
            case 0 :
                title = '<table><tr><td></td><td>タイトル</td><td>説明</td><td>更新日</td></tr></table>';
                break;
            case 1 :
//                title = '<table></td><td>タイトル</td><td>更新日</td></tr></table>';
                break;
        }
        this.getDrawTargetDom().empty();
//        this.getDrawTargetDom().append( title ).append( '<hr>' );
        for( var row of list ){ this.getRow( row ); }
    }
    
    // 1行描画
    // 関連図のID, 関連図の名前, 関連図の説明, 更新日時, サムネイルデータ，サムネイルの表示幅，その行が選択された際に実行される関数
    getRow( hash ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var list = [];
        switch( this.getListType() ){
            case 0 :
                list = [
                    '<img src=' + hash['thumbnail'] + '>'
                ,   hash['name'             ]
                ,   hash['note'             ]
                ,   hash['updateDateTime'   ]
                ];
                break;
            case 1 :
                list = [
                ,   hash['name'             ]
                ,   hash['updateDateTime'   ]
                ];
                break;
        }
        
        var thisClass = this;
        var id = hash['id'];
        
        var table = this.createRowTable( list ).on( 'click', function(){
            thisClass.getDrawTargetDom().find( 'table' ).removeClass( 'selecting' );
            table.addClass( 'selecting' );
            thisClass.setSelectingID( id );
            thisClass.getRowClickEvent()( id );
        } ).appendTo( this.getDrawTargetDom() );
        
        $('<hr>').appendTo( this.getDrawTargetDom() );
    }
    
    // 選択状態の関連図を開く
    openDiagram(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        $('<form action="' + AKaToolLib.SITE_URL + '" method="post">')
        .append( '<input type="hidden" name="action"    value=""        >' )
        .append( '<input type="hidden" name="page"      value="editer"  >' )
        .append( '<input type="hidden" name="diagramID" value="' + this.getSelectingID() + '">' )
        .submit();
    }
    
    // 選択状態の関連図を削除
    deleteDiagram(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 削除確認
        if( !window.confirm( '本当に削除しますか？' ) ){ return; }

        $('<form action="' + AKaToolLib.SITE_URL + '" method="post">')
        .append( '<input type="hidden" name="sessionID"         value="' + AKaToolLib.SESSION_ID + '"   >' )
        .append( '<input type="hidden" name="action"            value="deleteDiagram"   >' )
        .append( '<input type="hidden" name="page"              value="diagramList"     >' )
        .append( '<input type="hidden" name="diagramID" value="' + this.getSelectingID() + '">' )
        .submit();
    }
    
}