<?php
class Database{
    private $pdo;
    private $host;
    private $db_name;
    private $user;
    private $password;
    
    function __construct($host , $db_name , $user, $password){
        $this->host = $host;
        $this->db_name = $db_name;
        $this->user = $user;
        $this->password = $password;
    }

    public function connect(){
        if($this->pdo === null){
        $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->db_name}",
            $this->user,
                $this->password);
            }
            return $this->pdo;
    }
}
$db = new Database("localhost", "smart-wallet", "root", "");
$pdo = $db->connect();

// $stmt = $pdo->query("SELECT * FROM incomes");
// $result = $stmt->fetch(PDO::FETCH_ASSOC);
// echo $result['date'];


?>




