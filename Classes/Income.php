<?php

class Income
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByUser($userId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT 
                i.id,
                i.amount,
                i.description,
                i.date,
                i.category_id,
                c.name AS category
             FROM incomes i
             JOIN categories c ON i.category_id = c.id
             WHERE i.user_id = ?
             ORDER BY i.date DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($userId, $categoryId, $amount, $description, $date)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO incomes (user_id, category_id, amount, description, date)
             VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$userId, $categoryId, $amount, $description, $date]);
    }

    public function update($id, $userId, $categoryId, $amount, $description, $date)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE incomes
             SET category_id = ?, amount = ?, description = ?, date = ?
             WHERE id = ? AND user_id = ?"
        );
        return $stmt->execute([
            $categoryId,
            $amount,
            $description,
            $date,
            $id,
            $userId
        ]);
    }

    public function delete($id, $userId)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM incomes WHERE id = ? AND user_id = ?"
        );
        return $stmt->execute([$id, $userId]);
    }

    public function getTotalByUser($userId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT COALESCE(SUM(amount),0) FROM incomes WHERE user_id = ?"
        );
        $stmt->execute([$userId]);
        return (float) $stmt->fetchColumn();
    }

    public function getMonthlyTotals($userId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT MONTH(date) AS month, SUM(amount) AS total
             FROM incomes
             WHERE user_id = ?
             GROUP BY MONTH(date)"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
