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
<?php include 'head.php';?>
</head>

<body>







<div class="container">


<div class="row top-row">
<div class="col-sm-12">

<div class="navbar-header">
<div class="dropdown">
<span class="dropdown-toggle" data-toggle="dropdown">
<span class="glyphicon glyphicon-user"></span>
<?php echo $username ?> (<span id="userCount"></span>)
<span class="caret"></span></span>
<ul class="dropdown-menu pull-right">
<li class="disabled"><a href="#"><span class="glyphicon glyphicon-cog"></span>Settings</a></li>
<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Sign out</a></li>
</ul>
</div>
</div>



</div>
</div>


<div class='page-header row'>


<div class="col-sm-4">
<h1>Super bookmarker</h1>
</div>

<div class="col-sm-3">
</div>

<div class="col-sm-5  text-right">
<div class="search-container text-right">




<div class="input-group search-field">

<input type="text" class="form-control" placeholder="Search" id="search">
</div>


</div>
</div>









</div>




<div class="row">
<div class="col-sm-9">

<div class="user-menu"> 


<div class="col-sm-12">
<button data-toggle='tooltip' title='Add bookmark' class="btn btn-primary btn-circle pull-right" id="showPanel" ><span class="glyphicon glyphicon-plus"></span></button>
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
<button title = "Add" data-toggle: "tooltip" type="submit" value="add"  class="btn btn-circle btn-success buttonSave pull-right" id="addEntryButton"><span class="glyphicon glyphicon-ok"></span></button>

<button title = "Cancel" data-toggle: "tooltip" class="btn btn-circle btn-warning buttonCancel disabled pull-right" ><span class="glyphicon glyphicon-remove"></span></button>
</form>
</div>

<div class="entries" id="entries">
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






