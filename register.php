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
            <form action="includes/signup.inc.php" method="post">
                <label for="chk" aria-hidden="true">Sign Up</label>
                <select name="usertype" required>
                    <option value="employee">Employee</option>
                    <option value="manager">Manager</option>
                    <option value="accountant">Accountant</option>
                </select>
                <input type="text" name="uid" placeholder="Username">
                <input type="password" name="pwd" placeholder="Password">
                <input type="password" name="pwdrepeat" placeholder="Repeat Password">
                <input type="text" name="email" placeholder="E-mail">
                <button type="submit" name="submit">SIGN UP</button>
            </form>
        </div>
        <div class="login">
		    <label for="chk" aria-hidden="true">Info</label>
            <div class="info">
                <h2>Manager</h2>
                <h3>An account that will be able to monitor every requriement and accept or decline them.</h3>
                <h2>Employee</h2>
                <h3>An account that will be able to create new requirements and see the status of their own entries.</h3>
                <h2>Accountant</h2>
                <h3>An account that will be able to see all the entries but won't be able to accept or decline them.</h3>
            </div>
        </div>
    </div>
</body>
</html>
