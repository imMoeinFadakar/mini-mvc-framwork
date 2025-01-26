<?php

namespace Roocket\PocketCore\Database;

use PDO;
use PDOStatement;

// CRUD  CREATE READ UPDATE DELETE
class Model extends Database
{
    protected string $table;
    protected int $fetchMode = PDO::FETCH_OBJ;
    protected PDOStatement $statement;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function create(array  $data) : bool
    {

        $keysOfData = array_keys($data); //

        $params = implode("," ,array_map(fn($key) => ":$key", $keysOfData)); // ":title , :body"
        $fields = implode("," , $keysOfData); // "title , body"

        $this->statement = $this->pdo->prepare("INSERT INTO $this->table($fields) VALUES($params)");

        $this->bindValues($data);

        return $this->statement->execute();

    }

    protected function bindValues(?array $data = null): void {

        // data if null
        foreach($data as $key => $value){

            $this->statement->bindValue($key, $value);

        }


    }

    public function update(int $id, array $data): bool
    {

        $filedsOfUpdate =   array_map(fn($key) => "$key = :$key" , array_keys($data));
        $filedsOfUpdate = implode(",", $filedsOfUpdate);


        $this->statement = $this->pdo->prepare("UPDATE articles SET $filedsOfUpdate WHERE id = :id");

        $this->bindValues(array_merge($data , ["id"=> $id]));

        return $this->statement->execute();
    }

    public function delete(int $id)
    {
        
        $this->statement = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");


        $this->bindValues(['id' => $id]);

        $this->statement->execute();

        

    }


    public function get()
    {
        
      
        
        return $this->fetch('fetch');
    }

    public function fetch($fetchMethod = "fetchAll")
    {
        
        return $this->statement->{$fetchMethod}($this->fetchMode);

    }

    public function result(): self
    {
        
        $this->statement = $this->pdo->prepare("SELECT * FROM {$this->table} ");

        $this->statement->execute();

        return $this;

    }
}
