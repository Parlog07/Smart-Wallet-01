<?php
class Expense {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllByUser($userId) {
        $stmt = $this->pdo->prepare(
            "SELECT 
                e.id,
                e.amount,
                e.description,
                e.date,
                e.category_id,
                c.name AS category
             FROM expenses e
             JOIN categories c ON e.category_id = c.id
             WHERE e.user_id = ?
             ORDER BY e.date DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($userId, $categoryId, $amount, $description, $date) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO expenses (user_id, category_id, amount, description, date)
             VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$userId, $categoryId, $amount, $description, $date]);
    }

    public function update($id, $userId, $categoryId, $amount, $description, $date) {
        $stmt = $this->pdo->prepare(
            "UPDATE expenses
             SET category_id = ?, amount = ?, description = ?, date = ?
             WHERE id = ? AND user_id = ?"
        );
        return $stmt->execute([$categoryId, $amount, $description, $date, $id, $userId]);
    }

    public function delete($id, $userId) {
        $stmt = $this->pdo->prepare(
            "DELETE FROM expenses WHERE id = ? AND user_id = ?"
        );
        return $stmt->execute([$id, $userId]);
    }
}
