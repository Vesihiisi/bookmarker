<?php
            ob_start();
             include "base.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<title></title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>  
<body>  
<div id="main">
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
     
     
   <p>Log in, or <a href="register.php">register</a>.</p>
     
    <form method="post" action="login.php" name="loginform" id="loginform">
    <fieldset>
        <label for="username">Username:</label><input type="text" name="username" id="username" /><br />
        <label for="password">Password:</label><input type="password" name="password" id="password" /><br />
        <input type="submit" name="login" id="login" value="Login" />
    </fieldset>
    </form>
     
   <?php
}
?>
 
</div>
</body>
</html>
