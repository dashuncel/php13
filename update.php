<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'lib.php';

$query='update tasks set is_done ='.$_POST['done'].'  where tasks.id = '.$_POST['id'];
try {
    $statement = $pdo->prepare($query);
    $statement->execute();
}
catch (PDOException $e) {
    echo "Ошибка обновления записи в БД: ".$e->getMessage().'<br/>';
    exit;
}

echo prepareTable($mainQuery);
echo $query;
?>