<?php

if(isset($_POST["expenseGroupID"]) && isset($_POST["expenseTitle"])){

	// Data Base connection
	$dbConPath = getenv('DB_CON_PATH');
	require_once($dbConPath);

	$dbConnection = new dbConnection();
	$con = $dbConnection -> dbConnect();

	$expenseGroupID = $con -> real_escape_string($_POST["expenseGroupID"]);
	$expenseTitle = $con -> real_escape_string($_POST["expenseTitle"]);

	// Select all expense
	$sqlQuery = "INSERT INTO fa_expense(
								GroupID,
								Name)
				VALUES ($expenseGroupID, '$expenseTitle')";

	// Insert expense to DB
	if(!$result = $con -> query($sqlQuery)){
		print("Ошибка: " . $con->error);
    }

	$con -> close();
}else{
	exit();
}

?>