<?php
 /// get title function if page contain variable page title
 function pageTitle(){
      global $pagetitle;

      if(isset($pagetitle)){
          echo $pagetitle;
      }
      else{
          echo "default";
      }

 }


//  home redirct function [this function accept parmeters] v2.0
// $msg = echo the message [error sucess warning]
// $seconds =seconds before redirecting

function redircthome($msg, $url=null, $seconds=3){

    if($url === null){

        $url='index.php';

        $link='home page';

    }

    else{
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
                 
            $url=$_SERVER['HTTP_REFERER'];
            $link='previous page';
        }
        else{
            $url='index.php';
            $link='home page';
        }
    }
    echo $msg;
    echo "<div class='alert alert-info'>you will redirected to $link after $seconds seconds.</div>";
    header("refresh:$seconds;url=$url");
    exit();
}

// check item function
function checkitem($select,$from,$value){

   global $con;
     
    $statement =$con->prepare("SELECT $select FROM $from WHERE $select=?");

    $statement->execute(array($value));

    $count=$statement->rowCount();

    return $count;

}

// count number of items function v1
// function to count number of items rows
// $item=the item to count
// $table= the table to choose from

function countitems($item,$table){

    global $con;
    $stmt2= $con->prepare("SELECT COUNT($item) FROM $table");

    $stmt2->execute();
    return $stmt2->fetchColumn();  

}

// function to get latest items from database
function getlatest($select , $table , $order ,$limit=5){
  
    global $con;
    $getstmt=$con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT 0,$limit");
    $getstmt->execute();
    $rows=$getstmt->fetchAll();
    return $rows;
}