<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'lib.php';

if (! isset($_POST['id'])) {
    exit;
}

if (isset($_POST['done'])) {
    $query = 'update tasks set is_done =' . $_POST['done'] . '  where tasks.id = ' . $_POST['id'];
}

if (isset($_POST['description'])) {
    $query = 'update tasks set description =' . $_POST['description'] . '  where tasks.id = ' . $_POST['id'];
}

try {
    $statement = $pdo->prepare($query);
    $statement->execute();
}
catch (PDOException $e) {
    echo "Ошибка обновления записи в БД: ".$e->getMessage().'<br/>';
    exit;
}

echo prepareTable($mainQuery);

?>