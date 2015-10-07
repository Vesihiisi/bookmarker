<?php
include "base.php";

$userID = $_SESSION["UserID"];

echo getLinkCountFromUser($userID);
