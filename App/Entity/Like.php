<?php

namespace App\Entity;

class Like
{
    private $id;
    private $userId;
    private $postId;
    private $createdAt;

    public function __construct($userId, $postId)
    {
        $this->userId = $userId;
        $this->postId = $postId;
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
?>