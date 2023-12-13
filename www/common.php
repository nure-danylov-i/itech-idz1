<?php
function message($string, $error = false)
{
    if($error)
    {
	$class = "message error";
    }
    else
    {
	$class = "message";
    }
    echo "<p class=\"{$class}\">";
    echo $string;
    echo "</p>";
}
