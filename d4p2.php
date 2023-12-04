<?php

$file = explode("\n", rtrim(file_get_contents('d4.txt'), "\n"));

$sum = count($file);

$counts = array_fill(0, $sum, 1);

for ($i = 0; $i < count($file); $i++)
{
	[, $line] = explode(':', $file[$i]);
	[$wins, $ours] = explode('|', $line);
	preg_match_all('%\d+%', $wins, $matches);
	preg_match_all('%\d+%', $ours, $matches2);

	$intersect = count(array_intersect($matches[0], $matches2[0]));

	if ($intersect)
	{
		for ($j = 1; $j <= $intersect; $j++)
		{
			$counts[$i + $j] += $counts[$i];
		}
	}

	$sum += $counts[$i] - 1;
}

echo $sum, "\n";
