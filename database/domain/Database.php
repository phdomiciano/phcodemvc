<?php

namespace database\domain;

use \Exception;
use \PDO;

class Database
{
    
    private $pdo;
    private $columns;
    private $foreigns;
    private $table;
    private $query;
    private $primarys;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    public function setPDO(PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    public function create(bool $save = true): mixed
    {       
        if(is_string($this->table) && is_array($this->columns)){
            $this->query = "CREATE TABLE IF NOT EXISTS";
            $this->query .= " ".$this->table." (";
            foreach($this->columns as $index => $item){
                $this->query .= $item[0]." ";
                foreach($item[1] as $attribute){
                    $this->query .= $attribute." ";
                }
                if($index < (count($this->columns) - 1)) $this->query .= ", ";
            }
            if(is_array($this->foreigns)){
                foreach($this->foreigns as $index => $foreign){
                    $this->query .= ", FOREIGN KEY(".$foreign['column'].") REFERENCES ".$foreign['table']."(".$foreign['column_table'].") ";
                }
            }
            if(is_array($this->primarys)){
                $this->query .= ", PRIMARY KEY(";
                foreach($this->primarys as $index => $primary){
                    $this->query .= $primary;
                    if($index < (count($this->primarys) - 1)) $this->query .= ", ";
                }
                $this->query .= ")";
            }
            $this->query .= ");";
            if($save) return $this->save();
        }
        else{
            throw new Exception("\nError on migrate when try create a new table.\n");
        }
    }

    public function setColumn(string $name, array $paramters): void
    {
        if(is_string($name) && strlen($name) > 1 && count($paramters) > 0){
            $this->columns[] = [$name,$paramters];
        }
        else{
            throw new Exception("\nError on set column in a table.\n");
        }
    }

    public function setForeign(string $column, string $table, string $column_table): void
    {
        if(strlen($column) > 0 && strlen($column_table) > 0 && strlen($table) > 0){
            $this->foreigns[] = [
                "column" => $column,
                "table" => $table,
                "column_table" => $column_table
            ];
        }
        else{
            throw new Exception("\nError on set foreign key column in a table.\n");
        }
    }

    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    public function showQuery(): string
    {
        return $this->query ? $this->query : 'No query generated yet.';
    }

    public function save(): bool
    {
        try{
            $this->pdo->exec($this->query);
            return true;
        } catch (Exception $e) {
            throw new Exception("\nERROR: Execute query is not success! Query tried: ".$this->query."\n\n".$e);
        }
        return false;
    }

    public function destroy(): bool
    {
        $this->query = "DROP TABLE IF EXISTS ".$this->table;
        return $this->save();
    }

    public function setPrimaryKey(array $columns): void
    {
        if(count($columns) > 0){
            $this->primarys = $columns;
        }
        else{
            throw new Exception("\Error on set primary key in a table.\n");
        }
    }

}


// // Examples

// $this->setTable("phone");
// $this->setColumn("id",["INTEGER", "PRIMARY KEY", "AUTOINCREMENT", "NOT NULL"]);
// $this->setColumn("student_id",["INTEGER", "NOT NULL"]);
// $this->setColumn("number",["TEXT"]);
// $this->setForeign("student_id","student","id");
// return $this->create();

// // and other:

// $this->setTable("student_course");
// $this->setColumn("student_id",["INTEGER", "NOT NULL", "REFERENCES", "student(id)", "ON UPDATE CASCADE", "ON DELETE CASCADE"]);
// $this->setColumn("course_id",["INTEGER", "NOT NULL", "REFERENCES", "course(id)", "ON UPDATE CASCADE", "ON DELETE CASCADE"]);
// $this->setPrimaryKey(["student_id","course_id"]);
// return $this->create();