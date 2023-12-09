<?php

[$directions, $instructions] = explode("\n\n", rtrim(file_get_contents('d8.txt')));

$directions = str_split($directions);
$dirCount = count($directions);
$instructions = explode("\n", $instructions);

$map = [];
$currs = [];

foreach ($instructions as $instruction)
{
	preg_match('%([A-Z0-9]{3}) = \(([A-Z0-9]{3}), ([A-Z0-9]{3})\)%', $instruction, $matches);

	if ($matches[1][-1] === 'A')
	{
		$currs[] = $matches[1];
	}

	$map[$matches[1]] = [
		'L' => $matches[2],
		'R' => $matches[3],
	];
}

$steps = 0;
$endZ = 1;
do
{
	$dir = $steps % $dirCount;
	$steps++;

	foreach ($currs as $key => &$curr)
	{
		$curr = $map[$curr][$directions[$dir]];

		if ($curr[-1] === 'Z')
		{
			$endZ = gmp_lcm($endZ, $steps);
			unset($currs[$key]);
		}
	}
}
while (count($currs));

echo $endZ, "\n";
