<?php

$file = array_map('explode_on_newline', explode("\n\n", rtrim(file_get_contents('d13.txt'), "\n")));

function explode_on_newline(string $string) : array
{
	return explode("\n", $string);
}

$sum = 0;

$vertSquares = [];

function find_symmetry(array $square, int $factor, int &$sum) : void
{
	$prevLine = '';
	foreach ($square as $k => $line)
	{
		$lev = false;
		if ($line !== $prevLine)
		{
			if (levenshtein($line, $prevLine) !== 1)
			{
				goto retry;
			}

			$lev = true;
		}

		$count = $k;
		$hangUp = count($square) - $k < $k;
		$hangDown = count($square) - $k > $k;

		$pK = $k + 1;
		$k -= 2;
		do
		{
			$s1 = ($square[$k] ?? ($hangDown ? $square[$pK] : ''));
			$s2 = ($square[$pK] ?? ($hangUp ? $square[$k] : ''));
			$match = $s1 === $s2;

			if (! $match && $lev === false && levenshtein($s1, $s2) === 1)
			{
				$match = true;
				$lev = true;
			}

			$k--;
			$pK++;
		}
		while ($match && $k >= 0 && $pK < count($square));

		if ($match && $lev)
		{
			$sum += $factor * $count;

			return;
		}

		retry:
		$prevLine = $line;
	}
}

// Horizontal
foreach ($file as $square)
{
	find_symmetry($square, 100, $sum);
}

// Vertical
foreach ($file as $vertSquare)
{
	$rotSquare = [];

	$vertSquare = array_map('str_split', $vertSquare);

	// Rotate it
	for ($i = 0; $i < count($vertSquare[0]); $i++)
	{
		$rotSquare[] = join('', array_column($vertSquare, $i));
	}

	find_symmetry($rotSquare, 1, $sum);
}

echo $sum, "\n";
