<?php

$file = explode("\n", rtrim(file_get_contents('d3.txt'), "\n"));

$sum = 0;
$starMap;
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
		if(($file[$y][$startX - 1] ?? '.') === '*')
		{
			$starMap["$y:" . $startX - 1][] = $int;
		}

		// one after endX
		if (($file[$y][$endX] ?? '.') === '*')
		{
			$starMap["$y:" . $endX][] = $int;
		}

		// row above
		for ($aX = $startX - 1; $aX <= $endX; $aX++)
		{
			if (($file[$y - 1][$aX] ?? '.') === '*')
			{
				$starMap[$y - 1 . ':' . $aX][] = $int;
			}
		}

		// row below
		for ($bX = $startX - 1; $bX <= $endX; $bX++)
		{
			if (($file[$y + 1][$bX] ?? '.') === '*')
			{
				$starMap[$y + 1 . ':' . $bX][] = $int;
			}
		}

		$startX = $x;
	}
}

foreach ($starMap as $star)
{
	count($star) === 2 && $sum += array_product($star);
}

echo $sum, "\n";
