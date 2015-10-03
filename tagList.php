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
    echo "<div class='tag-list'>";
    echo "<ul>";
    foreach ($queryResult as $row) {
        $tag = $row["tag"];
        $count = $row["count"];
        echo "<li>$tag <span class='tag-count'>$count</span></li>";


    }
    echo "</ul>";
    echo "</div>";
}

$tags = getTagsOfUser($userID);
printTagList($tags);
