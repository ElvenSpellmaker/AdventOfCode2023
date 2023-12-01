<?php

$file = explode("\n", rtrim(file_get_contents('d1.txt'), "\n"));

$sum = 0;
foreach ($file as $line)
{
	preg_match('%.*?(\d).*(\d).*%', $line, $matches);

	if (count($matches) >= 3)
	{
		$sum += "{$matches[1]}{$matches[2]}";
	}
	else
	{
		preg_match('%\d%', $line, $matches);

		$sum += "{$matches[0]}{$matches[0]}";
	}
}

echo $sum, "\n";
