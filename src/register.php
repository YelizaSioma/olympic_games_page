<?php
session_start();

// Ak je pouzivatel uz prihlaseny, presmeruj ho
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: restricted.php");
    exit;
}

require_once __DIR__ . '/config.php';  // Pripojenie konfiguracneho suboru s pripojenim na DB
require_once __DIR__ . '/utils.php';  // Externy subor s funkciami isEmpty, userExist a pod...

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
        $stmt = $pdo->prepare("INSERT INTO user_accounts (first_name, last_name, email, password_hash) VALUES (:first_name, :last_name, :email, :password_hash)");

        $pw_hash = password_hash($_POST['password'], PASSWORD_ARGON2ID);

        $stmt->bindParam(":first_name", $_POST['first_name'], PDO::PARAM_STR);
        $stmt->bindParam(":last_name", $_POST['last_name'], PDO::PARAM_STR);
        $stmt->bindParam(":email", $_POST['email'], PDO::PARAM_STR);
        $stmt->bindParam(":password_hash", $pw_hash, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Presmeruj pouzivatela na prihlasenie
            header("location: login.php");
            exit;
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

<form method="post">
    <?php if (!empty($errors)) echo "<p style='color:red;'>" . nl2br(htmlspecialchars($errors)) . "</p>"; ?>
    <label for="firstname">
        Meno:
        <input type="text" name="first_name" value="" id="firstname" placeholder="napr. John">
    </label>

    <label for="lastname">
        Priezvisko:
        <input type="text" name="last_name" value="" id="lastname" placeholder="napr. Doe">
    </label>

    <br>

    <label for="email">
        E-mail:
        <input type="email" name="email" value="" id="email" placeholder="napr. johndoe@example.com">
    </label>

    <label for="password">
        Heslo:
        <input type="password" name="password" value="" id="password">
    </label>
    <label for="password_repeat">
        Heslo znova:
        <input type="password" name="password_repeat" value="" id="password_repeat">
    </label>

    <button type="submit">Vytvoriť konto</button>
</form>

<p>Máte už konto? <a href="login.php">Prihláste sa tu.</a></p>

</html>