<?php

$file = explode("\n", rtrim(file_get_contents('d20.txt')));

$modules = [];

$silent = true;

$finder = false;

enum Pulse
{
	case HIGH;
	case LOW;
}

abstract class BaseModule
{
	public array $outputs;

	public string $name;

	public SplQueue $gq;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function sendPulse(Pulse $pulse) : void
	{
		foreach ($this->outputs as $who)
		{
			$this->gq->push([$who, $pulse, $this->name]);
		}
	}

	abstract public function tick(Pulse $pulse) : void;
}

class FlipFlopModule extends BaseModule
{
	const ON = true;
	const OFF = false;

	public bool $state = self::OFF;

	public function tick(Pulse $pulse) : void
	{
		if ($pulse === Pulse::HIGH)
		{
			return;
		}

		$this->state = ! $this->state;

		$pulse = $this->state === self::ON
			? Pulse::HIGH
			: Pulse::LOW;

		$this->sendPulse($pulse);
	}
}

class BroadcasterModule extends BaseModule
{
	public function tick(Pulse $pulse) : void
	{
		$this->sendPulse($pulse);
	}
}

class GenericModule extends BaseModule
{
	public function tick(Pulse $pulse) : void
	{
		global $silent;

		if (! $silent)
		{
			echo $pulse->name, "\n";
		}
	}
}

class ConjunctionModule extends BaseModule
{
	public array $inputs;

	public function tick(Pulse $pulse) : void
	{
		$high = true;
		foreach ($this->inputs as $pulse)
		{
			if ($pulse === Pulse::LOW)
			{
				$high = false;
				break;
			}
		}

		$pulse = $high ? Pulse::LOW : Pulse::HIGH;

		$this->sendPulse($pulse);
	}
}

$globalQueue = new SplQueue;

foreach ($file as $line)
{
	[$module, $outputs] = explode(' -> ', $line);

	switch ($module[0])
	{
		case '%':
			$module = substr($module, 1);
			$moduleObj = new FlipFlopModule($module);
		break;
		case '&':
			$module = substr($module, 1);
			$moduleObj = new ConjunctionModule($module);

			foreach (($modules[$module] ?? []) as $input)
			{
				$moduleObj->inputs[$input] = Pulse::LOW;
			}
		break;
		default:
			$moduleObj = $module === 'broadcaster'
				? new BroadcasterModule($module)
				: new GenericModule($module);
		break;
	}

	$moduleObj->gq = $globalQueue;

	$modules[$module] = $moduleObj;

	$outputs = explode(', ', $outputs);
	foreach ($outputs as $output)
	{
		$moduleObj->outputs[] = $output;

		if ($output === 'rx')
		{
			$finder = $module;
		}

		if (! isset($modules[$output]))
		{
			$modules[$output] = [$module];
			continue;
		}

		if ($modules[$output] instanceof ConjunctionModule)
		{
			$modules[$output]->inputs[$module] = Pulse::LOW;
			continue;
		}

		if (is_array($modules[$output]))
		{
			$modules[$output][] = $module;
			continue;
		}
	}
}

foreach ($modules as $mK => $module)
{
	if (! is_array($module))
	{
		continue;
	}

	$modules[$mK] = new GenericModule($mK);
}

$buttonPress = 0;
$pulses = [];
while (true)
{
	$buttonPress++;
	$modules['broadcaster']->sendPulse(Pulse::LOW);

	while (count($globalQueue))
	{
		[$who, $pulse, $by] = $globalQueue->shift();

		if ($modules[$who] instanceof ConjunctionModule)
		{
			$modules[$who]->inputs[$by] = $pulse;
		}

		if ($who === $finder && $pulse === Pulse::HIGH)
		{
			$pulses[$by] = $buttonPress;

			if (count($pulses) === 4)
			{
				break 2;
			}
		}

		$modules[$who]->tick($pulse);
	}
}

echo array_product($pulses), "\n";
