'use strict'

/*
 Layer_Help.js
 Copyright : Tsubasa Nakano
 Created : 2016/09/05
 Last Updated : 2016/09/09
 [ note ]
 */

class Layer_Help extends Layer_Base {
    
    // コンストラクタ
    constructor( editer ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super( editer, $('#window_layer') );
        this.window         = new Help_Controller();
        this.nowContentName = null;     // 現在表示する内容の登録名
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // ウィンドウを返す
    getWindow       (){ return this.window; }
    
    // 内容の登録名を返す
    getContentName  (){ return this.nowContentName; }
    
    
    
    // 内容の登録名を設定
    setContentName( name ){ this.nowContentName = name; }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // domオブジェクトにヘルプ更新イベントを設定
    setHelp( dom, name ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thisClass = this;
        dom.on( 'mouseover', function( event ){
            thisClass.setContentName( name );
            event.stopPropagation();
        } );
        dom.on( 'mousemove', function( event ){
            thisClass.updateContent();
        } );
    }
    
    // 表示内容を設定
    updateContent(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var name = this.getContentName();
        // 矢印作成中
        if( this.getEditer().getCanvas().isArrowFolowing() ){
            name = 'create_arrow';
        }
        // 矢印作成中
        if( this.getEditer().isProblemViewMode() ){
            name = 'problem_view';
        }
        
        if( name == null ){ return; }
        
        var phrase = this.getEditer().getPhrase( 'HELP' )[name];
        this.getWindow().setWindowTitle    ( phrase[0] );
        this.getWindow().setWindowContent  ( phrase[1] );
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 表示状態を更新
    updateShow(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        if( this.getEditer().isHelpMode() ){
            this.update();
            this.getWindow().show();
        }else{
            this.getWindow().hide();
        }
    }
    
    // 最初の更新
    firstUpdate(){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var list = [
            [ $('#card_layer'               ), 'canvas'         ]
        
        ,   [ $('#menu_layer'               ), 'menu'           ]
        ,   [ $('#menu_button_add'          ), 'menu_add'       ]
        ,   [ $('#menu_button_problemView'  ), 'menu_view'      ]
        ,   [ $('#menu_button_save'         ), 'menu_save'      ]
        ,   [ $('#menu_button_load'         ), 'menu_load'      ]
        ,   [ $('#menu_button_print'        ), 'menu_print'     ]
        ,   [ $('#menu_button_help'         ), 'menu_help'      ]
        ,   [ $('#menu_button_config'       ), 'menu_config'    ]
        ,   [ $('#menu_button_delete'       ), 'menu_delete'    ]
    
        ,   [ $('#menu_zoom_reset'          ), 'menu_zoom_reset']
        ,   [ $('#menu_zoom_out'            ), 'menu_zoom_out'  ]
        ,   [ $('#menu_zoom_in'             ), 'menu_zoom_in'   ]
        ];
        for( var col of list ){ this.setHelp( col[0], col[1] ); }
    }
    
    // カードの解説を更新
    updateCard( cardClass, helpName ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var cardHelpName = cardClass.isEditing() ? 'card_editing' : 'card';
        var list = [
            [ cardClass.getCardDom(), cardHelpName          ]
        ,   [ $('.arrowButton'  )   , 'arrow_button'        ]
        ,   [ $('.deleteButton' )   , 'card_deleteButton'   ]
        ,   [ $('.confirmButton')   , 'card_confirm'        ]
        ];
        for( var col of list ){ this.setHelp( col[0], col[1] ); }
        
    }
    
}