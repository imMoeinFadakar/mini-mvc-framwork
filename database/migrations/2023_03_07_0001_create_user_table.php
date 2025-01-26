<?php


use Roocket\PocketCore\Application;

return new class
{
    public function up() : void
    {
        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        Application::$app->db->pdo->exec($sql);
    }

    public function down() : void
    {
        $sql = "DROP TABLE users";
        Application::$app->db->pdo->exec($sql);
    }
};
