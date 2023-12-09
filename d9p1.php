<?php

$file = explode("\n", rtrim(file_get_contents('d9.txt'), "\n"));

function find_next_number(array $arr) : int
{
	$diffs = [];
	$allZero = true;
	for ($i = 1; $i < count($arr); $i++)
	{
		$diffElem = $arr[$i] - $arr[$i - 1];
		$diffElem !== 0 && $allZero = false;
		$diffs[] = $diffElem;
	}

	if ($allZero)
	{
		return end($arr);
	}

	return end($arr) + find_next_number($diffs);
}

$sum = 0;
foreach ($file as $line)
{
	$sum += find_next_number(explode(' ', $line));
}

echo $sum, "\n";
