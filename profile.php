<?php
 session_start();
  $current_page = 'Profile';
  include_once "controller/UserController.php";
  include_once "controller/ItemController.php";
  include_once "controller/BidController.php";
  include_once "controller/BorrowController.php";

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
                                                    <?php 
                                                    
                                                    foreach ($itemList as $item) { ?>
                                                    <tr>
                                                        <td><?php  echo $item->getItemTitle(); ?></td>
                                                        <td><?php
                                                          $item_avid =  $item->getItemId();
                                                          $bidList = BidController\getTheHighestBidPoint($item_avid);
                                                          echo $bidList[0];
                                                           ?>
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
                                                                <th>Return Location</th>
                                                                
							</tr>
						</thead>
                                                <tbody>
                                                    <?php 
                                                        
                                                        $borroweremail = $userList->getEmail();
                                                        
                                                        $borrow = BorrowController\getAllBorrowsByUser($borroweremail);
                                                                                                                

                                                        foreach ($borrow as $borrowitem) {  ?>
                                                    <tr>
                                                        <td><?php 
                                                             $borrow_id= $borrowitem->getItemId();
                                                             
                                                             $borrowtitle = ItemController\getItem($borrow_id);
                                                             
                                                        foreach($borrowtitle as $titleBor){
                                                            
                                                               echo $titleBor->getItemTitle();
                                                             ?>title--</td>
                                                        <td><?php  echo $titleBor->getBorrowStartDate(); ?>start--</td> 
                                                        <td><?php  echo $titleBor->getBorrowEndDate(); ?>end--</td> 
                                                        <td><?php echo $titleBor->getReturnLocation(); } ?></td>
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
                                                                <th>Current Bid Point</th>
                                                                <th>Bid Point</th>
                                                                <th class="td-actions"></th>
                                                                
                                                                
							</tr>
						</thead>
                                                <tbody>
                                                    <?php 
                                                    $bidder = $userList->getEmail();
                                                    $bidderList = BidController\getSelectedBidByUser($bidder);
                                                    foreach ($bidderList as $bidItem) { ?>
                                                    <tr>
                                                        <td><?php  
                                                        $item_id = $bidItem->getItemId();
                                                        $item_title = ItemController\getItem($item_id);
                                                        foreach($item_title as $titleItem){
                                                            echo $titleItem->getItemTitle();
                                                         ?></td>
                                                        
                                                        <td><?php echo $titleItem->getBidEndDate();  } ?></td> 
                                                        <td><?php
                                                            $item_id =  $item->getItemId();
                                                            $bidList = BidController\getTheHighestBidPoint($item_id);
                                                            echo $bidList[0];
                                                             ?></td>
                                                        <td><input type="text" value="<?php echo $bidItem->getBidPoint(); ?>"class="input-xlarge" name="update-bid"></td>
                                                        <td><button class="btn btn-primary">Update</button></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                        </table>
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
