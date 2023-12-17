<?php

$file = rtrim(file_get_contents('d10-sample.txt'));

$sPos = strpos($file, 'S');

$file = explode("\n", $file);

$lineLength = strlen($file[0]) + 1;

$y = intdiv($sPos, $lineLength);
$x = ($sPos % $lineLength);

$validMoves = [
	'0:-1' => [
		'-',
		'L',
		'F',
	],
	'0:1' => [
		'-',
		'J',
		'7',
	],
	'-1:0' => [
		'|',
		'7',
		'F',
	],
	'1:0' => [
		'|',
		'L',
		'J',
	],
];

$validFromMoves = [
	'-' => ['0:-1' => [0, -1], '0:1' => [0, 1]],
	'|' => ['-1:0' => [-1, 0], '1:0' => [1, 0]],
	'L' => ['-1:0' => [-1, 0], '0:1' => [0, 1]],
	'J' => ['-1:0' => [-1, 0], '0:-1' => [0, -1]],
	'7' => ['0:-1' => [0, -1], '1:0' => [1, 0]],
	'F' => ['0:1' => [0, 1], '1:0' => [1, 0]],
];

$valid = [];
// left
in_array(($file[$y][$x - 1] ?? '.'), $validMoves['0:-1'])
	&& $valid[] = [
		'curr' => [$y, $x],
		'steps' => 1,
		'next' => [$y, $x - 1],
	];
// right
in_array(($file[$y][$x + 1] ?? '.'), $validMoves['0:1'])
	&& $valid[] = [
		'curr' => [$y, $x],
		'steps' => 1,
		'next' => [$y, $x + 1],
	];
// up
in_array(($file[$y - 1][$x] ?? '.'), $validMoves['-1:0'])
	&& $valid[] = [
		'curr' => [$y, $x],
		'steps' => 1,
		'next' => [$y - 1, $x],
	];
// down
in_array(($file[$y + 1][$x] ?? '.'), $validMoves['1:0'])
	&& $valid[] = [
		'curr' => [$y, $x],
		'steps' => 1,
		'next' => [$y + 1, $x],
	];

[$dir1, $dir2] = $valid;

function find_next_square(array $dir) : array
{
	global $file, $validFromMoves;

	[$prevY, $prevX] = $dir['curr'];
	// Move to square
	$dir['curr'] = $dir['next'];
	$dir['steps']++;

	[$currY, $currX] = $dir['curr'];

	$newPotentialMoves = $validFromMoves[$file[$currY][$currX]];

	// Can't come from the original direction
	unset($newPotentialMoves[$prevY - $currY . ':' . $prevX - $currX]);

	[$newY, $newX] = array_pop($newPotentialMoves);

	$dir['next'] = [$currY + $newY, $currX + $newX];

	return $dir;
}

do
{
	$dir1 = find_next_square($dir1);
	$dir2 = find_next_square($dir2);
}
while ($dir1['next'] !== $dir2['next']);

echo $dir1['steps'], "\n";
