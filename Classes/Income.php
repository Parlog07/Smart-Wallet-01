<?php

class Income
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($userId, $amount, $description, $date)
    {
        // Basic validation
        if ($amount <= 0 || empty($date)) {
            return false;
        }

        $sql = "INSERT INTO incomes (user_id, amount, description, date)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $userId,
            $amount,
            $description,
            $date
        ]);
    }

    public function getAllByUser($userId)
    {
        $sql = "SELECT * FROM incomes
                WHERE user_id = ?
                ORDER BY date DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public function delete($id, $userId)
    {
        $sql = "DELETE FROM incomes
                WHERE id = ? AND user_id = ?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id, $userId]);
    }
}
