<?php
include "base.php";

include "check.php";

$username = $_SESSION['Username'];

function stringToArray($string)
    {
    $nocommas = str_replace(",", "", $string);
    return explode(" ", $nocommas);
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
        preg_match("/<title>(.+)<\/title>/siU", file_get_contents($url) , $matches);
        $title = trim($matches[1]);
        $query = "INSERT into links (userID, url, title, timestamp) VALUES (?, ?, ?, now())";
        $params = [$userID, $url, $title];
        $lastInserted = editQuery($query, $params);
        if (strlen($_POST["tags"]) > 0)
            {
            $tags = stringToArray($_POST["tags"]);
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
      <link rel="stylesheet" type="text/css" href="css/style.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
      <title>super bookmarker</title>
  </head>

  <body>
  <div class="container">
  <div class="page-header">
    <h1>Super bookmarker</h1>
  </div>
<div class="row">
  <div class="col-sm-9">  <p>you are logged in as <?php
echo $username ?> -- <a href="logout.php">log out</a></p>
  <form id="addURL" class="addURL" method="post" role="form">
  <div class="form-group">
   <label for="url">URL:</label>
  <input type="url" name="url" id="url" class="form-control" required>
  </div>
    <div class="form-group">
   <label for="tags">TAGS:</label>
  <input type="tags" name="tags" id="tags" class="form-control">
  </div>
  <button type="submit" value="add" class="btn btn-primary fullWidthButton">add</button>
  </form>


<?php
$query = "SELECT * FROM links WHERE UserID = ? ORDER BY timestamp DESC";
$params = [$_SESSION['UserID']];
$result = selectQuery($query, $params);

function getTags($linkID, $userID)
    {
    $query = "select taggedlinks.tagID, tag, links.userID from taggedlinks, tags, links WHERE taggedlinks.linkID = ? AND taggedlinks.linkID = links.linkID AND taggedlinks.tagID = tags.tagID AND links.userID = ?;";
    $params = [$linkID, $userID];
    return selectQuery($query, $params);
    }

foreach($result as $row)
    {
    $url = $row["url"];
    $domain = parse_url($url) ["host"];
    $title = $row["title"];
    $timestamp = $row["timestamp"];
    $linkID = $row["linkID"];
    $userID = $_SESSION['UserID'];
    echo "<div class='entry'>";
    echo "<a href='$url'>$title</a>";
    echo "<p>$domain</p>";
    $tags = getTags($linkID, $userID);
    if (count($tags) > 0)
        {
        echo "<p>";
        foreach($tags as $row)
            {
            $tag = $row["tag"];
            echo "<span class='tag'>$tag</span>";
            }

        echo "</p>";
        }

    echo "<p class='timestamp text-muted'>$timestamp</p>";
    echo "</div>";
    }

?></div>
  <div class="col-sm-3"><?php include"tagList.php";?></div>
</div>




  </div>
  </body>



</html>






