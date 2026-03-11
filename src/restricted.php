<?php

session_start(); 

require_once 'vendor/autoload.php';

use Google\Client;

$client = new Client();
// Zavolanie funkcie setAuthConfig pre nastavenie cesty s autorizacnymi udajmi OAuth klienta 
// ktore sa nachadzaju v client_secret.json subore. Subor je mozne stiahnut z Google Cloud konzoly.
$client->setAuthConfig(__DIR__ . '/client_secret.json');

// Pouzivatel nam dal povolenie k udajom a pristupovy token sme ulozili do session
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);

    // Nacitanie udajov z Google uctu pouzivatela cez triedu Google OAuth 2.0.
    $oauth = new Google\Service\Oauth2($client);
    $account_info = $oauth->userinfo->get();
    
    $_SESSION['full_name'] = $account_info->name;
    $_SESSION['gid'] = $account_info->id;
    $_SESSION['email'] = $account_info->email;
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ochranná stránka</title>
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

<div class="container py-5">
<main>
    <div class="row justify-content-center g-4">
        <div class="col-12 col-lg-8">
            <section class="card shadow-lg border-0">
                <div class="card-body p-4 p-md-5">
                    <h1 class="page-title-accent mb-3" style="font-size: 2.1em;">
                        Vitaj späť, <?php echo htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8'); ?>
                    </h1>
                    <p class="page-subtitle mb-4" style="font-size: 1.35em;">
                        Ste prihlásený do aplikácie Olympijské hry.
                    </p>
                    <p class="mb-3 fs-5">
                        <strong>e‑mail:</strong> <?php echo htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                    <p class="mb-0 fs-6 text-muted">
                        Tu môžete bezpečne pracovať so svojimi údajmi a prezerať štatistiky.
                    </p>
                </div>
            </section>
        </div>

        <div class="col-12 col-lg-8">
            <section class="card shadow-lg border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="page-title-accent mb-3" style="display:inline-block; font-size: 1.2em;">
                        Navigácia
                    </div>
                    <p class="mb-0 fs-5">
                        <a href="logout.php" class="fw-bold fs-4">Odhlásenie</a>
                        alebo
                        <a href="index.php" class="fw-bold fs-4">Úvodná stránka</a>
                    </p>
                </div>
            </section>
        </div>
    </div>
</main>
</div>

</body>
</html>