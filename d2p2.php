<?php

$file = explode("\n", rtrim(file_get_contents('d2.txt'), "\n"));

$sum = 0;
$game = 1;
foreach ($file as $line)
{
	$picks = explode(';', $line);
	$need = ['red' => 0, 'green' => 0, 'blue' => 0];
	foreach ($picks as $pick)
	{
		$count = preg_match_all('%(\d+) (red|green|blue)%', $pick, $matches);

		for ($i = 0; $i < $count; $i++)
		{
			$matches[1][$i] > $need[$matches[2][$i]] && $need[$matches[2][$i]] = $matches[1][$i];
		}
	}

	$sum += array_product($need);

	$game++;
}

echo $sum, "\n";
