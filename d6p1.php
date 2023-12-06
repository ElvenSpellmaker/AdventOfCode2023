<?php

[$time, $distance] = explode("\n", rtrim(file_get_contents('d6.txt'), "\n"));

preg_match_all('%\d+%', $time, $matches);
preg_match_all('%\d+%', $distance, $matches2);

$waysToWin = array_fill(0, count($matches[0]), 0);

for ($i = 0; $i < count($matches[0]); $i++)
{
	for (
		$holdTime = 1, $moveTime = $matches[0][$i] - $holdTime, $moveDistance = $holdTime * $moveTime;
		$holdTime < $matches[0][$i];
		$holdTime++, $moveTime--, $moveDistance = $holdTime * $moveTime, $moveDistance > $matches2[0][$i] && $waysToWin[$i]++
	);
}

echo array_product($waysToWin), "\n";
