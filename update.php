<?php
include "base.php";

$title = $_POST["title"];
$tags = $_POST["tags"];
$linkID = $_POST["linkID"];

$query = "UPDATE links set title=? WHERE linkID = ?";
$params = [$title, $linkID];

editQuery($query, $params);
