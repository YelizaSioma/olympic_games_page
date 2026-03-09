<?php
require_once(__DIR__ . '/../config.php');
$conn = connectDatabase($hostname, $database, $username, $password);

// Get athlete ID from endpoint string, if wasn't provided then 0 then error
$athleteId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($athleteId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid athlete id']);
    exit;
}

// 1) General table athlete info
$sqlAthlete = "
    SELECT
        a.first_name,
        a.last_name,
        a.birth_date,
        a.birth_place,
        bc.name AS birth_country,
        a.death_date,
        a.death_place,
        dc.name AS death_country
    FROM athletes a
    LEFT JOIN countries bc ON a.birth_country_id = bc.id
    LEFT JOIN countries dc ON a.death_country_id = dc.id
    WHERE a.id = :id
";
$stmt = $conn->prepare($sqlAthlete);
$stmt->execute([':id' => $athleteId]);
$athlete = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$athlete) {
    http_response_code(404);
    echo json_encode(['error' => 'Athlete not found']);
    exit;
}

// 2) All medals for this athlete
$sqlMedals = "
    SELECT
        mt.placing,
        d.name        AS discipline,
        og.type       AS oh_type,
        og.year       AS oh_year,
        og.city       AS oh_city,
        hc.name       AS oh_country,
        og.order_number AS oh_order,
        hc.code       AS oh_code
    FROM athlete_medals am
    JOIN medal_types   mt ON am.medal_type_id    = mt.id
    JOIN disciplines   d  ON am.discipline_id    = d.id
    JOIN olympic_games og ON am.olympic_games_id = og.id
    JOIN countries     hc ON og.country_id       = hc.id
    WHERE am.athlete_id = :id
    ORDER BY og.year
";
$stmt = $conn->prepare($sqlMedals);
$stmt->execute([':id' => $athleteId]);
$medals = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'athlete' => [$athlete],
    'medals'  => $medals
]);
?>