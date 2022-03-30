<?php

if(isset($_POST["expenseID"]) && isset($_POST["expenseTotal"]) && isset($_POST["expenseDate"])){

	// Data Base connection
	$dbConPath = getenv('DB_CON_PATH');
	require_once($dbConPath);

	$dbConnection = new dbConnection();
	$con = $dbConnection -> dbConnect();

	$expenseID = $con -> real_escape_string($_POST["expenseID"]);
	$expenseTotal = $con -> real_escape_string($_POST["expenseTotal"]);
	$expenseDate = date( "Y-m-d", strtotime( $_POST["expenseDate"]));

	// Select all expense
	$sqlQuery = "INSERT INTO fa_expenseAccounting(
								ExpenseTotal,
								ExpenseDate,
								ExpenseID)
				VALUES ($expenseTotal, '$expenseDate', $expenseID)";

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