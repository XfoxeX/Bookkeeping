<?php

// Data Base connection
$dbConPath = getenv('DB_CON_PATH');
require_once($dbConPath);

$dbConnection = new dbConnection();
$con = $dbConnection -> dbConnect();

// Result array
$groupArray = array();

// Select all expense group
$sqlQuery = "SELECT ExpenseGroupID,
					ExpenseGroupName
			FROM fa_expenseGroup";

// Insert table to array
if($result = $con -> query($sqlQuery)){
	while ($row = $result -> fetch_array()) {
	 	// Pushing options to array
	    array_push($groupArray, array($row['ExpenseGroupID'], $row['ExpenseGroupName']));
	}
}else{
	exit();
}

$con -> close();

echo json_encode($groupArray);

?>