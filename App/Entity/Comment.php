<?php

namespace App\Entity;

class Comment {
    private $id;
    private $userId;
    private $articleId;
    private $content;
    private $createdAt;

    public function __construct($userId, $articleId, $content) {
        $this->userId = $userId;
        $this->articleId = $articleId;
        $this->content = $content;
        $this->createdAt = new \DateTime();
    }

    public function getId() { return $this->id; }
    public function getUserId() { return $this->userId; }
    public function getContent() { return $this->content; }
    public function getArticleId() { return $this->articleId; }}
?>