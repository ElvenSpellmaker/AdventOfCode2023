<?php

echo array_reduce(explode(',', rtrim(file_get_contents('d15.txt'), "\n")), function($carry, $item) {
	return $carry + array_reduce(str_split($item), function($carry, $item) {
		return (($carry + ord($item)) * 17) % 256;
	}, 0);
}, 0), "\n";
