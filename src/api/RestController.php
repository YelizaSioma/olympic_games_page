<?php
session_start();
require_once ("OlympicRestHandler.php");

// if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
//     echo json_encode(['data' => []]);
//     exit;
// }

// RestController.php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// $uri is now "/api/olympic/list" or "/api/olympic/list/3"

if (preg_match('#^/api/olympic/list/?$#', $uri)) {
    $olympicRestHandler = new OlympicRestHandler();
    $olympicRestHandler->getAllOlympics();

} elseif (preg_match('#^/api/olympic/list/(\d+)/?$#', $uri, $matches)) {
    $olympicRestHandler = new OlympicRestHandler();
    $olympicRestHandler->getOlympic($matches[1]);
} elseif (preg_match('#^/api/olympic/list/(\d+)/medals/?$#', $uri, $matches)) {
    $olympicRestHandler = new OlympicRestHandler();
    $olympicRestHandler->getOlympicMedals($matches[1]);
} else {
    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Endpoint not found']);
    exit;
}
?>