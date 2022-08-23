<?php

require __DIR__ . '/vendor/autoload.php';

use Phcode\Database\Database;

// This file is for to be execute on Terminal, just for a dev local
// Run: php db.php [command] [paramters]

//$argv

// configs
$mainPath = __DIR__ . "/database";
$dbFile = "db.sqlite";
$dbFullFile = $mainPath."/".$dbFile;
$migratePath = $mainPath."/migrate";

$pdo = New PDO("sqlite:".$dbFullFile);

// verify if db exists
if(!file_exists($dbFullFile)){
    try {
        fopen($dbFullFile,"w+");
    } catch (Exception $e) {
        echo 'Database is not created! - ERROR: ',  $e->getMessage(), "\n";
        die();
    }
}

// help
if(!isset($argv[1]) || $argv[1] == "help"){
    echo "\n\nRun some command like: php db.php [command]\n\n";
    echo "help -> To know the options of commands\n";
    echo "migrate -> To execute all migrate files\n";
    echo "migrate [file_name] -> To execute a specify migrate\n";
    echo "rollback [file_name] -> To execute a rollback method of specify migrate\n";
    echo "fresh -> Drop all tables and run all migrates again\n";
    echo "show [table_name] -> To show all registers of some table\n";
    echo "tables -> To list all tables exists in the database\n";
    die(); 
}

// tables
if($argv[1] == "tables"){
    echo "\nDatabase tables list:\n";
    $query = $pdo->query('SELECT name FROM sqlite_schema WHERE type="table" ORDER BY name');
    $resultados = $query->fetchAll(PDO::FETCH_ASSOC); // PDO::FETCH_ASSOC tras resultados somente com um tipo de index, se tirar isso ele tras duas vezes cada resultado
    $studentsList = [];
    foreach($resultados as $res){
        echo "Table: ".$res['name']."\n";
    }
    if(count($resultados) == 0) echo "Database is have not any table.\n";
    echo "\n";
    die();
}

// show
if($argv[1] == "show"){
    if(isset($argv[2])){
        echo "\nAll values of the '".$argv[2]."':\n";
        $query = $pdo->query('SELECT * FROM '.$argv[2]);
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC); // PDO::FETCH_ASSOC tras resultados somente com um tipo de index, se tirar isso ele tras duas vezes cada resultado
        $studentsList = [];
        foreach($resultados as $index => $res){
            print_r($res);
            // foreach($res as $r){
            //     echo $r." | ";
            // }
            echo "\n";
        }
        if(count($resultados) == 0) echo "Table is not have any register.\n";
        echo "\n";
    } else echo "Insert the table name after the command 'show'.";
    die();
}

// Rollback or fresh
if($argv[1] == "rollback" || $argv[1] == "fresh"){
    try {
        if($argv[1] == "fresh"){
            $dir = dir($migratePath);
            $files = [];
            while($file = $dir->read()){
                if(strlen($file) > 3){
                    if(file_exists($migratePath.'/'.$file)){
                        $files[] = $file;
                    }
                }
            }
            $files = array_reverse($files,true);
            foreach($files as $file){
                $db = require $migratePath.'/'.$file;
                $db->setPDO($pdo);
                $resDb = $db->rollback();
                if($resDb) echo "\n\nRollback at ".$file." executed with success!\n";
                else echo "\n\n".$resDb."\n";
            }
            $argv[1] = "migrate";
            if(isset($argv[2])) unset($argv[2]);
        }
        else if(isset($argv[2])){
            $file = $argv[2];
            if(file_exists($migratePath.'/'.$file)){
                $db = require $migratePath.'/'.$file;
                $db->setPDO($pdo);
                $resDb = $db->rollback();
                if($resDb) echo "\n\nRollback at ".$file." executed with success!\n";
                else echo "\n\n".$resDb."\n";
            }
            die();
        }
    } catch (Exception $e) {
        echo 'rollback|fresh is not executed! - ERROR:\n',  $e->getMessage(), "\n";
    }
}

// migrate
if($argv[1] == "migrate"){
    try {
        if(!isset($argv[2])){
            $dir = dir($migratePath);
            while($file = $dir->read()){
                if(strlen($file) > 3){
                    if(file_exists($migratePath.'/'.$file)){
                        $db = require $migratePath.'/'.$file;
                        $db->setPDO($pdo);
                        $resDb = $db->execute();
                        if($resDb) echo "\n\n".$file." executed with success!\n";
                        else echo "\n\n".$resDb."\n";
                    }
                }
            }
        }
        else{
            $file = $argv[2];
            if(strlen($file) > 3){
                if(file_exists($migratePath.'/'.$file)){
                    $db = require $migratePath.'/'.$file;
                    $db->setPDO($pdo);
                    $resDb = $db->execute();
                    if($resDb) echo "\n\n".$file." executed with success!\n";
                    else echo "\n\n".$resDb."\n";
                }
            }
        }
    } catch (Exception $e) {
        echo 'Migrate is not executed! - ERROR:\n',  $e->getMessage(), "\n";
    }
    die();
}

// $dir -> close();

echo "\nCommand not found. For help, run: php db.php help\n";
die();