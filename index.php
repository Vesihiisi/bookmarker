<?php
include "base.php";

include "check.php";

$username = $_SESSION['Username'];
$userID = $_SESSION['UserID'];



if ($_POST)
    {
//     processPost($_POST);
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
      <script src="js/stupidtable.min.js"></script>
      <script src="js/script.js"></script>
      <title>super bookmarker</title>
  </head>

  <body>







  <div class="container">


<div class="row top-row">
<div class="col-sm-12">







<div class="navbar-header">
 <div class="dropdown">
  <span class="dropdown-toggle" data-toggle="dropdown">
  <span class="glyphicon glyphicon-user"></span>
  <?php echo $username ?> (<?php echo getLinkCountFromUser($userID) ?>)
  <span class="caret"></span></span>
  <ul class="dropdown-menu pull-right">
      <li class="disabled"><a href="#"><span class="glyphicon glyphicon-cog"></span>Settings</a></li>
    <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Sign out</a></li>
  </ul>
</div>
</div>



</div>
</div>



  <div class="page-header">

    <h1>Super bookmarker</h1>
  </div>
<div class="row">
  <div class="col-sm-9">

<div class="user-menu"> 


<div class="col-sm-9">
<input type=search name=search class="form-control" placeholder="Search" disabled>
<button class="btn btn-primary" id="showPanel"><span class="glyphicon glyphicon-pencil"></span>Add a new link</button>

</div>

<div class="col-sm-3">

</div>










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
<button type="submit" value="add" class="btn btn-primary fullWidthButton" id="addEntryButton">add</button>
</form>
</div>

<div class="entries">
</div>




<?php

if( isset($_GET["tag"])&& !empty( $_GET['tag'] )) {
    $tag = $_GET["tag"];
    //echo "<p class='alert alert-info'>Filter: <a href='?tag=' class='removeTag'>$tag</a></p>";
    //printLinksFromTag($tag);
} else {
     // include "allLinks.php";
}



?></div>

<div class="col-sm-3" id="tag-column">

</div>
</div>




  </div>
  </body>



</html>






