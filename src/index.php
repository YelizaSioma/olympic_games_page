<?php
session_start();

require_once(__DIR__ . '/config.php');

$conn = connectDatabase($hostname, $database, $username, $password);
if ($conn) {
    echo "Pripojene k DB.";
}

// ---------------------------
// FUNKCIE PRE VKLADANIE DAT
// ---------------------------

// ---------------------------
//ATHLETE TABLE
function getOrCreateAthlete(PDO $pdo,
    string $firstName,
    string $lastName,
    ?string $birthDate = null,
    ?string $birthPlace = null,
    ?int $birthCountryId = null,
    ?string $deathDate = null,
    ?string $deathPlace = null,
    ?int $deathCountryId = null
    ): int {
    // Najdi atleta podla mena+priezvisko+kedy sa narodil
    $stmt = $pdo->prepare("SELECT id FROM athletes WHERE first_name = :first_name AND last_name = :last_name AND birth_date = :birth_date LIMIT 1");
    $stmt->execute([
        ':first_name' => $firstName,
        ':last_name' => $lastName,
        ':birth_date' => $birthDate,
    ]);
    $id = $stmt->fetchColumn();

    // Ak existuje, vrat ID.
    if ($id) {
        return (int) $id;
    }

    // Ak neexistuje, vytvor novy zaznam.
    $sql = "INSERT INTO athletes
                (first_name, last_name, birth_date, birth_place, birth_country_id,
                death_date, death_place, death_country_id)
                VALUES
                (:first_name, :last_name, :birth_date, :birth_place, :birth_country_id,
                :death_date, :death_place, :death_country_id)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':first_name' => $firstName,
        ':last_name' => $lastName,
        ':birth_date' => $birthDate,
        ':birth_place' => $birthPlace,
        ':birth_country_id' => $birthCountryId,
        ':death_date' => $deathDate,
        ':death_place' => $deathPlace,
        ':death_country_id' => $deathCountryId
    ]);

    return (int) $pdo->lastInsertId();
}

// ---------------------------s
// COUNTRY TABLE
function getOrCreateCountry(PDO $pdo, string $name, ?string $code = null): int {
    // Najprv najdi, ci krajina s danym nazvom uz existuje.
    $stmt = $pdo->prepare("SELECT id FROM countries WHERE name = :name LIMIT 1");
    $stmt->execute([':name' => $name]);
    $id = $stmt->fetchColumn();

    // Ak existuje, vrat jej ID
    if ($id) {
        return (int) $id;
    }

    // Ak neexistuje, vloz novy zaznam a vrat jeho ID.
    $stmt = $pdo->prepare("INSERT INTO countries (name, code) VALUES (:name, :code)");
    $stmt->execute([
        ':name' => $name,
        ':code' => $code
    ]);
    return (int) $pdo->lastInsertId();
}


// ---------------------------
//OLYMPIC_GAMES TABLE
function getOrCreateGames(PDO $pdo, int $year, int $order_number, string $city, string $type, int $countryId): int {
    // Najdi OH, podla roku konania a typu - kedze sme ich definovali ako UNIQUE
    $stmt = $pdo->prepare("SELECT id FROM olympic_games WHERE year = :year AND type = :type LIMIT 1");
    $stmt->execute([
        ':year' => $year,
        ':type' => $type
    ]);
    $id = $stmt->fetchColumn();

    // Ak existuje, vrat ID.
    if ($id) {
        return (int) $id;
    }

    match($type) {
        'LOH', 'ZOH' => null,
        default => throw new InvalidArgumentException("Invalid game type: '$type'. Allowed: LOH, ZOH")
    };

    // Ak neexistuje, vytvor novy zaznam.
    $stmt = $pdo->prepare("INSERT INTO olympic_games (year, order_number, city, type, country_id) VALUES (:year, :order_number, :city, :type, :country_id)");
    $stmt->execute([
        ':year' => $year,
        ':order_number' => $order_number,
        ':city' => $city,
        ':type' => $type,
        ':country_id' => $countryId
    ]);

    // Vrat ID novovytvoreneho zaznamu.
    return (int) $pdo->lastInsertId();
}


// ---------------------------
//ATHLETE_MEDALS TABLE
function getOrCreateAthleteMedals(PDO $pdo, int $athlete_id, int $olympic_games_id, int $discipline_id, int $medal_type_id): int {
    // Najdi AthleteMedals, podla vsetkej unique informacie ktore mame
    $stmt = $pdo->prepare("SELECT id FROM athlete_medals WHERE athlete_id = :athlete_id AND olympic_games_id = :olympic_games_id AND discipline_id = :discipline_id LIMIT 1");
    $stmt->execute([
        ':athlete_id' => $athlete_id,
        ':olympic_games_id' => $olympic_games_id,
        ':discipline_id' => $discipline_id
    ]);
    $id = $stmt->fetchColumn();

    // Ak existuje, vrat ID.
    if ($id) {
        return (int) $id;
    }

    // Ak neexistuje, vytvor novy zaznam.
    $stmt = $pdo->prepare("INSERT INTO athlete_medals (athlete_id, olympic_games_id, discipline_id, medal_type_id) 
    VALUES (:athlete_id, :olympic_games_id, :discipline_id, :medal_type_id)");
    $stmt->execute([
        ':athlete_id' => $athlete_id,
        ':olympic_games_id' => $olympic_games_id,
        ':discipline_id' => $discipline_id,
        ':medal_type_id' => $medal_type_id
    ]);

    // Vrat ID novovytvoreneho zaznamu.
    return (int) $pdo->lastInsertId();
}

// ---------------------------
//DISCIPLINES TABLE
function getOrCreateDisciplines(PDO $pdo, string $name): int {
    // Najprv najdi, ci disciplina s danym nazvom uz existuje.
    $stmt = $pdo->prepare("SELECT id FROM disciplines WHERE name = :name LIMIT 1");
    $stmt->execute([':name' => $name]);
    $id = $stmt->fetchColumn();

    // Ak existuje, vrat jej ID
    if ($id) {
        return (int) $id;
    }

    // Ak neexistuje, vloz novy zaznam a vrat jeho ID.
    $stmt = $pdo->prepare("INSERT INTO disciplines (name) VALUES (:name)");
    $stmt->execute([
        ':name' => $name
    ]);
    return (int) $pdo->lastInsertId();
}

// ---------------------------
//MEDAL_TYPES TABLE
function getOrCreateMedalTypes(PDO $pdo, int $placing): int {
    // Najprv najdi, ci medal s danym placingom uz existuje.
    $stmt = $pdo->prepare("SELECT id FROM medal_types WHERE placing = :placing LIMIT 1");
    $stmt->execute([':placing' => $placing]);
    $id = $stmt->fetchColumn();

    // Ak existuje, vrat jej ID
    if ($id) {
        return (int) $id;
    }

    // Ak neexistuje, vloz novy zaznam a vrat jeho ID.
    $stmt = $pdo->prepare("INSERT INTO medal_types (placing) VALUES (:placing)");
    $stmt->execute([
        ':placing' => $placing
    ]);
    return (int) $pdo->lastInsertId();
}

// ---------------------------
// CSV PARSER CODE
// ---------------------------

function parseCsvToAssocArray(string $filePath, string $delimiter = ","): array
{
    $result = [];

    if (!is_readable($filePath)) {
        throw new RuntimeException("The file $filePath does not exist or is not readable.");
    }

    $handle = fopen($filePath, 'r');
    if(!$handle) {
        throw new RuntimeException("The file wasn't opened properly.");
    }

    $headers = fgetcsv($handle, 0, $delimiter); // Nacitanie hlavicky - prveho riadku suboru. Nazvy v hlavicke sa pouziju ako kluce asoc. pola.
    if($headers === false) {
        fclose($handle);
        throw new RuntimeException("The first title raw does not exist.");
    }

    // Parsovanie riadkov
    while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
        if (count($row) === count($headers)) {
            $result[] = array_combine($headers, $row);
        }
    }

    // Korektne ukoncenie prace so suborom a vratenie spracovanych dat.
    fclose($handle);
    return $result;
}

//helper func bc sql containt another data format
function convertDate(?string $date): ?string {
    if (empty($date)) return null;
    // Parse DD/MM/YYYY and reformat to YYYY-MM-DD
    $d = DateTime::createFromFormat('d/m/Y', $date);
    return $d ? $d->format('Y-m-d') : null;
}

$data = []; // Definicia premennej pre ukladanie obsahu csv

// Ak bol odoslany formular, a vo formulari sa nachadza subor s klucom csv_file, spracujeme ho.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {

    $file = $_FILES['csv_file'];  // Ziskame subor zo superglobal pola
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);  // Zistime pripomu suboru...

    if (strtolower($ext) !== 'csv') {  // ...a skontrolujeme, ci ide o csv subor.
        die("Povolené sú iba CSV súbory.");  // Ak nie, skript sa ukonci.
    }

    if ($file['error'] === 0) {  // Ak bol subor nacitany bez chyby...
        $data = parseCsvToAssocArray($file['tmp_name'], ",");  // ...spracujeme ho pomocou funkcie.

        foreach ($data as $row) {
            $countryId    = getOrCreateCountry($conn, $row['oh_country'], $row['oh_code']);
            $disciplineId = getOrCreateDisciplines($conn, $row['discipline']);
            $medalTypeId  = getOrCreateMedalTypes($conn, (int)$row['placing']);

            $gamesId = getOrCreateGames($conn, 
                (int)$row['oh_year'], 
                (int)$row['oh_order'], 
                $row['oh_city'], 
                $row['oh_type'], 
                $countryId);

            $birthCountryId = getOrCreateCountry($conn, $row['birth_country']);

            $deathCountryId = !empty($row['death_country'])
            ? getOrCreateCountry($conn, $row['death_country'])
            : null;

            $deathDate  = !empty($row['death_day'])   ? convertDate($row['death_day'])   : null;
            $deathPlace = !empty($row['death_place']) ? $row['death_place']              : null;

            $athleteId = getOrCreateAthlete($conn, 
                $row['name'], 
                $row['surname'],
                convertDate($row['birth_day']), 
                $row['birth_place'], 
                $birthCountryId,
                $deathDate, 
                $deathPlace, 
                $deathCountryId);

            getOrCreateAthleteMedals($conn, $athleteId, $gamesId, $disciplineId, $medalTypeId);
        }
    } 
}
?>


<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Olympijské medaily</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Datatables + Bootstrap 5 skin -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>

<?php
    
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true ) {
        // Pouzivatel nie je prihlaseny, zobraz odkazy na prihlasovaci a registracny formular.
        echo '<p>Pre pokračovanie sa prosím <a href="login.php">prihláste</a> alebo sa <a href="register.php">zaregistrujte</a>.</p>';
    } else {
        // Pouzivatel je prihlaseny, zobraz jeho meno a odkazy na zabezpecene stranky.
        echo '<h3>Vitaj ' . $_SESSION['full_name'] . ' </h3>';
        echo '<a href="index.php">Hlavna stránka</a>';
    }

?>

<?php
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
?>

<div class="container mt-4">

    <h2>Slovenské olympijské medaily</h2>

    <!-- ── Upload section ───────────────────────────────────────── -->
    <div class="card mb-4">
        <div class="card-header">Nahrať dáta (CSV)</div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
                <input type="file" name="csv_file" accept=".csv" required class="form-control w-auto">
                <button type="submit" class="btn btn-primary">Nahrať a spracovať</button>
            </form>
        </div>
    </div>

    <!-- ── General table ────────────────────────────────────────── -->
    <table id="general-table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Meno a priezvisko</th>
                <th>Krajina OH</th>
                <th>Rok OH</th>
                <th>Umiestnenie</th>
            </tr>
        </thead>
        <tbody>
            <!-- Datatables fills this via AJAX from data_general.php -->
        </tbody>
    </table>

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#general-table').DataTable({ //initialize DataTable function to general-table found by JQuery
        ajax: {
            url: 'api/data_general.php',
            dataSrc: 'data'
        },
        columns: [
            {
                // Combine first_name + last_name into a clickable link
                // athlete.php?id=X is a dummy link for now — detail page comes later
                data: null,
                render: function (row) {
                    return '<a href="athlete.php?id=' + row.athlete_id + '">'
                         + row.first_name + ' ' + row.last_name
                         + '</a>';
                }
            },
            { data: 'birth_country' },
            { data: 'oh_year' },
            { data: 'placing' }
        ],
        order: [[2, 'asc']],   // default sort by year ascending
        pageLength: 25,
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

<?php
}
?>

</body>
</html>