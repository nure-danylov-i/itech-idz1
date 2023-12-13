<?php
include 'common.php';
session_start();
if (!isset($_SESSION['username']) or $_SESSION['username'] === '')
{
    header('location: login.php');
    exit();
}
$filename = "../data/{$_SESSION['username']}.csv";
?>
<!DOCTYPE html>
<html>
<head>
<title>Калькулятор витрат</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<main>
<h1>Калькулятор витрат</h1>
<?php
echo "<p id=\"user-info\">Ви ввійшли як <b>{$_SESSION['username']}</b> | <a href=login.php>Bихід</a></p>";

if (!file_exists($filename))
{
    $fp = fopen($filename, "w");
    fclose($fp);
}

if(isset($_POST["item"])) {
    $item = htmlentities($_POST["item"]);
    $item = str_replace(',', '&#044;', $item);
    $value = number_format((float)$_POST["value"], 2, '.', '');
    $data = "{$item},{$value}\n";
    file_put_contents($filename, $data, FILE_APPEND | LOCK_EX);
    message("Додано <b>{$item}</b> за ціною <b>{$value}</b>");
}

if (filesize($filename) !== 0)
{
    $fp = fopen($filename, "r");
    if ($fp)
    {
	echo '<table><tr><th>Назва позиції</th><th>Вартість</th>';

        $sum = 0;
        $count = 0;

	while (($buffer = fgets($fp)) !== false) 
	{
            $row = explode(",", $buffer);
            echo "<tr>";
	    foreach ($row as &$entry) 
	    {
        	echo "<td>$entry</td>";
            }
            $sum += $entry;
	    $count++;
            unset($entry);
            echo "</tr>";
        }

        if($count > 1)
        {
	    $sum = number_format($sum, 2, '.', '');
	    echo "<tr id=\"sum\"><td>Сума</td><td>{$sum}</td></tr>";
        }
	echo '</table>';
    }
}
else
{
    message("Поки що немає витрат.");
}
?>
<div id="add-box">
<form method="post" id="new-item">
<input type="text" name="item" id="item" placeholder="Назва позиції" required>
<input type="number" name="value" id="value" placeholder="Вартість" step="0.01" min="0" required>
<button type="submit" id="add">Додати</button>
</form>
</div>
</main>
</body>
</html>
