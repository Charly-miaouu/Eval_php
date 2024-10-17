<?php
require 'autoload.php';

use App\Repository\LikeRepository;
use App\Repository\ArticleRepository;

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$userId = $_SESSION['user_id'];
$articleId = (int)$_GET['id'];

$likeRepo = new LikeRepository();

if ($likeRepo->isLikedByUser($articleId, $userId)) {
    $likeRepo->removeLike($articleId, $userId);
} else {
    $likeRepo->addLike($articleId, $userId);
}

header('Location: index.php');
exit;
?>