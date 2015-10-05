<?php
include "base.php";

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

$id = $_POST['linkID'];
deleteEntry($id);
deleteConnections($id);
