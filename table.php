<?php
	$pdo = new PDO('mysql:host=localhost;dbname=netology_11', 'root', '');

	if (!empty($_POST)) {

		if ($_POST['act'] === 'newtable' && isset($_POST['tablename'])) {

			$querytext = 'CREATE TABLE `' . $_POST['tablename'] . '`
			(`id` int NOT NULL,
			`name` varchar(100) NOT NULL,
			`price` float NOT NULL) ENGINE InnoDB CHARSET=utf8';
			$pdo_prepare = $pdo -> prepare($querytext);
			$pdo_prepare -> execute();
		}
		elseif ($_POST['act'] === 'changetable') {
			
			if (isset($_POST['fieldact'])) {

				if ($_POST['fieldact'] === 'change') {

					if ($_POST['oldname'] !== $_POST['fieldname'] || $_POST['oldtype'] !== $_POST['fieldtype']) {

						$querytext = '
						ALTER TABLE ' . $_POST['tablename'] . ' CHANGE ' . $_POST['oldname'] . ' ' . $_POST['fieldname'] . ' ' . $_POST['fieldtype'] . ' NOT NULL';
						$pdo_prepare = $pdo -> prepare($querytext);
						$pdo_prepare -> execute();
						
					}

				}
				elseif ($_POST['fieldact'] === 'delete') {
					
					$querytext = 'ALTER TABLE ' . $_POST['tablename'] . ' DROP COLUMN ' . $_POST['fieldname'] ;
					$pdo_prepare = $pdo -> prepare($querytext);
					$pdo_prepare -> execute();

				}
			}


		}
	}

	$querytext = 'SHOW TABLES';
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
					<input type="hidden" name="act" value="changetable">
					<input type="hidden" name="tablename" value="' . $tablename . '">
					<input type="hidden" name="oldname" value="' . $fieldname . '">
					<input type="hidden" name="oldtype" value="' . $fieldtype . '">
					<label>Поле
						<input type="text" name="fieldname" value="' . $fieldname . '">
					</label>
					<label>Тип
						<input type="text" name="fieldtype" value="' . $fieldtype . '">
					</label>
					<button type="submit" name="fieldact" value="change">Изменить</button>
					<button type="submit" name="fieldact" value="delete">Удалить</button>
					</form>
					';
				}

				$tableact .=
				'<a href="table.php">На главную</a>';

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

		<table>
			<?php echo $table_text; ?>		
		</table>

</body>
</html>