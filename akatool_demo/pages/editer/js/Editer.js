'use strict'

/*
    Editer.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/15
    Last Updated : 2016/10/12
    [ note ]
*/

class Editer {
    
    // コンストラクタ
    constructor(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // バージョン
        this.version = '0.4.7';
        // 関連図のID
        this.diagramID   = null;
        // 関連図の名称
        this.diagramName = null;
        // 関連図の説明
        this.diagramNote = null;
        // 言語設定
        this.phrase = new AKaToolLib.Phrase();
        // カテゴリー設定
        this.categorySet = new Category_Controller();
        // レイヤー設定
        this.canvas     = new Layer_Canvas  ( this );
        this.menu       = new Layer_Menu    ( this );
        this.subview    = new Layer_Subview ( this );
        this.helpWindow = new Layer_Help    ( this );
        this.window     = new Layer_Window  ( this );
        // 保存/読み込み制御クラス
        this.dataIO     = new DataIO        ( this );
        // フラグ
        this.ProblemViewFlag    = false;    // 看護問題の可視化が有効ならtrue
        this.HelpModeFlag       = false;    // ヘルプが有効ならtrue
        // 初期化
        this.init();
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // バージョンを返す
    getVersion      (){ return this.version;        }
    
    // 関連図のIDを返す
    getDiagramID    (){ return this.diagramID;      }
    // 関連図の名称を返す
    getDiagramName  (){ return this.diagramName;    }
    // 関連図の説明を返す
    getDiagramNote  (){ return this.diagramNote;    }
    
    
    setDiagramID( id ){ this.diagramID = id; }
    // 関連図の情報を設定
    setDiagramProperty( id, name, note ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.setDiagramID( id );
        this.diagramName    = name;
        this.diagramNote    = note;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // カテゴリーを返す
    getCategorySet  (){ return this.categorySet;    }
    // キャンバスを取得
    getCanvas       (){ return this.canvas;         }
    // メニューの取得
    getMenu         (){ return this.menu;           }
    // サブビュー
    getSubview      (){ return this.subview;        }
    // ヘルプ
    getHelpWindow   (){ return this.helpWindow;     }
    // ウィンドウ
    getWindow       (){ return this.window;         }
    
    // 保存/読み込み制御クラスを返す
    getDataIO       (){ return this.dataIO;         }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 語句を返す
    getPhrase( phrase ){ return this.phrase.getPhrase( phrase ); }
    // 語句を設定
    setPhrase( hash ){ this.phrase.setPhraseHash( hash ); }
    // 現在の言語を指定
    setLanguage( language ){ this.phrase.setNowLanguage( language ); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 看護問題可視化状態の場合はtrueを返す
    isProblemViewMode   (){ return this.ProblemViewFlag;    }
    // ヘルプ状態の場合はtrueを返す
    isHelpMode          (){ return this.HelpModeFlag;       }
    
    // 看護問題可視化状態のフラグを設定
    setProblemViewMode  ( flag ){ this.ProblemViewFlag = flag; }
    // ヘルプ状態のフラグを設定
    setHelpMode         ( flag ){ this.HelpModeFlag    = flag; }
    
    // 看護問題可視化状態のフラグを反転
    toggleProblemViewMode   (){ this.setProblemViewMode ( !this.isProblemViewMode() ); }
    // ヘルプ状態のフラグを反転
    toggleHelpMode          (){ this.setHelpMode        ( !this.isHelpMode()        ); }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // カードを追加
    addCard( centerX, centerY ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.cancelCreateArrow();       // 矢印作成解除
        if( this.isProblemViewMode() ){ return; }
        centerX = centerX || 350;
        centerY = centerY ||  90;
        this.getCanvas().addCard( centerX, centerY );        
        console.log( 'カード追加' );
    }
    
    // 看護問題の可視化
    problemView(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.cancelCreateArrow();       // 矢印作成解除
        if( !this.getCanvas().confirmCheck() ){ return; }
        
        this.toggleProblemViewMode();                   // フラグ反転
        this.getMenu().update();                        // メニュー更新
        this.getCanvas().activateCard();                // 選択状態解除
        if( this.isProblemViewMode() ){
            this.getCanvas().highLightPath( null );     // 強調表示状態更新
        }else{
            this.getCanvas().lowLight();                // 強調表示を解除
        }
        this.getHelpWindow().updateContent();                       // ヘルプの更新
        console.log( '看護問題の可視化' );
    }
    
    // 保存
    save(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.cancelCreateArrow();       // 矢印作成解除
        if( !this.getCanvas().confirmCheck() ){ return; }
        
        if( this.isProblemViewMode() ){ return; }
        this.getWindow().showSaveWindow();
        console.log( '保存' );
    }
    
    // 読み込み
    load(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.cancelCreateArrow();       // 矢印作成解除
        if( this.isProblemViewMode() ){ return; }
        // 上書き確認
        if( this.getCanvas().isExistCard() && !confirm( this.getPhrase( 'over_write' ) ) ){ return; }
        this.getWindow().showLoadWindow();
        console.log( '読み込み' );
    }
    
    // 印刷
    print(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.cancelCreateArrow();       // 矢印作成解除
        // カードの選択を解除
        this.getCanvas().activateCard( null );
        // Canvasタグを画像形式にする
        this.getCanvas().getArrowSet().updateArrowImage();
        // 印刷
        $.jPrintArea( this.getCanvas().getLayerDom() );
        console.log( '印刷' );
    }
    
    // ヘルプ
    help(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.cancelCreateArrow();       // 矢印作成解除
        this.toggleHelpMode();                  // フラグ反転
        this.getMenu().update();                // メニュー更新
        this.getHelpWindow().updateShow();      // ヘルプ表示状態更新
        console.log( 'ヘルプ' );
    }
    
    // 全て削除
    allDelete(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.cancelCreateArrow();       // 矢印作成解除
        if( this.isProblemViewMode() ){ return; }
        this.getCanvas().deleteCard( null );            // 全て削除
        this.resetView();
        this.setDiagramProperty( null, null, null );
        console.log( 'すべて削除' );
    }
    
    // 拡大率をリセット
    resetView(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.cancelCreateArrow();       // 矢印作成解除
        this.getCanvas().setZoomValue( 1.0 );
        this.getCanvas().setViewPoint( 0, 0 );
        this.getCanvas().updateCanvasDimention();
        this.getCanvas().updateCard( null );
        this.getCanvas().activateCard( null );
        this.getMenu().updateFooter();
    }
    
    // 拡大率を変更
    changeView( moveValue ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getCanvas().zoom( moveValue );
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 初期化
    init(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // カードの色リスト
        var color = {
            'blue'      : 'rgb( 169, 226, 255 )'
        ,   'yellow'    : 'rgb( 255, 230, 103 )'
        ,   'red'       : 'rgb( 255, 196, 196 )'
        ,   'green'     : 'rgb( 175, 255, 153 )'
        ,   'purple'    : 'rgb( 222, 222, 255 )'
        ,   'pink'      : 'rgb( 255, 176, 244 )'
        ,   'white'     : 'rgb( 255, 255, 255 )'
        ,   'blue2'     : 'rgb( 180, 255, 255 )'
        };
        // カードの枠線の設定リスト
        var border = {
            'solid'     : [ '#999', 'solid' , '2px' ]
        ,   'double'    : [ '#999', 'double', '6px' ]
        ,   'dashed'    : [ '#999', 'dashed', '2px' ]
        }
        this.getCategorySet().addCategory( 1, '患者の基本情報'          , color['blue'   ], border['solid' ] );
        this.getCategorySet().addCategory( 2, '疾患や患者の反応の原因'  , color['yellow' ], border['solid' ] );
        this.getCategorySet().addCategory( 3, '疾患名'                  , color['red'    ], border['solid' ] );
        this.getCategorySet().addCategory( 4, '病状・治療・患者の反応'  , color['green'  ], border['solid' ] );
        this.getCategorySet().addCategory( 5, '日常生活への影響'        , color['purple' ], border['solid' ] );
        this.getCategorySet().addCategory( 6, '看護問題'                , color['pink'   ], border['double'] );
        this.getCategorySet().addCategory( 7, '推測できること'          , color['blue2'  ], border['dashed'] );
        this.getCategorySet().addCategory( 8, 'その他の情報'            , color['white'  ], border['solid' ] );
    }
    
    
    
    // 最初の更新
    start(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getCanvas  ().update();
        this.getMenu    ().update();
        this.getSubview ().update();
        this.getHelpWindow().firstUpdate();
        this.getDataIO().firstLoad();
    }
    
    // 矢印作成状態解除
    cancelCreateArrow(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.getCanvas().cancelArrow();
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // ローカルへ保存
//    saveToLocal(){ this.getDataIO().saveToLocal(); }
    // ローカルから読み込み
//    loadToLocal( file ){ this.getDataIO().loadToLocal( file ); }
    
    // サーバへ保存
//    saveToServer(){ this.getDataIO().saveToServer(); }
    
    // cookieへ保存
    saveToCookie(){ this.getDataIO().saveToCookie(); }
//    // cookieから読み込み
//    loadToCookie(){ this.getDataIO().loadToCookie(); }
    
    // データを読み込み
    setStatusToHash( hash ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.setProblemViewMode( false );               // 看護問題の可視化を解除
        this.getCanvas().setViewPoint( 0, 0 );          // 表示リセット
        this.getCanvas().setStatusToHash( hash );       // 連想配列から図を作成
    }
    
}