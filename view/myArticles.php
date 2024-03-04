<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My blog</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../design/css/defaut.css">
</head>
<body class="content">
    <?php
    session_start();
    require_once("../design/header.php");
    require_once("../connection.php");

    // Traitement de la suppression d'un article
    if (isset($_POST['delete_article'])) {
        $articleIdToDelete = $_POST['id'];
        $deleteQuery = $bdd->prepare("DELETE FROM article WHERE id = ?");
        $deleteQuery->execute([$articleIdToDelete]);
    }

    // Traitement de la modification d'un article
    if (isset($_POST['modifier_article'])) {
        $articleIdToModify = $_POST['id'];
        $nouveauTitre = $_POST['titre_article'];
        $nouveauContenu = $_POST['contenu_article'];

        $updateQuery = $bdd->prepare("UPDATE article SET title = ?, content = ? WHERE id = ?");
        $updateQuery->execute([$nouveauTitre, $nouveauContenu, $articleIdToModify]);

        header("Location: myArticles.php");
        exit();
    }

    // Traitement de l'ajout de commentaire
    if (isset($_POST['ajouter_commentaire'])) {
        $articleId = $_POST['id'];
        $commentaire = $_POST['commentaire'];

        // Vérifier si le même commentaire existe déjà
        $existingCommentQuery = $bdd->prepare("SELECT * FROM comments WHERE comment = ? AND article_id = ?");
        $existingCommentQuery->execute([$commentaire, $articleId]);

        if ($existingCommentQuery->rowCount() == 0) {
            $insertCommentQuery = $bdd->prepare("INSERT INTO comments (comment, article_id) VALUES (?, ?)");
            $insertCommentQuery->execute([$commentaire, $articleId]);
        }
        header("Location: myArticles.php");
        exit();
    }

    // Récupérer les articles de la base de données
    $req = $bdd->query("SELECT * FROM article");
    $articles = $req->fetchAll();

    // Afficher les articles de la base de données
    if (!empty($articles)) {
        foreach ($articles as $dbArticle) {
            echo "<div class='container'>";
            echo "<div class='article-container'>";
            echo "<h3>" . $dbArticle['title'] . "</h3>";
            echo "<p>" . $dbArticle['content'] . "</p>";

            // Boutons de modification et de suppression
            if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == 31) {
                echo "<form method='post' action='myArticles.php'>";
                echo "<input type='hidden' name='id' value='" . $dbArticle['id'] . "' />";
                echo "<input type='submit' class='btn btn-danger mt-2' name='delete_article' value='Supprimer' />";
                echo "</form>";
                
                echo "<form method='post' action='myArticles.php'>";
                echo "<input type='hidden' name='id' value='" . $dbArticle['id'] . "' />";
                echo "<label for='titre_article'>Titre:</label><br>";
                echo "<input type='text' id='titre_article' name='titre_article' value='" . $dbArticle['title'] . "'><br>";
                echo "<label for='contenu_article'>Contenu:</label><br>";
                echo "<textarea id='contenu_article' name='contenu_article'>" . $dbArticle['content'] . "</textarea><br><br>";
                echo "<input type='submit' class='btn btn-primary mt-2' name='modifier_article' value='Modifier' />";
                echo "</form>"; 
            }

            // Formulaire d'ajout de commentaire
            if (isset($_SESSION["user_id"])) {
                echo "<form method='post' action='myArticles.php'>";
                echo "<input type='hidden' name='id' value='" . $dbArticle['id'] . "' />";
                echo "<textarea name='commentaire' class='form-control mt-2' placeholder='Ajouter un commentaire'></textarea>";
                echo "<input type='submit' class='btn btn-success mt-2 w-100' name='ajouter_commentaire' value='Ajouter un commentaire' />";
                echo "</form>";
            }

            // Affichage des commentaires
            echo "<div class='comment-container'>";
            echo "<h5>Commentaires</h5>";
            $commentQuery = $bdd->prepare("SELECT * FROM comments WHERE article_id = ?");
            $commentQuery->execute([$dbArticle['id']]);
            $comments = $commentQuery->fetchAll();
            if (!empty($comments)) {
                foreach ($comments as $comment) {
                    echo "<p>" . $comment['comment'] . "</p>";
                }
            } else {
                echo "<p>Aucun commentaire pour cet article.</p>";
            }
            echo "</div>";

            echo "</div>"; 
            echo "</div>"; 
        }
    }
    ?>

    <footer>
    <a href="#" class="text-decoration-none text-white">Contact</a>
    <a href="#" class="text-decoration-none text-white">Mentions légales</a>
    <a href="#" class="text-decoration-none text-white">À propos</a>
    </footer>
</body>
</html>
