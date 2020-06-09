<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('ユーザ登録');
debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
debugLogStart();

$err_msg=array();


//POST送信ありの時
if(!empty($_POST)){

  $email=$_POST['email'];
  $password=$_POST['password'];
  $pass_retype=$_POST['pass_retype'];

  //未入力チェック
  validrequired($email,'email');
  validrequired($password,'password');
  validrequired($pass_retype,'pass_retype');

  
  if(empty($err_msg)){
  //最大文字数チェック
  validMaxLen($email,'email');
  validMaxLen($password,'password');
  validMaxLen($pass_retype,'pass_retype');
  
  if(empty($err_msg)){
  //Email形式チェック
  validemail($email,'email');
  //パスワード一致チェック
  validpasswordMatch($password,$pass_retype,'password');
  //パスワード最小文字数チェック
  validpasswordMin($password,'password');


  if(empty($err_msg)){
  //Emailアドレス重複チェック
  validEmailMatch($email);
  

  if(empty($err_msg)){
    //ユーザ登録
    //例外処理
    try{
      //DB接続
      $dbh=dbConnect();
      //SQL文作成
      $sql='INSERT INTO users (email,password,create_date,login_date) VALUE(:email,:password,:create_date,:login_date)';
      $data=array(':email'=>$email,':password'=>password_hash($password,PASSWORD_DEFAULT),':create_date'=>date('Y-m-d H:i:s'),':login_date'=>date('Y-m-d H:i:s'));
      $stmt=queryPost($dbh,$sql,$data);

      if($stmt){
        debug('ユーザ登録成功');
      }else{
        debug('ユーザ登録失敗');
        $err_msg['common']=MSG08;
      }
    }catch(Exception $e){
      debug('DB接続エラー');
      $err_msg['common']=MSG07;
  }
  }





















  }









  }
  


  }
}







?>


<?php
$title="ユーザ登録";
require('head.php');
?>


<body class="site-width">
  

<section class="form">
<h1>ユーザ登録</h1>
<form action="" method="POST">
<p><span>Email</span></p>
<input type="text" name="email">
<p><span>パスワード</span></p>
<input type="password" name="password">
<p><span>パスワード再入力</span></p>
<input type="password" name="pass_retype">
<div class="btn_wrapper">
<input type="submit" class="btn">
</div>


</form>
</section>
  
</body>
</html>