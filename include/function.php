<?php

//$user = $pdoSource->prepare($select);
//$user->execute();
//while ($row = $user ->fetch()){
//	var_dump($row);
//}

/**
 * Run php function trim() on passed parameter
 *
 * @param string or array $sentence
 *
 * @return string or array of string
 */
//function trimer($sentence){
//	if (is_array($sentence)){
//		foreach ($sentence as $item){
//			$item = trim($item);
//		}
//		return $sentence;
//	}else{
//		return trim($sentence);
//	}
//}

/**
 * Clean the passed parameter from white spaces at the begin/end
 * if sentence passed leave just one white space in between the words
 *
 * @param $string
 *
 * @return string
 */
function cleanSpace($string){
	if (stripos($string,' ')){
		$word = explode(' ', $string);
		foreach ($word as $key=>$value){
			$word[$key] = trim($value);
		}
		$newString = implode(' ', $word);
	}else{
		$newString = trim($string);
	}
	return $newString;
}
