<?php

$file = explode("\n", rtrim(file_get_contents('d1.txt'), "\n"));

$sum = 0;
foreach ($file as $line)
{
	preg_match('%.*?(\d).*(\d).*%', $line, $matches);
	preg_match('%(\d)%', $line, $matches2);

	$sum += count($matches) >= 3
		? "{$matches[1]}{$matches[2]}"
		: "{$matches2[0]}{$matches2[0]}";
}

echo $sum, "\n";
