<?php
session_start();
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

<div class="container mt-4">
    <main>
    <h1 class="page-title-accent mb-2" style="font-size: 2.1em;">
        Vitaj <?php echo htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <p class="mb-4 fs-5">
        <strong>e‑mail:</strong> <?php echo htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8'); ?>
    </p>

    <h3 class="mb-3" style="font-size: 1.8em;">Chces zmenit svoje informácie?</h3>
    
    <form action="" method="update_profile">
        <div class="mb-3">
        <label for="email" class="form-label">E‑mail</label>
        <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
        <label for="password" class="form-label">Heslo</label>
        <input type="password" name="password" id="password" class="form-control" required>
        </div>


        <button type="submit" name="action" value="update_profile" class="btn btn-primary w-100 mb-2">              
            Zmeniť informácie
        </button>

        <button type="submit" name="action" value="forgot_password" class="btn btn-outline-dark w-100 mb-3">
        Zabudli ste heslo?
        </button>
    </form>

    </main>
</div>
</body>
</html>