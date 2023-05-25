'use strict'

/*
    UserAddCheck.js
    Copyright : Tsubasa Nakano
    Created : 2016/10/27
    Last Updated : 2016/10/27
    [ note ]
*/

class UserAddCheck extends UserCheck {
    
    // コンストラクタ
    constructor( formDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super( formDom );
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // ユーザ追加時のチェック
    checkAdd( submitDom, errorDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        var thisClass = this;
        submitDom.on( 'click', function(){
            thisClass.addErrorCheck( errorDom );
        } );
    }
    
// ユーザ追加時のエラーチェック
    addErrorCheck( errorDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        this.setErrorFlag( false );
        errorDom.empty();
        
        // 空欄チェク
        if( this.emptyCheck( errorDom ) ){
            errorDom.append( '未設定の項目があります<br>' );
            return;
        }
        // 文字数チェック
        if( this.lengthCheck        ( errorDom ) ){ return; }
        // パスワードの一致チェック
        if( !this.checkMatchPassword( errorDom ) ){ return; }
        // IDの全角チェック
        if( this.idEmCheck          ( errorDom ) ){ return;}
        // IDのサーバチェック
        this.idServerCheck( errorDom );
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 空欄のチェック
    emptyCheck( errorDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 入力欄の空白チェック
        this.empty( this.getIdDom        () );
        this.empty( this.getNameDom      () );
        this.empty( this.getPassword1Dom () );
        this.empty( this.getPassword2Dom () );
        // 組織の新規追加がある場合はチェック
        if( this.getOrganizationOptionDom().val() === '1' ){
            this.empty( this.getNewOrganizationDom() );
        }else{
            if( this.getSelectOrganizationDom().val() === '-1' ){
                this.border( this.getSelectOrganizationDom() );
                return true;
            }
        }
        return  this.isError();
    }
    
    // 文字数チェック
    lengthCheck( errorDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 文字数チェック
        this.minLength( this.getIdDom        (), this.getMinID      (), errorDom, 'ID'          );
        this.minLength( this.getPassword1Dom (), this.getMinPassword(), errorDom, 'パスワード'  );
        return this.isError();
    }
    
    // IDの全角チェック
    idEmCheck( errorDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // IDの全角チェック
        if( !this.isEm( this.getIdDom() ) ){ return false; }
        this.border( this.getIdDom() );
        errorDom.append( 'IDは半角英数字で指定してください<br>' );
        return true;
    }
    
    idServerCheck( errorDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        // 同名のIDチェック
        var thisClass = this;
        var successEvent = function( response ){
            // ユーザが追加可能な場合はフォーム送信
            if( response === 'true' ){
                thisClass.getFormDom().submit();
            }else{
                thisClass.border( thisClass.getIdDom() );
                errorDom.append( '指定されたIDは既に使用されています<br>' );
            }
        };
        this.loadFromServer( successEvent, 'userIDCheck', { 'id' : this.getIdDom().val() } );
    }
    
}