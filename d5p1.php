<?php

$file = explode("\n\n", rtrim(file_get_contents('d5.txt'), "\n"));

preg_match_all('%\d+%', $file[0], $seeds);

function map_to_array(array &$arr, array $lines) : void
{
	for ($i = 1; $i < count($lines); $i++)
	{
		preg_match_all('%\d+%', $lines[$i], $matches);
		[$to, $from, $range] = $matches[0];

		$arr[] = [
			's_begin' => $from,
			's_end' => $from + $range - 1,
			'd_begin' => $to,
		];
	}
}

function map_item(array $arr, int $item)
{
	foreach ($arr as $range)
	{
		if ($item >= $range['s_begin'] && $item <= $range['s_end'])
		{
			return $range['d_begin'] + $item - $range['s_begin'];
		}
	}

	return $item;
}

$seedToSoil = [];
$soilToFertiliser = [];
$fertiliserToWater = [];
$waterToLight = [];
$lightToTemperature = [];
$temperatureToHumidity = [];
$humidityToLocation = [];

map_to_array($seedToSoil, explode("\n", $file[1]));
map_to_array($soilToFertiliser, explode("\n", $file[2]));
map_to_array($fertiliserToWater, explode("\n", $file[3]));
map_to_array($waterToLight, explode("\n", $file[4]));
map_to_array($lightToTemperature, explode("\n", $file[5]));
map_to_array($temperatureToHumidity, explode("\n", $file[6]));
map_to_array($humidityToLocation, explode("\n", $file[7]));

$min = INF;

foreach ($seeds[0] as $seed)
{
	$soil = map_item($seedToSoil, $seed);
	$fertiliser = map_item($soilToFertiliser, $soil);
	$water = map_item($fertiliserToWater, $fertiliser);
	$light = map_item($waterToLight, $water);
	$temperature = map_item($lightToTemperature, $light);
	$humidity = map_item($temperatureToHumidity, $temperature);
	$location = map_item($humidityToLocation, $humidity);

	var_dump($location);

	$location < $min && $min = $location;
}

echo $min, "\n";
