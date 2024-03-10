<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Sign Up</title>
    <link rel="stylesheet" type="text/css" href="css/login.style.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main">  	
	    <input type="checkbox" id="chk" aria-hidden="true">
                <div class="signup">
                <form action="includes/login.inc.php" method="post">
                <label for="chk" aria-hidden="true">Login</label>
                <input type="text" name="uid" placeholder="Username">
                <input type="password" name="pwd" placeholder="Password">
                <button type="submit" name="submit">LOGIN</button>
            </form>
        </div>
        <div class="login">
		    <label for="chk" aria-hidden="true">Info</label>
            <div class="info">
                <h3> Login with the username and password the manager has provided you. If you were not provided with one contact a manager. </h3>
            </div>
        </div>
    </div>
</body>
</html>
