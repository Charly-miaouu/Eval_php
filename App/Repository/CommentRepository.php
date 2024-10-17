<?php
    namespace App\Repository;

    use App\Config\Database;
    use App\Entity\Comment;

    class CommentRepository {
        private $db;

        public function __construct() {
            $this->db = (new Database())->getConnection();
        }

        public function add(Comment $comment) {
            $stmt = $this->db->prepare("INSERT INTO comments (user_id, article_id, content) VALUES (:user_id, :article_id, :content)");
            $stmt->bindValue(':user_id', $comment->getUserId());
            $stmt->bindValue(':article_id', $comment->getArticleId());
            $stmt->bindValue(':content', $comment->getContent());
            $stmt->execute();
        }
        /*public function findByArticle($articleId) {
            $stmt = $this->db->prepare("SELECT * FROM comments WHERE article_id = :article_id ORDER BY created_at DESC");
            $stmt->bindValue(':article_id', $articleId, \PDO::PARAM_INT);
            $stmt->execute();
            // Utilisez fetchAll pour obtenir un tableau de commentaires
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }*/
        public function findByArticle($articleId) {
            $stmt = $this->db->prepare("
                SELECT comments.*, users.username 
                FROM comments 
                JOIN users ON comments.user_id = users.id 
                WHERE comments.article_id = :article_id 
                ORDER BY comments.created_at DESC
            ");
            $stmt->bindValue(':article_id', $articleId, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        

}
