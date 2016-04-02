<?php
 session_start();
  $current_page = 'Profile';
  include_once "controller/UserController.php";
  include_once "controller/ItemController.php";
  include_once "controller/BidController.php";

  $username = $_SESSION["username"];
  // update profile
  if(isset($_POST['update-profile-submit'])) {
            $params = array($_POST["first-name"], $_POST["last-name"], $_POST["gender"], $_POST["description"], $username);
            $query = "UPDATE users SET first_name = $1, last_name = $2, gender = $3, description = $4 WHERE username = $5;";
            $result = pg_query_params($dbconn, $query, $params);
            if ($result) {
                create_notification('success', 'Profile updated!');
            } else {
                die("Query failed: " . pg_last_error());
            }
        }
  // update password
  if(isset($_POST['update-password-submit'])) {
            $params = array($_POST["current-password"], $username);
            $query = "SELECT * FROM users WHERE password = $1 AND username = $2";
            $result = pg_query_params($dbconn, $query, $params);
            if (pg_num_rows($result) > 0) {
                $params = array($_POST["new-password"], $username);
                $query = "UPDATE users SET password = $1 WHERE username = $2";
                $result = pg_query_params($dbconn, $query, $params);
                if ($result) {
                    create_notification('success', 'Password updated!');
                } else {
                    die("Query failed: " . pg_last_error());
                }
            } else {
                create_notification('danger', 'Current password is not correct.');
            }
        }
  
?>
<?php
    $userList = UserController\getUser($username);
    $owner = $userList->getEmail();
    $itemList = ItemController\getAvailableItem($owner);
?>


<?php ob_start(); ?>
   <br/>
  <div class="inner cover container">
    <?php
        include_once 'template/message.php';
        if ($message_type != 'danger') {
          echo $message;
          echo $message_type;
        } else {}
      ?>
      <span class="black">
          <div class="well" align="left">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">Profile</a></li>
      <li><a href="#profile" data-toggle="tab">Password</a></li>
      <li><a href="#item" data-toggle="tab">Items</a></li>
      <li><a href="#bidHistory" data-toggle="tab">Bid History</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home" align="left">
        <form id="tab">
            <label>Username</label><br>
            <input type="text" value="<?php echo $userList->getUsername(); ?>" class="input-xlarge" name="username"><br>
            <label>Name</label><br>
            <input type="text" value="<?php echo $userList->getName(); ?>" class="input-xlarge" name ="name"><br>
            <label>Contact Number</label><br>
            <input type="text" value="<?php echo $userList->getContactNum(); ?>" class="input-xlarge" name="contact_num"><br>
            <label>Email</label><br>
            <?php echo $userList->getEmail(); ?><br>
            <label>Current bid point</label><br>
            <?php echo $userList->getBidPoint(); ?><br><br>
          <div align = "center">
           <button class="btn btn-primary">Update</button>
        </div>
        </form>
      </div>
      <div class="tab-pane fade" id="profile" align="left">
    	<form id="tab2">
                <label>Current Password</label>
                <input type="password" name='current-password' class="form-control" id="input-current-password" placeholder="Current password">
        	<label>New Password</label>
        	<input type="password" name='new-password' class="form-control" id="input-new-password" placeholder="New password">
        	<div>
        	    <button class="btn btn-primary" name='update-password-submit' type="submit">Update</button>
        	</div>
    	</form>
      </div>
      <div class="tab-pane fade" id="item" align="left">
    	<form id="tab2">
                <div class="span7">
                    <div class="widget stacked widget-table action-table">
                        <div class="widget-header">
					<i class="icon-th-list"></i>
					<h4>Available Items</h4>
				</div> <!-- /widget-header -->
		        <div class="widget-content">
					
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Title</th>
								<th>Current Bid Points</th>
								<th class="td-actions"></th>
							</tr>
						</thead>
                                                <tbody>
                                                    <?php foreach ($itemList as $item) { ?>
                                                    <tr>
                                                        <td><?php  echo $item->getItemTitle(); ?></td>
                                                        <td><?php
                                                          // $itemID = $item->getItemId();
                                                          // $bidPoint = BidController\getSelectedBids($itemID);
                                                           //echo $item->getBidPoint();
                                                           ?> need to complete
                                                        </td> 
                                                        <td><button class="btn btn-primary">edit</button><button class="btn btn-primary">delete</button></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                        </table>
                    </div>  
                                
                                
                       <div class="widget-header">
					<i class="icon-th-list"></i>
					<h4>Borrowed Items</h4>
				</div> <!-- /widget-header -->
		        <div class="widget-content">
					
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Title</th>
								<th>Borrow Start Date</th>
								<th>Borrow End Date</th>
							</tr>
						</thead>
                                                <tbody>
                                                    <?php foreach ($itemList as $item) { ?>
                                                    <tr>
                                                        <td><?php  echo $item->getItemTitle(); ?></td>
                                                        <td><?php echo $item->getBorrowStartDate() ?> </td> 
                                                        <td><?php echo $item->getBorrowEndDate() ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                        </table>
                    </div> 
                                
                                
                                
                    <div class="widget-header">
					<i class="icon-th-list"></i>
					<h4>Current Bidding Items</h4>
				</div> <!-- /widget-header -->
		        <div class="widget-content">
					
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Title</th>
								<th>Bid End Date</th>
                                                                <th>Bid Point</th>
								<th>Current Bid Point</th>
                                                                <th class="td-actions"></th>
                                                                
                                                                
							</tr>
						</thead>
                                                <tbody>
                                                    <?php foreach ($itemList as $item) { ?>
                                                    <tr>
                                                        <td><?php  echo $item->getItemTitle(); ?></td>
                                                        <td><?php echo $item->getBidEndDate() ?> </td> 
                                                        <td><?php // echo $item->getBidList() ?>Need Complete</td>
                                                        <td><?php// echo $item->getAvailable() ?>Need Complete</td>
                                                        <td><button class="btn btn-primary">Update</button></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                        </table>
                    </div> 
                                
                                
                                
                                
                </div>
                
        	
    	</form>
      </div>
      <div class="tab-pane fade" id="bidHistory" align="left">
    	<form id="tab2">
        	<label>Bidding History</label>
        	<input type="password" class="input-xlarge">
        	<div>
        	    <button class="btn btn-primary">Update</button>
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
