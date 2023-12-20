<?php

[$code, $parts] = explode("\n\n", rtrim(file_get_contents('d19.txt')));

$funcs = [];

$valid = [
	'x' => ['low' => 1, 'high' => 4000],
	'm' => ['low' => 1, 'high' => 4000],
	'a' => ['low' => 1, 'high' => 4000],
	's' => ['low' => 1, 'high' => 4000],
];

$validCombs = [];

$funcString = 'return function(array $valid) { global $funcs, $validCombs; %s };';

foreach (explode("\n", $code) as $workflow)
{
	preg_match('%([a-z]+){(.+)}%', $workflow, $matches);

	$body = '';

	foreach (explode(',', $matches[2]) as $expression)
	{
		if (! preg_match('%(x|m|a|s)(>|<)([0-9]+):([a-zAR]+)%', $expression, $expMatch))
		{
			switch ($expression)
			{
				case 'A':
					$body .= '$validCombs[] = $valid;';
				break;
				case 'R':
				break;
				default:
					$body .= "\$funcs['$expression'](\$valid);";
				break;
			}

			break;
		}

		$num = $expMatch[3];
		$invNum = $expMatch[3];
		if ($expMatch[2] === '<')
		{
			// < 10 --> high becomes 9
			// >= 10 --> low becomes 10
			$lowOrHigh = 'high';
			$num--;
			$invLowOrHigh = 'low';
		}
		else
		{
			// > 10 --> low becomes 11
			// <= 10 --> high becomes 10
			$lowOrHigh = 'low';
			$num++;
			$invLowOrHigh = 'high';
		}

		$body .= "\$orig = \$valid['{$expMatch[1]}']['$lowOrHigh'];";
		$body .= "\$valid['{$expMatch[1]}']['$lowOrHigh'] = $num;";

		switch ($expMatch[4])
		{
			case 'A':
				$body .= '$validCombs[] = $valid;';
			break;
			case 'R':
			break;
			default:
				$body .= "\$funcs['{$expMatch[4]}'](\$valid);";
			break;
		}

		$body .= "\$valid['{$expMatch[1]}']['$lowOrHigh'] = \$orig;";
		$body .= "\$valid['{$expMatch[1]}']['$invLowOrHigh'] = $invNum;";
	}

	$funcs[$matches[1]] = eval(sprintf($funcString, $body));
}

$sum = 0;

$funcs['in']($valid);

foreach ($validCombs as ['x' => $x, 'm' => $m, 'a' => $a, 's' => $s])
{
	$xRange = $x['high'] + 1 - $x['low'];
	$mRange = $m['high'] + 1 - $m['low'];
	$aRange = $a['high'] + 1 - $a['low'];
	$sRange = $s['high'] + 1 - $s['low'];

	if ($xRange * $mRange * $aRange * $sRange <= 0)
	{
		continue;
	}

	$sum += $xRange * $mRange * $aRange * $sRange;
}

echo $sum, "\n";
