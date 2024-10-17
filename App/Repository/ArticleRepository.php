<?php
    namespace App\Repository;

    use App\Config\Database;
    use App\Entity\Article;
    
    class ArticleRepository {
        private $db;
    
        public function __construct() {
            $this->db = (new Database())->getConnection();
        }
    
        public function create(Article $article) {
            $stmt = $this->db->prepare("INSERT INTO articles (user_id, title, content, image) VALUES (:user_id, :title, :content, :image)");
            $stmt->bindValue(':user_id', $article->getUserId());
            $stmt->bindValue(':title', $article->getTitle());
            $stmt->bindValue(':content', $article->getContent());
            $stmt->bindValue(':image', $article->getImage());
            return $stmt->execute();
        }
    
        public function findAll() : array {
            $stmt = $this->db->query("SELECT articles.*, users.username AS author_name 
                FROM articles 
                JOIN users ON articles.user_id = users.id");
            $articles = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $articles[] = new Article(
                    $row['user_id'],
                    $row['title'], 
                    $row['content'], 
                    $row['image'],
                    $row['id'],
                    $row['author_name']
                );
            }
            return $articles;
        }

        public function find($id) : ?Article {
            $stmt = $this->db->prepare("
                SELECT articles.*, users.username AS author_name 
                FROM articles 
                JOIN users ON articles.user_id = users.id 
                WHERE articles.id = :id
            ");
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $data ? new Article(
                $data['user_id'],
                $data['title'], 
                $data['content'], 
                $data['image'],
                $data['id'],
                $data['author_name']
            ) : null;
        }
        
    
        public function findByUserId($userId) : ?array {
            $stmt = $this->db->prepare("SELECT * FROM articles WHERE user_id = :user_id");
            $stmt->bindValue(':user_id', $userId);
            $stmt->execute();

            $articles = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $articles[] = new Article(
                    $row['user_id'],
                    $row['title'], 
                    $row['content'], 
                    $row['image'],
                    $row['id']
                );
            }
            return $articles;
        }

        public function delete($id) {
            $stmt = $this->db->prepare("DELETE FROM articles WHERE id = :id");
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        }
    }
    