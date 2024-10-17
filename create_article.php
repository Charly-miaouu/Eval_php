<?php
    require 'autoload.php';

    use App\Entity\Article;
    use App\Repository\ArticleRepository;

    session_start();
    
    $articleRepo = new ArticleRepository();
    $articles = $articleRepo->findAll();

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $userId = $_SESSION['user_id']; // Assurez-vous que l'utilisateur est connecté
        
        // Gestion de l'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $image = $_FILES['image']['name'];
            $imagePath = 'uploads/' . uniqid() . '-' . $image;
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        } else {
            $imagePath = null;
        }

        // Enregistrement dans la base de données
        $article = new Article($userId, $title, $content, $imagePath);
        $articleRepo = new ArticleRepository();
        $articleRepo->create($article);

        header('Location: index.php'); // Redirection vers la liste des articles
    }
?>

<form action="create_article.php" method="POST" enctype="multipart/form-data">
    <label for="title">Titre :</label>
    <input type="text" name="title" required>
    <br>

    <label for="content">Contenu :</label>
    <textarea name="content" required></textarea>
    <br>

    <label for="image">Image :</label>
    <input type="file" name="image" required>
    <br>

    <button type="submit">Ajouter l'article</button>
</form>

