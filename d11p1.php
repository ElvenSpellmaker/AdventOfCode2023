<?php

$file = explode("\n", rtrim(file_get_contents('d11.txt'), "\n"));

$rows = array_fill_keys(range(0, count($file) - 1), 1);
$columns = [];

$galaxies = [];

$processed = [];

$sum = 0;

for ($col = 0; $col < strlen($file[0]); $col++)
{
	$column = '';
	$hashSeen = false;
	foreach ($file as $row => $line)
	{
		$column .= $line[$col];

		if ($line[$col] === '#')
		{
			$hashSeen = true;
			$galaxies[] = [$row, $col];
			unset($rows[$row]);
		}
	}

	if (! $hashSeen)
	{
		$columns[$col] = 1;
	}
}

for ($i = 0; $i < count($galaxies) - 1; $i++)
{
	// echo "$i:\n";

	for ($j = $i; $j < count($galaxies); $j++)
	{
		$steps = 0;
		[$y1, $x1] = $galaxies[$i];
		[$y2, $x2] = $galaxies[$j];

		// echo "Cmp ($y1, $x1) --> ($y2, $x2)\n";

		if ($y1 > $y2)
		{
			$tmp = $y1;
			$y1 = $y2;
			$y2 = $tmp;
		}

		if ($x1 > $x2)
		{
			$tmp = $x1;
			$x1 = $x2;
			$x2 = $tmp;
		}

		for ($dy = $y1; $dy < $y2; $dy++)
		{
			$steps++;
			array_key_exists($dy, $rows) && $steps++;
		}

		for ($dx = $x1; $dx < $x2; $dx++)
		{
			$steps++;
			array_key_exists($dx, $columns) && $steps++;
		}

		// echo "steps: $steps\n\n";

		$sum += $steps;
	}
}

echo $sum, "\n";
