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
        if ($amount <= 0 || empty($date)) {
            return false;
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO incomes (user_id, amount, description, date)
             VALUES (?, ?, ?, ?)"
        );

        return $stmt->execute([$userId, $amount, $description, $date]);
    }

    public function getAllByUser($userId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM incomes
             WHERE user_id = ?
             ORDER BY date DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function update($id, $userId, $amount, $description, $date)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE incomes
             SET amount = ?, description = ?, date = ?
             WHERE id = ? AND user_id = ?"
        );

        return $stmt->execute([$amount, $description, $date, $id, $userId]);
    }

    public function delete($id, $userId)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM incomes
             WHERE id = ? AND user_id = ?"
        );

        return $stmt->execute([$id, $userId]);
    }
}
