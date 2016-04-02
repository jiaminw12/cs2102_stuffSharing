<?php
session_start();
$current_page = 'Sign Up';

if (isset($_SESSION["username"])) {
    header("Location: index.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once("controller/UserController.php");
    $fullname = $_POST["fullname"];
    $roles = $_POST["roles"];
    if ($roles == "admin") {
        $admin = 1;
    } else {
        $admin = 0;
    }
    echo $admin;
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $contact_num = $_POST["contact_num"];
    $verify_password = $_POST["verify_password"];
    if ($password !== $verify_password) {
        $message = "Password doesn't match";
        $message_type = "danger";
    } else {
        $message = "User created";
        $message_type = "success";

        $user = UserController\createNewUser($username, $email, $fullname, $password, $contact_num, $admin);

        if (isset($user)) {
            unset($fullname);
            unset($username);
            unset($email);
            unset($contact_num);
            unset($admin);
        } else {
            $message = "Username or email already exists or invalid email format";
            $message_type = "danger";
        }
    }
}
?>

<? ob_start(); ?>

<script type="text/javascript">
    // When the document is ready
    $(document).ready(function() {
        $('#date_of_birth').datepicker({});
    });
</script>

<br/>
<div class="inner cover container">
    <div class="row">

        <?php
        include_once 'template/message.php'
        ?>
        <form method="post" class="form" role="form">
            <div class="form-group ">
                <input class="form-control" id="username" name="username" placeholder="Username" required type="text" value="<?php echo $username ?>">
            </div>
            <div class="form-group ">
                <input class="form-control" id="fullname" name="fullname" placeholder="Full Name" required type="text" value="<?php echo $fullname ?>">
            </div>
            <div class="form-group">
                <select class="form-control" id="roles" name="roles" selected="<?php echo $admin ?>">
                    <option <?php echo $admin == 'user' ? "selected" : ""; ?> value="creator">User</option>
                    <option <?php echo $admin == 'admin' ? "selected" : ""; ?> value="contributor">Administrator</option>
                </select>
            </div>
            <div class="form-group ">
                <input class="form-control" id="email" name="email" placeholder="Email" required type="email" value="<?php echo $email ?>">
            </div>
            <div class="form-group ">
                <input class="form-control" id="contact_num" name="contact_num" placeholder="Contact Number" required type="tel">
            </div>
            <div class="form-group ">
                <input class="form-control" id="password" name="password" placeholder="Password" required type="password">
            </div>
            <div class="form-group ">
                <input class="form-control" id="verify_password" name="verify_password" placeholder="Re-type password" required type="password">
            </div>
            <button class="btn btn-success btn-lg btn-block" id="submit" name="submit" type="button">Submit</button>
        </form>
        <br/>
    </div>
</div>
<?php
$content = ob_get_clean();
include_once 'template/skeleton.php';
?>
