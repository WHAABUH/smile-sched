<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/login.css">
	<title>Login Page</title>
</head>
<body>  

    <div class="left-div">

        <div class="input-container">

                <div class="remarks">
                    <h1>Login</h1>
                    <p>Welcome back user!</p>
                </div>
               
                <div class="form">
                    <form action="authorization.php" method="post">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" required>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                        <button type="submit"> Sign In</button>
                    </form>
                </div>
                
                <div class="not-member">
                    <a href="sign-up.php">Not yet a member? Sign up here!</a>   
                </div>
                
        </div>
            
    </div>
   
    <div class="right-div">
         <div class="welcome">
            <h1>Welcome to Smile-Sched!</h1>
         </div>
         <div class="image">
            <img src="./Images/loginImage.png" alt="">
         </div>
    </div>


    
</body>
</html>