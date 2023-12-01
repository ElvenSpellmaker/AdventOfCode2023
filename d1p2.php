<?php

$file = explode("\n", rtrim(file_get_contents('d1.txt'), "\n"));

$conversion = [
	'0' => 0,
	'1' => 1,
	'2' => 2,
	'3' => 3,
	'4' => 4,
	'5' => 5,
	'6' => 6,
	'7' => 7,
	'8' => 8,
	'9' => 9,
	'zero' => 0,
	'one' => 1,
	'two' => 2,
	'three' => 3,
	'four' => 4,
	'five' => 5,
	'six' => 6,
	'seven' => 7,
	'eight' => 8,
	'nine' => 9,
];

$sum = 0;
foreach ($file as $line)
{
	preg_match('%.*?(\d|one|two|three|four|five|six|seven|eight|nine).*(\d|one|two|three|four|five|six|seven|eight|nine).*%', $line, $matches);

	if (count($matches) >= 3)
	{
		$sum += "{$conversion[$matches[1]]}{$conversion[$matches[2]]}";
	}
	else
	{
		preg_match('%\d|one|two|three|four|five|six|seven|eight|nine%', $line, $matches);

		$sum += "{$conversion[$matches[0]]}{$conversion[$matches[0]]}";
	}
}

echo $sum, "\n";
