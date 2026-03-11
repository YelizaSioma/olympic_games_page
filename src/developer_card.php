<?php
session_start();
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>O autorovi</title>
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
      Prihlásenie
    </a>
  </div>
</nav>

<div class="container py-5">
  <main>
    <div class="row justify-content-center g-4">
      <div class="col-12 col-lg-8">
        <section class="card shadow-lg border-0">
          <div class="card-body p-4 p-md-5 d-flex flex-column flex-md-row align-items-center gap-4">
            <div class="flex-shrink-0">
              <img src="assets/developer_card_image.jpg"
                   alt="Autor stránky"
                   class="img-fluid rounded-circle shadow-sm"
                   style="max-width: 180px; height: auto;">
            </div>
            <div>
              <h1 class="page-title-accent mb-3" style="font-size: 2.1em;">
                Ahoj!
              </h1>
              <p class="page-subtitle mb-4" style="font-size: 1.35em;">
                Si sa dostal na stránku Olympic Games.
              </p>
              <p class="mb-3 fs-5">
                Táto stránka bola vytvorená mnou.
              </p>
              <p class="mb-3 fs-5">
                Volám sa <strong>Yelizaveta Siomchanka</strong>.
              </p>
              <p class="mb-1 fs-5"><strong>Študujem:</strong> STU FEI</p>
              <p class="mb-1 fs-5"><strong>Odbor:</strong> Aplikovaná informatika</p>
              <p class="mb-3 fs-5"><strong>Ročník:</strong> 3</p>
              <p class="mb-3 fs-5">
                <strong>Kontakt:</strong>
                <a href="mailto:xsiomchanka@stuba.sk" class="fw-bold fs-5">
                  xsiomchanka@stuba.sk
                </a>
              </p>
              <p class="mb-0 fs-5 text-muted">
                Ak máš nápad alebo si našiel chybu na stránke, neváhaj ma kontaktovať!
              </p>
            </div>
          </div>
        </section>
      </div>
    </div>
  </main>
</div>

</body>
</html>

