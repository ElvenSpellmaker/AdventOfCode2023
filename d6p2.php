<?php

[$time, $distance] = explode("\n", rtrim(file_get_contents('d6.txt'), "\n"));

preg_match_all('%\d+%', $time, $matches);
preg_match_all('%\d+%', $distance, $matches2);

$matches = join('', $matches[0]);
$matches2 = join('', $matches2[0]);

$waysToWin = 0;

for (
	$holdTime = 1, $moveTime = $matches - $holdTime, $moveDistance = $holdTime * $moveTime;
	$holdTime < $matches;
	$holdTime++, $moveTime--, $moveDistance = $holdTime * $moveTime, $moveDistance > $matches2 && $waysToWin++
);

echo $waysToWin, "\n";
