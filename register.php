<?php include "base.php"; ?>
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
<?php

if(!empty($_POST['username']) && !empty($_POST['password'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    $params = [$username];

    $query = "SELECT * FROM users WHERE Username = ?";
    $res = selectQuery($query, $params);
    $result_count = count($res);

    if($result_count == 1)
    {
        echo "<h1>Error</h1>";
        echo "<p>Sorry, that username is taken. Please go back and try again.</p>";
    }
    else
    {
        $query = "INSERT INTO users (Username, Password, EmailAdress) VALUES (?, ?, ?)";
        $params = [$username, $password, $email];
        editQuery($query, $params);
        echo "<h1>Success</h1>";
        echo "<p>Registered user: $username.</p>";
        echo "<p><a href='login.php'>log in</a></p>";
}
}

    ?>
     


<div class="row">

<div class="col-sm-3">
</div>

<div class="col-sm-6">




   <h2>Sign up</h2>
     
     
    <form method="post" action="register.php" name="registerform" id="registerform" class="form-signin">
    <fieldset>
        <label for="username" class="sr-only">Username</label>
        <input type="text" name="username" id="username" placeholder ="Username" class="form-control">
        <label for="password" class="sr-only">Password</label>
        <input type="password" name="password" id="password" placeholder ="Password" class="form-control">
        <label for="email" class="sr-only">Email address</label>
        <input type="email" name="email" id="email" placeholder ="E-mail" class="form-control">
        <div class="lower-row">
        <button class="btn btn-circle btn-primary pull-right" type="submit" name="register" id="register" value="Register"><span class="glyphicon glyphicon-ok"></span></button>
        </div>
    </fieldset>
    </form>
     

 </div>
<div class="col-sm-3">
</div>
</div>
</div>
</div>
</body>
</html>
