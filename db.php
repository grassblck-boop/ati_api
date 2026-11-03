<?php
$host = "localhost";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password is blank
$database = "ati_incident_db"; // The database name you created

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}
?>