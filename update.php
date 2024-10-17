<?php
require 'autoload.php';

session_start();

use App\Entity\User;
use App\Repository\UserRepository;

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$admin = $_SESSION['admin'];
$userId = $_SESSION['user_id'];

// Si l'utilisateur est connecté, on récupère son nom
$userName = $_SESSION['username'];

$userRepo = new UserRepository();
$user = null;

if (isset($_GET['id'])) {
    $user = $userRepo->read($_GET['id']);
}

//Verifie que l'utilisateur est admin
if (!$admin && $userId !== $user->getId()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $mail = $_POST['mail'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];

    function contientUniquementDesChiffres($chaine) {
        return ctype_digit($chaine);
    }

    if (empty($name)) {
        $errors[] = "Le nom d'utilisateur est obligatoire.";
    } elseif (contientUniquementDesChiffres($name)) {
        $errors[] = "Le nom d'utilisateur ne doit pas contenir uniquement des chiffres.";
    }

    // Validation de l'email
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }

    if (!empty($password) && !empty($confirm_password)){
        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }
    
        if ($password !== $confirm_password) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $photo = $_FILES['photo']['name'];
        $tailleFichier = $_FILES['photo']['size'];
        $typeFichier = $_FILES['photo']['type'];
        $target_dir = "uploads/";

        if ($tailleFichier > 25 * 1024 * 1024) {
            $errors[] = "Le fichier est trop grand. Maximum 25 Mo.";
        }

        $typesAutorises = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($typeFichier, $typesAutorises)) {
            $errors[] = "Seuls les fichiers JPG, PNG et GIF sont autorisés.";
        }

        $nouveauNomFichier = uniqid() . '-' . $photo;
        $target_file = $target_dir . $nouveauNomFichier;

        if (!empty($user->getMediaObject()) && file_exists($user->getMediaObject())) {
            unlink($user->getMediaObject());
        }

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $errors[] = "Erreur lors du téléchargement du fichier.";
        }
    } else {
        $target_file = $user->getMediaObject();
    }

    if (empty($errors)) {
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
        } else {
            $password_hash = $user->getPassword();
        }

        // Créer l'objet utilisateur avec les données mises à jour
        $updatedUser = new User($name, $mail, $password_hash, $target_file, $user->getId());

        try {
            $userRepo->update($updatedUser);
            header('Location: index.php');
            exit;
        } catch(Exception $e) {
            echo "Erreur: " . $e->getMessage();
        }
    } else {
        echo "<h2>Des erreurs ont été trouvées :</h2>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
    }
}

if ($user === null) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Utilisateur</title>
</head>
<body>
    <h1>Modifier un Utilisateur</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $user->getId() ?>">
        <label for="name">Nom:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user->getUsername()) ?>" required>
        <br>
        <label for="mail">E-mail:</label>
        <input type="email" id="mail" name="mail" value="<?= htmlspecialchars($user->getMail()) ?>" required>
        <br>
        <label>Nouveau mot de passe (optionnel):</label>
        <input type="password" name="password"><br>
        <label>Confirmez votre mot de passe:</label>
        <input type="password" name="confirm_password">
        <br>
        <label>Changer la photo de profil (optionnel):</label>
        <input type="file" name="photo"><br>
        <button type="submit" name="modification">Modifier l'utilisateur</button>
    </form>
    <br/><br/>
    <a href="index.php">Retour à la liste des utilisateurs</a>
</body>
</html>
