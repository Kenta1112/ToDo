<?php
//=====================================
//関数ファイル
//======================================

//========================================
//ログ
//=======================================
//ログの開始とログファイルの指定
ini_set('log_errors','on');
//ログファイルの指定
ini_set('error_log','php.log');

//========================================
//デバッグ
//=========================================

$debug_flg=true;
function debug($str){
  global $debug_flg;
  if($debug_flg){
    error_log('デバッグ'.$str);
  }
}

function debugLogStart(){
  debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>画面表示処理開始');
    debug('SESSION ID:'.session_id());
    debug('SESSION変数の中身'.print_r($_SESSION,true));
    debug('現在時刻のUNIXタイムスタンプ'.time());
    if(!empty($_SESSION['login_limit'])&&!empty($_SESSION['login_date'])){
        debug('ログイン期限日時タイムスタンプ:'.(($_SESSION['login_date']+$_SESSION['login_limit'])));
    }
}


//=======================================
//セッション
//=======================================
//セッションファイルの置き場所の変更
session_save_path("var\tmp");
//ガーベージコレクションが削除する有効期限を変更(30days)
ini_set('session.gc_maxlifetime',60*60*24*30);
//cookieの有効期限を伸ばす
ini_set('session.cookie_lifetime',60*60*24*30);
//セッション開始
session_start();
//セッションIDを都度変更する
session_regenerate_id();

// ===================================================
// エラーメッセージ
// ===================================================
define('MSG01','入力必須です');
define('MSG02','入力は２５６字までです');
define('MSG03','Emailの形式が違います');
define('MSG04','パスワードが一致しません');
define('MSG05','パスワードは８文字以上です');




// ====================================================
// バリデーションチェック
// ====================================================

//未入力チェック
function validrequire($str,$key){
  global $err_msg;
  if(empty($str)){
    $err_msg[$key]=MSG01;
  }
}

//最大文字数チェック
function validMaxLen($str,$key,$max=256){
  global $err_msg;
  if(mb_strlen($str)>$max){
    $err_msg[$key]=MSG02;
  }
}

//Email形式チェック
function validemail($str,$key){
  global $err_msg;
  if(!preg_match($str,"/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/")){
    $err_msg[$key]=MSG03;
  }
}

//パスワード一致チェック
function validpassword($password,$pass_retype,$key){
  global $err_msg;
  if($password!==$pass_retype){
    $err_msg[$key]=MSG04;
  }
}

//パスワード最小文字数チェック
function validpassword($str,$key,$min=8){
  global $err_msg;
  if(mb_strlen($str)<$min){
    $err_msg[$key]=MSG05;
  }
}







?>