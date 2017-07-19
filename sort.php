<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'lib.php';

$query=$mainQuery.' order BY '.$_POST['column'].' '.$_POST['sort'];
echo prepareTable($query);

?>