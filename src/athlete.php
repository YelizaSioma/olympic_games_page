<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

require_once(__DIR__ . '/config.php');
$conn = connectDatabase($hostname, $database, $username, $password);

// Get athlete ID from endpoint string, if wasn't provided then 0 then error
$athleteId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($athleteId <= 0) {
    die("Neplatný športovec.");
}

$sqlAthlete = "
    SELECT
        a.first_name
    FROM athletes a
    WHERE a.id = :id
";
$stmt = $conn->prepare($sqlAthlete);
$stmt->execute([':id' => $athleteId]);
$athlete = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$athlete) {
    die("Neplatný športovec.");
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sportovci</title>

    <!-- Datatables + Bootstrap 5 skin -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Scope+One&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="assets/styles.css" rel="stylesheet">
</head>
<body>

<nav class="olympic-nav">
  <div class="olympic-nav-inner">
    <a class="olympic-nav-item nav-item-blue" href="index.php">
      Domov
    </a>
    <a class="olympic-nav-item nav-item-yellow" href="profile_settings.php">
      Nastavenia profilu
    </a>
    <a class="olympic-nav-item nav-item-red" href="logout.php">
      Odhlásiť sa
    </a>
  </div>
</nav>

<div class="container mt-4">
<main>
    <div class="table-responsive">
    <table id="athlete-main-table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Meno</th>
                <th>Priezvisko</th>
                <th>Den narodenia</th>
                <th>Miesto narodenia</th>
                <th>Krajina narodenia</th>
                <th>Den umrnutia</th>
                <th>Miesto umrnutia</th>
                <th>Krajina umrnutia</th>
            </tr>
        </thead>
        <tbody>
            <!-- Datatables fills this via fetch from data_athlete.php athlete table -->
        </tbody>
    </table>
    </div>
    <div class="table-responsive">
    <table id="athlete-medals-table" class="table table-striped table-bordered">
        <thead>
            <th>Umiestnenie</th>
            <th>Sport</th>
            <th>Typ OH</th>
            <th>Rok OH</th>
            <th>Miesto OH</th>
            <th>Krajina OH</th>
            <th>Poradie OH</th>
            <th>Kod OH</th>
        </thead>
        <tbody>
             <!-- Datatables fills this via fetch from data_athlete.php medals table -->
        </tbody>
    </table>
    </div>
</main>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#athlete-main-table').DataTable({
        ajax: {
            url: 'api/data_athlete.php?id=<?= $athleteId ?>',
            dataSrc: 'athlete'
        },
        columns: [
            { data: 'first_name'},
            { data: 'last_name'},
            { data: 'birth_date' },
            { data: 'birth_place' },
            { data: 'birth_country'},
            { data: 'death_date' },
            { data: 'death_place' },
            { data: 'death_country' },
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        language: {
            search: "Hľadať:",
            lengthMenu: "Zobraziť _MENU_ záznamov",
            info: "Zobrazené _START_ až _END_ z _TOTAL_ záznamov",
            paginate: {
                previous: "Predchádzajúca",
                next: "Nasledujúca"
            }
        }
    });
});
</script>

<script>
$(document).ready(function () {
    $('#athlete-medals-table').DataTable({
        ajax: {
            url: 'api/data_athlete.php?id=<?= $athleteId ?>',
            dataSrc: 'medals'
        },

        columns: [
            { data: 'placing'},
            { data: 'discipline'},
            { data: 'oh_type' },
            { data: 'oh_year' },
            { data: 'oh_city'},
            { data: 'oh_country' },
            { data: 'oh_order' },
            { data: 'oh_code' },
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        language: {
            search: "Hľadať:",
            lengthMenu: "Zobraziť _MENU_ záznamov",
            info: "Zobrazené _START_ až _END_ z _TOTAL_ záznamov",
            paginate: {
                previous: "Predchádzajúca",
                next: "Nasledujúca"
            }
        }
    });
});
</script>

</body>
</html>