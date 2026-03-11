<?php
session_start();

// Ak je pouzivatel uz prihlaseny, presmeruj ho
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: restricted.php");
    exit;
}

require_once __DIR__ . '/config.php';  // Pripojenie konfiguracneho suboru s pripojenim na DB
require_once __DIR__ . '/utils.php';  // Externy subor s funkciami isEmpty, userExist a pod...

require_once 'vendor/autoload.php';  // Nacitanie kniznice

use RobThree\Auth\Providers\Qr\BaconQrCodeProvider;  // Cesta pre triedu providera generatora QR kodu.
use RobThree\Auth\TwoFactorAuth;  // Cesta pre triedu generovania 2FA kodu.

$pdo = connectDatabase($hostname, $database, $username, $password);

$errors = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    // Ak bol odoslany formular - tzn. bol urobeny HTTP POST Request na tento skript...
    
    // Validacia zadania e-mailu
    if (isEmpty($_POST['email']) === true) {
        $errors .= "Nevyplnený e-mail.\n";
    }

    // TODO: validacia, zi pouzivatel zadal e-mail v korektnom formate
    if (isEmpty($_POST['email']) === false) {
        if (!filter_var(test_input($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
            $errors .= "Nespravny email format";
        }
    }

    // Validacia, ci pouzivatel v DB existuje - kontrolujeme stlpec e-mail, ktory sme si zadali ako UNIQUE.
    if (userExist($pdo, $_POST['email']) === true) {
        $errors .= "Používateľ s týmto e-mailom už existuje.\n";
    }

    // Valiadacia zadania mena a priezviska
    if (isEmpty($_POST['first_name']) === true) {
        $errors .= "Nevyplnené meno.\n";
    } elseif (isEmpty($_POST['last_name']) === true) {
        $errors .= "Nevyplnené priezvisko.\n";
    }

    // TODO: Implementujte validaciu dlzky mena a priezviska na zaklade dlzky, ktoru ste definovali pre stlpce v DB
    if(strlen($_POST['first_name']) > 64){
        $errors .= "Meno pilis dhe.\n";
    } elseif (strlen($_POST['last_name']) > 64){
        $errors .= "Priezvisko pilis dhe.\n";
    }

    // TODO: Implementujte validaciu, ci meno a priezvisko obsahuje iba povolene znaky
    // should contain letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",test_input($_POST["first_name"]))) {
      $errors .= "Len pismena a medzery povolene v mene.\n";
    }
    if (!preg_match("/^[a-zA-Z-' ]*$/",test_input($_POST["last_name"]))) {
    $errors .= "Len pismena a medzery povolene v priezvisku.\n";
    }

    // Validacia hesla
    $password_errors = validatePassword($_POST['password'], $_POST['password_repeat']);
    if (!empty($password_errors)) {
        $errors .= $password_errors;
    }

    // TODO: Osetrite a validujte vstupy pouzivatela
    // Vypisat error aky uzivatelu

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO user_accounts (first_name, last_name, email, password_hash, tfa_secret) VALUES (:first_name, :last_name, :email, :password_hash, :tfa_secret)");

        $pw_hash = password_hash($_POST['password'], PASSWORD_ARGON2ID);

        // Vytvorenie 2FA kodu a konstruktora kniznice pre QR kod.
        // Pripadne zmeny alebo personalizaciu pozri: https://robthree.github.io/TwoFactorAuth/
        $tfa = new TwoFactorAuth(new BaconQrCodeProvider(4, '#ffffff', '#000000', 'svg'));
        $user_secret = $tfa->createSecret(); // Vygenerovanie kodu, ktory sa ulozi do databazy.
        // Vygenerovanie QR kodu pre naskenovanie mob. aplikaciou pre TOTP, napr. Google Authenticator a pod.
        $qr_code = $tfa->getQRCodeImageAsDataUri('Olympic Games APP', $user_secret);  

        $stmt->bindParam(":first_name", $_POST['first_name'], PDO::PARAM_STR);
        $stmt->bindParam(":last_name", $_POST['last_name'], PDO::PARAM_STR);
        $stmt->bindParam(":email", $_POST['email'], PDO::PARAM_STR);
        $stmt->bindParam(":password_hash", $pw_hash, PDO::PARAM_STR);
        $stmt->bindParam(":tfa_secret", $user_secret, PDO::PARAM_STR);

        if ($stmt->execute()) {
        } else {
            $errors = "Chyba pri registracii.";
        }

        unset($stmt);
    }
    unset($pdo);
}
?>

<!doctype html>
<html lang="sk">
<!-- Zvysok HTML template -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrácia</title>
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
    <a class="olympic-nav-item nav-item-yellow" href="developer_card.php">
      Kontakt
    </a>
    <a class="olympic-nav-item nav-item-red" href="login.php">
      Prihlásiť sa
    </a>
  </div>
</nav>

<div class="container py-5">
  <main>
    <div class="row justify-content-center">
      <div class="col-12 col-md-9 col-lg-7">
        <div class="card shadow-lg">
          <div class="card-body p-4 p-md-5">
            <h1 class="mb-1 text-center page-title-accent">Registrácia</h1>
            <p class="text-center text-muted mb-4">Vytvorte si účet pre prístup k olympijským štatistikám</p>

            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger" role="alert">
                <?php echo nl2br(htmlspecialchars($errors)); ?>
              </div>
            <?php endif; ?>

            <form method="post">
              <div class="row g-3 mb-2">
                <div class="col-12 col-sm-6">
                  <label for="firstname" class="form-label">Meno</label>
                  <input type="text" name="first_name" id="firstname" class="form-control" placeholder="napr. John">
                </div>
                <div class="col-12 col-sm-6">
                  <label for="lastname" class="form-label">Priezvisko</label>
                  <input type="text" name="last_name" id="lastname" class="form-control" placeholder="napr. Doe">
                </div>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">E‑mail</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="napr. johndoe@example.com">
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Heslo</label>
                <input type="password" name="password" id="password" class="form-control">
              </div>

              <div class="mb-4">
                <label for="password_repeat" class="form-label">Heslo znova</label>
                <input type="password" name="password_repeat" id="password_repeat" class="form-control">
              </div>

              <button type="submit" class="btn btn-primary w-100 mb-3">
                Vytvoriť konto
              </button>

              <p class="text-center mb-0">
                Máte už konto?
                <a href="login.php" class="fw-bold fs-5">Prihláste sa tu.</a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>

<?php
if (isset($qr_code)) {
    // Ak sme po uspesnej registracii vygenerovali QR kod, zobrazime ho na stranke v stylovanom bloku
    ?>
    <div class="mt-4">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">
          <h2 class="page-title-accent mb-3" style="font-size: 1.6em;">
            Nastavte si 2FA ochranu
          </h2>
          <p class="mb-2 fs-5">
            Zadajte kód:
            <strong><?php echo htmlspecialchars($user_secret, ENT_QUOTES, 'UTF-8'); ?></strong>
            do aplikácie pre 2FA.
          </p>
          <p class="mb-3 fs-5">
            alebo naskenujte QR kód:
          </p>
          <div class="mb-3">
            <img src="<?php echo $qr_code; ?>" alt="QR kód pre aplikáciu authenticator" class="img-fluid">
          </div>
          <p class="mb-0 fs-5">
            Teraz sa môžete prihlásiť:
            <a href="login.php" class="fw-bold fs-5">Login stránka</a>
          </p>
        </div>
      </div>
    </div>
    <?php
}
?>
</main>
</div>
</body>
</html>