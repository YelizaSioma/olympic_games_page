<?php

class Olympic
{
    private PDO $pdo;

    public function __construct()
    {
        require_once(__DIR__ . '/../config.php');
        $this->pdo = connectDatabase($hostname, $database, $username, $password);
        if (!$this->pdo) {
            die(json_encode(['error' => 'DB connection failed']));
         }
    }

    public function getAllOlympic()
    {
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

    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOlympic($id)
    {
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
    $stmt = $this->pdo->prepare($sqlAthlete);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOlympicMedals($id)
    {
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
    $stmt = $this->pdo->prepare($sqlMedals);
    $stmt->execute([':id' => $id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}