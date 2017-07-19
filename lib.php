<?php

require_once 'mydata.php';

$host='localhost';
/*$user=LOGIN;
$password=PASSWD;*/
$user='root';
$password='';
$database='global';
$dbport=3306;
$mainQuery="SELECT * FROM tasks ";

$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false
];

try
{
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $password, $opt);
}
catch (PDOException $e)
{
    echo 'Ошибка подключения к БД: '.$e->getMessage().'<br/>';
    exit;
}

// выполняет запрос из параметре и готовит HTML таблицу
function prepareTable($query) {
    global $pdo;
    $statement=$pdo->prepare($query);
    $statement->execute();
    $rows=$statement->fetchAll();

    if (empty($rows) || ! is_array($rows)) {
        exit;
    }
    $str='';
    foreach ($rows as $row) {
        if ($row['is_done'] == 0) {
            $done='undone';
            $title='невыполнено';
        }
        else {
            $done='done';
            $title='выполнено';
        }

        $a = "<pre><a title='редактировать' href='#' id={$row['id']} class='edit'><img src='.\img\\ed.png'></a>";
        $a .= "  <a title='удалить' href='#' id={$row['id']} class='del'><img src='.\img\drop.png'></a></pre>";
        $str.="<tr>";
        $str.="<td>{$row['date_added']}</td><td >{$row['description']}</td><td id={$row['id']} class=$done title=$title></td><td>$a</td>";
        $str.="</tr>";
    }
    return $str;
}

