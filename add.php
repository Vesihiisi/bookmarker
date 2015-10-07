<?php
include "base.php";

$url = $_POST["url"];
$tags = $_POST["tags"];
$userID = $_SESSION['UserID'];

echo $url;
echo $tags;
echo $userID;

function stringToArray($string)
{
    return explode(",", $string);
}

function checkIfUserAlreadyHasThisUrl($url, $userID) {
    $query = "SELECT * from links where url = ? and userID = ?";
    $params = [$url, $userID];
    $result = selectQuery($query, $params);
    if (count($result) == 1) {
        return 0;
    } else {
        return 1;
    }
}


if (checkIfUserAlreadyHasThisUrl($url, $userID) == 0) {
    echo "link already exists";
    break;
} else {
    $title = getPageTitle($url);
    $query = "INSERT into links (userID, url, title, timestamp) VALUES (?, ?, ?, now())";
    $params = [$userID, $url, $title];
    $lastInserted = editQuery($query, $params);
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
            $params = [$tagID, $lastInserted];
            editQuery($query, $params);
        }
    }
}
