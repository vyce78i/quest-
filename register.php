<?php
// Prevent any HTML notice from corrupting the JSON response
error_reporting(0);
ini_set('display_errors', '0');

header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

// Safe session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents('php://input'), true);

$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Username and password are required.']);
    exit;
}

try {
    // 1. Check if hero name is taken
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Hero name already taken. Choose another!']);
        exit;
    }

    // 2. Hash password & insert using exact table column names
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $insert = $pdo->prepare("INSERT INTO users (username, password_hash, xp, current_level) VALUES (?, ?, 0, 1)");
    $insert->execute([$username, $hash]);

    // 3. Store session
    $_SESSION['user_id'] = $pdo->lastInsertId();
    $_SESSION['username'] = $username;

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'DB error: ' . $e->getMessage()]);
}
