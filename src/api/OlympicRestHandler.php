<?php
require_once ("SimpleRest.php");
require_once ("Olympic.php");
// No header() here — headers belong inside methods only

class OlympicRestHandler extends SimpleRest {
    function getAllOlympics() {
        $olympic  = new Olympic();
        $rawData  = $olympic->getAllOlympic();
        $statusCode = empty($rawData) ? 404 : 200;

        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['data' => $rawData]);
        exit;
    }

    public function getOlympic($id) {
        $olympic  = new Olympic();
        $rawData  = $olympic->getOlympic($id);
        $statusCode = empty($rawData) ? 404 : 200;

        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['athlete' => [$rawData]]);
        exit;
    }

    public function getOlympicMedals($id) {
        $olympic  = new Olympic();
        $rawData  = $olympic->getOlympicMedals($id);
        $statusCode = empty($rawData) ? 404 : 200;

        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['medals' => $rawData]);
        exit;
    }
}