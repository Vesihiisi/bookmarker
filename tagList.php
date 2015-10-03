<?php

$userID = $_SESSION["UserID"];

function getTagsOfUser($userID)
{


    $query = "SELECT
    tag, COUNT(*) AS 'count'
    from taggedlinks, tags, links
    WHERE  taggedlinks.linkID = links.linkID
    AND taggedlinks.tagID = tags.tagID
    AND links.userID = ?
    GROUP BY tag;";
    $params = [$userID];
    return selectQuery($query, $params);
}

function printTagList($queryResult) {
    foreach ($queryResult as $row) {
        $tag = $row["tag"];
        $count = $row["count"];
        echo "<ul>";
        echo "<li>$tag ($count)</li>";
        echo "</ul>";
    }
}

$tags = getTagsOfUser($userID);
printTagList($tags);
