<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Sign Up</h1>
    <a href="login.php">Already a member? Login here!</a>
    
    <form action="sign-up-db.php" method="post">
        <label for="fullname">Fullname:</label>
        <input type="text" id="fullname" name="fullname" required>

        <label for="age">Age:</label>
        <input type="text" id="age" name="age" required>

        <label for="sex">Sex:</label>
        <select id="sex" name="sex" required>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        </select>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
       
        <input type="submit" id="logButton" value="log-in">
    </form>
    
    <h1>Welcome to Smile-Sched!</h1>

</body>
</html>