<?php
$server = 'localhost';
$username = 'root';
$password = 'root';
$database = 'onlinevotingsystem';
$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die('connection to this database failed due to' . mysqli_connect_error());
}
// echo"Success connecting to the db ";
?>