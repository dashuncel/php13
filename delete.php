<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'lib.php';

$query='delete from tasks where tasks.id = '.$_POST['id'];
try {
    $statement = $pdo->prepare($query);
    $statement->execute();
}
catch (PDOException $e) {
    echo "Ошибка удаления записи из БД: ".$e->getMessage().'<br/>';
    exit;
}

echo prepareTable($mainQuery);

?>