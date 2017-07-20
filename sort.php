<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'lib.php';

$query=$mainQuery.' ORDER BY ';
$asc=explode(',', $_POST['sort']);
$col=explode(',', $_POST['column']);
foreach ($asc as $key=>$item) {
    $query.= $col[$key].' '.$item;
    if ($key < count($asc) - 1 ) { $query.=',';}
}
echo prepareTable($query);

?>