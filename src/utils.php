<?php

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function isEmpty($data) {
    return empty(trim($data));
}

function userExist($pdo, $email) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_accounts WHERE email = :email");
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function validatePassword($password, $password_repeat) {
    if (isEmpty($password)) {
        return "Nevyplnené heslo.\n";
    }
    if (strcmp($password, $password_repeat) != 0) {
        return "Nespravne zopakovali ste heslo.\n";
    }
    return "";
}

?>
