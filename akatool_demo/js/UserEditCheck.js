'use strict'

/*
    UserEditCheck.js
    Copyright : Tsubasa Nakano
    Created : 2016/10/27
    Last Updated : 2016/10/27
    [ note ]
*/

class UserEditCheck extends UserCheck {
    
    // コンストラクタ
    constructor( formDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super( formDom );
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // ユーザ名変更時のチェック
    checkName( submitDom, errorDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thisClass = this;
        submitDom.on( 'click', function(){
            thisClass.nameErrorCheck( errorDom );
        } );
    }
    
    // パスワード変更時のチェック
    checkPassword( submitDom, errorDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thisClass = this;
        submitDom.on( 'click', function(){
            thisClass.passwordErrorCheck( errorDom );
        } );
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    //名前のチェック
    nameErrorCheck( errorDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.setErrorFlag( false );
        errorDom.empty();
        
        // 入力欄の空白チェック
        this.empty( this.getNameDom() );
        // 空欄チェク
        if( this.isError() ){
            errorDom.append( '未設定の項目があります<br>' );
            return;
        }
        
        this.getFormDom().submit();
    }
    
    // パスワードのチェック
    passwordErrorCheck( errorDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.setErrorFlag( false );
        errorDom.empty();
        
        // 入力欄の空白チェック
        this.empty( this.getPassword1Dom () );
        this.empty( this.getPassword2Dom () );
        // 空白のエラーメッセージ
        if( this.isError() ){
            errorDom.append( '未入力の項目があります<br>' );
            return;
        }
        
        // パスワードが入力されている場合
        if( '' !== this.getPassword1Dom().val() || '' !== this.getPassword2Dom().val() ){
            this.minLength( this.getPassword1Dom (), this.getMinPassword(), errorDom, 'パスワード'  );
            if( this.isError() ){ return; }
            // パスワードの一致チェック
            if( !this.checkMatchPassword( errorDom ) ){ return; }
        }
        this.getFormDom().submit();
    }
    
}