<?php

class DB
{
    public $pdo;

    private $host = 'mysql';
    private $db = 'game';
    private $user = 'root';
    private $pass = 'root';


    function __construct()
    {
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=UTF8";
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }
}
