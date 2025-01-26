<?php

namespace Roocket\PocketCore\Database;

use PDO;
use Roocket\PocketCore\Application;

class Migrations
{

    protected $db;
    public function __construct( Database $database) {

        $this->db = $database;

    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $appliedMigrations = array_map(fn($migration) => $migration->migration , $appliedMigrations);

        $files = scandir(Application::$ROOT_DIR .'/database/migrations');

        $newMigrations = [];
        $migrations = array_diff($files , $appliedMigrations);
        foreach ($migrations as $migration) {
            if($migration === '.' || $migration === '..') {
                continue;
            }

            $migrateInstance = require_once Application::$ROOT_DIR ."/database/migrations/$migration";

            $this->log("applying migration $migration");
            $migrateInstance->up();
            $this->log("applied migration $migration");

            $newMigrations[] = $migration;
        }

        if(! empty($newMigrations) ) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("there are no migrations to apply");
        }

    }

    public function rollbackMigrations()
    {
        $appliedMigrations = $this->getAppliedMigrations();
        $lastBatch = $this->getLastBatchOfMigrations();

        $mustRollbackMigration = array_filter($appliedMigrations , fn($migration) => $migration->batch === $lastBatch);

        foreach ($mustRollbackMigration as $migration) {

            $migrateInstance = require_once Application::$ROOT_DIR ."/database/migrations/{$migration->migration}";

            $this->log("rolling back migration {$migration->migration}");
            $migrateInstance->down();
            $this->log("rolled back migration {$migration->migration}");
        }


        if(! empty($mustRollbackMigration) ) {
            $this->deleteMigrations(array_map(fn($migration) => $migration->id , $mustRollbackMigration));
        } else {
            $this->log("there are no migrations to rollback");
        }

    }

    protected function createMigrationsTable()
    {
        $this->db->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration varchar(255),
            batch INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");
    }

    protected function saveMigrations($newMigrations) : void
    {
        $batchNumber = $this->getLastBatchOfMigrations() + 1;

        $rows = implode("," , array_map(fn($migration) => "( '$migration' , $batchNumber )" , $newMigrations));
        $this->db->pdo->exec("INSERT INTO migrations (migration ,batch) values $rows");
    }

    protected function deleteMigrations($migrationsId) : void
    {
        $ids = implode("," , $migrationsId);
        $this->db->pdo->exec("DELETE FROM migrations where id In ({$ids})");
    }

    protected function getAppliedMigrations() : ?array
    {
        $statement = $this->db->pdo->prepare("SELECT id , migration , batch FROM migrations");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    protected function getLastBatchOfMigrations() : int
    {
        $statement = $this->db->pdo->prepare(" SELECT MAX( batch ) as max FROM migrations");
        $statement->execute();

        return $statement->fetch(PDO::FETCH_COLUMN) ?? 0;
    }

    private function log($message) : void
    {
        $time = date("Y-m-d H:i:s");
        echo "[$time] - $message" . PHP_EOL;
    }
}
