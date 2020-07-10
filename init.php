<?php
  

  include 'connect.php';
  //routes
  $lang ="includes/languages/";
  $tpl="includes/templets/";
  $func="includes/functions/";
  $css="layout/css/";
  $js="layout/js/";
  

  //include the important files
  include $func."function.php";
  include_once $lang."english.php";
  include $tpl."header.php";

  //include navar in all pages except the one with navbar variable
   
  if(!isset($nonavbar)){
    include "includes/templets/navbar.php";
  
  }
 


  