<?php
    require 'autoload.php';
    
    use App\Repository\ArticleRepository;
    use App\Repository\LikeRepository;
    use App\Repository\UserRepository;
    use App\Repository\CommentRepository;
    

    session_start();
    
    $articleRepo = new ArticleRepository();
    $articles = $articleRepo->findAll();
    $likeRepo = new LikeRepository();
    $likes = $likeRepo->getLikesCount($_SESSION['user_id']);
    $article = $articleRepo->find($articleId); 


    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    // Si l'utilisateur est connecté, on récupère son nom
    $userName = $_SESSION['username'];
    $userId = $_SESSION['user_id'];
    $admin = $_SESSION['admin']
?>
    <p> Welcome <?php echo $userName ?>
    <br/>
    <a href="logout.php">Deconnexion</a>
    <br/>
    <a href="read.php?id=<?= $userId ?>">Profil</a>
    <br/>
    <?php if ($admin == true): ?>
        <a href="list_users.php">Liste des utilisateurs</a>
    <?php endif; ?>
    <h1>Liste des articles</h1>
    <br/>
    <a href="create_article.php">Créer un article</a>
    <ul>
    <?php foreach (array_reverse($articles) as $article): ?>
    <li>
        <h2><?= htmlspecialchars($article->getTitle() ?? '') ?></h2>
        <?php if ($article->getImage()): ?>
            <p><strong>Auteur :</strong> <a href="read.php?id=<?= $article->getUserId() ?>" style="text-decoration:none;"><?= htmlspecialchars($article->getAuthorName() /*?? 'Inconnu'*/) ?></a></p>
            <img src="<?= htmlspecialchars($article->getImage() ?? '') ?>" alt="Image de l'article" width="200">
        <?php endif; ?>
        <br/>
        <button>
            <a href="like_article.php?id=<?= $article->getId() ?>" style="text-decoration:none;">
                Like <?= htmlspecialchars($likes ?? 0) ?>
            </a>
        </button>
        <a href="article.php?id=<?= $article->getId() ?>">Lire l'article</a>
    </li>
<?php endforeach; ?>


    </ul>
    