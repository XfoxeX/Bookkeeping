<?php 
$bookkeepingPassword = getenv('BOOKKEEPING_PASSWORD');

if($_REQUEST['PASSWORD'] == $bookkeepingPassword):?>	
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>Учет расходов</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/media-queries.css">
	<script src="https://api.bitrix24.com/api/v1/"></script>
	<script src="../js/main.js"></script>
	<script>
		expenseGroupLoader();
		expenseAccountingLoader('<? echo $_REQUEST['DOMAIN'] ?>');
	</script>
</head>
<body>
	<div class="expense-container">
		<div class="expense-container-box">
			<div class="main-menu-buttons">
				<a class="back-button" href="">
					Главное меню
				</a>

				<div class="create-expense-item-mobile-button">
					<input type="button" onclick="expensePopupView('Popup-create-expense');" value="Создать группу/подгруппу">
				</div>
			</div>
			<div class="expense-box-header">
				<h2>Учет расходов</h2>
			</div>
			<div class="expense-box-body">
				<div class="expense-menu">
					<div class="expense-select-box">
						<div class="select-expense-group">
							<div class="select-expense-group-header">
								Группа расхода
							</div>
							<div class="select-expense-group-body">
								<select id="expense-group" onchange="selectExpenseGroup(this.options[this.selectedIndex].value);">
								</select>
							</div>
						</div>

						<div class="create-expense-item">
							<input type="button" onclick="expensePopupView('Popup-create-expense');" value="Создать группу/подгруппу">
						</div>

						<div class="select-expense-item">
							<div class="select-expense-item-header">
								Подгруппа расхода
							</div>
							<div class="select-expense-item-body">
								<select id="expense-item" onchange="selectExpenseItem(this.options[this.selectedIndex].text);">
								</select>
							</div>
						</div>
					</div>	

					<div class="expense-input-panel"></div>

					<div class="send-button">
						<input type="button" onclick="expenseSubmit();" value="Выполнить">
					</div>
					<div class="send-error-box">
						<p class='error-text'></p>
					</div>
					<div class="send-success-box">
						<p class='success-text'></p>
					</div>

					<div class="expense-accounting-list">
						<table class="expense-accounting-table">
							<thead>
						    	<tr>
						        	<th>Дата</th>
						        	<th>Группа</th>
						        	<th>Подгруппа</th>
						        	<th>Сумма</th>
						    	</tr>
						    </thead>
						    <tbody id="expense-accounting-table">
						    </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>










	<!-- Модальное окно изменения расхода -->
	<div id="Popup-change-expense" class="Popup-change-expense">
		<div class="popup-container">
			<div class="popup-box">
				<div class="popup-header">
					<div class="popup-header-text">
						<h2>Изменение расхода</h2>
					</div>
					<a class="popup-button-close" onclick="expensePopupClose('Popup-change-expense');">
						<label class="burger-btn burger-btn-close" for="menu-toggle">
							<span></span>
						</label>
					</a>
				</div>
				<div class="popup-body">
					<div class="change-expense-items">
						<div class="change-expense-item">
							<div class="expense-change-id" id="expense-change-id">
							</div>
						</div>
						<div class="change-expense-item">
							<div class="item-change-title">
								Дата
							</div>
							<input type="date" id="expense-change-date">
						</div>
						<div class="change-expense-item">
							<div class="item-change-title">
								Группа
							</div>
							<div class="expense-change-expense-group" id='expense-change-expense-group'>
							</div>
						</div>
						<div class="change-expense-item">
							<div class="item-change-title">
								Подгруппа
							</div>
							<div class="expense-change-expense-group-item" id='expense-change-expense-group-item'>
							</div>
						</div>
						<div class="change-expense-item">
							<div class="item-change-title">
								Сумма
							</div>
							<input type="text" id="expense-change-total">
						</div>
					</div>
					<div class="change-buttons-row">
						<div class="create-expense-submit">
							<input type="button" onclick="fetchCurrentUser('<?php echo $_REQUEST['AUTH_ID']; ?>', 'expenseItemChangeSave');" value="Сохранить изменения">
						</div>
						<div class="delete-expense-button">
							<input type="button" onclick="fetchCurrentUser('<?php echo $_REQUEST['AUTH_ID']; ?>', 'expenseItemDelete');" value="Удалить расход">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



	<!-- Модальное окно создания расхода -->
	<div id="Popup-create-expense" class="Popup-create-expense">
		<div class="popup-container">
			<div class="popup-box">
				<div class="popup-header">
					<div class="popup-header-text">
						<h2>Создание расхода</h2>
					</div>
					<a class="popup-button-close" onclick="expensePopupClose('Popup-create-expense');">
						<label class="burger-btn burger-btn-close" for="menu-toggle">
							<span></span>
						</label>
					</a>
				</div>
				<div class="popup-body">
					<div class="create-expense-select-group-menu">
						<div class="create-expense-select-group-item">
							<select id='expense-group-create-item'>
							</select>	
						</div>
						<div class="create-group-button">
							<input type="button" onclick="expensePopupView('Popup-create-group-expense');" value="Создать группу">
						</div>			
					</div>
					<div class="create-expense-item-title">
						<input type="text" name="creating_expense_total" placeholder="Введите название подгруппы">
					</div>
					<div class="create-expense-submit">
						<input type="button" onclick="expenseItemCreate();" value="Создать подгруппу">
					</div>
				</div>
			</div>
		</div>
	</div>




	<!-- Модальное окно создания группы -->
	<div id="Popup-create-group-expense" class="Popup-create-group-expense">
		<div class="popup-container">
			<div class="popup-box">
				<div class="popup-header">
					<div class="popup-header-text">
						<h2>Создание группы расходов</h2>
					</div>
					<a class="popup-button-close" onclick="expensePopupClose('Popup-create-group-expense');">
						<label class="burger-btn burger-btn-close" for="menu-toggle">
							<span></span>
						</label>
					</a>
				</div>
				<div class="popup-body">
					<div class="create-expense-item-title">
						<input type="text" name="creating_expense_group_name" placeholder="Введите название группы">
					</div>
					<div class="create-expense-submit">
						<input type="button" onclick="expenseGroupCreate();" value="Создать группу">
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<?php endif;?>
