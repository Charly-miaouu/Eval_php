<?php
    session_start();

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    // Détruire toutes les variables de session
    session_unset();

    // Détruire la session elle-même
    session_destroy();

    header('Location: login.php');
    exit;
