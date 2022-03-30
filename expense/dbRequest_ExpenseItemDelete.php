<?php

if(isset($_POST["rowId"]) && isset($_POST["currentUser"])){

	// Data Base connection
	$dbConPath = getenv('DB_CON_PATH');
	require_once($dbConPath);

	$dbConnection = new dbConnection();
	$con = $dbConnection -> dbConnect();

	$expenseId = $con -> real_escape_string($_POST["rowId"]);
	$currentUser = $con -> real_escape_string($_POST["currentUser"]);

	// current Msk time
	date_default_timezone_set("Europe/Moscow");
	$today = date("Y.m.d");


	// Update old expense
	$sqlQuery = "UPDATE fa_expenseAccounting
				SET ExpenseRemove = '1'
				WHERE ExpenseAccountingID = $expenseId";

	// Query to data base
	$result = $con -> query($sqlQuery);

    // Insert create log
	$sqlQuery = "INSERT INTO fa_expenseAccountingLog(
								Employee,
								LogDate,
								OldExpID,
								OperType)
				VALUES ($currentUser, '$today', $expenseId, 'Delete')";

	// Insert expense to DB
	if($result = $con -> query($sqlQuery)){
		print('Готово!');
    }else{
       	print("Ошибка: " . $con->error);
    }

	$con -> close();
}else{
	exit();
}

?>