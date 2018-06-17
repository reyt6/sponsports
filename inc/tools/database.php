<?php if ( count( get_included_files() ) == 1) die( '--access denied--' );
function connectDb($db,$usr,$pswd){
    try {
        return new PDO($db, $usr, $pswd);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}

function getDb(){
    return connectDb("mysql:host=localhost;dbname=sponsports", "root", "Pmrt06031995");
}
function dbQuery($q){
    $database = getDb();
    $query = $database->prepare($q);
    $query->execute();
    return $query -> fetchAll(PDO::FETCH_ASSOC);
}

function loadDB(){
    $database = getDb();
    return $database;
}
function loadTable($tbName){
    $dbData = dbQuery("SELECT * FROM sponsports.".$tbName);
    return $dbData;
}

