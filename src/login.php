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
        $errors .= "Nespravne udaje.\n";
    }

    // TODO: validacia, zi pouzivatel zadal e-mail v korektnom formate
    if (isEmpty($_POST['email']) === false) {
        if (!filter_var(test_input($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
            $errors .= "Invalid email format";
        }
    }

    //Check if user entered a password before validating it
    if (isEmpty($_POST['password'])) { $errors .= "Heslo je povinné.\n"; }

    if (!empty($errors)) {
        // Errors will be displayed in the HTML below
    } else {
        $sql = "SELECT id, first_name, last_name, email, password_hash, created_at FROM user_accounts WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":email", $_POST["email"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                // Pouzivatel existuje, skontroluj, ci zadal spravne heslo
                $row = $stmt->fetch();
                $hashed_password = $row["password_hash"];
            
            if (password_verify($_POST['password'], $hashed_password)) {
                    // Heslo sa zhoduje, pouzivatel je autentifikovany
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
                } else {
                    $errors = "Nesprávne meno alebo heslo.";
                }
            } else {
                $errors = "Nesprávne meno alebo heslo.";
            }
        } else {
            $errors = "Chyba prihlásenia";
        }

    unset($stmt);
    }
}

unset($pdo);
?>

<!doctype html>
<html lang="sk">

<!-- Zvysok HTML template -->

<main>
    <?php if (!empty($errors)) echo "<p style='color:red;'>" . nl2br($errors) . "</p>"; ?>
    <form action="" method="post">

        <label for="email">
            E-Mail:
            <input type="text" name="email" value="" id="email" required>
        </label>
        <br>
        <label for="password">
            Heslo:
            <input type="password" name="password" value="" id="password" required>
        </label>

        <button type="submit" name="action" value="login">Prihlásiť sa</button>
        <button type="submit" name="action" value="forgot_password">Zabudli ste heslo?</button>

    </form>
    <p>Nemáte vytvorené konto? <a href="register.php">Zaregistrujte sa tu.</a></p>
</main>
</body>
</html>