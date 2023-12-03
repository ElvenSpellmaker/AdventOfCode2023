<?php

$file = explode("\n", rtrim(file_get_contents('d3.txt'), "\n"));

$sum = 0;
for ($y = 0; $y < count($file); $y++)
{
	$startX = 0;
	$endX = 0;

	while ($endX < strlen($file[0]))
	{
		$int = '';
		$isPart = true;

		if (! is_numeric($file[$y][$startX]))
		{
			$startX++;
			$endX++;
			continue;
		}

		for ($x = $startX; $x < strlen($file[0]); $x++, $endX++)
		{
			if (! is_numeric($file[$y][$x]))
			{
				break;
			}

			$int .= $file[$y][$x];
		}

		// one before startX
		$validStart = ($file[$y][$startX - 1] ?? '.') !== '.';

		// one after endX
		$validEnd = ($file[$y][$endX] ?? '.') !== '.';

		// row above
		$validAbove = false;
		for ($aX = $startX - 1; $aX <= $endX; $aX++)
		{
			$validAbove = $validAbove || ($file[$y - 1][$aX] ?? '.') !== '.';
		}

		// row below
		$validBelow = false;
		for ($bX = $startX - 1; $bX <= $endX; $bX++)
		{
			$validBelow = $validBelow || ($file[$y + 1][$bX] ?? '.') !== '.';
		}

		if ($validStart || $validEnd || $validAbove || $validBelow)
		{
			$sum += $int;
		}

		$startX = $x;
	}
}

echo $sum, "\n";
