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
    if( isset($_GET["tag"])&& !empty( $_GET['tag'] )) {
        $tagToHighlight = $_GET["tag"];
    } else {
        $tagToHighlight = null;
    }
    echo "<div class='tag-list'>";
    echo "<ul>";
    foreach ($queryResult as $row) {
        $tagName = $row["tag"];
        if ($tagToHighlight == $tagName) {
            $addHighlightClass = "class = highlightedTag";
        } else {
            $addHighlightClass = null;
        }
        $count = $row["count"];
        echo "<li $addHighlightClass><a href='?tag=$tagName'>$tagName<span class='tag-count'>$count</span></li>";
    }
    echo "</ul>";
    echo "</div>";
}

$tags = getTagsOfUser($userID);
printTagList($tags);
