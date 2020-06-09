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
    error_log('デバッグ::'.$str);
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
session_save_path("var/tmp");
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
define('MSG06','登録済みのEmailのアドレスです');
define('MSG07','データベースの接続に失敗しました');
define('MSG08','ユーザ情報の登録に失敗しました');



//======================================================
//データベース関係
//======================================================

//データベース接続
function dbConnect(){
  $dsn='mysql:dbname=todo;host=localhost;charset=utf8';
  $user='root';
  $password='root';
  $options=array(

    // SQL実行失敗時にはエラーコードのみ設定
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    // デフォルトフェッチモードを連想配列形式に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
    // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        
  );
    $dbh= new PDO($dsn,$user,$password,$options);
    return $dbh;
}

//クエリ実行
function queryPost($dbh,$sql,$data){
 
  //クエリ作成
  $stmt=$dbh->prepare($sql);
  //クエリ実行
  $stmt->execute($data);
  
  return $stmt;
}




// ====================================================
// バリデーションチェック関係
// ====================================================

//エラーメッセージの表示
function err_msg($key){
  global $err_msg;
  if(!empty($err_msg[$key])){
    echo $err_msg[$key];
  }
}

//未入力チェック
function validrequired($str,$key){
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
  if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$str)){
    $err_msg[$key]=MSG03;
  }
}

//パスワード一致チェック
function validpasswordMatch($password,$pass_retype,$key){
  global $err_msg;
  if($password!==$pass_retype){
    $err_msg[$key]=MSG04;
  }
}

//パスワード最小文字数チェック
function validpasswordMin($str,$key,$min=8){
  global $err_msg;
  if(mb_strlen($str)<$min){
    $err_msg[$key]=MSG05;
  }
}

//Emailアドレス重複チェック
function validEmailMatch($email){
  global $err_msg;
  
    //例外処理
    try{
      //DB接続
      $dbh=dbConnect();
      //SQL作成
      $sql='SELECT count(*) FROM users WHERE email=:email AND delete_flg=0';
      //プレースホルダー
      $data=array(':email'=>$email);
      //SQL実行
      $stmt=queryPost($dbh,$sql,$data);
      //結果
      $result=$stmt->fetch(PDO::FETCH_ASSOC);

      if(!empty(array_shift($result))){
        debug('Email重複'.print_r($result,true));
        $err_msg['email']=MSG06;
      }else{
        debug('Email重複なし');
      }
    }catch(Exception $e){
        debug('DB接続エラー');
        $err_msg['common']=MSG07;
    }

}



















?>