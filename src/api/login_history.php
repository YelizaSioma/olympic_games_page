<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['data' => []]);
    exit;
}

require_once __DIR__ . '/../config.php';
$pdo = connectDatabase($hostname, $database, $username, $password);
$email = $_SESSION['email'] ?? '';

$stmt = $pdo->prepare("SELECT id FROM user_accounts WHERE email = :email LIMIT 1");
$stmt->execute([':email' => $email]);
$user_id = $stmt->fetchColumn();
if (!$user_id) {
    echo json_encode(['data' => []]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT login_type, created_at
    FROM login_history
    WHERE user_id = :user_id
    ORDER BY created_at DESC
");
$stmt->execute([':user_id' => $user_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as &$r) {
    $r['created_at'] = $r['created_at'] ?? '';
}
unset($r);

echo json_encode(['data' => $rows]);
