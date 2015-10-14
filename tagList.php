<?php

include "base.php";

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


function jsonTagList($queryResult)
{
    return json_encode($queryResult);

}

$tags = getTagsOfUser($userID);
if (count($tags) > 0) {
    echo jsonTagList($tags);
}

