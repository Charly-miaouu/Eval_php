<?php
session_start();

require 'autoload.php';

use App\Repository\ArticleRepository;
use App\Entity\Article;


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

$id = $_GET['id'];
$articleRepo = new ArticleRepository();
$article = $articleRepo->find($id);
var_dump($article); 

if (isset($_SESSION['admin']) || $_SESSION['admin'] === true || ($_SESSION['user_id'] === $article->getUserId())) {
    if ($article instanceof Article) {
        unlink($article->getImage());
        $articleRepo->delete($article->getId());
        header('Location: index.php');
        exit;
    } else {
        header('Location: index.php');
        exit;
    }
} else {
    echo "Vous n'avez pas les droits pour supprimer cet article";
}
?>