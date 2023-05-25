'use strict'

/*
    Layer_Window.js
    Copyright : Tsubasa Nakano
    Created : 2016/09/04
    Last Updated : 2016/09/04
    [ note ]
*/

class Layer_Window extends Layer_Base {
    
    // コンストラクタ
    constructor( editer ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super( editer, $('#window_layer') );
        this.window = new Window_Controller();
        this.init();
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // コントローラーを取得
    getWindow(){ return this.window; }
    // ウィンドウのDomを取得
    getWindowDom(){ return this.getWindow().getWindowDom(); }
    // ウィンドウ内のDomを取得
    getContentDom( domName ){ return this.getWindowDom().find( domName ); }
    
    // ウィンドウを登録
    addPage( name, dom ){ this.getWindow().addPage( name, dom ); }
    
    // ウィンドウを閉じる
    hide(){ this.getWindow().hide(); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 初期化
    init(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // ウィンドウを登録
        this.addPage( 'save'        , $(INCLUDE.WINDOW_SAVE         ) );
        this.addPage( 'saveLocal'   , $(INCLUDE.WINDOW_SAVE_PROPERTY) );
        this.addPage( 'saveServer'  , $(INCLUDE.WINDOW_SAVE_PROPERTY) );
        this.addPage( 'load'        , $(INCLUDE.WINDOW_LOAD         ) );
        this.addPage( 'loadLocal'   , $(INCLUDE.WINDOW_LOAD_LOCAL   ) );
        this.addPage( 'loadServer'  , $(INCLUDE.WINDOW_LOAD_SERVER  ) );
    }
    
    // キャンセルボタン更新
    updateCancelButton(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var button = this.getContentDom( $('.cancel') );
        // 表示を更新
        button.html( this.getEditer().getPhrase( 'cancel' ) );
        // イベント
        var thisClass = this;
        button.on( 'click', function( event ){
            thisClass.hide();
            event.stopPropagation();
        } );
    }
    
    // 保存ウィンドウを表示
    showSaveWindow(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getWindow().show( 'save' );
        var local           = this.getContentDom( '#saveLocal'          );
        var server          = this.getContentDom( '#saveServer'         );
        var overwriteServer = this.getContentDom( '#overwriteServer'    );
        // 表示を更新
        local.html          ( this.getEditer().getPhrase( 'save_local'          ) );
        server.html         ( this.getEditer().getPhrase( 'save_server'         ) );
        overwriteServer.html( this.getEditer().getPhrase( 'overwrite_server'    ) );
        
        console.log( this.getEditer().getDiagramID() );
        if( this.getEditer().getDiagramID() == null ){
            overwriteServer.attr( 'disabled', '' );
        }else{
            overwriteServer.removeAttr( 'disabled' );
        }
        // ページ内イベント
        var thisClass = this;
        local.on( 'click', function( event ){
            thisClass.showSaveToLocalWindow();
            event.stopPropagation();
        } );
        server.on( 'click', function( event ){
            thisClass.showSaveToServerWindow( false );
            event.stopPropagation();
        } );
        overwriteServer.on( 'click', function( event ){
            thisClass.showSaveToServerWindow( true );
            event.stopPropagation();
        } );
        this.updateCancelButton();      // キャンセルボタン更新
    }
    
    // ローカルへの保存
    showSaveToLocalWindow(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getWindow().show( 'saveLocal' );         // 表示切り替え
        
        var titleLabel      = this.getContentDom( 'span.saveTitle'          );
        var titleInput      = this.getContentDom( 'input.propertyInput'     );
        var noteLabel       = this.getContentDom( 'span.saveNote'           );
        var noteInput       = this.getContentDom( 'textarea.propertyInput'  );
        var confirmButton   = this.getContentDom( '.saveConfirm'            );
        
        // 関連図の名前と説明
        var diagramName = this.getEditer().getDiagramName() || this.getEditer().getDataIO().getNowTime();
        var diagramNote = this.getEditer().getDiagramNote();
        // 表示を更新
        this.getContentDom( '.title' ).html( this.getEditer().getPhrase( 'save_local' ) );
        titleLabel.html     ( this.getEditer().getPhrase( 'label_title'  ) + ':' );
        titleInput.val      ( diagramName );
        noteLabel.html      ( this.getEditer().getPhrase( 'label_note'   ) + ':' );
        noteInput.val       ( diagramNote );
        confirmButton.html  ( this.getEditer().getPhrase( 'save_confirm' ) );
        
        // 保存ボタンのイベント
        var thisClass = this;
        confirmButton.on( 'click', function( event ){
            var title = titleInput.val();
            thisClass.getEditer().setDiagramProperty( null, titleInput.val(), noteInput.val() );
            thisClass.getEditer().getDataIO().saveToLocal( title );
            thisClass.hide();                           // ウィンドウを閉じる
            event.stopPropagation();
        } );
        
        this.updateCancelButton();      // キャンセルボタン更新
    }
    
    // サーバへの保存
    // isOverwrite : 上書き保存する場合はtrue
    showSaveToServerWindow( isOverwrite ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getWindow().show( 'saveServer' );          // 表示切り替え
        
        var titleLabel      = this.getContentDom( 'span.saveTitle'          );
        var titleInput      = this.getContentDom( 'input.propertyInput'     );
        var noteLabel       = this.getContentDom( 'span.saveNote'           );
        var noteInput       = this.getContentDom( 'textarea.propertyInput'  );
        var confirmButton   = this.getContentDom( '.saveConfirm'            );
        
        // 関連図の名前と説明
        var diagramName = this.getEditer().getDiagramName() || this.getEditer().getDataIO().getNowTime();
        var diagramNote = this.getEditer().getDiagramNote();
        // 表示を更新
        this.getContentDom( '.title' ).html( this.getEditer().getPhrase( 'save_server' ) );
        titleLabel.html     ( this.getEditer().getPhrase( 'label_title'  ) + ':' );
        titleInput.val      ( diagramName );
        noteLabel.html      ( this.getEditer().getPhrase( 'label_note'   ) + ':' );
        noteInput.val       ( diagramNote );
        confirmButton.html  ( this.getEditer().getPhrase( 'save_confirm' ) );
        
        // 保存ボタンのイベント
        var thisClass = this;
        confirmButton.on( 'click', function( event ){
            var diagramID = isOverwrite ? thisClass.getEditer().getDiagramID() : null;
            thisClass.getEditer().setDiagramProperty( diagramID, titleInput.val(), noteInput.val() );
            thisClass.getEditer().getDataIO().saveToServer();
            thisClass.hide();                           // ウィンドウを閉じる
            event.stopPropagation();
        } );
        
        this.updateCancelButton();      // キャンセルボタン更新
    }
    
    // 読み込みウィンドウ表示
    showLoadWindow(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getWindow().show( 'load' );
        var local   = this.getContentDom( '#loadLocal'  );
        var server  = this.getContentDom( '#loadServer' );
        // 表示を更新
        local.html( this.getEditer ().getPhrase( 'load_local'  ) );
        server.html( this.getEditer().getPhrase( 'load_server' ) );
        // ページ内イベント
        var thisClass = this;
        local.on( 'click', function( event ){
            thisClass.showLoadFromLocal();
            event.stopPropagation();
        } );
        server.on( 'click', function( event ){
            thisClass.showLoadFromServerWindow();
            event.stopPropagation();
        } );
        this.updateCancelButton();      // キャンセルボタン更新
    }
    
    // ローカルから読み込み
    showLoadFromLocal(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getWindow().show( 'loadLocal' );         // 表示切り替え
        
        var pathLabel       = this.getContentDom( 'span.loadPath'       );
        var pathInput       = this.getContentDom( 'input.loadPath'      );
        var referenceButton = this.getContentDom( '.reference'          );
        var confirmButton   = this.getContentDom( '.loadConfirm'        );
        
        // 表示を更新
        this.getContentDom( '.title' ).html( this.getEditer().getPhrase( 'load_local' ) );
        pathLabel.html      ( this.getEditer().getPhrase( 'label_path'      ) );
        pathInput.val( '' );
        referenceButton.html( this.getEditer().getPhrase( 'reference'       ) );
        confirmButton.html  ( this.getEditer().getPhrase( 'load_confirm'    ) );
        confirmButton.attr( 'disabled', '' );
        
        // 読み込むファイルのパス
        var path = '';
        
        var thisClass = this;
        
        // 参照ボタンのイベント
         referenceButton.on( 'click', function( event ){
            var inputDom = $( '<input type="file">' );
            
            // パスが変更された場合に入力内容を更新する
            inputDom.on( 'change', function( event ){
                path = event.target.files[0];
                pathInput.val( path.name );
                confirmButton.removeAttr( 'disabled' );
            } );
            // ファイル選択ボタンのクリックイベントを発生させる
            inputDom.click();
            event.stopPropagation();
        } );
        
        // 読み込みボタンのイベント
        confirmButton.on( 'click', function( event ){
            thisClass.getEditer().getDataIO().loadFromLocal( path );
            thisClass.getEditer().resetView();
            thisClass.hide();
            event.stopPropagation();
        } );
        
        this.updateCancelButton();      // キャンセルボタン更新
    }
    
    // サーバから読み込み
    showLoadFromServerWindow(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thisClass = this;
        this.getWindow().show( 'loadServer' );         // 表示切り替え
        
        // タイトル描画
        this.getContentDom( '.title' ).html( this.getEditer().getPhrase( 'load_server' ) );
        
        // リストを描画
        var loadDiagram = new LoadDiagram( $('#loadDiagramArea') );
        
        var confirmButton   = this.getContentDom( '.loadConfirm' );
        confirmButton.html( this.getEditer().getPhrase( 'load_confirm' ) );
        confirmButton.attr( 'disabled', '' );
        confirmButton.on( 'click', function( event ){
            thisClass.getEditer().getDataIO().loadFromServer( loadDiagram.getSelectingID() );
            thisClass.getEditer().resetView();
            thisClass.hide();
            event.stopPropagation();
        } );
        
        loadDiagram.setRowClickEvent( function( response ){
                confirmButton.removeAttr( 'disabled' );
        } );
        loadDiagram.loadDiagramList( 1 );
        
        this.updateCancelButton();      // キャンセルボタン更新
    }
    
}