<?php


include 'bootstrap.php';


function import($pdoSource, $pdoTT){

	/**
	 * Declare all the arrays where I will store entity for TT database table
	 */
	$tt_emailArray = array();
	$tt_phoneArray = array();
	$tt_countryArray = array();
	$tt_addressArray = array();
	$tt_userArray = array();

	/**
     * Get all the name of the table from the unknown database stored in the array $tableName
	*/
    $tables = $pdoSource->query('SHOW TABLES');
	while($row = $tables->fetch()){
		foreach ($row as $item){
			$tableName[] = $item;
		}
	}

    /**
     * Get all the value from each table as an associative array
     */
    foreach ($tableName as $value){
        //table name has already been assessed
	    if ($tableName === 'name'){
	        continue;
        }
        $query = 'SELECT * FROM '.$value.'';
        $stmt = $pdoSource->query($query);
        $item = $stmt->fetchAll();
        //Check if there are record in the table
        if ($item){
            //Execute specific operation in each table
            switch ($value) {
	            case 'name':
		            foreach ( $item as $row) {
		            	foreach ($row as $column=>$attributes){
		            		if ($column !== 'reference'){
		            			$attributes = formatEntry($attributes);
				            }
		            	    $tt_userArray[$row['reference']][$column]=$attributes;
			            }
		            }
		            break;
                case 'deceased':
                    foreach ($item as $row){
						$tt_userArray[$row['reference']]['status']='deceased';
                    }
                    break;
	            case 'dob':
		            foreach ($item as $row){
		            	$dob=formatDate($row['dob']);
			            $tt_userArray[$row['reference']]['dob']=$dob;
		            }
		            break;
	            case 'address':
	            	foreach ($item as $row){
	            		$address=formatAddress($row['address']);
	            		foreach ($address as $key => $value){
							$tt_addressArray[$row['reference']][$key] = $value;
			            }
			            $country = formatEntry($row['country']);
			            $tt_addressArray[$row['reference']]['postcode'] = strtoupper(formatEntry($row['postcode']));
	            		$tt_addressArray[$row['reference']]['country'] = $country;
	            		if (array_search($country,$tt_countryArray,true) === false){
	            			if (strlen($country) > 2){
	            			    $tt_countryArray[] = $country;
				            }
			            }
		            }
	            	break;
	            case 'contact':
	            	foreach ($item as $row){
	            		$type = formatEntry($row['type']);
	            		$description = formatEntry($row['description']);
	            		if ($type === 'Phone'){
	            			if ($description === 'Business' || $description === 'Work'){
	            				$phoneEntry['type'] = 'work';
	            				$numberArray = formatPhone($row['contact']);
	            				$phoneEntry['number'] = $numberArray[1];
	            				$phoneEntry['country'] = $numberArray[0];
	            				$phoneEntry['reference'] = $row['reference'];
	            				$tt_phoneArray[] = $phoneEntry;
				            }else{
					            $phoneEntry['type'] = 'home';
					            $numberArray = formatPhone($row['contact']);
					            $phoneEntry['number'] = $numberArray[1];
					            $phoneEntry['country'] = $numberArray[0];
					            $phoneEntry['reference'] = $row['reference'];
					            $tt_phoneArray[] = $phoneEntry;
				            }
			            }else{
				            if ($description === 'Business' || $description === 'Work'){
					            $emailEntry['type'] = 'work';
					            $emailEntry['email'] = strtolower(formatEntry($row['contact']));
					            $emailEntry['reference'] = $row['reference'];
				                $tt_emailArray[] = $emailEntry;
				            }else{
					            $emailEntry['type'] = 'home';
					            $emailEntry['reference'] = $row['reference'];
					            $emailEntry['email'] = strtolower(formatEntry($row['contact']));
				                $tt_emailArray[] = $emailEntry;
				            }
			            }
		            }
		            break;
            }
        }
    }

	/**
	 *  ----IMPORT----
	 * Populate tt_user table
	 */
//     foreach ($tt_userArray as $ref=>$entry){
//        $query = 'INSERT INTO tt_user (first_name, last_name, dob,';
//     	$values='';
//     	$values .= '\''.$tt_userArray[$ref]['name'].'\',\''.$tt_userArray[$ref]['surname'].'\',\''.$tt_userArray[$ref]['dob'].'\'';
//     	if(array_key_exists('status',$tt_userArray[$ref])){
//     		$query .= 'status,reference) VALUES (';
//     		$values .= ',\''.$tt_userArray[$ref]['status'].'\',\''.$ref.'\')';
//        }else{
//     		$query .= 'reference) VALUES (';
//     		$values .= ',\''.$ref.'\')';
//        }
//		$sql = $pdoTT->prepare($query.$values);
//     	$sql->execute();
//     }

	/**
	 * Populate tt_email table
	 */
//	foreach ($tt_emailArray as $key => $value){
//		//retrieve the corresponding user_id from tt_user table
//		$reference = $tt_emailArray[$key]['reference'];
//		$stmt = $pdoTT->prepare('SELECT id FROM tt_user WHERE reference = ?');
//		$stmt->execute([$reference]);
//		//prepare the query to insert in the tt_email table and bind parameters
//		$query = 'INSERT INTO tt_email (user_id, email, type) VALUES (:id, :mail, :type)';
//		$user_id = $stmt->fetchColumn();
//		$email = $tt_emailArray[$key]['email'];
//		$type = $tt_emailArray[$key]['type'];
//		$sql = $pdoTT->prepare($query);
//		$sql->bindParam(':id', $user_id);
//		$sql->bindParam(':mail', $email);
//		$sql->bindParam(':type', $type);
//		//execute query foreach entity
//		$sql->execute();
//	}

	/**
	 * Populate tt_phone table
	 */
	foreach ($tt_phoneArray as $key => $value){
		//retrieve the corresponding user_id from tt_user table
		$reference = $tt_phoneArray[$key]['reference'];
		$stmt = $pdoTT->prepare('SELECT id FROM tt_user WHERE reference = ?');
		$stmt->execute([$reference]);
		//prepare the query to insert in the tt_email table and bind parameters
		$query = 'INSERT INTO tt_phone (user_id, country, number, type) VALUES (:id, :country, :number, :type)';
		$user_id = $stmt->fetchColumn();
		$country = $tt_phoneArray[$key]['country'];
		$number = $tt_phoneArray[$key]['number'];
		$type = $tt_phoneArray[$key]['type'];
		$sql = $pdoTT->prepare($query);
		$sql->bindParam(':id', $user_id);
		$sql->bindParam(':country', $country);
		$sql->bindParam(':number', $number);
		$sql->bindParam(':type', $type);
		//execute query foreach entity
		$sql->execute();
	}


//    var_dump($tt_userArray);
//    var_dump($tt_countryArray);
//    var_dump($tt_addressArray);
//    var_dump($tt_emailArray);
//    var_dump($tt_phoneArray);
}
import($pdoSource, $pdoTT);


