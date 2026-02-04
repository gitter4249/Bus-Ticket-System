<?php
$conn = new mysqli("localhost", "root", "", "bus_system");

if ($conn->connect_error) {
    die("DB Connection Failed");
}
?>
