<?php
session_start();
require_once("config.php");
if ($_SESSION['key'] != "AdminKey") {
    echo "<script> location.assign('logout.php');</script>";
    die;
}
?>
<!DOCTYPE html> ̰
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adminpanel-Online College Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row bg-black text-white">
            <div class="col-1">
                <img src="../assets/images/logo.jpeg" width="80px" />
            </div>
            <div class="col-11 my-auto">
                <h3>ONLINE COLLEGE VOTING SYSTEM - <small>Welcome <?php echo
                    $_SESSION['username']; ?></small></h3>
            </div>
        </div>