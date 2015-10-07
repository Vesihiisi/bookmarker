<?php

include_once "base.php";

$searchTerm = $_POST["query"];

printLinksFromSearch($searchTerm);
