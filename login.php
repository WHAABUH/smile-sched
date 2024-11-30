<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login Page</title>
</head>
<body>  

    <h1>Welcome to Smile-Sched!</h1>
    <h1>Login</h1>
    <p>Welcome back user!</p>

    <form action="authorization.php" method="post">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" id="logButton" value="Sign in">
    </form>
    
    <a href="sign-up.php">Not yet a member? Sign up here!</a>   
    
</body>
</html>