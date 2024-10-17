<?php
    namespace App\Repository;

    use App\Config\Database;
    use App\Entity\Like;

class LikeRepository
{
    private $db;

        public function __construct() {
            $this->db = (new Database())->getConnection();
        }

    public function isLikedByUser($articleId, $userId)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM likes WHERE article_id = ? AND user_id = ?');
        $stmt->execute([$articleId, $userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function addLike($articleId, $userId)
    {
        $stmt = $this->db->prepare('INSERT INTO likes (article_id, user_id) VALUES (?, ?)');
        $stmt->execute([$articleId, $userId]);
    }

    public function removeLike($articleId, $userId)
    {
        $stmt = $this->db->prepare('DELETE FROM likes WHERE article_id = ? AND user_id = ?');
        $stmt->execute([$articleId, $userId]);
    }

    public function getLikesCount($articleId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE article_id = :article_id");
        $stmt->bindValue(':article_id', $articleId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
}
?>