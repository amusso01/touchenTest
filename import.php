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
	$tables->closeCursor();

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
                    	if (formatEntry($row['deceased'])){
							$tt_userArray[$row['reference']]['status'] = 'deceased';
	                    }else{
                    		$tt_userArray[$row['reference']]['status'] = false;
	                    }
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
			            $tt_addressArray[$row['reference']]['postcode'] = formatEntry($row['postcode']);
	            		$tt_addressArray[$row['reference']]['country'] = $country;
	            		if (array_search($country,$tt_countryArray,true) === false){
	            			if (strlen($country) > 3){//There is no country with a name shortest than 3 letters
	            			    $tt_countryArray[] = $country;
				            }
			            }
		            }
	            	break;
	            case 'contact':
	            	foreach ($item as $row){
	            		$type = formatEntry($row['type']);
	            		$description = formatEntry($row['description']);
			            $phoneEntry['reference'] = $row['reference'];
	            		if ($type === 'Phone'){
				            if ($numberArray = formatPhone($row['contact'])){
					            $phoneEntry['number'] = $numberArray[1];
					            $phoneEntry['country'] = $numberArray[0];
				            }else{
					            $phoneEntry['number'] = false;
					            $phoneEntry['country'] = false;
				            }
	            			if ($description === 'Business' || $description === 'Work'){
	            				$phoneEntry['type'] = 'work';
					            $tt_phoneArray[] = $phoneEntry;
				            }elseif ($description === 'Home' || $description === 'Private'){
					            $phoneEntry['type'] = 'home';
					            $tt_phoneArray[] = $phoneEntry;
				            }else{
	            				$phoneEntry['type'] = false;
					            $tt_phoneArray[] = $phoneEntry;
				            }

			            }elseif($type === 'Email'){
	            			$email = formatEntry($row['contact']);
				            $emailEntry['reference'] = $row['reference'];
	            			if (filter_var($email, FILTER_VALIDATE_EMAIL)){
					            $emailEntry['email'] = strtolower($email);
				            }else{
	            				$emailEntry['email'] = false;
				            }
				            if ($description === 'Business' || $description === 'Work'){
					            $emailEntry['type'] = 'work';
					            $tt_emailArray[] = $emailEntry;
				            }elseif ($description === 'Home' || $description === 'Private'){
					            $emailEntry['type'] = 'home';
					            $tt_emailArray[] = $emailEntry;
				            }else{
	            				$emailEntry['type'] = false;
					            $tt_emailArray[] = $emailEntry;
				            }
			            }else{
				            $rawContact = formatEntry($row['contact']);
							if ($contact = filter_var($rawContact,FILTER_VALIDATE_EMAIL)){
								//is email
								$emailEntry['email'] = strtolower($contact);
								$emailEntry['reference'] = $row['reference'];
								if ($description === 'Business' || $description === 'Work'){
									$emailEntry['type'] = 'work';
								}elseif ($description === 'Home' || $description === 'Private'){
									$emailEntry['type'] = 'home';
								}else{
									$emailEntry['type'] = false;
								}
								$tt_emailArray[] = $emailEntry;
							}elseif($contact =formatPhone($rawContact)){
								//is phone
								$phoneEntry['number'] = $contact[1];
								$phoneEntry['country'] = $contact[0];
								$phoneEntry['reference'] = $row['reference'];
								if ($description === 'Business' || $description === 'Work'){
									$phoneEntry['type'] = 'work';
								}elseif ($description === 'Home' || $description === 'Private'){
									$phoneEntry['type'] = 'home';
								}else{
									$phoneEntry['type'] = false;
								}
								$tt_phoneArray[] = $phoneEntry;
							}
			            }
		            }
		            break;
            }
        }
    }
	$stmt->closeCursor();
	/**
	 *  ----IMPORT----
	 * Populate tt_user table
	 */
     foreach ($tt_userArray as $ref=>$entry){
		if ($tt_userArray[$ref]['name'] !== false && $tt_userArray[$ref]['surname']){
	        $query = 'INSERT INTO tt_user (first_name, last_name';
	        $values = 'VALUES (:first_name, :last_name';
	        if (array_key_exists('dob',$tt_userArray[$ref]) && $tt_userArray[$ref]['dob'] !== false){
	        	$query .= ', dob';
	        	$values .= ', :dob';
	        }
			if(array_key_exists('status',$tt_userArray[$ref]) && $tt_userArray[$ref]['status'] !== false){
				$query .= ', status';
				$values .= ', :status';
			}
			$query .= ', reference) ';
			$values .= ', :reference)';
			$sql = $pdoTT->prepare($query.$values);
			$sql->bindParam(':first_name', $tt_userArray[$ref]['name']);
			$sql->bindParam(':last_name', $tt_userArray[$ref]['surname']);
			if (array_key_exists('dob',$tt_userArray[$ref]) && $tt_userArray[$ref]['dob'] !== false){
				$sql->bindParam(':dob', $tt_userArray[$ref]['dob']);
			}
			if (array_key_exists('status',$tt_userArray[$ref]) && $tt_userArray[$ref]['status'] !== false){
				$sql->bindParam(':status', $tt_userArray[$ref]['status']);
			}
			$sql->bindParam(':reference', $ref);
	     	$sql->execute();
		}else{
			//In this scenario NAME.name or NAME.surname are null
			//In our database tt_user.first_name and tt_user.last_name are not allowed to be null
			/**
			 * todo write a response to this issue if necessary
			 */
		}
     }
			$sql->closeCursor();

//	/**
//	 * Populate tt_email table
//	 */
	foreach ($tt_emailArray as $key => $value){
		if ($tt_emailArray[$key]['email'] !== false){
			//retrieve the corresponding user_id from tt_user table
			$reference = $tt_emailArray[$key]['reference'];
			$stmt = $pdoTT->prepare('SELECT id FROM tt_user WHERE reference = ?');
			$stmt->execute([$reference]);
			$user_id = $stmt->fetchColumn();
			if ($user_id){
				//prepare the query to insert in the tt_email table and bind parameters
				$query = 'INSERT INTO tt_email (user_id, email';
				$values = ' VALUES (:id, :mail';
				$email = $tt_emailArray[$key]['email'];
				$type = $tt_emailArray[$key]['type'];
				if ($type){
					$query .= ', type';
					$values .= ', :type';
				}
				$query .= ')';
				$values .= ')';
				$sql = $pdoTT->prepare($query.$values);
				$sql->bindParam(':id', $user_id);
				$sql->bindParam(':mail', $email);
				if ($type){
					$sql->bindParam(':type', $type);
				}
				//execute query foreach entity
				$sql->execute();
			}else{
				//In this scenario we have an entry that does not have a correspondence in the tt_user table
				//no user_id found for this entry
				/**
				 * todo decide what to do about those entry
				 */
			}
		}else{
			//In this scenario the CONTACT.email from the source db are null
			//In our database this is not allowed
			/**
			 * todo write response to this scenario if necessary
			 * it could be possible to fill the fill the tt_email.user_id and tt_email.type field
			 * even if I don't see any advantage in doing that
			 */
		}
	}
	$sql->closeCursor();

//	/**
//	 * Populate tt_phone table
//	 */
	foreach ($tt_phoneArray as $key => $value){
		if ($tt_phoneArray[$key]['number'] !== false && $tt_phoneArray[$key]['country'] !== false){
			//retrieve the corresponding user_id from tt_user table
			$reference = $tt_phoneArray[$key]['reference'];
			$stmt = $pdoTT->prepare('SELECT id FROM tt_user WHERE reference = ?');
			$stmt->execute([$reference]);
			$user_id = $stmt->fetchColumn();
			if ($user_id){
				//prepare the query to insert in the tt_email table and bind parameters
				$query = 'INSERT INTO tt_phone (user_id, country, number';
				$values =' VALUES (:id, :country, :number';
				$country = $tt_phoneArray[$key]['country'];
				$number = $tt_phoneArray[$key]['number'];
				$type = $tt_phoneArray[$key]['type'];
				if ($type){
					$query .= ', type';
					$values .= ', :type';
				}
				$query .= ')';
				$values .= ')';
				$sql = $pdoTT->prepare($query.$values);
				$sql->bindParam(':id', $user_id);
				$sql->bindParam(':country', $country);
				$sql->bindParam(':number', $number);
				if ($type){
					$sql->bindParam(':type', $type);
				}
				//execute query foreach entity
				$sql->execute();
			}else{
				//the entry doesn't have any reference in the tt.user table
				//no user_id found for this entry
			}
		}else{
			// tt_phone.contry or tt_phone.number are null
			//this is not allowed in our DB
		}
	}
	$sql->closeCursor();

//	/**
//	 * Populate tt_country table
//	 */
	foreach ($tt_countryArray as $value){
		$query = 'INSERT INTO tt_country (country) VALUES (?)';
		$sql = $pdoTT->prepare($query);
		$sql->execute([$value]);
	}
	$sql->closeCursor();

	/**
	 * Populate tt_address table
	 */
	foreach ($tt_addressArray as $key => $value){
		if (array_key_exists('address_line_1',$tt_addressArray[$key])){
			//retrieve the user_id from tt_user table using the reference
			$reference = $key;
			$stmt = $pdoTT->prepare('SELECT id FROM tt_user WHERE reference = ?');
			$stmt->execute([$reference]);
			$user_id = $stmt->fetchColumn();
			if ($user_id){
				//retrieve country_id from tt_country
				$stmt->closeCursor();
				$country = $tt_addressArray[$key]['country'];
				$stmt = $pdoTT->prepare('SELECT id FROM tt_country WHERE country = ?');
				$stmt->execute([$country]);
				$country_id =  $stmt->fetchColumn();
				if (array_key_exists('postcode', $tt_addressArray[$ref]) && $tt_addressArray[$ref]['postcode'] !== false){
					//create query to insert entry in the tt_address db
					$query = 'INSERT INTO tt_address (user_id';
					$values = 'VALUES (:user_id';
						foreach ($value as $column => $item){
							switch ($column){
								case 'address_line_1':
									$address_line_1 = $item;
									$query  .= ', address_line_1';
									$values .= ', :address_line_1';
									break;
								case 'address_line_2':
									$address_line_2 = $item;
									$query  .= ', address_line_2';
									$values .= ', :address_line_2';
									break;
								case 'address_line_3':
									$address_line_3 = $item;
									$query  .= ', address_line_3';
									$values .= ', address_line_3';
									break;
								case 'town_city':
									$town_city = $item;
									$query  .= ', town_city';
									$values .= ', :town_city';
									break;
								case 'postcode':
									$postcode = $item;
									$query  .= ', postcode';
									$values .= ', :postcode';
									break;
								case 'country':
									if ($country_id){
									$query  .= ', country_id';
									$values .= ', :country_id';
									}
									break;
							}
						}
						$query .= ') ';
						$values .= ')';
						$finalQuery = $query.$values;
						$sql = $pdoTT->prepare($finalQuery);
						$sql->bindParam(':user_id', $user_id);
						$sql->bindParam(':address_line_1', $address_line_1);
						if (array_key_exists('address_line_2',$tt_addressArray[$key])){
							$sql->bindParam(':address_line_2', $address_line_2);
						}
						if (array_key_exists('address_line_3', $tt_addressArray[$key])){
							$sql->bindParam(':address_line_3', $address_line_3);
						}
						$sql->bindParam(':town_city', $town_city);
						$sql->bindParam(':postcode', $postcode);
						if ($country_id){
							$sql->bindParam(':country_id', $country_id);
						}
						$sql->execute();
				}//missing postcode
			}//missing user_id
		}//missing address_line_1
	}
	$sql->closeCursor();
}



import($pdoSource, $pdoTT);


