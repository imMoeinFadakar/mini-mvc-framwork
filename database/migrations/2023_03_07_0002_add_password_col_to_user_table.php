<?php


use Roocket\PocketCore\Application;

return new class
{
    public function up() : void
    {
        $sql = "ALTER TABLE users ADD COLUMN password varchar(255) not null";
        Application::$app->db->pdo->exec($sql);
    }

    public function down() : void
    {
        $sql = "ALTER TABLE users drop column password";
        Application::$app->db->pdo->exec($sql);
    }
};
