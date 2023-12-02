<?php

$file = explode("\n", rtrim(file_get_contents('d2.txt'), "\n"));

$totals = [
	'red' => 12,
	'green' => 13,
	'blue' => 14,
];

$sum = 0;
$game = 1;
foreach ($file as $line)
{
	$valid = true;
	$picks = explode(';', $line);
	foreach ($picks as $pick)
	{
		$count = preg_match_all('%(\d+) (red|green|blue)%', $pick, $matches);

		for ($i = 0; $i < $count; $i++)
		{
			if (! ($valid = $matches[1][$i] <= $totals[$matches[2][$i]]))
			{
				break 2;
			}
		}
	}

	$valid && $sum += $game;

	$game++;
}

echo $sum, "\n";
