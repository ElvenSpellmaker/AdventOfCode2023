<?php

$file = explode("\n", rtrim(file_get_contents('d4.txt'), "\n"));

$sum = 0;

foreach ($file as $line)
{
	[, $line] = explode(':', $line);
	[$wins, $ours] = explode('|', $line);
	preg_match_all('%\d+%', $wins, $matches);
	preg_match_all('%\d+%', $ours, $matches2);

	$intersect = count(array_intersect($matches[0], $matches2[0]));

	$intersect && $sum += 1 << ($intersect - 1);
}

echo $sum, "\n";
