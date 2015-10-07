<?php
include "base.php";

$title = $_POST["title"];
$tags = $_POST["tags"];
$linkID = $_POST["linkID"];

function updateTitle($linkID, $newTitle) 
{
    $query = "UPDATE links set title=? WHERE linkID = ?";
    $params = [$newTitle, $linkID];
    editQuery($query, $params);
}




updateTitle($linkID, $title);
deleteConnections($linkID);


if (strlen($tags) > 0) {
    $tagString = strtolower($tags);
    $tags = stringToArray($tagString);
    $tags = array_unique($tags);
    foreach($tags as $tag) {
        $query = "SELECT * from tags WHERE tag = ?";
        $params = [$tag];
        $res = selectQuery($query, $params);
        if (count($res) == 0) {
            $query = "INSERT into tags (tag) VALUES (?)";
            $params = [$tag];
            editQuery($query, $params);
        }
    }
    foreach($tags as $tag) {
        $query = "SELECT tagID from tags WHERE tag = ?";
        $params = [$tag];
        $res = selectQuery($query, $params);
        $tagID = $res[0]["tagID"];
        $query = "INSERT into taggedlinks (tagID, linkID) VALUES (?, ?)";
        $params = [$tagID, $linkID];
        editQuery($query, $params);
    }
}
