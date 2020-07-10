<?php
// category page
session_start();
$pagetitle="categories";

 if(isset($_SESSION['Username'])){
     include 'init.php';

     $do =isset($_GET['do'])? $_GET['do'] : 'manage';

     if($do=='manage'){

        // start manage page 
    
             $stmt=$con->prepare("SELECT 
                                       items.*,categories.name 
                                  AS 
                                     category_name,users.username 
                                 FROM
                                     items 
                                INNER JOIN 
                                      categories
                                 ON 
                                     categories.id=items.id
                                INNER JOIN 
                                      users 
                                ON 
                                     users.userid=items.userid");
               
               $stmt->execute();

               $items=$stmt->fetchAll();

             ?>
      
           <h1 class="text-center"> Manage Items</h1>
               <div class="container">
                  <div class="table-responsive">
                  <table id="main-table" class="table table-bordered">
                    <tr>
                      <td>#id</td>
                      <td>name</td>
                      <td>description</td>
                      <td>price</td>
                      <td>adding date</td>
                      <td>category name </td>
                      <td>user name </td>
                      <td>control</td>

                    </tr>
                     
                    <?php 
                         foreach($items as $item){
                             echo"<tr>";
                                echo"<td>". $item['itemid']."</td>";
                                echo"<td>". $item['name']."</td>";
                                echo"<td>". $item['description']."</td>";
                                echo"<td>". $item['price']."</td>";
                                echo "<td>".$item['adddate']."</td>";
                                echo "<td>".$item['category_name']."</td>";
                                echo "<td>".$item['username']."</td>";
                                echo"<td>
                                     <a href='items.php?do=edit&itemid=" .$item["itemid"]." '  class='btn btn-success' ><i class='fa fa-edit'></i>Edit</a>
                                     <a href='items.php?do=delete&itemid=" .$item["itemid"]."' class='btn btn-danger' id='confirm' ><i class='fa fa-delete'></i>Delete</a>";
                                     if($item['approve'] == 0){
                                        echo" <a href='items.php?do=approve&itemid=" .$item["itemid"]."' class='btn btn-info' id='confirm' >approve</a>";
                                        }
                                    
                                   echo  "</td>";
                                    
                             echo "</tr>";
                         }
                    ?>
                     
                  </table>
                  </div>
                  <a href="items.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i>add new member</a>
               </div> 

       
          
  <?php  }
     elseif($do=='add'){?>

        <!-- add categories page -->
        <!-- echo'welcome to add categories page'; -->
           
        <h1 class="text-center">Add Items</h1>
        <form class="login" action="?do=insert" method="POST" style="margin-top:50px">
          

           <input type="text" name="name" placeholder="name of item"  class="form-control"  />

           <input type="text" name="descripe" placeholder="description of item"   class="form-control"  >
                            
           <input type="text" name="country" placeholder="country"   class="form-control" >
         
           <input type="text" name="price" placeholder="price"   class="form-control" >

           <select class="form-control" style="margin-bottom:10px" name="member">
                <option value="0"  > member</option>
                 <?php
                 $stmt=$con->prepare('SELECT * FROM users');
                 $stmt->execute();
                 $users=$stmt->fetchAll();
                 foreach($users as $user){
                     echo "<option value='".$user['userid']."'>".$user['username']."</option>";
                 }
                 ?>
            </select>
            
            <select class="form-control" style="margin-bottom:10px" name="category">
                <option value="0"  > category</option>
                 <?php
                 $stmt2=$con->prepare('SELECT * FROM categories');
                 $stmt2->execute();
                 $cats=$stmt2->fetchAll();
                 foreach($cats as $cat){
                     echo "<option value='".$cat['id']."'>".$cat['name']."</option>";
                 }
                 ?>
            </select>

          
            <select class="form-control" style="margin-bottom:10px" name="status">
                <option value="0"  > status</option>
                <option value="1">new</option>
                <option value="2">like new</option>
                <option value="3">old</option>
                <option value="4">very old</option>
            </select>
           

           <input type="submit" value="add item" class=" btn btn-primary"  style="width:100%" />

          </form>

 <?php    }
      elseif($do=='insert'){

         //    insert page 
         echo "<h1 class='text-center'> insert  Member</h1> " ;
         echo "<div class='container'>";
 
         if($_SERVER['REQUEST_METHOD'] =='POST'){
 
             // user info
              $name=$_POST['name'];
              $des=$_POST['descripe'];
              $price=$_POST['price'];
              $country = $_POST['country'];
              $status = $_POST['status'];
              $member = $_POST['member'];
              $cat = $_POST['category'];
               
             // validate form
             $formerrors=array();
              
             if(empty($name)){
                 $formerrors[]="name cannot be <strong>empty</strong> ";
             }
             
             if(empty($des)){
                 $formerrors[]="descripe cannot be <strong>empty</strong>";
             }
             
             if(empty($price)){
                 $formerrors[]="price cannot be <strong>empty</strong>";
             }
 
              if(empty($country)){
                  $formerrors[]=" country cannot be <strong>empty</strong>";
              }
 
              if( $status == 0){
                 $formerrors[]=" status you must choose value";
             }
             if( $member == 0){
                $formerrors[]=" member you must choose value";
            }
            if( $cat == 0){
                $formerrors[]=" category you must choose value";
            }
 
 
              foreach($formerrors as $error){
                  echo "<div class='alert alert-danger'>". $error."</div>";
              }
              
              // if there is no errors in  formerrors array
              if(empty($formerrors)){
                 // insert data in THE database 
                 $stmt=$con->prepare("INSERT INTO items ( name, description ,price , countrymade,status ,id,userid,adddate) VALUES (:zname, :zdescripe ,:zprice ,:zcountry,:zstatus,:zid,:zuserid,now() )");
                 $stmt->execute(array('zname'=>$name,'zdescripe'=>$des,'zprice'=>$price,'zcountry'=>$country,'zstatus'=>$status,'zid'=>$cat,'zuserid'=>$member));
 
                 // echo sucess message
                $themsg= "<div class='alert alert-success'>".$stmt->rowCount(). "record inserted" ."</div>";
                redircthome($themsg,'back');
                 }
 
              
 
             
 
                 }
 
           else{

              $themsg="<div class='alert alert-danger'>you cannot browse this page directly</div>";
                redircthome($themsg);
 
           } 
           echo "</div>";    

    }

    elseif($do=='edit' ){
          //  check if get request itemid is numeric and get the integer vale of it 
          $itemid= isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) :0;
        
          // select all data depend on thid id
          $stmt=$con->prepare('SELECT * FROM items WHERE  itemid=? ');
  
          // execute query
          $stmt->execute(array($itemid));
  
          // fetch data
          $item=$stmt->fetch();
  
          // row count
          $count=$stmt->rowCount();
  
          if($count>0){
          
          ?> 
          <h1 class="text-center">Edit </h1>
          <form class="login" action="?do=update" method="POST" style="margin-top:50px">
          

          <input type="text" name="name" value="<?php echo $item['name'] ?>" class="form-control"  />

          <input type="hidden" name="itemid" value="<?php echo $itemid ?>" class="form-control"  />

          <input type="text" name="descripe" value="<?php echo $item['description'] ?>"   class="form-control"  >
                           
          <input type="text" name="country" value="<?php echo $item['countrymade'] ?>"  class="form-control" >
        
          <input type="text" name="price" value="<?php echo $item['price'] ?>"   class="form-control" >

          <select class="form-control" style="margin-bottom:10px" name="member">
               <option value="0"  > member</option>
                <?php
                $stmt=$con->prepare('SELECT * FROM users');
                $stmt->execute();
                $users=$stmt->fetchAll();
                foreach($users as $user){
                    echo "<option value='".$user['userid']."'";
                    if($item['userid'] == $user['userid']){echo 'selected';}

                    echo ">".$user['username']."</option>";
                }
                ?>
           </select>
           
           <select class="form-control" style="margin-bottom:10px" name="category">
               <option value="0"> category</option>
                <?php
                $stmt2=$con->prepare('SELECT * FROM categories');
                $stmt2->execute();
                $cats=$stmt2->fetchAll();
                foreach($cats as $cat){
                    echo "<option value='".$cat['id']."'";
                    if($item['id'] == $cat['id']) echo 'selected';
                   echo ">" .$cat['name']."</option>";
                }
                ?>
           </select>

           <select class="form-control" style="margin-bottom:10px" name="status">
               <option value="0"   > status</option>
               <option value="1" <?php if($item['status']==1) echo 'selected' ;?>>new</option>
               <option value="2"  <?php if($item['status']==2) echo 'selected' ;?> >like new</option>
               <option value="3"  <?php if($item['status']==3) echo 'selected' ;?> >old</option>
               <option value="4" <?php if($item['status']==4) echo 'selected' ;?>>very old</option>
           </select>
          

          <input type="submit" value="edit item" class=" btn btn-primary"  style="width:100%" />

         </form>

  
       <?php 
          }
          else{
          echo "<div class='container'>";
             $themsg="<div class='alert alert-danger'>there no id</div>";
             redircthome($themsg);
             echo "</div>";
          }
    }
    elseif($do=='update'){  
        // update page
       
        echo "<h1 class='text-center'> Update  Item</h1> " ;
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD'] =='POST'){

            // get variables the form
            $itemid=$_POST['itemid'];
            $name=$_POST['name'];
             $des=$_POST['descripe'];
             $price=$_POST['price'];
             $country=$_POST['country'];
             $status=$_POST['status'];
             $cat_id=$_POST['category'];
             $member_id=$_POST['member'];
         

            // validate form
            $formerrors=array();
          
            if(empty($name)){
                $formerrors[]="name cannot be <strong>empty</strong> ";
            }
            
            if(empty($des)){
                $formerrors[]="descripe cannot be <strong>empty</strong>";
            }
            
            if(empty($price)){
                $formerrors[]="price cannot be <strong>empty</strong>";
            }

             if(empty($country)){
                 $formerrors[]=" country cannot be <strong>empty</strong>";
             }

             if( $status == 0){
                $formerrors[]=" status you must choose value";
            }
            if( $member_id == 0){
               $formerrors[]=" member you must choose value";
           }
           if( $cat_id == 0){
               $formerrors[]=" category you must choose value";
           }

              
             
             // if there is no errors in  formerrors array
             if(empty($formerrors)){
                
                // UPDATE THE database with this info
                $stmt=$con->prepare("UPDATE items set name=?, description=? , price=? , countrymade=? ,status=?, id=?,userid=? WHERE itemid=? ");
                $stmt->execute(array($name , $des, $price, $country, $status, $cat_id, $member_id, $itemid));

                // echo sucess message
               $themsg= "<div class='alert alert-success'>".$stmt->rowCount(). "record updated" ."</div>";
               redircthome($themsg ,'back');

             }

            

                }

          else{
              $themsg ="<div class='alert alert-danger'>you cannot browse this page directly</div>";

              redircthome($themsg );
          } 
          echo "</div>";     
    }
    elseif($do=='delete'){
           
      // delete page
      echo"<h1 class='text-center'> Delete Item</h1>";
      echo '<div class="container">';
      
      
      //  check if get request userid is numeric and get the integer vale of it 
     $itemid= isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) :0;
     
     // select all data depend on thid id
     $stmt=$con->prepare('SELECT * FROM items WHERE  itemid=?');

     // execute query
     $stmt->execute(array($itemid));

     // row count
     $count=$stmt->rowCount();

     if($count>0){
          
         $stmt=$con->prepare('DELETE FROM items WHERE itemid=:zuser');

         $stmt->bindParam(':zuser',$itemid);

         $stmt->execute();
            
         $themsg = "<div class='alert alert-success'>".$stmt->rowCount(). "record deleted" ."</div>";
         redircthome($themsg);

     }
      else{
          
          $themsg ="<div class='alert alert-danger'>this id is not exit</div>";

          redircthome($themsg);

          }

          echo "</div>";
    }
    elseif($do=='approve'){
          // approve page
          echo"<h1 class='text-center'> approve Item</h1>";
          echo '<div class="container">';
          
          
          //  check if get request userid is numeric and get the integer vale of it 
         $itemid= isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) :0;
         
         // select all data depend on thid id
         $stmt=$con->prepare('SELECT * FROM items WHERE  itemid=? ');
 
         // execute query
         $stmt->execute(array($itemid));
 
         // row count
         $count=$stmt->rowCount();
 
         if($count>0){
              
             $stmt=$con->prepare('UPDATE items set approve=1 WHERE itemid=?');
 
             $stmt->execute(array($itemid));
                
             $themsg = "<div class='alert alert-success'>".$stmt->rowCount(). "record approve" ."</div>";
             redircthome($themsg ,'back');
 
         }
          else{
              
              $themsg ="<div class='alert alert-danger'>this id is not exit</div>";
 
              redircthome($themsg);
 
              }
 
              echo "</div>";
    }

    include $tpl."footer.php";
 }
else{
    header('Location: index.php');
    exit();
}
// ob_end_flush();
?>