<?php
session_start();

include "config.php";

function connectToDb()
{
    try {
        include "config.php";
        $db = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8", "$username", "$password");
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        return $db;
    } catch(PDOException $e) {
        echo $e->getMessage();
    }
}

function selectQuery($query, $params)
{
    $db = connectToDb();
    $statement = $db->prepare($query);
    $statement -> execute($params);
    $row = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $row;
}

function editQuery($query, $params)
{
    $db = connectToDb();
    $statement = $db->prepare($query);
    $statement -> execute($params);
    return $db->lastInsertId();
}

