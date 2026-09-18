<?php
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
}
require_once __DIR__ . "/../config/db.php";

header('Content-Type: application/json; charset=utf-8');

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode([]);
    exit();
}

$userId = $_SESSION['user']['id'];

// If clicking a specific conversation thread or auto-loading active threads on refresh
if (isset($_GET['session_id']) && !empty($_GET['session_id'])) {
    $sessionId = $_GET['session_id'];
    $stmt = $conn->prepare("SELECT sender, message, timestamp, session_id FROM chat_history WHERE user_id = ? AND session_id = ? ORDER BY id ASC");
    $stmt->bind_param("is", $userId, $sessionId);
} else {
    // Group by session_id to get the first USER message to use as the sidebar title label
    $stmt = $conn->prepare("SELECT sender, message, timestamp, session_id 
                            FROM chat_history 
                            WHERE id IN (SELECT MIN(id) FROM chat_history WHERE user_id = ? AND sender = 'user' GROUP BY session_id) 
                            ORDER BY id DESC");
    $stmt->bind_param("i", $userId);
}

$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        "sender"     => $row['sender'],
        // JSON_UNESCAPED_SLASHES and raw tracking strings keep HTML elements clean
        "message"    => $row['message'], 
        "created_at" => $row['timestamp'], 
        "session_id" => $row['session_id']
    ];
}

// Added JSON_UNESCAPED_SLASHES to keep card strings intact
echo json_encode($messages, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$stmt->close();
$conn->close();
?>
