<?php
class Category {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query(
            "SELECT id, name FROM categories ORDER BY name"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
