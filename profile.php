<?php
 session_start();
  $current_page = 'Profile';
  include_once "controller/UserController.php";

  $username = $_SESSION["username"];
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
      
      
<?php
  $content = ob_get_clean();
  include_once 'template/skeleton.php';
?>