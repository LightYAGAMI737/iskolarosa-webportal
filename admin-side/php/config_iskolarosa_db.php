<?php
$server = "localhost";
$username = "u789664695_iskolarosa_db";
$password = "iSkolarosa-03";
$dbname = "u789664695_iskolarosa_db";

$conn = mysqli_connect($server, $username, $password, $dbname);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

// Set the session time zone to 'Asia/Manila'
$query = "SET time_zone = '+08:00'";
mysqli_query($conn, $query);

?>
