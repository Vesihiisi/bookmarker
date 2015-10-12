<?php
            ob_start();
             include "base.php"; ?>
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
&nbsp;
</div>



</div>
</div>


<div class='page-header row'>


<div class="col-sm-4">
<h1>Super bookmarker</h1>
</div>



</div>



<div class="row">

<div class="col-sm-3">
</div>

<div class="col-sm-6">
<?php

if(!empty($_POST['username']) && !empty($_POST['password']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];


    $params = [$username];
    $query = "SELECT * FROM users WHERE Username = ?";
    $res = selectQuery($query, $params);
     
    if(count($res) == 1)
    {

        $hash = $res[0]['Password'];
        if (password_verify($password, $hash)) {
            $_SESSION['Username'] = $res[0]['Username'];
            $_SESSION['UserID']= $res[0]['UserID'];
            $_SESSION['EmailAddress'] = $res[0]['EmailAdress'];
            $_SESSION['LoggedIn'] = 1;
            echo "<h1>Success</h1>";
            echo "<p>redirecting...</p>";

            header("Location: index.php");
        } else {
            echo "wrong password";
        }
    }
    else
    {
        echo "<h1>Error</h1>";
        echo "<p>Sorry, your account could not be found. Please <a href=\"index.php\">click here to try again</a>.</p>";
    }
}
else
{
    ?>
     





<h2>Sign in</h2>

     
    <form method="post" action="login.php" name="loginform" id="loginform" class="form-signin">
    <fieldset>
        <label for="username" class="sr-only">Username</label>
        <input type="text" name="username" id="username" class="form-control" placeholder="Username" required autofocus>
        <label for="password" class="sr-only">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        

        <div class="lower-row">
         <a href="register.php">Create an account</a>
        <button class="btn btn-circle btn-primary pull-right" type="submit" name="login" id="login" value="Login"><span class="glyphicon glyphicon-log-in"></span></button>
        </div>


       
    </fieldset>
    </form>
    </div>
     
   <?php
}
?>
<div class="col-sm-3">
</div>
</div>
</div>
</div>
</body>
</html>
