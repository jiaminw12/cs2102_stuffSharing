<?php
 session_start();
  $current_page = 'Profile';
  include_once "controller/UserController.php";
  include_once "controller/ItemController.php";

  $username = $_SESSION["username"];
  
?>
<?php
        include_once 'template/message.php';
        if ($message_type != 'danger') {
          echo $message;
          echo $message_type;
        } else {}
      ?>
<?php
    $userList = UserController\getUser($username);
    $email = $userList->getEmail();
    $itemList = ItemController\getAvailableUserItem($email);
?>


 <?php ob_start(); ?>
   <br/>
  <div class="inner cover container">
      <span class="black">
         <ul class="nav nav-tabs">
             <li class="active"><a href="#home" data-toggle="tab">Profile</a></li>
             <li><a href="#profile" data-toggle="tab">Password</a></li>
             <li><a href="#bidHistory" data-toggle="tab">Bid History</a></li>
             <li><a href="#Item" data-toggle="tab">Items</a></li>
    </ul>
    <div id="tabContent" class="tab-content" align="left">
      <div class="tab-pane active in" id="home" algin="left">
        <form id="tab">
            <label>Username</label><br>
            <input type="text" value="<?php echo $userList->getUsername(); ?>" class="input-xlarge"><br>
            <label>Name</label><br>
            <input type="text" value="<?php echo $userList->getName(); ?>" class="input-xlarge"><br>
            <label>Contact Number</label><br>
            <input type="text" value="<?php echo $userList->getContactNum(); ?>" class="input-xlarge"><br>
            <label>Email</label><br>
            <?php echo $userList->getEmail(); ?><br>
            <label>Current bid point</label><br>
            <?php echo $userList->getBidPoint(); ?><br><br>
          	<div align = "center">
        	    <button class="btn btn-primary">Update</button>
        	</div>
        </form>
      </div>
      <div class="tab-pane fade" id="profile">
    	<form id="tab2">
        	<label>New Password</label>
        	<input type="password" class="input-xlarge">
        	<div>
        	    <button class="btn btn-primary">Update</button>
        	</div>
    	</form>
      </div>
      
       <div class="tab-pane fade" id="bidHisory">
    	<form id="tab2">
        	<label>Bid History</label>
        	
        	<div>
        	    <button class="btn btn-primary">Update</button>
        	</div>
    	</form>
      </div>
        
       <div class="tab-pane fade" id="Item">
    	<form id="tab2">
            <div class="span7">
                <div class="widget stacked widget-table action-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>Available Items</h3>
                    </div>
                    
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Current Bid Point</th>
                                    <th class="td-actions"></th>
                                </tr>
                             
                                <tr>
                                    <td><?php echo $itemList->getTitle() ?></td>
                                    <td>haha</td>
                                    <td>update</td>
                                </tr>  
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    	</form>
      </div>
  </div> 
      </span>
      
     
  </div> 
      
      
<?php
  $content = ob_get_clean();
  include_once 'template/skeleton.php';
?>


