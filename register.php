<?php include "base.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
 
<title></title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>  
<body>  
<div id="main">
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
     
   <h1>Register</h1>
     
   <p>Please enter your details below to register.</p>
     
    <form method="post" action="register.php" name="registerform" id="registerform">
    <fieldset>
        <label for="username">Username:</label><input type="text" name="username" id="username" /><br />
        <label for="password">Password:</label><input type="password" name="password" id="password" /><br />
        <label for="email">Email Address:</label><input type="text" name="email" id="email" /><br />
        <input type="submit" name="register" id="register" value="Register" />
    </fieldset>
    </form>
     

 
</div>
</body>
</html>
