<?php
header("Content-Type: application/json");
require_once 'db.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data sent from the Android app
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);

    if ($data) {
        $user_id = $data['user_id'] ?? null;
        $client_site = $data['client_site'] ?? null;
        $issue_description = $data['issue_description'] ?? null;
        $status = $data['status'] ?? 'COMPLETED'; // Default to completed since we only sync finished tasks

        // Basic validation
        if (!empty($user_id) && !empty($client_site) && !empty($issue_description)) {
            $stmt = $conn->prepare("INSERT INTO incident_reports (user_id, client_site, issue_description, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $client_site, $issue_description, $status);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Incident report synced successfully!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Database insert failed: " . $stmt->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Missing required fields."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid JSON payload."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method. Use POST."]);
}

$conn->close();
?>