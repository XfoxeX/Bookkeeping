<?php

if(isset($_POST["groupID"])){

	$groupID = $_POST["groupID"];

	// Data Base connection
	$dbConPath = getenv('DB_CON_PATH');
	require_once($dbConPath);

	$dbConnection = new dbConnection();
	$con = $dbConnection -> dbConnect();

	// Result array
	$expenseArray = array();

	// Select all expense
	$sqlQuery = "SELECT ExpenseID,
						Name
				FROM fa_expense
				WHERE GroupID = $groupID";

	// Insert table to array
	if($result = $con -> query($sqlQuery)){
		while ($row = $result -> fetch_array()) {
		 	// Pushing options to array
		    array_push($expenseArray, array($row['ExpenseID'], $row['Name']));
		}
	}else{
		exit();
	}

	$con -> close();

	echo json_encode($expenseArray);
}else{
	exit();
}

?>