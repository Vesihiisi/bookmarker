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
    echo "<table class='tag-list'>";
    echo "<thead>";
    echo "<tr><th data-sort='string'><span class='glyphicon glyphicon-tags'></span></th><th data-sort='int' class='text-right'><span class='glyphicon glyphicon-sort'></span></th></tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($queryResult as $row) {
        $tagName = $row["tag"];
        if ($tagToHighlight == $tagName) {
            $addHighlightClass = "class = highlightedTag";
        } else {
            $addHighlightClass = null;
        }
        $count = $row["count"];
        echo "<tr $addHighlightClass>";
        echo "<td>";
        echo "<a href='?tag=$tagName'>$tagName</a>";
        echo "</td>";
        echo "<td>";
        echo "<span class='tag-count'>$count</span>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
}

$tags = getTagsOfUser($userID);
printTagList($tags);
