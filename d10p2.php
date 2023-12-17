<?php

$file = rtrim(file_get_contents('d10.txt'));

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
		'curr' => [$y * 2, $x * 2],
		'steps' => 1,
		'next' => [$y * 2, $x * 2 - 1],
		'start' => '0:-1',
	];
// right
in_array(($file[$y][$x + 1] ?? '.'), $validMoves['0:1'])
	&& $valid[] = [
		'curr' => [$y * 2, $x * 2],
		'steps' => 1,
		'next' => [$y * 2, $x * 2 + 1],
		'start' => '0:1',
	];
// up
in_array(($file[$y - 1][$x] ?? '.'), $validMoves['-1:0'])
	&& $valid[] = [
		'curr' => [$y * 2, $x * 2],
		'steps' => 1,
		'next' => [$y * 2 - 1, $x * 2],
		'start' => '-1:0',
	];
// down
in_array(($file[$y + 1][$x] ?? '.'), $validMoves['1:0'])
	&& $valid[] = [
		'curr' => [$y * 2, $x * 2],
		'steps' => 1,
		'next' => [$y * 2 + 1, $x * 2],
		'start' => '1:0',
	];

[$dir1, $dir2] = $valid;

$chars = [];

// Map Start to new chars
// left - right
// S-
// ..
$chars['0:-1<>0:1'] = ['S', '-', '.', '.'];
// left - up
// S.
// ..
$chars['0:-1<>-1:0'] = ['S', '.', '.', '.'];
// left - down
// S.
// |.
$chars['0:-1<>1:0'] = ['S', '.', '|', '.'];
// right - up
// S-
// ..
$chars['0:1<>-1:0'] = ['S', '-', '.', '.'];
// right - down
// S-
// |.
$chars['0:1<>1:0'] = ['S', '-', '|', '.'];
// up - down
// S.
// |.
$chars['-1:0<>1:0'] = ['S', '.', '|', '.'];

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

$maxY = count($file);
$maxX = strlen($file[0]);

// Expand Map
$newMap = [];
for ($y = 0; $y < $maxY; $y++)
{
	for ($x = 0; $x < $maxX; $x++)
	{
		switch ($file[$y][$x])
		{
			case '.':
				// ..
				// ..
				$newMap[$y * 2][$x * 2] = '.';
				$newMap[$y * 2][$x * 2 + 1] = '.';
				$newMap[$y * 2 + 1][$x * 2] = '.';
				$newMap[$y * 2 + 1][$x * 2 + 1] = '.';
			break;
			case '|':
				// |.
				// |.
				$newMap[$y * 2][$x * 2] = '|';
				$newMap[$y * 2][$x * 2 + 1] = '.';
				$newMap[$y * 2 + 1][$x * 2] = '|';
				$newMap[$y * 2 + 1][$x * 2 + 1] = '.';
			break;
			case '-':
				// --
				// ..
				$newMap[$y * 2][$x * 2] = '-';
				$newMap[$y * 2][$x * 2 + 1] = '-';
				$newMap[$y * 2 + 1][$x * 2] = '.';
				$newMap[$y * 2 + 1][$x * 2 + 1] = '.';
			break;
			case 'L':
				// L-
				// ..
				$newMap[$y * 2][$x * 2] = 'L';
				$newMap[$y * 2][$x * 2 + 1] = '-';
				$newMap[$y * 2 + 1][$x * 2] = '.';
				$newMap[$y * 2 + 1][$x * 2 + 1] = '.';
			break;
			case 'J':
				// J.
				// ..
				$newMap[$y * 2][$x * 2] = 'J';
				$newMap[$y * 2][$x * 2 + 1] = '.';
				$newMap[$y * 2 + 1][$x * 2] = '.';
				$newMap[$y * 2 + 1][$x * 2 + 1] = '.';
			break;
			case '7':
				// 7.
				// |.
				$newMap[$y * 2][$x * 2] = '7';
				$newMap[$y * 2][$x * 2 + 1] = '.';
				$newMap[$y * 2 + 1][$x * 2] = '|';
				$newMap[$y * 2 + 1][$x * 2 + 1] = '.';
			break;
			case 'F':
				// F-
				// |.
				$newMap[$y * 2][$x * 2] = 'F';
				$newMap[$y * 2][$x * 2 + 1] = '-';
				$newMap[$y * 2 + 1][$x * 2] = '|';
				$newMap[$y * 2 + 1][$x * 2 + 1] = '.';
			break;
			case 'S':
				$newMap[$y * 2][$x * 2] = $chars[$dir1['start'] . '<>' . $dir2['start']][0];
				$newMap[$y * 2][$x * 2 + 1] = $chars[$dir1['start'] . '<>' . $dir2['start']][1];
				$newMap[$y * 2 + 1][$x * 2] = $chars[$dir1['start'] . '<>' . $dir2['start']][2];
				$newMap[$y * 2 + 1][$x * 2 + 1] = $chars[$dir1['start'] . '<>' . $dir2['start']][3];
			break;
		}
	}
}

// Munge all the things
$maxY *= 2;
$maxX *= 2;
$dir = $dir1;
$file = $newMap;

$mazeTiles[$dir['curr'][0] . ':' . $dir['curr'][1]] = 1;
do
{
	$dir = find_next_square($dir);
	$mazeTiles[$dir['curr'][0] . ':' . $dir['curr'][1]] = 1;
}
while ($file[$dir['next'][0]][$dir['next'][1]] !== 'S');

$outsideTiles = [];
$insideTiles = [];

$creepedSquares = [];


// Draws new expanded grid, red highlights maze blocks
// for ($y2 = 0; $y2 < $maxY; $y2++)
// {
// 	for ($x2 = 0; $x2 < $maxX; $x2++)
// 	{
// 		echo isset($mazeTiles[$y2 . ':' . $x2])
// 			? "\033[0;31m" . $file[$y2][$x2] . "\033[0m"
// 			: (
// 				isset($outsideTiles[$y2 . ':' . $x2])
// 					? 'O'
// 					: 'I'
// 			);
// 	}

// 	echo "\n";
// }

for ($y = 0; $y < $maxY; $y++)
{
	for ($x = 0; $x < $maxX; $x++)
	{
		// $searchY = 19;
		// $searchX = 19;

		// Maze tiles can't be checked, and if we're already outside then continue
		if (isset($mazeTiles[$y . ':' . $x]) || isset($outsideTiles[$y . ':' . $x]))
		{
			// if ($x === $searchX && $y === $searchY)
			// {
			// 	echo "\n";
			// 	for ($y2 = 0; $y2 < $maxY; $y2++)
			// 	{
			// 		for ($x2 = 0; $x2 < $maxX; $x2++)
			// 		{
			// 			if ($searchY === $y2 && $searchX === $x2)
			// 			{
			// 				echo "\033[1m";
			// 			}
			// 			echo isset($mazeTiles[$y2 . ':' . $x2])
			// 				? "\033[0;31m" . $file[$y2][$x2] . "\033[0m"
			// 				: (
			// 					isset($outsideTiles[$y2 . ':' . $x2])
			// 						? 'O'
			// 						: 'I'
			// 				);
			// 			if ($searchY === $y2 && $searchX === $x2)
			// 			{
			// 				echo "\033[0m";
			// 			}
			// 		}

			// 		echo "\n";
			// 	}

			// 	var_dump($outsideTiles);
			// 	echo "skippy";
			// 	exit;
			// }
			continue;
		}

		$searchTiles = [
			$y . ':' . $x - 1 => [$y, $x - 1],
			$y . ':' . $x + 1 => [$y, $x + 1],
			$y - 1 . ':' . $x => [$y - 1, $x],
			$y + 1 . ':' . $x => [$y + 1, $x],
		];

		$findBlocks = $searchTiles;

		$creepedSquares[$y . ':' . $x] = [$y, $x];

		do
		{
			// if ($x === $searchX && $y === $searchY)
			// {
			// 	echo "--start--";
			// 	var_dump($searchTiles);
			// 	var_dump($findBlocks);
			// 	echo "--end";
			// 	echo "\n\n";
			// 	exit;
			// }

			foreach ($searchTiles as $stKey => [$stY, $stX])
			{
				$creepedSquares[$stY . ':' . $stX] = [$stY, $stX];

				if (
					$stY < 0
					|| $stY >= $maxY
					|| $stX < 0
					|| $stX >= $maxX
				)
				{
					$outsideTiles[$y . ':' . $x] = 1;
					unset($findBlocks[$stY . ':' . $stX]);
				}
				elseif (isset($mazeTiles[$stY . ':' . $stX]))
				{
					unset($findBlocks[$stY . ':' . $stX]);
				}
				elseif (isset($outsideTiles[$stY . ':' . $stX]))
				{
					$outsideTiles[$y . ':' . $x] = 1;
					unset($findBlocks[$stY . ':' . $stX]);
				}
				elseif (! isset($outsideTiles[$y . ':' . $x]))
				{
					// echo 'creep';
					$newSearchTiles = [];

					// Find all four squares from here if not visited before
					if (! isset($creepedSquares[$stY . ':' . $stX - 1]))
					{
						$newSearchTiles[$stY . ':' . $stX - 1] = [$stY, $stX - 1];
					}
					elseif (isset($outsideTiles[$stY . ':' . $stX - 1]))
					{
						{
							$outsideTiles[$y . ':' . $x] = 1;
							unset($findBlocks[$stY . ':' . $stX]);
						}
					}

					if (! isset($creepedSquares[$stY . ':' . $stX + 1]))
					{
						$newSearchTiles[$stY . ':' . $stX + 1] = [$stY, $stX + 1];
					}
					elseif (isset($outsideTiles[$stY . ':' . $stX + 1]))
					{
						$outsideTiles[$y . ':' . $x] = 1;
						unset($findBlocks[$stY . ':' . $stX]);
					}

					if (! isset($creepedSquares[$stY - 1 . ':' . $stX]))
					{
						$newSearchTiles[$stY - 1 . ':' . $stX] = [$stY - 1, $stX];
					}
					elseif (isset($outsideTiles[$stY - 1 . ':' . $stX]))
					{
						$outsideTiles[$y . ':' . $x] = 1;
						unset($findBlocks[$stY . ':' . $stX]);
					}

					if (! isset($creepedSquares[$stY + 1 . ':' . $stX]))
					{
						$newSearchTiles[$stY + 1 . ':' . $stX] = [$stY + 1, $stX];
					}
					elseif (isset($outsideTiles[$stY + 1 . ':' . $stX]))
					{
						$outsideTiles[$y . ':' . $x] = 1;
						unset($findBlocks[$stY . ':' . $stX]);
					}

					$creepedSquares = array_merge($creepedSquares, $newSearchTiles);

					$searchTiles = array_merge($searchTiles, $newSearchTiles);

					$findBlocks = array_merge($findBlocks, $newSearchTiles);

					// if ($x === $searchX && $y === $searchY)
					// {
					// 	echo 'beall';
					// 	var_dump($newSearchTiles, $creepedSquares);
					// 	echo "endall";
					// }
				}

				unset($searchTiles[$stKey]);
			}

			// if ($x === $searchX && $y === $searchY)
			// {
			// 	echo "--startl--";
			// 	// var_dump($searchTiles);
			// 	// var_dump($findBlocks);
			// 	// var_dump(array_intersect_key($searchTiles, $creepedSquares));
			// 	echo "--endl";
			// 	echo "\n\n";

			// 	for ($y2 = 0; $y2 < $maxY; $y2++)
			// 	{
			// 		for ($x2= 0; $x2 < $maxX; $x2++)
			// 		{
			// 			if ($searchY === $y2 && $searchX === $x2)
			// 			{
			// 				echo "\033[1m";
			// 			}
			// 			if (isset($searchTiles[$y2 . ':' . $x2]))
			// 			{
			// 				echo "\033[48;5;32m";
			// 			}
			// 			echo isset($mazeTiles[$y2 . ':' . $x2])
			// 				? "\033[38;5;196m" . $file[$y2][$x2] . "\033[0m"
			// 				: (
			// 					isset($outsideTiles[$y2 . ':' . $x2])
			// 						? 'O'
			// 						: 'I'
			// 				);
			// 			if ($searchY === $y2 && $searchX === $x2)
			// 			{
			// 				echo "\033[0m";
			// 			}
			// 			if (isset($searchTiles[$y2 . ':' . $x2]))
			// 			{
			// 				echo "\033[0m";
			// 			}
			// 		}

			// 		echo "\n";
			// 	}

			// 	exit;
			// 	sleep(5);
			// }
		}
		while(count($searchTiles));

		if (isset($outsideTiles[$y . ':' . $x]))
		{
			foreach ($findBlocks as [$fbY, $fbX])
			{
				// if (isset($mazeTiles[$fbY . ':' . $fbX]))
				// {
				// 	echo 'PANIC', "$fbY:$fbX", "\n";exit;
				// }
				$outsideTiles[$fbY . ':' . $fbX] = 1;
			}
		}

		// if ($x === $searchX && $y === $searchY)
		// {
		// 	echo "\n";
		// 	for ($y2 = 0; $y2 < $maxY; $y2++)
		// 	{
		// 		for ($x2= 0; $x2 < $maxX; $x2++)
		// 		{
		// 			if ($searchY === $y2 && $searchX === $x2)
		// 			{
		// 				echo "\033[1m";
		// 			}
		// 			echo isset($mazeTiles[$y2 . ':' . $x2])
		// 				? "\033[0;31m" . $file[$y2][$x2] . "\033[0m"
		// 				: (
		// 					isset($outsideTiles[$y2 . ':' . $x2])
		// 						? 'O'
		// 						: 'I'
		// 				);
		// 			if ($searchY === $y2 && $searchX === $x2)
		// 			{
		// 				echo "\033[0m";
		// 			}
		// 		}

		// 		echo "\n";
		// 	}
		// 	// echo "Outside:", var_dump($outsideTiles);
		// 	exit;
		// }
	}
}

$size = $maxX * $maxY;

// var_dump($size, count($outsideTiles), count($mazeTiles));

// for ($y = 0; $y < $maxY; $y++)
// {
// 	for ($x = 0; $x < $maxX; $x++)
// 	{
// 		echo isset($mazeTiles[$y . ':' . $x])
// 			? $file[$y][$x]
// 			: (
// 				isset($outsideTiles[$y . ':' . $x])
// 					? 'O'
// 					: 'I'
// 			);
// 	}

// 	echo "\n";
// }

// ---- Draw Full Expanded Grid, red highlights maze blocks ----
// for ($y = 0; $y < $maxY; $y++)
// {
// 	for ($x = 0; $x < $maxX; $x++)
// 	{
// 		echo isset($mazeTiles[$y . ':' . $x])
// 			? "\033[0;31m" . $file[$y][$x] . "\033[0m"
// 			: (
// 				isset($outsideTiles[$y . ':' . $x])
// 					? 'O'
// 					: 'I'
// 			);
// 	}

// 	echo "\n";
// }
// --------

$inside = 0;

for ($y = 0; $y < $maxY; $y += 2)
{
	for ($x = 0; $x < $maxX; $x += 2)
	{
		$inside += ! isset($mazeTiles[$y . ':' . $x]) && ! isset($outsideTiles[$y . ':' . $x]);

		// Draw Squished Grid, red highlights maze blocks
		// echo isset($mazeTiles[$y . ':' . $x])
		// 	? "\033[0;31m" . $file[$y][$x] . "\033[0m"
		// 	: (
		// 		isset($outsideTiles[$y . ':' . $x])
		// 			? 'O'
		// 			: 'I'
		// 	);
	}

	// echo "\n";
}

echo $inside, "\n";
