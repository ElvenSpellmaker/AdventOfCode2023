<?php

$file = explode("\n", rtrim(file_get_contents('d7.txt'), "\n"));

enum Type : int
{
	case FIVE_OF_A_KIND = 6;
	case FOUR_OF_A_KIND = 5;
	case FULL_HOUSE = 4;
	case THREE_OF_A_KIND = 3;
	case TWO_PAIR = 2;
	case ONE_PAIR = 1;
	case HIGH_CARD = 0;
}

class CardLine
{
	public string $cards;
	public int $bid;
	public Type $type;
}

class CardsList extends SplHeap
{
	protected function compare($value1, $value2) : int
	{
		// Rank Types
		if ($value1->type->value > $value2->type->value)
		{
			return -1;
		}

		if ($value1->type->value < $value2->type->value)
		{
			return 1;
		}

		// Rank Same Type
		for ($i = 0; $i < strlen($value1->cards); $i++)
		{
			$val1 = $value1->cards[$i];
			$val1 = is_numeric($val1) ? $val1 : ($val1 === 'A' ? 14 : ($val1 === 'K' ? 13 : ($val1 === 'Q' ? 12 : ($val1 === 'J' ? 11 : 10))));

			$val2 = $value2->cards[$i];
			$val2 = is_numeric($val2) ? $val2 : ($val2 === 'A' ? 14 : ($val2 === 'K' ? 13 : ($val2 === 'Q' ? 12 : ($val2 === 'J' ? 11 : 10))));

			if ($val1 > $val2)
			{
				return -1;
			}

			if ($val1 < $val2)
			{
				return 1;
			}
		}

		return 0;
	}
}

$cardsRanked = new CardsList;

foreach ($file as $line)
{
	[$cards, $bid] = explode(' ', $line);

	$counts = [
		'A' => 0,
		'K' => 0,
		'Q' => 0,
		'J' => 0,
		'T' => 0,
		'9' => 0,
		'8' => 0,
		'7' => 0,
		'6' => 0,
		'5' => 0,
		'4' => 0,
		'3' => 0,
		'2' => 0,
	];

	for ($i = 0; $i < strlen($cards); $i++)
	{
		$counts[$cards[$i]]++;
	}

	arsort($counts);

	$top = reset($counts);

	$cardLine = new CardLine;
	$cardLine->cards = $cards;
	$cardLine->bid = $bid;

	switch ($top)
	{
		case 5:
			$cardLine->type = Type::FIVE_OF_A_KIND;
		break;
		case 4:
			$cardLine->type = Type::FOUR_OF_A_KIND;
		break;
		case 3:
			$cardLine->type = (next($counts) === 2)
				? Type::FULL_HOUSE
				: Type::THREE_OF_A_KIND;
		break;
		case 2:
			$cardLine->type = (next($counts) === 2)
				? Type::TWO_PAIR
				: Type::ONE_PAIR;
		break;
		case 1:
			$cardLine->type = Type::HIGH_CARD;
		break;
	}

	$cardsRanked->insert($cardLine);
}

$rank = 1;

echo array_reduce(iterator_to_array($cardsRanked), function($carry, $item) use(&$rank) {
	// echo $item->cards, ' ', $item->type->name, "\n";
	return $carry + ($item->bid * $rank++);
}, 0), "\n";
