<?php 
require_once("../design/header.php"); 

if(!empty($_POST["pseudo"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
    
    // Connexion à la bdd
    require_once("../connection.php");

    // Les variables
    $pseudo     = htmlspecialchars($_POST["pseudo"]);
    $email      = htmlspecialchars($_POST["email"]);
    $password   = $_POST["password"];

    // Hasher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // L'adresse email est-elle correcte ?
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("location: registration.php?error=1&message=Votre adresse email est invalide");
        exit();
    }

    // L'adresse email est-elle en doublon ?
    $req = $bdd->prepare("SELECT COUNT(*) as numberEmail FROM user WHERE email = ?");
    $req->execute([$email]);

    while($emailVerification = $req->fetch()) {
        if($emailVerification["numberEmail"] != 0)  {
            header("location: registration.php?error=1&message=Votre adresse email est invalide");
            exit();
        }
    }

    // Ajouter un utilisateur
    $req = $bdd->prepare("INSERT INTO user(pseudo, email, password) VALUES(?, ?, ?)");
    $req->execute([$pseudo, $email, $hashedPassword]);

    header("location: registration.php?success=1");
}
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
<div class="formulaire">
    <section class="connect">
        <h1>S'inscrire !</h1>
            <form method="post" action="registration.php">
                <input type="pseudo" name="pseudo" class="form-control mt-2" placeholder="Entrez votre pseudo">
                <input type="email" name="email" class="form-control mt-2" placeholder="Entrez votre email">
                <input type="password" name="password" class="form-control mt-2" placeholder="Entrez votre mot de passe">
                <button type="submit" class="btn btn-primary">S'inscrire</button>
                <a id="connexion" href="connexion.php">Se connecter</a>
            </form>
    </section>
</div>

<footer>
    <a href="#" class="text-decoration-none text-white">Contact</a>
    <a href="#" class="text-decoration-none text-white">Mentions légales</a>
    <a href="#" class="text-decoration-none text-white">À propos</a>
    </footer>
   
</body>
</html>