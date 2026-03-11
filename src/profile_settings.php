<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/utils.php';

$pdo = connectDatabase($hostname, $database, $username, $password);
$errors = "";
$success = false;

$current_email = $_SESSION['email'] ?? '';

// Load current user row for pre-fill
$stmt = $pdo->prepare("SELECT first_name, last_name, email FROM user_accounts WHERE email = :email LIMIT 1");
$stmt->execute([':email' => $current_email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$first_name = $user ? (string)($user['first_name'] ?? '') : '';
$last_name  = $user ? (string)($user['last_name'] ?? '') : '';
$email      = $user ? (string)($user['email'] ?? $current_email) : $current_email;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isEmpty($_POST['first_name'])) {
        $errors .= "Nevyplnené meno.\n";
    }
    if (isEmpty($_POST['last_name'])) {
        $errors .= "Nevyplnené priezvisko.\n";
    }
    if (isEmpty($_POST['email'])) {
        $errors .= "Nevyplnený e-mail.\n";
    }
    if (!isEmpty($_POST['email']) && !filter_var(test_input($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $errors .= "Neplatný formát e-mailu.\n";
    }
    if (strlen($_POST['first_name'] ?? '') > 64) {
        $errors .= "Meno je príliš dlhé.\n";
    }
    if (strlen($_POST['last_name'] ?? '') > 64) {
        $errors .= "Priezvisko je príliš dlhé.\n";
    }
    if (!preg_match("/^[a-zA-Z-' ]*$/", test_input($_POST["first_name"] ?? ''))) {
        $errors .= "Len písmená a medzery povolené v mene.\n";
    }
    if (!preg_match("/^[a-zA-Z-' ]*$/", test_input($_POST["last_name"] ?? ''))) {
        $errors .= "Len písmená a medzery povolené v priezvisku.\n";
    }
    // If email changed, must not be taken by another user
    $new_email = trim($_POST['email'] ?? '');
    if ($new_email !== $current_email && userExist($pdo, $new_email)) {
        $errors .= "Používateľ s týmto e-mailom už existuje.\n";
    }

    $change_password = !isEmpty($_POST['password'] ?? '');
    if ($change_password) {
        $pw_errors = validatePassword($_POST['password'], $_POST['password_repeat'] ?? '');
        if ($pw_errors !== '') {
            $errors .= $pw_errors;
        }
    }

    if ($errors === '') {
        if ($change_password) {
            $sql = "UPDATE user_accounts SET first_name = :first_name, last_name = :last_name, email = :email, password_hash = :password_hash WHERE email = :current_email";
            $hash = password_hash($_POST['password'], PASSWORD_ARGON2ID);
        } else {
            $sql = "UPDATE user_accounts SET first_name = :first_name, last_name = :last_name, email = :email WHERE email = :current_email";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":first_name", $_POST['first_name'], PDO::PARAM_STR);
        $stmt->bindParam(":last_name", $_POST['last_name'], PDO::PARAM_STR);
        $stmt->bindParam(":email", $new_email, PDO::PARAM_STR);
        $stmt->bindParam(":current_email", $current_email, PDO::PARAM_STR);
        if ($change_password) {
            $stmt->bindParam(":password_hash", $hash, PDO::PARAM_STR);
        }
        if ($stmt->execute()) {
            $success = true;
            $_SESSION['full_name'] = trim($_POST['first_name']) . ' ' . trim($_POST['last_name']);
            $_SESSION['email'] = $new_email;
            $first_name = trim($_POST['first_name']);
            $last_name  = trim($_POST['last_name']);
            $email = $new_email;
            $current_email = $new_email;
        } else {
            $errors = "Chyba pri ukladaní.";
        }
    }
}
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nastavenia profilu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Scope+One&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="assets/styles.css" rel="stylesheet">
</head>
<body>

<nav class="olympic-nav">
  <div class="olympic-nav-inner">
    <a class="olympic-nav-item nav-item-blue" href="index.php">Domov</a>
    <a class="olympic-nav-item nav-item-yellow" href="profile_settings.php">Nastavenia profilu</a>
    <a class="olympic-nav-item nav-item-red" href="logout.php">Odhlásiť sa</a>
  </div>
</nav>

<div class="container py-5">
  <main>
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-lg border-0">
          <div class="card-body p-4 p-md-5">
            <h1 class="page-title-accent mb-3" style="font-size: 1.8em;">Nastavenia profilu</h1>

            <?php if ($errors !== ''): ?>
              <div class="alert alert-danger" role="alert"><?php echo nl2br(htmlspecialchars($errors)); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
              <div class="alert alert-success" role="alert">Uspesne obnovili ste informacie.</div>
            <?php endif; ?>

            <form method="post" action="">
              <div class="mb-3">
                <label for="first_name" class="form-label">Meno</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo htmlspecialchars($first_name ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
              </div>
              <div class="mb-3">
                <label for="last_name" class="form-label">Priezvisko</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo htmlspecialchars($last_name ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Nové heslo (nevyplňujte, ak nemeníte)</label>
                <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
              </div>
              <div class="mb-4">
                <label for="password_repeat" class="form-label">Nové heslo znova</label>
                <input type="password" name="password_repeat" id="password_repeat" class="form-control" autocomplete="new-password">
              </div>
              <button type="submit" class="btn btn-primary w-100">Zmeniť informácie</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="row justify-content-center mt-4">
      <div class="col-12">
        <h2 class="mb-3" style="font-size: 1.4em;">História prihlásení</h2>
        <div class="table-responsive">
          <table id="login-history-table" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Typ prihlásenia</th>
                <th>Dátum a čas</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
  $('#login-history-table').DataTable({
    ajax: { url: 'api/login_history.php', dataSrc: 'data' },
    columns: [
      { data: 'login_type' },
      { data: 'created_at' }
    ],
    order: [[1, 'desc']],
    pageLength: 10,
    language: {
      search: "Hľadať:",
      lengthMenu: "Zobraziť _MENU_ záznamov",
      info: "Zobrazené _START_ až _END_ z _TOTAL_ záznamov",
      paginate: { previous: "Predchádzajúca", next: "Nasledujúca" }
    }
  });
});
</script>
</body>
</html>