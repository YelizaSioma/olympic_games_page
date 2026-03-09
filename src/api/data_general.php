<?php
require_once(__DIR__ . '/../config.php');
$conn = connectDatabase($hostname, $database, $username, $password);

$sql = "
    SELECT
        a.id          AS athlete_id,
        a.first_name,
        a.last_name,
        og.year       AS oh_year,
        bc.name        AS birth_country,
        mt.placing
    FROM athlete_medals am
    JOIN athletes      a  ON am.athlete_id       = a.id
    JOIN olympic_games og ON am.olympic_games_id = og.id
    LEFT JOIN countries bc ON a.birth_country_id = bc.id
    JOIN medal_types   mt ON am.medal_type_id    = mt.id
    ORDER BY a.last_name, a.first_name, og.year
";

$stmt = $conn->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['data' => $rows]);
