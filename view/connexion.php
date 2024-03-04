<?php
session_start();
require_once("../design/header.php");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../design/css/defaut.css">
    <title>Mon blog</title>
</head>

<body>
    <div class="containerA">
        <div class="containerLogout">
        <?php
            if (isset($_SESSION["connect"]) && $_SESSION["connect"] == 1) {
                echo "Vous êtes bien connecté avec le pseudo " . $_SESSION["pseudo"];
            ?>
            <!-- Formulaire de déconnexion -->
            <form method="post" action="logout.php">
                <button class="btn btn-primaryu border-0" type="submit">Déconnexion</button>
            </form>
            <?php
            exit();
        }
        ?>
        </div>
    </div>
        
    <?php
    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
        // Connexion à la base de données
        require_once("../connection.php");
    
        // Récupérer les données du formulaire
        $email = htmlspecialchars($_POST["email"]);
        $password = $_POST["password"];
    
        // Vérifier si l'adresse email est valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("location: connexion.php?error=1&message=Votre adresse email est invalide");
            exit();
        }
    
        // Récupérer les données de l'utilisateur à partir de l'email
        $req = $bdd->prepare("SELECT * FROM user WHERE email = ?");
        $req->execute([$email]);
        $user = $req->fetch();
    
        // Vérifier si un utilisateur correspondant à l'email existe
        if (!$user) {
            header("location: connexion.php?error=1&message=Impossible de vous authentifier");
            exit();
        }
        // Vérifier si le mot de passe correspond
        if (password_verify($password, $user["password"])) {
            
            // Authentification réussie
            $_SESSION["connect"] = 1;
            $_SESSION["pseudo"] = $user["pseudo"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["user_id"] = $user["idd"];
            header("location: connexion.php?success=1");
            exit();
        } else {
            // Mot de passe incorrect
            header("location: connexion.php?error=1&message=Mot de passe incorrect");
            exit();
        }
    }
    ?>

<div class="containerA">
    <div class="formulaire">
        <section class="connect">
            <h1>Se connecter !</h1>
            <form method="post" action="connexion.php">
                <input type="email" name="email" class="form-control mt-2" placeholder="Entrez votre email">
                <input type="password" name="password" class="form-control mt-2" placeholder="Entrez votre mot de passe">
                <button type="submit" class="btn">Connexion</button>
                <a class="buttonn mt-2" href="registration.php">S'inscrire</a>
            </form>
        </section>
    </div>
</div>>

    <footer class="fixed-bottom">
        <a href="#" class="text-decoration-none text-white">Contact</a>
        <a href="#" class="text-decoration-none text-white">Mentions légales</a>
        <a href="#" class="text-decoration-none text-white">À propos</a>
    </footer>
</body>

</html>
