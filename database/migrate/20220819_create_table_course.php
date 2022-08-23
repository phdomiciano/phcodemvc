<?php

use database\domain\Database;

return new class extends Database
{

    public function execute(): mixed
    {
        $this->setTable("courses");
        $this->setColumn("id",["INTEGER", "PRIMARY KEY", "AUTOINCREMENT", "NOT NULL"]);
        $this->setColumn("name",["TEXT", "NOT NULL"]);
        $this->setColumn("description",["TEXT", "NOT NULL"]);
        $this->setColumn("user_id",["INTEGER", "NOT NULL"]);
        $this->setForeign("user_id","user","id");
        return $this->create();
    }

    public function rollback(): mixed
    {
        $this->setTable("courses");
        return $this->destroy();
    }

};