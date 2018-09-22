<?php

/**
 * Clean the passed parameter from white spaces at the begin/end
 * if sentence passed leave just one white space in between the words
 * capitalize first letter
 *
 * @param $string
 *
 * @return string
 */
function formatEntry($string){
	$string = strtolower($string);
	$string = trim($string);
	if (stripos($string," ")){
		$word = explode(' ', $string);
		foreach ($word as $key=>$value){
			if (trim($value) === ''){
				unset($word[$key]);
			}else{
				$word[$key] = ucfirst($value);
			}
		}
		$newString = implode(' ', $word);
	}else{
		$newString = ucfirst($string);
	}
	return $newString;
}

/**
 * @param $string date in format mm/dd/YYYY
 *
* @return string date format YYYY-mm-dd
 */
function formatDate($string){
	$date = new DateTime($string);
	return $date->format('Y-m-d');
}


/**
 * @param $address string coming from ADDRESS.address
 *
 * @return array associative, the index array map to tt_address column name
 */
function formatAddress($address){
	$addressLines = explode(',', $address);
	$splitAddress['town_city'] = formatEntry(array_pop($addressLines));
	$i=1;
	foreach ($addressLines as $value) {
		$splitAddress[ 'address_line_' . $i ] = formatEntry($value);
		$i++;
	}
	return $splitAddress;
}

/**
 * @param $number
 *
 * @return array
 */
function formatPhone($number){
	$numberArray = explode(')',$number);
	foreach ($numberArray as $value){
		$phone[] = preg_replace('/(\s+)|(\()/','',$value);
	}
	return $phone;
}
