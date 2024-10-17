<?php
    require 'autoload.php';

    use App\Repository\ArticleRepository;
    use App\Repository\CommentRepository;

    session_start();

    if (!isset($_GET['id'])) {
        echo "Aucun article spécifié.";
        exit;
    }

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $userId = $_SESSION['user_id'];
    $admin = $_SESSION['admin'] ?? false; // Obtenez le statut d'admin de la session

    $articleId = $_GET['id'];
    $articleRepo = new ArticleRepository();
    $article = $articleRepo->find($articleId);

    if (!$article) {
        echo "L'article n'existe pas.";
        exit;
    }
?>

<h1><?= htmlspecialchars($article->getTitle()) ?></h1>
<p><?= htmlspecialchars($article->getContent()) ?></p>

<?php if ($article->getImage()): ?>
    <img src="<?= htmlspecialchars($article->getImage()) ?>" alt="Image de l'article" width="300">
<?php endif; ?>

<p><strong>Auteur :</strong> <a href="read.php?id=<?= $article->getUserId() ?>" style="text-decoration:none;"><?= htmlspecialchars($article->getAuthorName()) ?></a></p>

<!-- Vérification des droits pour modifier ou supprimer l'article -->
<?php if ($article->getUserId() == $userId && !$admin): ?>
    <a href="update.php?id=<?= $article->getId() ?>">Modifier</a>
    <br>
    <a href="delete_post.php?id=<?= $article->getId() ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">Supprimer cet article</a>
<?php endif; ?>
<?php if ($admin): ?>
    <?php if ($article->getUserId() == $userId): ?>
    <a href="update.php?id=<?= $article->getId() ?>">Modifier</a>
    <br>
    <?php endif; ?>
    <a href="delete_post.php?id=<?= $article->getId() ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">Supprimer cet article</a>
<?php endif; ?>

<?php 
$commentRepo = new CommentRepository();
$comments = $commentRepo->findByArticle($articleId);
?>

<!-- Formulaire d'ajout de commentaire -->
<form action="add_comment.php" method="post">
    <textarea name="content" placeholder="Votre commentaire..." required></textarea>
    <input type="hidden" name="article_id" value="<?= $articleId ?>">
    <button type="submit">Poster le commentaire</button>
</form>

<!-- Affichage des commentaires -->
<?php if (!empty($comments)): ?>
    <?php foreach ($comments as $comment): ?>
        <div class="comment">
            <p><strong>Utilisateur <?= htmlspecialchars((string)($comment['username'] ?? 'Inconnu')) ?> :</strong></p>
            <p><?= htmlspecialchars((string)($comment['content'] ?? 'Commentaire non disponible')) ?></p>
            <p><small>Posté le <?= htmlspecialchars($comment['created_at'] ?? 'Date inconnue') ?></small></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucun commentaire pour cet article.</p>
<?php endif; ?>



<a href="index.php">Retour à la liste des articles</a>
