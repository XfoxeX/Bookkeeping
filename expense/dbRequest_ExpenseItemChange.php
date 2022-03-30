<?php

if(isset($_POST["date"]) && isset($_POST["total"]) && isset($_POST["rowId"]) && isset($_POST["currentUser"])){

	// Data Base connection
	$dbConPath = getenv('DB_CON_PATH');
	require_once($dbConPath);

	$dbConnection = new dbConnection();
	$con = $dbConnection -> dbConnect();

	$expenseId = $con -> real_escape_string($_POST["rowId"]);
	$expenseTotal = $con -> real_escape_string($_POST["total"]);
	$expenseDate = date( "Y-m-d", strtotime( $_POST["date"]));
	$currentUser = $con -> real_escape_string($_POST["currentUser"]);

	// current Msk time
	date_default_timezone_set("Europe/Moscow");
	$today = date("Y.m.d");


	// Update old expense
	$sqlQuery = "UPDATE fa_expenseAccounting
				SET ExpenseRemove = 1
				WHERE ExpenseAccountingID = $expenseId";

	// Query to data base
	$result = $con -> query($sqlQuery);

	// Select ExpenseID
    $sqlQuery = "SELECT ExpenseID
    			FROM fa_expenseAccounting
    			WHERE ExpenseAccountingID = $expenseId";

	// Query to data base
	$result = $con -> query($sqlQuery);

	while ($row = $result -> fetch_array()) {
		$expenseGroup = $row['ExpenseID'];
	}

    // Insert new expense
    $sqlQuery = "INSERT INTO fa_expenseAccounting(
								ExpenseTotal,
								ExpenseDate,
								ExpenseID)
				VALUES ($expenseTotal, '$expenseDate', $expenseGroup)";

	// Query to data base
	$result = $con -> query($sqlQuery);

	$newAccrualID = $con -> insert_id;

    // Insert create log
	$sqlQuery = "INSERT INTO fa_expenseAccountingLog(
								Employee,
								LogDate,
								NewExpID,
								OldExpID,
								OperType)
				VALUES ($currentUser, '$today', $newAccrualID, $expenseId, 'Update')";

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