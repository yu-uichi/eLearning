'use strict'

/*
    Phrase.js
    Copyright : Tsubasa Nakano
    Created : 2016/08/15
    Last Updated : 2016/08/15
    [ note ]
        { 言語キー : { 語句キー : 語句 } }
*/

AKaToolLib.Phrase = class {
    
    // コンストラクタ
    constructor(){
        this.nowLanguageKey = null;
        this.phrase = {};
    }
    
    
    
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
/* - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - --|-- - */
    
    // 現在の言語を設定
    setNowLanguage( languageKey ){ this.nowLanguageKey = languageKey; }
    // 語句を返す
    getPhrase( phraseKey ){ return this.phrase[ this.nowLanguageKey ][ phraseKey ]; }
    // 語句を設定
    setPhraseHash( hash ){ this.phrase = hash; }
    
}