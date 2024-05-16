<?php
$db_server_name = "mariadb";
$db_username = "cs332e14";
$db_password = "HiYdE87v";
$db_name = "cs332e14";

// Create connection
$conn = new mysqli($db_server_name,
                    $db_username,
                    $db_password,
                    $db_name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>