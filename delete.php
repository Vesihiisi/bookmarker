<?php
include "base.php";

function deleteEntry($id) {
    $query = "SELECT from links where linkID = ?";
    $params = [$id];
    editQuery($query, $params);
}

$id = $_POST['linkID'];
deleteEntry($id);
