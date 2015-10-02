<?php
if (array_key_exists("LoggedIn", $_SESSION)) {
    if($_SESSION["LoggedIn"] == 0) {
header("Location: login.php");
die();
    }
} else {
header("Location: login.php");
die();
}
