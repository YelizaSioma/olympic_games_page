<?php
session_start();
// Ak je pouzivatel uz prihlaseny, presmeruj ho na index alebo restricted. 
// Nie je potrebne znovu vyplnat formular. 
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: restricted.php");
    exit;
}

require_once __DIR__ . '/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // TODO: Implementovat osetrenie vstupov formulara

    $sql = "SELECT id, first_name, last_name, email, password_hash, created_at FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":email", $_POST["email"], PDO::PARAM_STR);
    $errors = "";

    if ($stmt->execute()) {
        if ($stmt->rowCount() == 1) {
            // Pouzivatel existuje, skontroluj, ci zadal spravne heslo
            $row = $stmt->fetch();
            $hashed_password = $row["password"];
            
            if (password_verify($_POST['password'], $hashed_password)) {
                    // Heslo sa zhoduje, pouzivatel je autentifikovany
                    // Do session superglobal uloz potrebne udaje
                    $_SESSION["loggedin"] = true;
                    $_SESSION["full_name"] = $row['first_name'] . " " . $row['last_name'];
                    $_SESSION["email"] = $row['email'];
                    $_SESSION["created_at"] = $row['created_at'];


                    // TODO: Implementujte funkciu pre pridanie zaznamu o prihlaseni do tabulky login_history
                    // kedze pozname aj ID pouzivatela, sposob prihlasenia je LOCAL, cas a datum prihlasenia sa nam vytvori automaticky.


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
    unset($pdo);
}
?>

<!doctype html>
<html lang="sk">

<!-- Zvysok HTML template -->

<main>
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

        <button type="submit">Prihlásiť sa</button>

        <!-- TODO: Implementacia funkcionality "zabudol som"/"resetovať heslo" -->

    </form>
    <p>Nemáte vytvorené konto? <a href="register.php">Zaregistrujte sa tu.</a></p>
</main>
</body>
</html>