<?php

$file = explode("\n", rtrim(file_get_contents('d14.txt')));
$file = array_map('str_split', $file);

do
{
	$change = false;
	$load = 0;
	for ($i = 1; $i < count($file); $i++)
	{
		$k = $i - 1;
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

foreach ($file[0] as $letter)
{
	$load += $letter === 'O' ? count($file) : 0;
}

echo $load, "\n";
