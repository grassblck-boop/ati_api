<?php
header("Content-Type: application/json");
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);

    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        // Query user from the central database
        $stmt = $conn->prepare("SELECT user_id, username, password_hash, fullname, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Verify the entered password against the stored secure hash
            if (password_verify($password, $row['password_hash'])) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Login successful",
                    "user" => [
                        "user_id" => $row['user_id'],
                        "username" => $row['username'],
                        "fullname" => $row['fullname'],
                        "role" => $row['role']
                    ]
                ]);
            } else {
                echo json_encode(["status" => "error", "message" => "Invalid password."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "User not found."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Missing username or password."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method. Use POST."]);
}

$conn->close();
?>