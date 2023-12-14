<?php

$file = explode("\n", rtrim(file_get_contents('d14.txt')));
$file = array_map('str_split', $file);

$times = 1000000000;
$origTimes = $times;

$runs = 0;
$loads = [];
$grids = [];
$cycle = -1;

while ($times--)
{
	// North
	do
	{
		$change = false;
		for ($i = 1; $i < count($file); $i++)
		{
			$k = $i - 1;
			for ($j = 0; $j < count($file[$i]); $j++)
			{
				if ($file[$i][$j] !== 'O')
				{
					continue;
				}

				if ($file[$k][$j] !== '.')
				{
					continue;
				}

				$change = true;

				$file[$k][$j] = 'O';
				$file[$i][$j] = '.';
			}
		}
	}
	while ($change);

	// West
	do
	{
		$change = false;
		for ($j = 1; $j < count($file[0]); $j++)
		{
			for ($i = 0; $i < count($file); $i++)
			{
				$k = $j - 1;
				if ($file[$i][$j] !== 'O')
				{
					continue;
				}

				if ($file[$i][$k] !== '.')
				{
					continue;
				}

				$change = true;

				$file[$i][$k] = 'O';
				$file[$i][$j] = '.';
			}
		}
	}
	while ($change);

	// South
	do
	{
		$change = false;
		$load = 0;
		for ($i = count($file) - 2; $i >= 0; $i--)
		{
			$k = $i + 1;
			for ($j = 0; $j < count($file[$i]); $j++)
			{
				if ($file[$i][$j] !== 'O')
				{
					continue;
				}

				$load += count($file) - $i;

				if ($file[$k][$j] !== '.')
				{
					continue;
				}

				$change = true;

				$file[$k][$j] = 'O';
				$file[$i][$j] = '.';
			}
		}
	}
	while ($change);

	// East
	do
	{
		$change = false;
		for ($j = count($file[0]) - 2; $j >= 0; $j--)
		{
			$k = $j + 1;
			for ($i = 0; $i < count($file); $i++)
			{
				if ($file[$i][$j] !== 'O')
				{
					continue;
				}

				if ($file[$i][$k] !== '.')
				{
					continue;
				}

				$change = true;

				$file[$i][$k] = 'O';
				$file[$i][$j] = '.';
			}
		}
	}
	while ($change);

	foreach ($file[count($file) - 1] as $letter)
	{
		$load += $letter === 'O';
	}

	$loads[] = $load;

	$grid = '';
	foreach ($file as $line)
	{
		$grid .= join('', $line);
	}

	if (isset($grids[$grid]))
	{
		$cycle = $grids[$grid];

		break;
	}

	$grids[$grid] = $runs;

	$runs++;
}

$index = $cycle === -1
	? $runs - 1
	: ($origTimes - $runs - 1) % ($runs - $cycle) + $cycle;

echo $loads[$index], "\n";
