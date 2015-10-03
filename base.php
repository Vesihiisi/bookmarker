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

function getPageTitle($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    $output = curl_exec($ch);
    curl_close($ch);
    preg_match("/<title>(.+)<\/title>/siU", $output , $result);
    return $result[1];
}

function printEntry($rowFromDb)
{
    $url = $rowFromDb["url"];
    $domain = parse_url($url) ["host"];
    $title = $rowFromDb["title"];
    $timestamp = $rowFromDb["timestamp"];
    $linkID = $rowFromDb["linkID"];
    $userID = $_SESSION['UserID'];
    echo "<div class='entry'>";
    echo "<a href='$url'>$title</a>";
    echo "<p>$domain</p>";
    $tags = getTags($linkID, $userID);
    echo "<div class='tagrow'>";
    if (count($tags) > 0) {
        echo "<div class='tags'>";
        foreach($tags as $row) {
            $tag = $row["tag"];
            echo "<span class='tag'>$tag</span>";
        }
        echo "</div>";
    }
    echo "<span class='timestamp text-muted'>$timestamp</span>";
    echo "</div>";
    echo "</div>";
}
