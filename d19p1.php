<?php

[$code, $parts] = explode("\n\n", rtrim(file_get_contents('d19.txt')));

$funcs = [];

$funcString = 'return function(int $x, int $m, int $a, int $s) : string { %s };';

foreach (explode("\n", $code) as $workflow)
{
	preg_match('%([a-z]+){(.+)}%', $workflow, $matches);

	$body = '';

	foreach (explode(',', $matches[2]) as $expression)
	{
		if (! preg_match('%(x|m|a|s)(>|<)([0-9]+):([a-zAR]+)%', $expression, $expMatch))
		{
			$body .= " { return '$expression'; }";
			break;
		}

		$body .= "if (\${$expMatch[1]} {$expMatch[2]} {$expMatch[3]}) { return '{$expMatch[4]}'; } else";
	}

	$funcs[$matches[1]] = eval(sprintf($funcString, $body));
}

$sum = 0;

foreach (explode("\n", $parts) as $part)
{
	preg_match('%{x=(\d+),m=(\d+),a=(\d+),s=(\d+)}%', $part, $matches);

	$return = 'in';
	do
	{
		$return = $funcs[$return]($matches[1], $matches[2], $matches[3], $matches[4]);
	}
	while ($return !== 'A' && $return !== 'R');

	$return === 'A' && $sum += $matches[1] + $matches[2] + $matches[3] + $matches[4];
}

echo $sum, "\n";
