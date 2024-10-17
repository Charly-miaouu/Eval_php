<?php
    session_start();
    require 'autoload.php';

    use App\Repository\UserRepository;
    use App\Repository\ArticleRepository;
    use App\Entity\User;

    $userRepo = new UserRepository();
    $articleRepo = new ArticleRepository();

    if (isset($_GET['id'])) {
        $user = $userRepo->read($_GET['id']);

        if (!($user instanceof User)) {
            header('Location: index.php');
            exit;
        }
    } else {
        header('Location: index.php');
        exit;
    }

    $articles = $articleRepo->findByUserId($user->getId());

    $admin = $_SESSION['admin'];
    $userId = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'Utilisateur</title>
</head>
<body>
    <h1>Détails de l'Utilisateur</h1>
    
    <p><strong>Nom d'utilisateur:</strong> <?= htmlspecialchars($user->getUsername()) ?></p>
    <p><strong>E-mail:</strong> <?= htmlspecialchars($user->getMail()) ?></p>
    <p><strong>Photo de Profil:</strong></p>
    <?php if (!empty($user->getMediaObject())): ?>
        <img src="<?= htmlspecialchars($user->getMediaObject()) ?>" width="100" height="100" alt="Photo de profil">
    <?php else: ?>
        <p>Aucune photo disponible.</p>
    <?php endif; ?>

    <br>
    <?php if ($user->getId() == $userId && !$admin): ?>
        <a href="update.php?id=<?= $user->getId() ?>">Modifier</a>
        <br>
        <a href="delete.php?id=<?= $user->getId() ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');">Supprimer votre compte</a>
    <?php endif; ?>
    <?php if ($admin): ?>
        <?php if ($user->getId() == $userId): ?>
            <a href="update.php?id=<?= $user->getId() ?>">Modifier</a>
            <br>
        <?php endif; ?>
        <a href="delete.php?id=<?= $user->getId() ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer cet Utilisateur</a>
        <br>
    <?php endif; ?>
    <br><br>
    <h2>Vos articles créés :</h2>
    <?php if (!empty($articles)): ?>
        <ul>
            <?php foreach ($articles as $article): ?>
                <li>
                    <a href="article.php?id=<?= $article->getId() ?>">
                        <?= htmlspecialchars($article->getTitle()) ?>
                        <img src="<?= htmlspecialchars($article->getImage()) ?>" alt="Image de l'article" width="200">
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Cet utilisateur n'a pas encore créé d'articles.</p>
    <?php endif; ?>
    <br><br>
    <?php if ($admin == true): ?>
        <a href="list_users.php">Liste des utilisateurs</a>
    <?php endif; ?>
    <a href="index.php">Retour à l'acceuil</a>
</body>
</html>
