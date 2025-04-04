<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "TestDBS";

// Connect to the database
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected to the database successfully!";
}
?>s
