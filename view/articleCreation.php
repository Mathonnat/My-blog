<?php 
    session_start();
    require_once("../design/header.php"); 
?>
    <div class="text-center text-warning">

<?php   // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] != 31) {
        echo "Vous n'avez pas les permissions nécessaires pour ajouter un article.";
        exit;
    }
?>
</div>
<?php    
    if (!empty($_POST["titre_article"]) && !empty($_POST["contenu_article"])) {

        // Connexion à la base de données
        require_once("../connection.php");

        // Les variables 
        $titre       = ($_POST["titre_article"]);
        $contenu     = ($_POST["contenu_article"]);

        // Ajouter un article
        $req = $bdd->prepare("INSERT INTO article(title, content) VALUES(?, ?)");
        $req->execute([$titre, $contenu]);

        // Récupérer l'article
        $req = $bdd->prepare("SELECT * FROM article WHERE title = ?");
        $req->execute([$titre]);
        $article = $req->fetch();

        // Stocker les données dans une session
        if (!isset($_SESSION['articles'])) {
            $_SESSION['articles'] = array();
        }

        $_SESSION['articles'][] = $article;
        header("Location: myArticles.php");
        exit;
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
    <!-- Création d'article -->
    <div class="container d-flex align-item-center mt-4">
        <form class="m-auto" method="post" action="articleCreation.php">
            <input type="text" name="titre_article" class="form-control" placeholder="Nom du titre"><br><br>
            <textarea id="contenu_article" class="form-control" name="contenu_article" rows="15" cols="20" placeholder="Contenu d'article" ></textarea> <br>
            <div class="text-center">
            <button type="submit">Envoyer</button>   
            </div>
        </form>
    </div>

    <footer>
    <a href="#" class="text-decoration-none text-white">Contact</a>
    <a href="#" class="text-decoration-none text-white">Mentions légales</a>
    <a href="#" class="text-decoration-none text-white">À propos</a>
    </footer>
</body>
</html>

