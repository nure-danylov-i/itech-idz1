<?php
include 'common.php';
function user_exists($username)
{
    $fp = fopen("../passwd", "r");
    if($fp)
    {
	while (($line = fgets($fp)) !== false)
	{
	    $name = explode(" ", $line)[0];
	    if ($name === $username)
	    {
		return true;
	    }
	}
	return false;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Реєстрація</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<main>
<h1>Калькулятор витрат</h1>
<h2>Реєстрація</h2>
<?php
if (isset($_POST['password']))
{
    if ($_POST['username'] === '')
    {
        message("Ім'я не може бути пустим", true);
    } 
    else if (!preg_match('/^[0-9a-zA-Z_\-]*$/', $_POST['username']))
    {
        $username = htmlspecialchars($_POST['username']);
        message("<b>{$username}</b> - недопустиме ім'я. Ім'я може містити тільки латинські літери, цифри, - та _", true);
    }
    else if ($_POST['password'] === '')
    {
        message("Пароль не може бути пустим", true);
    }
    else if ($_POST['password'] !== $_POST['password-repeat'])
    {
        message("Паролi не співпадають", true);
    }
    else if (user_exists($_POST['username']))
    {
        $username = htmlspecialchars($_POST['username']);
        message("Ім'я <b>{$username}</b> вже зайняте", true);
    }
    else
    {
        $pass_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $username = $_POST['username'];
        $str = "$username $pass_hash\n";
        file_put_contents('../passwd', $str, FILE_APPEND | LOCK_EX);
        message("Реєстрація успішна! Тепер ви можете <a href=\"login.php\">увійти.</a>");
    }
}
?>
<div id="login-box">
<div>
<form method="post" name="login">
<p>
<label for="username">Ім'я:</label>
<input type="text" name="username" id="username" placeholder=" " pattern="^[0-9a-zA-Z_\-]*$" 
title="Може містити латинські літери, цифри, - та _" required>
</p>
<p>
<label for="password">Пароль:</label>
<input type="password" name="password" id="password" placeholder=" " required>
</p>
<p>
<label for="password-repeat">Повторіть пароль:</label>
<input type="password" name="password-repeat" id="password-repeat" placeholder=" " required>
</p>
<p>
<button type="submit">Зареєструватися</button>
</p>
</div>
</div>
<p><a href="login.php">Увійти в існуючий обліковий запис</a></p>
</main>
</body>
</html>
