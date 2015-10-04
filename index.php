<?php
include "base.php";

include "check.php";

$username = $_SESSION['Username'];

function stringToArray($string)
    {
    return explode(",", $string);
    }

function checkIfUserAlreadyHasThisUrl($url, $userID) {
    $query = "SELECT * from links where url = ? and userID = ?";
    $params = [$url, $userID];
    $result = selectQuery($query, $params);
    if (count($result) == 1) {
        return 0;
    } else {
        return 1;
    }
}

function processPost($data)
    {
    $userID = $_SESSION['UserID'];
    $url = $data["url"];
    if (filter_var($url, FILTER_VALIDATE_URL) === false)
        {
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
        }
      else
        {
        if (checkIfUserAlreadyHasThisUrl($url, $userID) == 0) {
            echo "link already exists";
            break;
        } else {
                    $title = getPageTitle($url);
        $query = "INSERT into links (userID, url, title, timestamp) VALUES (?, ?, ?, now())";
        $params = [$userID, $url, $title];
        $lastInserted = editQuery($query, $params);
        if (strlen($_POST["tags"]) > 0) {
            $tagString = strtolower($_POST["tags"]);
            $tags = stringToArray($tagString);
            $tags = array_unique($tags);
            foreach($tags as $tag)
                {
                $query = "SELECT * from tags WHERE tag = ?";
                $params = [$tag];
                $res = selectQuery($query, $params);
                if (count($res) == 0)
                    {
                    $query = "INSERT into tags (tag) VALUES (?)";
                    $params = [$tag];
                    editQuery($query, $params);
                    }
                }

            foreach($tags as $tag)
                {
                $query = "SELECT tagID from tags WHERE tag = ?";
                $params = [$tag];
                $res = selectQuery($query, $params);
                $tagID = $res[0]["tagID"];
                $query = "INSERT into taggedlinks (tagID, linkID) VALUES (?, ?)";
                $params = [$tagID, $lastInserted];
                editQuery($query, $params);
                }
            }
        }
        }

    }

if ($_POST)
    {
    processPost($_POST);
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
    }

?>





<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
      <link href="css/jquery.tagit.css" rel="stylesheet" type="text/css">

      <link rel="stylesheet" type="text/css" href="css/style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
      <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
      <script src="js/jquery.sticky.js"></script>
      <script src="js/tag-it.min.js"></script>
      <script src="js/script.js"></script>
      <title>super bookmarker</title>
  </head>

  <body>
  <div class="container">
  <div class="page-header">
    <h1>Super bookmarker</h1>
  </div>
<div class="row">
  <div class="col-sm-9">

<div class="user-menu"> 

<button class="btn btn-primary" id="showPanel"><span class="glyphicon glyphicon-pencil"></span>Add a new link</button>
</div>

<div class="addURLPanel">
<form id="addURL" class="addURL" method="post" role="form">
<div class="form-group">
<label for="url">URL:</label>
<input type="url" name="url" id="url" class="form-control" required>
</div>
<div class="form-group">
<label for="tags">TAGS:</label>
<input type="tags" name="tags" id="tagForm">
</div>
<button type="submit" value="add" class="btn btn-primary fullWidthButton">add</button>
</form>
</div>




<?php

if( isset($_GET["tag"])&& !empty( $_GET['tag'] )) {
    $tag = $_GET["tag"];
    echo "<p class='alert alert-info'>Filter: <a href='?tag=' class='removeTag'>$tag</a></p>";
    printLinksFromTag($tag);
} else {
    include "allLinks.php";
}



?></div>

<div class="col-sm-3"><p>you are logged in as <?php echo $username ?></p>
<p><a href="logout.php">log out</a></p><?php include"tagList.php";?></div>
</div>




  </div>
  </body>



</html>






