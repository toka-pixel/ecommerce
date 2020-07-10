<?php 
 
  
   $dsn="mysql:host=localhost;dbname=shop";
   $user ='root';
   $pass="";
   $option = array(
      1002 => 'SET NAMES utf8' );

   try{
       $con = new PDO($dsn , $user , $pass , $option);
       $con->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
        // echo 'you are connected ' ;
   }

   catch(PDOException $e){
       echo 'failed'. $e->getMessage();
   }