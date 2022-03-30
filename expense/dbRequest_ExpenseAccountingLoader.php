<?php

	function cmp($a, $b){
    	if ($a[0] == $b[0]) {
       		return 0;
    	}
    	return ($a[0] > $b[0]) ? -1 : 1;
	}

	// Data Base connection
	$dbConPath = getenv('DB_CON_PATH');
	require_once($dbConPath);

	$dbConnection = new dbConnection();
	$con = $dbConnection -> dbConnect();
	
	// expenses array
	$axpenseArray = array();

	// Select all expense
	$sqlQuery = "SELECT A.ExpenseAccountingID, A.ExpenseTotal, A.ExpenseDate, B.Name, C.ExpenseGroupName
				FROM fa_expenseAccounting A
					JOIN fa_expense B
    					ON A.ExpenseID = B.ExpenseID
                        	JOIN fa_expenseGroup C
                            	ON B.GroupID = C.ExpenseGroupID
                WHERE A.ExpenseRemove IS NULL";

	// Insert table to array
	if($result = $con -> query($sqlQuery)){
		while ($row = $result -> fetch_array()) {
			// date reform to d-m-Y
    		$rowDate = date( "d-m-Y", strtotime($row['ExpenseDate']));

	 		// Pushing options to array
	    	array_push($axpenseArray, array($row['ExpenseDate'], array($rowDate, $row['ExpenseGroupName'], $row['Name'], $row['ExpenseTotal'], $row['ExpenseAccountingID'])));
		}
    }else{
       	print("Ошибка: " . $con->error);
    }

    // Sort axpenses array by date
    usort($axpenseArray, 'cmp');

    // Array without useless dates for sort
    $axpenseArrayResultArray = array();

    foreach ($axpenseArray as $item) {
    	array_push($axpenseArrayResultArray, $item[1]);
    }

	$con -> close();

	echo json_encode($axpenseArrayResultArray);
?>