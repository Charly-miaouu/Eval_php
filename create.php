<?php
    session_start();
    require 'autoload.php';

    use App\Entity\User;
    use App\Repository\UserRepository;

    if (!isset($_SESSION['user_id'])) {
        $connected = false;
    } else {
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header('Location: index.php');
            exit;
        }
        $connected = true;
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
        $userRepository = new UserRepository();
        if (($userRepository->findByEmail($mail)) !== null) {
            $errors[] = "L'email correspond déjà à un compte.";
        }
        // ----------------------

        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }

        if ($password !== $confirm_password) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        // Gestion de l'upload de la photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $photo = $_FILES['photo']['name'];
            $tailleFichier = $_FILES['photo']['size'];
            $typeFichier = $_FILES['photo']['type'];
            $target_dir = "uploads/";

            if ($tailleFichier > 25 * 1024 * 1024) {
                $errors[] = "Le fichier est trop grand. Maximum 25 Mo.";
                exit;
            }

            // Autoriser uniquement certains types de fichiers (ex. images)
            $typesAutorises = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($typeFichier, $typesAutorises)) {
                $errors[] = "Seuls les fichiers JPG, PNG et GIF sont autorisés.";
                exit;
            }

            // Créer un nom unique pour éviter les conflits de fichiers
            $nouveauNomFichier = uniqid() . '-' . $photo;
            $target_file = $target_dir . $nouveauNomFichier;

            // Déplacer le fichier téléchargé du dossier temporaire vers sa destination
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                if (empty($errors)) {
                    // Hachage du mot de passe
                    $password_hash = password_hash($password, PASSWORD_BCRYPT);

                    $user = new User($name, $mail, $password_hash, $target_file);
                    $userRepo = new UserRepository();
                    try {
                        $userRepo->create($user);
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
            } else {
                echo "Erreur lors du téléchargement du fichier.";
            } 
        } else {
            echo "Erreur sur le fichier.";
        }  
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        if($connected) {
            echo "<title>Créer un Utilisateur</title>";
        } else {
            echo "<title>Créer un compte</title>";
        }
    ?>
    
</head>
<body>
    <?php
        if($connected) {
            echo "<h1>Créer un Utilisateur</h1>";
        } else {
            echo "<h1>Créer un compte</h1>";
        }
    ?>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="name">Nom:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="mail">E-mail:</label>
        <input type="email" id="mail" name="mail" required>
        <br>
        <label>Mot de passe:</label>
        <input type="password" name="password" required>
        <br>
        <label>Confirmez votre mot de passe:</label>
        <input type="password" name="confirm_password" required>
        <br>
        <label>Photo de profil:</label>
        <input type="file" name="photo" required>
        <br>
        <button type="submit" name="inscription">Ajouter l'utilisateur</button>
    </form>
    <br/><br/>
    <?php
        if($connected) {
            echo '<a href="index.php">Retour à la liste des utilisateurs</a>';
        } else {
            echo '<a href="login.php">Retour à la page de login</a>';
        }
    ?>
    
</body>
</html>
