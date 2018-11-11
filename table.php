<?php
	$pdo = new PDO('mysql:host=localhost;dbname=netology_11', 'root', '');

	if (!empty($_POST)) {

		if ($_POST['act'] === 'newtable' && isset($_POST['tablename'])) {

			$querytext = 'CREATE TABLE `' . $_POST['tablename'] .
			'` (`id` int NOT NULL AUTO_INCREMENT,
			`name` varchar(100) NOT NULL,
			`price` float NOT NULL,
			`descr` TINYTEXT NULL,
			PRIMARY KEY (`id`)) ENGINE InnoDB CHARSET=utf8';
			$pdo_prepare = $pdo -> prepare($querytext);
			$pdo_prepare -> execute();
		}
		elseif ($_POST['act'] === 'changetable') {
			
			var_dump($_POST);

			if (isset($_POST['fieldact'])) {

				if ($_POST['fieldact'] === 'change') {

					

				}
				elseif ($_POST['fieldact'] === 'delete') {
					
					

				}
			}


		}
	}

	$querytext = 'SHOW TABLES';
	// $querytext = 'SHOW TABLES like \'tt\'';
	$pdo_prepare = $pdo -> prepare($querytext);
	$pdo_prepare -> execute();
	$arrtable = $pdo_prepare -> fetchAll(PDO::FETCH_ASSOC);

	$table_text = '';

	foreach ($arrtable as $table) {

		foreach ($table as $tablename) {

			$tableact = '';

			if (!empty($_GET) && isset($_GET['tab']) && ($_GET['tab'] === $tablename)) {
	
				$querytext = 'DESCRIBE ' . $tablename;
				$pdo_prepare = $pdo -> prepare($querytext);
				$pdo_prepare -> execute();
				$arrfield = $pdo_prepare -> fetchAll(PDO::FETCH_ASSOC);

				$fieldtext = '';
				foreach ($arrfield as $row) {
					$fieldname = $row['Field'];
					$fieldtype = $row['Type'];
					$tableact .=
					'
					<form action="" method="POST">
					<label>Поле
						<input type="text" readonly name="fieldname" value="' . $fieldname . '">
					</label>
					<label>Тип
						<input type="text" name="fieldtype" value="' . $fieldtype . '">
					</label>
					<button type="submit" name="fieldact" value="change">Изменить</button>
					<button type="submit" name="fieldact" value="delete">Удалить</button>
					<input type="hidden" name="act" value="changetable">
					<input type="hidden" name="tablename" value="' . $tablename . '">
					</form>';
				}

				// $tableact .=
				// '<input type="hidden" name="act" value="changetable">';
				// '<input type="hidden" name="tablename" value="' . $tablename . '">';

			}
			else {
				$tableact = '<a href="?tab=' . $tablename . '">Редактировать</a>';
			}

			$table_text .=
			'<tr>
				<td>' . $tablename . '</td>
				<td>' . $tableact . '</td>
			</tr>';
		}
	}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Создание таблицы в SQL</title>
	<meta charset="utf-8">
	<style>
		table {
			border-spacing: 0;
			border-collapse: collapse;
		}
		table td {
			border: 1px solid black;
		}
	</style>
</head>
<body>
	<form action="" method="POST">
		
		<label>Новая таблица
			<input type="text" name="tablename">
		</label>
		<input type="hidden" name="act" value="newtable">
		<input type="submit" name="Создать" value="Создать">

	</form>

	<h2>Список таблиц</h2>

	<!-- <form action="" method="POST"> -->

		<table>
			<?php echo $table_text; ?>		
		</table>

	<!-- </form> -->

</body>
</html>