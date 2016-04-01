<?php
 session_start();
  $current_page = 'Profile';
  include_once "controller/UserController.php";
  include_once "controller/ItemController.php";
  include_once "controller/BidController.php";

  $username = $_SESSION["username"];
  
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
      <div class="tab-pane fade" id="profile" align="left">
    	<form id="tab2">
        	<label>New Password</label>
        	<input type="password" class="input-xlarge">
        	<div>
        	    <button class="btn btn-primary">Update</button>
        	</div>
    	</form>
      </div>
      <div class="tab-pane fade" id="item" align="left">
    	<form id="tab2">
                <div class="span7">
                    <div class="widget stacked widget-table action-table">
                        <div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Available Items</h3>
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
                                                           echo $item->getBidPoint();
                                                           ?>
                                                        </td> 
                                                        <td><button class="btn btn-primary">edit</button><button class="btn btn-primary">delete</button></td>
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
