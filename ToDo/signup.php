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
  validpassword($password,$pass_retype,'password');
  //パスワード最小文字数チェック
  validpassword($password,'password');
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