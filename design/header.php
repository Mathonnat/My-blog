<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../design/css/header.css">
    <title>Mon Blog</title>
</head>
<body>
<header>
    <div class="d-flex justify-content-center ">
      <nav class="navbar navbar-expand-lg navbar-dark">
        <div id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li>
              <a class="nav-link" href="../index.php">My Blog</a>
            </li>

            <?php
                    // Vérifier si l'utilisateur est connecté
                    if (isset($_SESSION["connect"]) && $_SESSION["connect"] == 1 && isset($_SESSION["user_id"]) && $_SESSION["user_id"] == 31) {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../view/articleCreation.php">Créer un article</a>
                        </li>
                        <?php
                    }
                    ?>

            <li class="nav-item">
              <a class="nav-link" href="../view/myArticles.php">Mes articles</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../view/connexion.php">Se connecter</a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </header>
</body>
</html>