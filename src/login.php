<?php
session_start();
// Ak je pouzivatel uz prihlaseny, presmeruj ho na index alebo restricted. 
// Nie je potrebne znovu vyplnat formular. 
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: restricted.php");
    exit;
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/utils.php';  // Externy subor s funkciami isEmpty, userExist a pod...
require_once 'vendor/autoload.php';

use RobThree\Auth\Providers\Qr\EndroidQrCodeProvider;
use RobThree\Auth\TwoFactorAuth;

// Odkaz na stranku oauth2callback.php, ktora zabezpecuje autentifikaciu Google OAuth
$redirect_uri = "http://localhost:8080/oauth2callback.php";

$pdo = connectDatabase($hostname, $database, $username, $password);

$errors = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Zistime, ktore tlacidlo bolo stlacene
    $action = $_POST['action'] ?? 'login';

    // Ak uzivatel stlacil tlacidlo "Zabudli ste heslo?"
    if ($action === 'forgot_password') {
        header("location: forgot_password.php");
        exit;
    }

    // TODO: Implementovat osetrenie vstupov formulara
    // Validacia zadania e-mailu
    if (isEmpty($_POST['email']) === true) {
        $errors .= "Nevyplnený e-mail.\n";
    }

    // TODO: validacia, zi pouzivatel zadal e-mail v korektnom formate
    if (isEmpty($_POST['email']) === false) {
        if (!filter_var(test_input($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
            $errors .= "Nespravny email format.\n";
        }
    }

    //Check if user entered a password before validating it
    if (isEmpty($_POST['password'])) { $errors .= "Nevyplnené heslo.\n"; }

    if (!empty($errors)) {
        // Errors will be displayed in the HTML below
    } else {
        $sql = "SELECT id, first_name, last_name, email, password_hash, created_at FROM user_accounts WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":email", $_POST["email"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            if ($stmt->rowCount() !== 1) {
                // Zly email alebo pouzivatel neexistuje
                $errors .= "Nesprávny e-mail alebo heslo.\n";
            } else {
                // Pouzivatel existuje, skontroluj, ci zadal spravne heslo
                $row = $stmt->fetch();
                $hashed_password = $row["password_hash"];
            
                if (password_verify($_POST['password'], $hashed_password)) {
                    // Heslo sa zhoduje, skontroluj 2FA    
                    $tfa = new TwoFactorAuth(new EndroidQrCodeProvider());
                    // Treti argument metody verifyCode je tzv. discrepancy - urcuje nasobok casu kolko bude platit generovany kod.
                    // Standardne su kody generovane kazdych 30 sekund a rovnaky cas su aj platne. Tym, ze nastavime 
                    // discrepancy na 2, umoznime zadat generovany 6 ciselny TOTP kod 60 sekund.
                    if ($tfa->verifyCode($row["tfa_secret"], $_POST['totp'], 2)) {
                        // Kod aj heslo sa zhoduju, pouzivatel je autentifikovany   
                        // Do session superglobal uloz potrebne udaje
                        $_SESSION["loggedin"] = true;
                        $_SESSION["full_name"] = $row['first_name'] . " " . $row['last_name'];
                        $_SESSION["email"] = $row['email'];
                        $_SESSION["created_at"] = $row['created_at'];

                        // Pridat zaznam do tabulky login_history
                        $login_history_sql = "INSERT INTO login_history (user_id, login_type, created_at) VALUES (:user_id, 'LOCAL', NOW())";
                        $login_history_stmt = $pdo->prepare($login_history_sql);
                        $login_history_stmt->bindParam(":user_id", $row['id'], PDO::PARAM_INT);
                        $login_history_stmt->execute();

                        // Presmeruj pouzivatela na stranku s obsahom pre prihlasenych
                        header("location: restricted.php");
                        exit;
                    } else {
                        $errors .= "Nesprávne meno alebo heslo.\n";
                    }
                } else {
                    $errors .= "Nesprávne meno alebo heslo.\n";
                }
            }
        } else {
            $errors .= "Chyba prihlásenia.\n";
        }

    unset($stmt);
    }
}

unset($pdo);
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prihlásenie</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Scope+One&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="assets/styles.css" rel="stylesheet">
</head>
<body>
<!-- Zvysok HTML template -->

<nav class="olympic-nav">
  <div class="olympic-nav-inner">
    <a class="olympic-nav-item nav-item-blue" href="index.php">
      Domov
    </a>
    <a class="olympic-nav-item nav-item-yellow" href="developer_card.php">
      Kontakt
    </a>
    <a class="olympic-nav-item nav-item-red" href="register.php">
      Registrácia
    </a>
  </div>
</nav>

<div class="container py-5">
  <main>
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-lg">
          <div class="card-body p-4 p-md-5">
            <h1 class="mb-1 text-center page-title-accent">Prihlásenie</h1>
            <p class="text-center text-muted mb-4">Vstúpte do aplikácie Olympijské medaily</p>

            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger" role="alert">
                <?php echo nl2br(htmlspecialchars($errors)); ?>
              </div>
            <?php endif; ?>

            <form action="" method="post">
              <div class="mb-3">
                <label for="email" class="form-label">E‑mail</label>
                <input type="email" name="email" id="email" class="form-control" required maxlength="128">
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Heslo</label>
                <input type="password" name="password" id="password" class="form-control" required>
              </div>

              <div class="mb-4">
                <label for="totp" class="form-label">TOTP kód</label>
                <input type="text" name="totp" id="totp" class="form-control" placeholder="6‑miestny kód z aplikácie" required>
              </div>

              <button type="submit" name="action" value="login" class="btn btn-primary w-100 mb-2">
                Prihlásiť sa
              </button>

              <button type="submit" name="action" value="forgot_password" class="btn btn-outline-dark w-100 mb-3">
                Zabudli ste heslo?
              </button>

              <p class="text-center mb-2">
                Alebo sa prihláste pomocou
                <a href="<?php echo filter_var($redirect_uri, FILTER_SANITIZE_URL) ?>" class="fw-bold fs-5">Google konta</a>
              </p>

              <p class="text-center mb-0">
                Nemáte vytvorené konto?
                <a href="register.php" class="fw-bold fs-5">Zaregistrujte sa tu.</a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

</body>
</html>