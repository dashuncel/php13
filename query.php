<?php

/*
 * обработчик всех AJAX-запросов на сервер.
 * typeQuery - тип запроса SQL
 * id - поле идентификации записи
 */

require_once __DIR__.DIRECTORY_SEPARATOR.'lib.php';


// проверка наличия обязательных входящих параметров:
if (! isset($_POST['typeQuery'])) {
    exit;
}
$typeQuery = $_POST['typeQuery'];
if (($typeQuery != 'sort' ) && (! isset($_POST['id']))) {
    exit;
}

$param=[]; // параметры для запроса, массив
$query=''; // текст промежуточного запроса

// формируем промежуточный запрос:
switch ($typeQuery) {
    case "delete":
        $param = [ $_POST['id'] ];
        $query='delete from tasks where tasks.id = ?';
        break;
    case "update":
        if (isset($_POST['done'])) {
            $param = [$_POST['done'], $_POST['id']];
            $query = 'update tasks set is_done = ?  where tasks.id = ?';
        }
        else // в данной реализации одновременный update Нескольких полей не предусмотрен
        if (isset($_POST['description'])) {
            $param = [$_POST['description'], $_POST['id']];
            $query = 'update tasks set description = ?  where tasks.id = ?';
        }
        break;
    case "create":
        break;
}

// если есть промежуточный зпрос - выполняем его:
if (isset($query)) {
    try {
        $statement = $pdo->prepare($query);
        $statement->execute($param);
    } catch (PDOException $e) {
        echo "Ошибка обновления записи в БД: " . $e->getMessage() . '<br/>';
        exit;
    }
}

// утанавливаем сортировку, если задана:
$query = $mainQuery;
if (isset($_POST['sort']) && isset($_POST['column'])) {
    $query .= ' ORDER BY ';
    $asc = explode(',', $_POST['sort']);
    $col = explode(',', $_POST['column']);
    foreach ($asc as $key => $item) {
        $query .= $col[$key] . ' ' . $item;
        if ($key < count($asc) - 1) {
            $query .= ',';
        }
    }
}

// обновляем текущий запрос в таблице:
echo prepareTable($query);

?>