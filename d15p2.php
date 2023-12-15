<?php

$file = explode(',', rtrim(file_get_contents('d15.txt'), "\n"));

function reduction(string $str) : int
{
	$str = str_split($str);
	return array_reduce($str, function($carry, $item) {
		return (($carry + ord($item)) * 17) % 256;
	}, 0);
}

$hashMap = array_fill(0, 256, []);

foreach ($file as $item)
{
	preg_match('%([a-z]+)(-|=)([0-9])?%', $item, $matches);

	$hash = reduction($matches[1]);

	switch ($matches[2])
	{
		case '=':
			$hashMap[$hash][$matches[1]] = $matches[3];
		break;
		case '-':
			unset($hashMap[$hash][$matches[1]]);
		break;
	}
}

echo array_reduce($hashMap, function($carry, $item) {
	$sum = $carry['sum'];
	$slot = 1;
	foreach ($item as $lens)
	{
		$sum += $carry['box'] * $slot++ * $lens;
	}

	return ['box' => $carry['box'] + 1, 'sum' => $sum];
}, ['box' => 1, 'sum' => 0])['sum'], "\n";
