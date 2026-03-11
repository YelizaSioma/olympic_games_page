<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use Google\Client;
use Google\Service\Oauth2;

$client = new Client();

// Povinne, zavolanie funkcie setAuthConfig pre nastavenie cesty s autorizacnymi udajmi OAuth klienta 
// ktore sa nachadzaju v client_secret.json subore. Subor je mozne stiahnut z Google Cloud konzoly.
$client->setAuthConfig(__DIR__ . '/client_secret.json');
$redirect_uri = "http://localhost:8080/oauth2callback.php"; // Zadajte URI pre presmerovanie z OAuth2. Musi suhlasit s URI zadanym v Google Cloud konzole.
$client->setRedirectUri($redirect_uri);

// Povinne, zavolanie funkcie addScope pre ziskanie pozadovaneho rozsahu udajov.
// Mame pravo len na udaje, ktore sme povolili v konfiguracii klienta v Google konzole.
// Scopes definuju uroven pristupu a rozsahu udajov, ktore aplikacia pozaduje od Google.
$client->addScope(["email", "profile"]);
// Povolenie inkrementalnej autorizacie. Odporucane ako best practice.
$client->setIncludeGrantedScopes(true);

// Odporucane, offline pristup nam poskytne acces token a refresh token, ktore vieme pouzit 
// na obnovenie pristupu aj bez nutnej interakcie a zasahu pouzivatela.
$client->setAccessType("offline");

// Vygenerovanie URL pre autorizaciu, pokial neobsahuje uz autorizacny kod alebo chybovu hlasku
if (!isset($_GET['code']) && !isset($_GET['error'])) {
    // Generovanie a nastavenie state premennej
    $state = bin2hex(random_bytes(16));
    $client->setState($state);
    $_SESSION['state'] = $state;

    // Generovanie URL, ktora vyziada od pouzivatela opravnenie na poskytnutie udajov.
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
}

// Pouzivatel autorizoval poziadavku a bol nam vrateny autorizacnykod na výmenu za pristupovy token a obnovovaci token.  
// Ak parameter state nie je nastavený alebo sa nezhoduje s parametrom state v autorizacnej poziadavke,
// je mozne, ze poziadavku vytvorila tretia strana a pouzivatel bude presmerovaný na URL s chybovou správou.  
// Ak bola autorizacia uspesna, URI odpovede bude obsahovat autorizacny kod.
if (isset($_GET['code'])) {
    // Skontroluj hodnotu state.
    if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['state']) {
        die('State mismatch. Possible CSRF attack.');
    }

    // Ziskaj pristupovy a obnovovaci token (ak access_type je nasteveny na offline)
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    // Ulozenie pristupoveho a obnovovacieho tokenu do session
    // TODO: Na produkcnom prostredi by sme si to mali ulozit do nejakeho perzistentneho uloziska, napr. databaza
    $_SESSION['access_token'] = $token;
    $_SESSION['refresh_token'] = $client->getRefreshToken();

    $_SESSION['loggedin'] = true;  // Pouzivatel je autentifikovany a teda prihlaseny, nastav premennu session.

    // TODO: na tomto mieste je potrebne ulozit informaciu o prihlaseni pouzivatela do databazy. Typ bude OAUTH.
    // Najprv ziskame email z Google (prostrednictvom OAuth2 service).
    $email = null;
    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        // Nacitanie udajov z Google uctu pouzivatela cez triedu Google OAuth 2.0.
        $client->setAccessToken($token);
        $oauth2 = new Google\Service\Oauth2($client);

        $googleUser = $oauth2->userinfo->get();
        $email = $googleUser->getEmail();
        $firstName = $googleUser->getGivenName();
        $lastName = $googleUser->getFamilyName();
    }

    // Najdeme uzivatela podla emailu; ak neexistuje, vytvorime ho (email je UQ). Inak nebudeme moct ulozit do login_history ak nemame user_id validne
    $pdo = connectDatabase($hostname, $database, $username, $password);
    if ($pdo && $email) {
        $stmt = $pdo->prepare("SELECT id FROM user_accounts WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $userId = $stmt->fetchColumn();

        if (!$userId) {
            $insert = $pdo->prepare("INSERT INTO user_accounts (email, first_name, last_name) VALUES (:email, :first_name, :last_name)");
            $insert->bindParam(':email', $email, PDO::PARAM_STR);
            $insert->bindParam(':first_name', $firstName, PDO::PARAM_STR);
            $insert->bindParam(':last_name', $lastName, PDO::PARAM_STR);
            $insert->execute();
            $userId = $pdo->lastInsertId();
        }

        $log = $pdo->prepare("INSERT INTO login_history (user_id, login_type, created_at) VALUES (:user_id, 'OAUTH', NOW())");
        $log->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $log->execute();

        unset($pdo);
    }

    $redirect_uri = 'restricted.php'; // Presmerovanie na zabezpecenu stranku alebo index.
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    exit;
}
// Ak nam Google server vratil error, zobrazime chybu na stranke - pouzivatel nie je autentifikovany
if (isset($_GET['error'])) {
    echo "Error: " . $_GET['error'];
}