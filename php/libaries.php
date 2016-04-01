<?php
/************************************************************
 * Created by PhpStorm.
 * User: Bobby
 * Date: 21/9/2015
 * Time: 9:09 AM
 ************************************************************/
session_start();

// Initialize Session variables
function initSessionVar($row) {
    $_SESSION["profileID"] = $row['PROFILEID'];
    $_SESSION["profileName"] = $row['FIRSTNAME'];
    $_SESSION["profileAccountBalance"] = $row['ACCBALANCE'];
    $_SESSION["profileCreditCardNo"] = $row['CREDITCARDNUM'];
    $_SESSION["profileAdmin"] = $row['ADMIN'];
}

function logOut() {
    session_unset();
}

// ==============================================
// Redirect methods
// ==============================================
function redirectToLoginPage() {
    echo "<script type='text/javascript'> document.location = 'login.php'; </script>";
}

function redirectToPaymentPage() {
    echo "<script type='text/javascript'> document.location = 'payment.php'; </script>";
}

function redirectToSearchPage() {
    echo "<script type='text/javascript'> document.location = 'search.php'; </script>";
}

function redirectToHomePage() {
    echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
}

// ==============================================
// Check methods
// ==============================================
function isUserLoggedIn() {
    return (isset($_SESSION["profileID"]) && isset($_SESSION["profileName"]));
}

function isUserAdmin() {
    return (isset($_SESSION["profileAdmin"]) && $_SESSION["profileAdmin"] == 1);
}

// ==============================================
// Setters and Getters methods
// ==============================================
function getProfileName() {
    $profileName = isset($_SESSION["profileName"]) ? $_SESSION["profileName"] : "User not logged in";

    echo $profileName;
}

function getProfileAccountBalance() {
    $accountBalance = isset($_SESSION["profileAccountBalance"]) ? "$ ".$_SESSION["profileAccountBalance"] : "Account is not logged in";

    echo $accountBalance;
}

function getProfileID() {
    if(isset($_SESSION["profileID"]) == false) {
        echo "User not logged in";
    }
    else {
        return $_SESSION["profileID"];
    }
}
?>
