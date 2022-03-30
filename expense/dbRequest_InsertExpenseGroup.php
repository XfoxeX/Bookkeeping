<?php

if(isset($_POST["expenseGroupTitle"])){

	// Data Base connection
	$dbConPath = getenv('DB_CON_PATH');
	require_once($dbConPath);

	$dbConnection = new dbConnection();
	$con = $dbConnection -> dbConnect();

	$expenseGroupTitle = $con -> real_escape_string($_POST["expenseGroupTitle"]);

	// Select all expense
	$sqlQuery = "INSERT INTO fa_expenseGroup(ExpenseGroupName)
				VALUES ('$expenseGroupTitle')";

	// Insert expense to DB
	if(!$result = $con -> query($sqlQuery)){
		print("Ошибка: " . $con->error);
    }

	$con -> close();
}else{
	exit();
}

?>