<?php
require_once 'db.php';

// Simple check to see if database is reachable
if ($conn) {
    echo json_encode(["status" => "success", "message" => "Connected to MySQL successfully from the network API!"]);
}
?>