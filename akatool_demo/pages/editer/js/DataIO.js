'use strict'

/*
    AKaToolDataIO.js
    Copyright : Tsubasa Nakano
    Created : 2016/09/04
    Last Updated : 2016/10/23
    [ note ]
*/

class DataIO extends AKaToolLib.AKaToolDataIO {
    
    
    
    // コンストラクタ
    constructor( editer ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super();
        this.editer = editer;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // エディタを返す
    getEditer(){ return this.editer; }
    
    // 関連図のデータを返す
    getSaveData(){ return this.toJson( this.getEditer().getCanvas().getStatusToHash() ); }
    
    // 読み込み
    load( json ){ this.getEditer().setStatusToHash( this.toHash( json ) ); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    firstLoad(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var diagramID = AKaToolLib.LOAD_DIAGRAM_DATA;
        // サーバーからの読み込みを行う
        if( diagramID != '' ){
            this.loadFromServer( diagramID );
            return;
        }
//        this.loadToCookie();
//        this.saveToCookie();
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // サーバーに保存
    saveToServer(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thisClass = this;
        super.saveToServer(
            function( response ){ thisClass.getEditer().setDiagramID( response ); }
        );
    }
    
    // サーバから読み込み
    loadFromServer( diagramID ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thisClass = this;
        super.loadFromServer(
            function( response ){ thisClass.load( response ); }
        ,   'ownDiagramData'
        ,   { 'diagramID'     : diagramID }
        );
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // ローカルに保存
    saveToLocal( fileName ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // ファイル名
        fileName = ( fileName || this.getNowTime() ) + '.akatool';
        // 内容
        var data = this.getSaveData();
        
        super.saveToLocal( fileName, data );
    }
    
    // ローカルから読み込み
    loadFromLocal( file ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thisClass = this;
        super.loadFromLocal( file, 'akatool', function(){
            thisClass.load( this.result );
        } );
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // cookieに保存
    saveToCookie(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super.saveToCookie( this.getSaveData(), 24 );
    }
    
    // cookieから読み込み
    loadFromCookie(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.load( super.loadToCookie() );
    }
    
}