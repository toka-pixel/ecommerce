<?php

// <!-- manage member page -->
// <!-- you can edit | add | deleste members  from here -->

session_start();
$pagetitle="members";

 if(isset($_SESSION['Username'])){
     include 'init.php';

     $do =isset($_GET['do'])? $_GET['do'] : 'manage';

      
      if($do=='manage'){ 
          // start manage page 
             
          $query='';
          if(isset($_GET['page']) && $_GET['page']=='pending'){
              $query='AND regstatus=0';
            
          }

            // select all users except admin 
               
               $stmt=$con->prepare("SELECT * FROM users WHERE groupid!=1 $query");
                 
                 $stmt->execute();

                 $rows=$stmt->fetchAll();

               ?>
        
             <h1 class="text-center"> manage member</h1>
                 <div class="container">
                    <div class="table-responsive">
                    <table id="main-table" class="table table-bordered">
                      <tr>
                        <td>#id</td>
                        <td>username</td>
                        <td>email</td>
                        <td>fullname</td>
                        <td>registerd date</td>
                        <td>control</td>

                      </tr>
                       
                      <?php 
                           foreach($rows as $row){
                               echo"<tr>";
                                  echo"<td>". $row['userid']."</td>";
                                  echo"<td>". $row['username']."</td>";
                                  echo"<td>". $row['email']."</td>";
                                  echo"<td>". $row['fullname']."</td>";
                                  echo "<td>".$row['date']."</td>";
                                  echo"<td>
                                       <a href='members.php?do=edit&userid=" .$row["userid"]." '  class='btn btn-success' ><i class='fa fa-edit'></i>Edit</a>
                                       <a href='members.php?do=delete&userid=" .$row["userid"]."' class='btn btn-danger' id='confirm' ><i class='fa fa-delete'></i>Delete</a>";
                                      
                                       if($row['regstatus'] == 0){
                                       echo" <a href='members.php?do=activate&userid=" .$row["userid"]."' class='btn btn-info' id='confirm' ><i class='fa fa-delete'></i>Activate</a>";
                                       }
                                     echo  "</td>";
                                      
                               echo "</tr>";
                           }
                      ?>
                       
                    </table>
                    </div>
                    <a href="members.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i>add new member</a>
                 </div> 

         
            
    <?php  }
      elseif($do == 'add'){ ?>

         <!-- add members page -->
        <!-- echo'welcome to add members page'; -->
           
        <h1 class="text-center">Add Member</h1>
        <form class="login" action="?do=insert" method="POST" style="margin-top:50px">
          

           <input type="text" name="username" placeholder="User Name"  class="form-control input-add" autocomplete="off" required="required" />

           <input type="password" name="password" placeholder="Password"  id="pass" class="form-control input-add" autocomplete="new-password" required="required" />
           <span id="show-pass"><i class=" far fa-eye"></i></span>
                            
           <input type="email" name="email" placeholder="Email"  class="form-control input-add" required="required"/>
                             
           <input type="text" name="fullname" placeholder="Full Name"  class="form-control input-add" required="required"/>

           <input type="submit" value="add member" class=" btn btn-primary"  style="width:100%" />

          </form>


    <?php  }
       elseif($do=='insert'){

           //    insert page 
           echo "<h1 class='text-center'> insert  Member</h1> " ;
           echo "<div class='container'>";
   
           if($_SERVER['REQUEST_METHOD'] =='POST'){
   
               // user info
                $user=$_POST['username'];
                $pass=$_POST['password'];
                $email=$_POST['email'];
                $name=$_POST['fullname'];
                 
                $hashpass=sha1($pass);
   
               // validate form
               $formerrors=array();
                
               if(strlen($user)<4){
                   $formerrors[]="username cannot be lower than <strong>4</strong> characters ";
               }
               
               if(strlen($user)>20){
                   $formerrors[]=" username cannot be greater than 20 characters";
               }
               
               if(empty($user)){
                   $formerrors[]="username cannot be empty";
               }
   
                if(empty($email)){
                    $formerrors[]=" email cannot be empty";
                }
   
                if(empty($name)){
                   $formerrors[]="fullname cannot be empty";
               }
   
   
                foreach($formerrors as $error){
                    echo "<div class='alert alert-danger'>". $error."</div>";
                }
                
                // if there is no errors in  formerrors array
                if(empty($formerrors)){
                    
                    // check if user exit in database
                    $check=checkitem("username", "users" ,$user);
                     
                    if($check ==1 ){
                        $themsg= "<div class='alert alert-danger'>sorry this user exit</div>";
                     redircthome($themsg,'back');
                    }
                    else{
                   
                   // insert data in THE database 
                   $stmt=$con->prepare("INSERT INTO users ( username, password ,email , fullname,regstatus,date) VALUES (:zuser, :zpass ,:zmail , :zfull,1,now() )");
                   $stmt->execute(array('zuser'=>$user,'zpass'=>$hashpass,'zmail'=>$email,'zfull'=>$name));
   
                   // echo sucess message
                  $themsg= "<div class='alert alert-success'>".$stmt->rowCount(). "record inserted" ."</div>";
                  redircthome($themsg,'back');
                   }
   
                }
   
               
   
                   }
   
             else{

                $themsg="<div class='alert alert-danger'>you cannot browse this page directly</div>";
                  redircthome($themsg);
   
             } 
             echo "</div>";    

       }
    
    //  edit page 
    elseif($do =='edit'){ 
        //  check if get request userid is numeric and get the integer vale of it 
        $userid= isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) :0;
        
        // select all data depend on thid id
        $stmt=$con->prepare('SELECT * FROM users WHERE  userid=? LIMIT 1');

        // execute query
        $stmt->execute(array($userid));

        // fetch data
        $row=$stmt->fetch();

        // row count
        $count=$stmt->rowCount();

        if($count>0){
        
        ?> 
        <h1 class="text-center">Edit Member</h1>
          <form class="login" action="?do=update" method="POST" style="margin-top:50px">
             <input type="hidden" name="userid"  value="<?php echo $userid ?>"  >

             <input type="text" name="username" placeholder="User Name" value="<?php echo $row['username']?>" class="form-control" autocomplete="off" required="required" />
             
             <input type="hidden"  name="oldpassword" value="<?php echo $row['password']?>" />

             <input type="password" name="newpassword" placeholder="Password" class="form-control" autocomplete="new-password" />
                              
             <input type="email" name="email" placeholder="Email" value="<?php echo $row['email']?>" class="form-control" required="required"/>
                               
             <input type="text" name="fullname" placeholder="Full Name" value="<?php echo $row['fullname']?>" class="form-control" required="required"/>

             <input type="submit" value="save" class=" btn btn-primary"  style="width:100%" />

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
       
        echo "<h1 class='text-center'> Update  Member</h1> " ;
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD'] =='POST'){

            // get variables the form
             $id=$_POST['userid'];
             $user=$_POST['username'];
             $email=$_POST['email'];
             $name=$_POST['fullname'];

            //  password
            $pass = ''; 
            if(empty($_POST['newpassword'])){
                $pass = $_POST['oldpassword'];
            }
            else{
                $pass = $_POST['newpassword'];
            }

            // validate form
            $formerrors=array();
             
            if(strlen($user)<4){
                $formerrors[]="<div class='alert alert-danger'>username cannot be lower than <strong>20</strong> characters </div>";
            }
            
            if(strlen($user)>20){
                $formerrors[]=" <div class='alert alert-danger' >username cannot be greater than  characters</div>";
            }
            
            if(empty($user)){
                $formerrors[]="<div class='alert alert-danger'>username cannot be empty</div>";
            }

             if(empty($email)){
                 $formerrors[]="<div class='alert alert-danger'> email cannot be empty</div>";
             }

             if(empty($name)){
                $formerrors[]="<div class='alert alert-danger'>fullname cannot be empty</div>";
            }


             foreach($formerrors as $error){
                 echo $error;
             }
             
             // if there is no errors in  formerrors array
             if(empty($formerrors)){
                
                // UPDATE THE database with this info
                $stmt=$con->prepare("UPDATE users set username=?, email=? , fullname=? , password=? WHERE userid=? ");
                $stmt->execute(array($user,$email,$name,$pass,$id));

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
    }elseif($do=='delete'){
           
        // delete page
         echo"<h1 class='text-center'> Delete member</h1>";
         echo '<div class="container">';
         
         
         //  check if get request userid is numeric and get the integer vale of it 
        $userid= isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) :0;
        
        // select all data depend on thid id
        $stmt=$con->prepare('SELECT * FROM users WHERE  userid=? LIMIT 1');

        // execute query
        $stmt->execute(array($userid));

        // row count
        $count=$stmt->rowCount();

        if($count>0){
             
            $stmt=$con->prepare('DELETE FROM users WHERE userid=:zuser');

            $stmt->bindParam(':zuser',$userid);

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
     elseif($do=='activate'){
              // delete page
         echo"<h1 class='text-center'> Activate member</h1>";
         echo '<div class="container">';
         
         
         //  check if get request userid is numeric and get the integer vale of it 
        $userid= isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) :0;
        
        // select all data depend on thid id
        $stmt=$con->prepare('SELECT * FROM users WHERE  userid=? LIMIT 1');

        // execute query
        $stmt->execute(array($userid));

        // row count
        $count=$stmt->rowCount();

        if($count>0){
             
            $stmt=$con->prepare('UPDATE users set regstatus=1 WHERE userid=?');

            $stmt->execute(array($userid));
               
            $themsg = "<div class='alert alert-success'>".$stmt->rowCount(). "record Activate" ."</div>";
            redircthome($themsg);

        }
         else{
             
             $themsg ="<div class='alert alert-danger'>this id is not exit</div>";

             redircthome($themsg);

             }

             echo "</div>";
       }

     include $tpl.'footer.php';
 }
 else{
     header('Location: index.php');
     exit();
 }