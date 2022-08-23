<?php

use database\domain\Database;

return new class extends Database
{

    public function execute(): mixed
    {
        $this->setTable("phones");
        $this->setColumn("id",["INTEGER", "PRIMARY KEY", "AUTOINCREMENT", "NOT NULL"]);
        $this->setColumn("number",["TEXT"]);
        $this->setColumn("user_id",["INTEGER", "NOT NULL"]);
        $this->setForeign("user_id","user","id");
        return $this->create();
    }

    public function rollback(): mixed
    {
        $this->setTable("phones");
        return $this->destroy();
    }

};