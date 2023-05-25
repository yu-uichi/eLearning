'use strict'

/*
    UserCheck.js
    Copyright : Tsubasa Nakano
    Created : 2016/10/26
    Last Updated : 2016/10/26
    [ note ]
*/

class UserCheck extends AKaToolLib.AKaToolDataIO {
    
    // コンストラクタ
    constructor( formDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        super();
        this.formDom = formDom;
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    getFormDom              (){ return this.formDom;               }
    
    getIdDom                (){ return $('#addUserID'           ); }
    getNameDom              (){ return $('#addUserName'         ); }
    getPassword1Dom         (){ return $('#addUserPassword'     ); }
    getPassword2Dom         (){ return $('#addUserPasswordCheck'); }
    
    getOrganizationOptionDom(){ return $('#addUserOrganizationOption:checked'   ); }
    getNewOrganizationDom   (){ return $('#addUserOrganizationInput'            ); }
    getSelectOrganizationDom(){ return $('#addUserOrganizationSelect'           ); }
    
    getMinID                (){ return 4; }
    getMinPassword          (){ return 6; }
    
    // パスワードが一致する場合はtrueを返す
    isMutchPassword         (){ return this.getPassword1Dom().val() === this.getPassword2Dom().val(); }
    
    // エラーの場合はtrue
    isError                 (){ return this.errorFlag; }
    setErrorFlag            ( flag ){ this.errorFlag = flag; }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 指定されたフォームをにクリックによって解除される赤枠をつける
    border( dom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        dom.css( 'box-shadow' , '0px 0px 2px 2px rgba(255,0,0,0.4) inset' );
        dom.on ( 'click keyup', function(){
            dom.css( 'box-shadow', 'none' );
        } );
        this.setErrorFlag( true );
    }
    
    // 指定されたフォームが空欄の場合はエラーをセットする
    empty( dom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        if( '' !== dom.val() ){ return; }
        this.border( dom );
    }
    
    // 指定されたフォームが指定文字数未満の場合はエラーをセットする
    minLength( dom, length, errorDom, contentName ){
 /* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        if( dom.val().length >= length ){ return; }
        errorDom.append( contentName + 'は' + length + '文字以上で指定してください<br>' );
        this.border( dom );
        this.setErrorFlag( true );
    }
    
    // 全角を含む場合はtrueを返す
    isEm( dom ){ return dom.val().match(/[^A-Za-z0-9]+/); }
    
    // パスワードが一致する場合はtrueを返す
    checkMatchPassword( errorDom ){
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
        if( this.isMutchPassword() ){ return true; }
        this.getPassword1Dom().val('');
        this.getPassword2Dom().val('');
        this.border( this.getPassword1Dom() );
        this.border( this.getPassword2Dom() );
        errorDom.append( 'パスワードが一致しません<br>' );
        return false;
    }
    
}