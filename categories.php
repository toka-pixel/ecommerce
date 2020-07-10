<?php
// category page
session_start();
$pagetitle="categories";

 if(isset($_SESSION['Username'])){
     include 'init.php';

     $do =isset($_GET['do'])? $_GET['do'] : 'manage';

     if($do=='manage'){

        $sort = "ASC";

        $sort_array=array("ASC","DESC");

        if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array))
        {
            $sort=$_GET['sort'];
        }
        $stmt2=$con->prepare("SELECT * FROM categories ORDER BY ordering $sort");

        $stmt2->execute();

        $cats=$stmt2->fetchAll();

        ?>

        <h1 class="text-center">Manage Categories</h1>

        <div class="container categories">
           <div class="panel panel-default">
             <div class="panel-heading"> Manage Categories
                   <div class="option pull-right">
                            Ordering:
                            <a class="<?php if($sort== 'ASC') echo 'active';?>" href='?sort=ASC'>ASC</a> |
                            <a class="<?php if($sort== 'DESC') echo 'active' ;?>"  href='?sort=DESC'>DESC</a>
                           View:
                           <span data-view="full">Fill</span> |
                           <span data-view="classic" >Classic</span>
                    </div>  
              </div>       
                <div class="panel-body">
                   <?php
                    foreach($cats as $cat){
                        echo "<div class='cat'>";
                                echo "<div class='hidden-buttons'>";
                                    echo"<a href='?do=edit&catid=".$cat['id']."'class='btn btn-primary'><i class='fa fa-edit'></i>Edit</a>";
                                    echo"<a href='?do=delete&catid=".$cat['id']."' class='confirm btn btn-danger'><i class='fa fa-delete'></i>Delete</a>";
                                echo "</div>";   
                            echo '<h3>'.$cat['name'].'</h3>';
                              echo"<div class='full-view'";
                                        echo '<p>';
                                        if($cat['description']== '')
                                            echo "this category has no description";
                                            else 
                                        echo $cat['description'];
                                            echo '</p>';
                                            if($cat['visibility'] == 1)
                                        echo '<span class="visibility"><i class="fa fa-eye"></i> Hidden </span>';
                                        if($cat['allowcomment'] == 1)
                                        echo '<span class="commenting"> <i class="fa fa-close"></i> Comment Disable </span>';
                                        if($cat['allowads'] == 1)
                                        echo '<span class="advertises"><i class="fa fa-close"></i>  Ads Disabled </span>';
                              echo "</div>";
                       
                        echo "</div>";
                        echo "<hr>";
                         }
                     ?>
                </div>
           </div>
           <a href="categories.php?do=add" class="btn btn-primary" style="margin-top:20px"><i class='fa fa-plus'></i>Add New Categories</a>
        </div>

<?php  }

     elseif($do=='add'){?>

        <!-- add categories page -->
        <!-- echo'welcome to add categories page'; -->
           
        <h1 class="text-center">Add Categories</h1>
        <form class="login" action="?do=insert" method="POST" style="margin-top:50px">
          

           <input type="text" name="name" placeholder="name /name of category"  class="form-control" autocomplete="off" required="required" />

           <input type="text" name="descripe" placeholder="description /descripe the category"   class="form-control"  >
          
                            
           <input type="text" name="ordering" placeholder="ordering /number to arrange category"  class="form-control" >
                             
            
               <p class="category-p">visibility</p>
               <div class="category-input">
                 <input type="radio" id="vis-yes" name="visibility" value="0" checked><label for="vis-yes">yes</label>
                
                 <input type="radio" id="vis-no" name="visibility" value="1" ><label for="vis-no">no</label>
             </div>
            <div class="category-form">
               <p class="category-p">allow commenting</p>
                 <input type="radio" id="com-yes" name="commenting" value="0" checked><label for="com-yes">yes</label>
                
                 <input type="radio" id="com-no" name="commenting" value="1" ><label for="com-no">no</label>
            </div>
            <div class="category-form" >
               <p class="category-p">allow ads</p>
                 <input type="radio" id="ads-yes" name="ads" value="0" checked><label for="ads-yes">yes</label>
                
                 <input type="radio" id="ads-no" name="ads" value="1" ><label for="ads-no">no</label>
            </div>
           

           <input type="submit" value="add category" class=" btn btn-primary"  style="width:100%" />

          </form>

 <?php    }
      elseif($do=='insert'){

        //    insert page 
        echo "<h1 class='text-center'> insert Category</h1> " ;
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD'] =='POST'){

            // category info
             $name=$_POST['name'];
             $order=$_POST['ordering'];
             $desc=$_POST['descripe'];
             $visible=$_POST['visibility'];
             $comment=$_POST['commenting'];
             $ads=$_POST['ads'];
              
          
                 // check if category exit in database
                 $check=checkitem("name", "categories" ,$name);
                  
                 if($check ==1 ){
                     $themsg= "<div class='alert alert-danger'>sorry this category exit</div>";
                  redircthome($themsg,'back');
                 }
                 else{
                
                // insert data in THE database 
                $stmt=$con->prepare("INSERT INTO categories ( name, ordering ,description, visibility , allowcomment , allowads ) VALUES (:zname, :zorder ,:zdesc , :zvisible,:zcomment,:zads )");
                $stmt->execute(array("zname"=>$name,"zorder"=>$order,"zdesc"=>$desc,"zvisible"=>$visible,"zcomment"=>$comment,"zads"=>$ads));

                // echo sucess message
               $themsg= "<div class='alert alert-success'>".$stmt->rowCount(). "record inserted" ."</div>";
               redircthome($themsg,'back');
                }

                }

          else{

             $themsg="<div class='alert alert-danger'>you cannot browse this page directly</div>";
               redircthome($themsg,'back');

          } 
          echo "</div>";    

    }

    elseif($do=='edit' ){
          //  check if get request catid is numeric and get the integer vale of it 
        $catid= isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) :0;
        
        // select all data depend on thid id
        $stmt=$con->prepare('SELECT * FROM categories WHERE id=?');

        // execute query
        $stmt->execute(array($catid));

        // fetch data
        $cat=$stmt->fetch();

        // row count
        $count=$stmt->rowCount();

        if($count>0){
        ?> 

         <h1 class="text-center">edit Categories</h1>
        <form class="login" action="?do=update" method="POST" style="margin-top:50px">
          
        <input type="hidden" name="catid"  value="<?php echo $catid ?>"  >
           <input type="text" name="name" placeholder="name /name of category"  class="form-control"  required="required" value="<?php echo $cat['name']?>" />

           <input type="text" name="descripe" placeholder="description /descripe the category"   class="form-control" value="<?php echo $cat['description']?>"  >
          
                            
           <input type="text" name="ordering" placeholder="ordering /number to arrange category"  class="form-control" value="<?php echo $cat['ordering']?>" >
                             
            
               <p class="category-p">visibility</p>
               <div class="category-input">
                 <input type="radio" id="vis-yes" name="visibility" value="0" <?php if($cat['visibility']==0) echo'checked'?> ><label for="vis-yes">yes</label>
                
                 <input type="radio" id="vis-no" name="visibility" value="1" <?php if($cat['visibility']==1) echo'checked'?> ><label for="vis-no">no</label>
             </div>
            <div class="category-form">
               <p class="category-p">allow commenting</p>
                 <input type="radio" id="com-yes" name="commenting" value="0" <?php if($cat['allowcomment']==0) echo'checked'?> ><label for="com-yes">yes</label>
                
                 <input type="radio" id="com-no" name="commenting" value="1"<?php if($cat['allowcomment']==1) echo'checked'?> > <label for="com-no">no</label>
            </div>
            <div class="category-form" >
               <p class="category-p">allow ads</p>
                 <input type="radio" id="ads-yes" name="ads" value="0" <?php if($cat['allowads']==0) echo'checked'?> > <label for="ads-yes">yes</label>
                
                 <input type="radio" id="ads-no" name="ads" value="1" <?php if($cat['allowads']==1) echo'checked'?> > <label for="ads-no">no</label>
            </div>
           

           <input type="submit" value="update category" class=" btn btn-primary"  style="width:100%" />

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
       
        echo "<h1 class='text-center'> Update  category</h1> " ;
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD'] =='POST'){

            // get variables the form
             $id=$_POST['catid'];
             $name=$_POST['name'];
             $desc=$_POST['descripe'];
             $order=$_POST['ordering'];
             $visible=$_POST['visibility'];
             $comment=$_POST['commenting'];
             $ads=$_POST['ads'];
                
                // UPDATE THE database with this info
                $stmt=$con->prepare("UPDATE categories set name=?, description=? , ordering=? , visibility=? ,allowcomment=?,allowads=? WHERE id=? ");
                $stmt->execute(array($name,$desc,$order,$visible,$comment,$ads,$id));

                // echo sucess message
               $themsg= "<div class='alert alert-success'>".$stmt->rowCount(). "record updated" ."</div>";
               redircthome($themsg ,'back');


            

                }

          else{
              $themsg ="<div class='alert alert-danger'>you cannot browse this page directly</div>";

              redircthome($themsg );
          } 
          echo "</div>";     
    }
    elseif($do=='delete'){
           
       
         echo"<h1 class='text-center'> Delete category</h1>";
         echo '<div class="container">';
         
         
         //  check if get request userid is numeric and get the integer vale of it 
        $catid= isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) :0;

        $check=checkitem('id','categories',$catid);
        
        if($check>0){
             
            $stmt=$con->prepare('DELETE FROM categories WHERE id=:zid');

            $stmt->bindParam(':zid',$catid);

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
   
    include $tpl."footer.php";
 }
else{
    header('Location: index.php');
    exit();
}
// ob_end_flush();
?>