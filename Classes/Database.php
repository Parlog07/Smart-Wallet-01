<?php

class Database
{
    private $pdo;
    private $host;
    private $db_name;
    private $user;
    private $password;

    public function __construct(
        $host = "localhost",
        $db_name = "money_wallet",
        $user = "root",
        $password = ""
    ) {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->user = $user;
        $this->password = $password;
    }

    public function connect()
    {
        if ($this->pdo === null) {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->user,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }

        return $this->pdo;
    }
}
