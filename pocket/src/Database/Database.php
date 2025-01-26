<?php namespace Roocket\PocketCore\Database;

use PDO;

class Database
{
    public PDO $pdo;
    public Migrations $migrations;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=mvcproject", "root" , "");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
        $this->migrations = new Migrations($this);
    }
}
