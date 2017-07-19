<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'lib.php';

$query='delete from tasks where tasks.id = '.$_POST['id'];
$statement=$pdo->prepare($query);
$statement->execute();
/*
echo prepareTable($mainQuery);*/

echo $query;

?>