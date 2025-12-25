<?php

class Income
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function create($userId, $amount, $description, $date, $categoryId = null)
    {
        if ($amount <= 0 || empty($date)) {
            return false;
        }

        $sql = "INSERT INTO incomes (user_id, amount, description, date, category_id)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $userId,
            $amount,
            $description,
            $date,
            $categoryId
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

    public function getById($id, $userId)
    {
        $sql = "SELECT * FROM incomes WHERE id = ? AND user_id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id, $userId]);

        return $stmt->fetch();
    }
    public function delete($id, $userId)
    {
        $sql = "DELETE FROM incomes WHERE id = ? AND user_id = ?";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$id, $userId]);
    }
}
