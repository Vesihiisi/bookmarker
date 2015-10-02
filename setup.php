<?php

include "base.php";

function createTableUsers() {
    $query = "CREATE TABLE IF NOT EXISTS users (UserID int(25) NOT NULL AUTO_INCREMENT, Username varchar(65) NOT NULL, Password varchar(255) NOT NULL, EmailAdress varchar(255) NOT NULL, PRIMARY KEY (UserID))";
    try
    {
        $db = connectToDb();
        $db->exec($query);
    }
    catch (PDOException $e)
    {
        echo "<br>" . $e->getMessage();
    }
}

function createTableLinks()
{
    $query ="CREATE TABLE IF NOT EXISTS links (linkID INT AUTO_INCREMENT NOT NULL, userID int(11) NOT NULL, url varchar(2083) NOT NULL, title varchar(300), timestamp DATETIME NOT NULL, PRIMARY KEY (linkID))";
    try
    {
        $db = connectToDb();
        $db->exec($query);
    }
    catch (PDOException $e)
    {
        echo "<br>" . $e->getMessage();
    }
}

function createTableTags() {
    $query ="CREATE TABLE IF NOT EXISTS tags (tagID INT AUTO_INCREMENT NOT NULL, tag varchar(30) NOT NULL, PRIMARY KEY (tagID))";
    try
    {
        $db = connectToDb();
        $db->exec($query);
    }
    catch (PDOException $e)
    {
        echo "<br>" . $e->getMessage();
    }
}

function createTableTaggedLinks() {
    $query ="CREATE TABLE IF NOT EXISTS taggedlinks (connectionID INT AUTO_INCREMENT NOT NULL, tagID INT NOT NULL, linkID INT, PRIMARY KEY (connectionID))";
    try
    {
        $db = connectToDb();
        $db->exec($query);
    }
    catch (PDOException $e)
    {
        echo "<br>" . $e->getMessage();
    }
}

createTableUsers();
createTableLinks();
createTableTags();
createTableTaggedLinks();
