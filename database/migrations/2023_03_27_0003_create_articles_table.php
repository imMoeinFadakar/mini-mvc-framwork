<?php


use Roocket\PocketCore\Application;

return new class
{
    public function up() : void
    {
        $sql = "CREATE TABLE articles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            body TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        Application::$app->db->pdo->exec($sql);
    }

    public function down() : void
    {
        $sql = "DROP TABLE articles";
        Application::$app->db->pdo->exec($sql);
    }
};
