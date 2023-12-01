<?php

$file = explode("\n", rtrim(file_get_contents('d1.txt'), "\n"));

$conversion = [
	'0', 'zero' => 0,
	'1', 'one' => 1,
	'2', 'two' => 2,
	'3', 'three' => 3,
	'4', 'four' => 4,
	'5', 'five' => 5,
	'6', 'six' => 6,
	'7', 'seven' => 7,
	'8', 'eight' => 8,
	'9', 'nine' => 9,
];

$regexPart = '(\d|one|two|three|four|five|six|seven|eight|nine)';

$sum = 0;
foreach ($file as $line)
{
	preg_match("%.*?$regexPart.*$regexPart.*%", $line, $matches);
	preg_match("%$regexPart%", $line, $matches2);

	$sum += count($matches) >= 3
		? "{$conversion[$matches[1]]}{$conversion[$matches[2]]}"
		: "{$conversion[$matches2[0]]}{$conversion[$matches2[0]]}";
}

echo $sum, "\n";
