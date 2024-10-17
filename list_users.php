<?php
    

    require 'autoload.php';

    use App\Repository\UserRepository;

    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    // Liste inacessible pour les non-admin
    if ($_SESSION['admin'] == false) {
        header('Location: index.php');
        exit;
    }

    // Si l'utilisateur est connecté, on récupère son nom
    $userName = $_SESSION['username'];
    $admin = $_SESSION['admin'];

    $userRepo = new UserRepository();
    $users = $userRepo->getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>
</head>
<body>
    <p> Welcome <?php echo $userName ?>
    <h1>Liste des Utilisateurs</h1>
    <?php
        if($admin) {
            echo '<a href="create.php">Créer un Nouvel Utilisateur</a>';
        }
    ?>
    <br/><br/>
    <a href="logout.php">Deconnexion</a>
    <br/><br/>
    <a href="index.php">Liste des articles</a>
    <table border="1">
        <tr>
            <th>Username</th>
            <th>E-mail</th>
            <th>Photo</th>
            <?php
                if($admin) {
                    echo '<th>Actions</th>';
                }
            ?>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><a href="read.php?id=<?= $user->getId() ?>" style="text-decoration:none;"><?= $user->getUsername() ?></a></td>
            <td><a href="read.php?id=<?= $user->getId() ?>" style="text-decoration:none;"><?= $user->getMail() ?></a></td>
            <td><a href="read.php?id=<?= $user->getId() ?>" style="text-decoration:none;"><img src="<?= htmlspecialchars($user->getMediaObject()); ?>" width="50" height="50"></a></td>
            <?php
                if($admin) {
                    echo '<td>
                        <a href="delete.php?id='.$user->getId().'" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cet utilisateur ?\');">Supprimer</a>
                    </td>';
                }
            ?>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
