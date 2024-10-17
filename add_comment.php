<?php
session_start();
require 'autoload.php';

use App\Entity\Comment;
use App\Repository\CommentRepository;

if (!isset($_SESSION['user_id']) || !isset($_POST['content']) || !isset($_POST['article_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$articleId = $_POST['article_id'];
$content = $_POST['content'];

$comment = new Comment($userId, $articleId, $content);
$commentRepo = new CommentRepository();
$commentRepo->add($comment);

header("Location: article.php?id=$articleId");
exit;
