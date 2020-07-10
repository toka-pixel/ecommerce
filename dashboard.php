<?php
session_start();
$pagetitle="dashboard";
if(isset($_SESSION['Username'])){
  include 'init.php';


   $numusers=3;
   $latestusers=getlatest('*','users','userid', $numusers);
   
   $numitems=3;
   $latestitems=getlatest('*','items','itemid', $numitems);

  ?>
  <div class="container home-stats text-center">
         <h1>Dashboard</h1>
     <div class="row">
        <div class="col-md-3">
           <div class="stat st-members">Total Members
            <p><a href="members.php"><?php  
                                          // conutitem function
                                          $stmt= $con->prepare('SELECT COUNT(userid) FROM users');
                                          $stmt->execute();
                                           echo $stmt->fetchColumn(); ?></a></p>
            </div>
        </div>
        <div class="col-md-3">
           <div class="stat st-pending">
              Pending Members
              <p><a href="members.php?do=manage&page=pending">
               <?php
                    //  <!-- checkitem function -->
                   $statement =$con->prepare('SELECT regstatus FROM users WHERE regstatus = 0 ');
                   $statement->execute(array());
                   $count=$statement->rowCount();
                   echo $count;
               ?>
               </a>
             </p>
            </div>
        </div>
        <div class="col-md-3">
           <div class="stat st-items">
              Total Items
              <p>
              <a href="items.php">
               <?php
                    //  <!-- checkitem function -->
                   echo countitems("itemid","items");
               ?>
               </a>
              </p>
            </div>
        </div>
        <div class="col-md-3">
           <div class="stat st-comments">
              Total comments
              <p>2000</p>
            </div>
        </div>
    </div>
  </div>
  <div class="latest">
     <div class="container">
         <div class="row">
             <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-users"></i>
                        Latest <?php echo $numusers ?> Registerd Users
                          <span class="toggle-info pull-right">
                            <i class="fa fa-plus "></i>
                          </span> 
                    </div>
                    <div class="panel-body">
                          <ul class="list-unstyled latest-users">
                            <?php
                            foreach($latestusers as $user)
                               {
                                 echo '<li>';
                                 echo $user['username'];
                                 echo '<a href="members.php?do=edit&userid='.$user['userid'].'">';
                                 echo '<span class="btn btn-success pull-right> <i class="fa fa-edit"></i> Edit';
                                 if($user['regstatus'] == 0){
                                  echo" <a href='members.php?do=activate&userid=" .$user["userid"]."' class='btn btn-info' id='confirm' ><i class='fa fa-delete'></i>Activate</a>";
                                  }
                                 echo'</span>';
                                  echo '</a>';
                                 echo '</li>';
                               }
                           ?>
                        </ul>
                    </div>
                </div>
             </div>
             <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-tag"></i>
                        Latest Items Added
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled latest-users">
                                <?php
                                foreach($latestitems as $item)
                                  {
                                    echo '<li>';
                                    echo $item['name'];
                                    echo '<a href="items.php?do=edit&itemid='.$item['itemid'].'">';
                                    echo '<span class="btn btn-success pull-right> <i class="fa fa-edit"></i> Edit';
                                    if($item['approve'] == 0){
                                      echo" <a href='items.php?do=approve&itemid=" .$item["itemid"]."' class='btn btn-info' id='confirm' ><i class='fa fa-delete'></i>approve</a>";
                                      }
                                    echo'</span>';
                                      echo '</a>';
                                    echo '</li>';
                                  }
                              ?>
                            </ul>
                    </div>
                </div>
             </div>
         </div>
      </div>
  </div>

  <?php
  include "includes/templets/footer.php";
   }
    else{
      header('Location: index.php');
      echo "no";
    }
    ?>