<?php
include 'common.php';
function login_verify($username, $password)
{
    $fp = fopen("../passwd", "r");
    if($fp)
    {
	while (($line = fgets($fp)) !== false)
	{
	    $line = str_replace("\n", '', $line);
	    [$name, $hash] = explode(" ", $line);
	    if ($name === $username)
	    {
		if (password_verify($password, $hash))
		{
		    return true;
		}
	    }
	}
	return false;
    }
}

session_start();
session_destroy();
if (isset($_POST['password']) && isset($_POST['username']))
{
    if (login_verify($_POST['username'], $_POST['password']))
    {
	session_start();
	$_SESSION['username'] = $_POST['username'];
	header("location: index.php");
	exit();
    }
    else
    {
	$login_failed = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Вхід</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<main>
<h1>Калькулятор витрат</h1>
<h2>Вхід</h2>
<?php
if (isset($login_failed))
{
    message("Неправильне ім'я користувача або пароль", true);
}
?>
<div id="login-box">
<div>
<form method="post" name="login">
<p>
<label for="username">Ім'я:</label>
<input type="text" name="username" id="username" placeholder=" " pattern="^[0-9a-zA-Z_\-]*$" required>
</p>
<p>
<label for="password">Пароль:</label>
<input type="password" name="password" id="password" placeholder=" " required>
</p>
<button type="submit">Увійти</button>
</div>
</div>
<p><a href="register.php">Cтворити обліковий запис</a></p>
</main>
</body>
</html>
