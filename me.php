<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

// Check if user has an active session
if (!isset($_SESSION['user_id'])) {
    jsonResponse(['authenticated' => false], 200);
}

// Return fresh user profile and progression stats
$stmt = $pdo->prepare("SELECT id, username, email, xp, current_level, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    jsonResponse(['authenticated' => false], 200);
}

jsonResponse([
    'authenticated' => true,
    'user' => $user
]);
