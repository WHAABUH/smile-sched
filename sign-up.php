<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/signup.css">
    <title>Document</title>
</head>
<body>


    <div class="left-div">

        <div class="input-container">

            <div class="remarks">
                <h1>Sign Up</h1>
                <a href="login.php">Already a member? Login here!</a>
            </div>

            <div class="inputs-container">

                <form action="sign-up-db.php" method="post">

                    <div class="fullname-container">
                        <label for="fullname">Fullname:</label>
                        <input type="text" id="fullname" name="fullname" required>
                    </div>

                    <div class="age-sex-container">
                        <div class="age-container">
                            <label for="age">Age:</label>
                            <input type="text" id="age" name="age" required>
                        </div>

                        <div class="sex-container">
                            <label for="sex">Sex:</label>
                            <select id="sex" name="sex" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="email-container">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" required>
                    </div>

                    <div class="password-container">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="button-container">
                        <button type="submit">Sign up</button>
                    </div>

                </form>

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