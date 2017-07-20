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
if ($typeQuery == 'delete' || $typeQuery == 'update' )  {
    if (! isset($_POST['id'])) {
        exit;
    }
    $id = (int)($_POST['id'] ? : 0);
}

$param=[]; // параметры для запроса, массив
$query=''; // текст промежуточного запроса
$validSortOptions=['is_done', 'description', 'add_date'];

// формируем промежуточный запрос:
switch ($typeQuery) {
    case "delete":
        $param = [ "id" => $id ];
        $query="delete from tasks where tasks.id = :id ";
        break;
    case "update":
        if (isset($_POST['done'])) {
            $param = [ "done" => $_POST['done'], "id" => $id ];
            $query = "update tasks set is_done = :done  where tasks.id = :id";
        }
        else // в данной реализации одновременный update Нескольких полей не предусмотрен
        if (isset($_POST['description'])) {
            $param = ["desc" => $_POST['description'], "id" => $id ];
            $query = "update tasks set description = :desc where tasks.id = :id";
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

// утанавливаем сортировку, если условия сортировки заданы:
$query = $mainQuery;
if (isset($_POST['sort']) && isset($_POST['column'])) {
    $query .= ' ORDER BY ';
    $asc = explode(',', $_POST['sort']);
    $col = explode(',', $_POST['column']);
    foreach ($asc as $key => $item) {
        if (! in_array($col[$key], $validSortOptions)) { continue; }
        $query .= $col[$key] . ' ' . $item;
        if ($key < count($asc) - 1) {
            $query .= ',';
        }
    }
}

// обновляем текущий запрос в таблице:
echo prepareTable($query);

?>