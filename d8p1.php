<?php

[$directions, $instructions] = explode("\n\n", rtrim(file_get_contents('d8.txt')));

$directions = str_split($directions);
$dirCount = count($directions);
$instructions = explode("\n", $instructions);

$map = [];

foreach ($instructions as $instruction)
{
	preg_match('%([A-Z]{3}) = \(([A-Z]{3}), ([A-Z]{3})\)%', $instruction, $matches);

	$map[$matches[1]] = [
		'L' => $matches[2],
		'R' => $matches[3],
	];
}

$steps = 0;
$curr = 'AAA';
do
{
	$curr = $map[$curr][$directions[$steps++ % $dirCount]];
}
while ($curr !== 'ZZZ');

echo $steps, "\n";
