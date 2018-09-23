<?php

/**
 * Clean the passed parameter from white spaces at the begin/end
 * if sentence passed leave just one white space in between the words
 * capitalize first letter
 *
 * @param $string
 *
 * @return string / if string is empty or null return false
 */
function formatEntry($string){
	$string = trim($string);
	if (!empty($string)){
		$string = strtolower($string);
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
	}else{
		return false;
	}
}

/**
 * @param $string date in format mm/dd/YYYY
 *
* @return string date format YYYY-mm-dd / if passed parameter is empty or null return false
 */
function formatDate($string){
	if (formatEntry($string)){
		$date = new DateTime($string);
		return $date->format('Y-m-d');
	}else {
		return false;
	}
}

/**
 * Perform operations on the ADDRESS.address in order to obtain values to input in tt_address table
 *
 * @param $address string coming from ADDRESS.address
 *
 * @return array associative the index is the tt_address column name
 */
function formatAddress($address){
	$addressLines = explode(',', $address);
	if (count($addressLines) > 1){

		$splitAddress['town_city'] = formatEntry(array_pop($addressLines));
		$i=1;
		if (!empty($addressLines)){
			foreach ($addressLines as $value) {
				$splitAddress[ 'address_line_' . $i ] = formatEntry($value);
				$i++;
			}
		}else{
			$splitAddress['address_line_1'] = false;
		}
		return $splitAddress;
	}else{
		return [false];
	}
}

/**
 *
 * @param $number
 *
 * @return array of country code and phone number respectively at index 0 and 1 // if empty or null parameter enter return false
 */
function formatPhone($number){
	if (formatEntry($number)){
		$numberArray = explode(')',$number);
		foreach ($numberArray as $value){
			$phone[] = preg_replace('/(\s+)|(\()/','',$value);
		}
		return $phone;
	}else{
		return false;
	}
}
