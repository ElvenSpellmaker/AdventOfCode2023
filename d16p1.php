<?php

$file = explode("\n", rtrim(file_get_contents('d16.txt')));

enum Direction
{
	case LEFT;
	case RIGHT;
	case UP;
	case DOWN;
}

$dirs = [
	Direction::LEFT->name => [0, -1],
	Direction::RIGHT->name => [0, 1],
	Direction::UP->name => [-1, 0],
	Direction::DOWN->name => [1, 0],
];

$dirsChange = [
	'/' => [
		Direction::LEFT->name => Direction::DOWN,
		Direction::RIGHT->name => Direction::UP,
		Direction::UP->name => Direction::RIGHT,
		Direction::DOWN->name => Direction::LEFT,
	],
	'\\' => [
		Direction::LEFT->name => Direction::UP,
		Direction::RIGHT->name => Direction::DOWN,
		Direction::UP->name => Direction::LEFT,
		Direction::DOWN->name => Direction::RIGHT,
	],
];

$beams = [['y' => 0, 'x' => 0, 'dir' => Direction::RIGHT]];

$maxY = count($file);
$maxX = strlen($file[0]);

$energised = [];
$seenSplit = [];

do
{
	$prevEnegergised = count($energised, COUNT_RECURSIVE);
	foreach ($beams as $k => ['y' => $y, 'x' => $x, 'dir' => $dir])
	{
		if ($y < 0 || $y >= $maxY || $x < 0 || $x >= $maxX)
		{
			unset($beams[$k]);
			continue;
		}

		switch ($dir)
		{
			case Direction::RIGHT:
			case Direction::LEFT:
				$ignoreBeam = '-';
			break;
			case Direction::UP:
			case Direction::DOWN:
				$ignoreBeam = '|';
			break;
		}

		$curr = $file[$y][$x];

		$energised["$y:$x"][$dir->name] = 1;

		switch ($curr)
		{
			case '/':
			case '\\':
				$beams[$k]['dir'] = $dirsChange[$curr][$dir->name];
			case '.':
			case $ignoreBeam:
				[$dY, $dX] = $dirs[$beams[$k]['dir']->name];
				$beams[$k]['y'] += $dY;
				$beams[$k]['x'] += $dX;
			break;
			case '-':
				if (! isset($seenSplit["$y:$x"]))
				{
					$seenSplit["$y:$x"] = true;
					$beams[$k]['dir'] = Direction::LEFT;
					$beams[$k]['x']--;
					$beams[] = [
						'y' => $y,
						'x' => $x + 1,
						'dir' => Direction::RIGHT,
					];
				}
			break;
			case '|':
				if (! isset($seenSplit["$y:$x"]))
				{
					$seenSplit["$y:$x"] = true;
					$beams[$k]['dir'] = Direction::UP;
					$beams[$k]['y']--;
					$beams[] = [
						'y' => $y + 1,
						'x' => $x,
						'dir' => Direction::DOWN,
					];
				}
			break;
			break;
		}
	}
}
while(count($beams) && count($energised, COUNT_RECURSIVE) !== $prevEnegergised);

echo count($energised), "\n";
