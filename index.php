<?php 
session_start();
$pagetitle="login";
$nonavbar ='';
if(isset($_SESSION['Username'])){
  $u='dashboard.php';
  header('Location: ' .$u);
  // include 'dashboard.php';
  exit();
 }
include 'init.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
$username= $_POST['user'];
$password= $_POST['pass'];
// $hashedpass=sha1($password);
$hashedpass=$password;
$stmt=$con->prepare('SELECT username , password,userid FROM users WHERE username=? AND password = ? AND groupid=1 LIMIT 1');
$stmt->execute(array($username,$hashedpass));
$row=$stmt->fetch();
$count=$stmt->rowCount();
if($count>0){
$_SESSION['Username']=$username;
$_SESSION['id']=$row['userid'];
$u='dashboard.php';
header('Location: ' .$u);
  // include 'dashboard.php';
exit();
   }
  }
  
?>
 <!-- <?php
     echo lang('MESSAGE'). ' ' .lang('ADMIN');
     ?> -->
     <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" >
       <h4 class="text-center">Admin Login </h4>
       <input class="form-control" type="text" name="user" placeholder="user name" autocomplete="off">
       <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password">
       <input class="btn btn-primary btn-block" type="submit" value="login">
     </form>
  <?php include "includes/templets/footer.php"; ?>