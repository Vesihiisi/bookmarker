<?php
session_start();
date_default_timezone_set(date_default_timezone_get());

include "config.php";
require "vendor/autoload.php";
use Sunra\PhpSimple\HtmlDomParser;

function logIt($string)
{
    $filename = "log.log";
    $fh = fopen($filename, "a") or die("Could not open log file.");
    fwrite($fh, date("d-m-Y, H:i")." - $string\n") or die("Could not write file!");
    fclose($fh);
}

function stringToArray($string)
{
    return explode(",", $string);
}

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

function getPage($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function getPageTitle($url)
{
    $dom = HtmlDomParser::str_get_html(getPage($url));
    return $dom->find('title', 0)->innertext;
}

function printEntry($rowFromDb)
{
    $url = $rowFromDb["url"];
    $domain = parse_url($url)["host"];
    $title = htmlspecialchars($rowFromDb["title"]);
    $timestamp = $rowFromDb["timestamp"];
    $Date = new DateTime($timestamp);
    $Date->setTimezone(new DateTimeZone('Europe/Stockholm'));
    $timestamp = $Date->format('d/m/Y H:i'); 
    $linkID = $rowFromDb["linkID"];
    $userID = $_SESSION['UserID'];
    echo "<div class='entry'>";
    echo "<div class='edit-links'>";
    echo "<span class='glyphicon glyphicon-edit' data-toggle='tooltip' title='edit' id=$linkID></span>";
    echo "<span class='glyphicon glyphicon-remove' data-toggle='tooltip' title='delete' id=$linkID></span>";
    echo "</div>";
    echo "<div class='title-bar'>";
    echo "<div class='title'>";
    echo "<a href='$url'>$title</a>";
    echo "</div>";
    echo "</div>";
        echo "<div class='middle-row'>";
    echo "<p>$domain</p>";
    echo "</div>";
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

function getLinksFromTag($tag)
{
    $userID = $_SESSION['UserID'];
    $query = "SELECT
    links.linkID
    from taggedlinks, tags, links
    WHERE  taggedlinks.linkID = links.linkID
    AND taggedlinks.tagID = tags.tagID
    AND links.userID = ?
    AND tag = ?
    ORDER BY timestamp DESC";
    $params = [$userID, $tag];
    $linkIds = array();
    $res = selectQuery($query, $params);
    foreach ($res as $row) {
        array_push($linkIds, $row["linkID"]) ;
    }
    return $linkIds;
}

function getLinkFromId($linkID)
{
    $query = "select * from links where linkID = ?";
    $params = [$linkID];
    return selectQuery($query, $params);
}

function printLinksFromTag($tag)
{
    $linkIds = getLinksFromTag($tag);
    foreach ($linkIds as $row) {
        $link = getLinkFromId($row)[0];
        printEntry($link);
    }
}

function getLinksFromSearch($searchTerm)
{
    $userID = $_SESSION['UserID'];
    $searchTerm = "%".$searchTerm."%";
    $query = "SELECT linkID from links where title like ? AND userID = ?";
    $params = [$searchTerm, $userID];
    $linkIds = array();
    $res = selectQuery($query, $params);
    foreach ($res as $row) {
        array_push($linkIds, $row["linkID"]) ;
    }
    return $linkIds;
}

function printLinksFromSearch($searchTerm)
{
    $linkIds = getLinksFromSearch($searchTerm);
    foreach ($linkIds as $row) {
        $link = getLinkFromId($row)[0];
        printEntry($link);
    }
}

function getTags($linkID, $userID)
{
  $query = "select taggedlinks.tagID, tag, links.userID from taggedlinks, tags, links WHERE taggedlinks.linkID = ? AND taggedlinks.linkID = links.linkID AND taggedlinks.tagID = tags.tagID AND links.userID = ?;";
  $params = [$linkID, $userID];
  return selectQuery($query, $params);
}

function printAllLinks($userID)
{
    $query = "SELECT * FROM links WHERE UserID = ? ORDER BY timestamp DESC";
    $params = [$userID];
    $result = selectQuery($query, $params);
    foreach($result as $row) {
        printEntry($row);
    }
}

function getLinkCountFromUser($userID) {
    $query = "SELECT count(*) as 'count' from links WHERE userID = ?";
    $params = [$userID];
    $res = selectQuery($query, $params);
    return $res[0]["count"];
}

function deleteConnections($id)
{
    $query = "DELETE from taggedlinks where linkID = ?";
    $params = [$id];
    editQuery($query, $params);
}

function deleteEntry($id)
{
    $query = "DELETE from links where linkID = ?";
    $params = [$id];
    editQuery($query, $params);
}
