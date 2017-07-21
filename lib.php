<?php

require_once 'mydata.php';

$host='localhost';

$user=LOGIN;
$password=PASSWD;
$database='byankina';
/*
$user='root';
$password='';
$database='global';
*/
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

// выполняет запрос из параметра и готовит HTML таблицу
function prepareTable($query) {
    global $pdo;
    try {
        $statement = $pdo->prepare($query);
        $statement->execute();
    }
    catch (PDOException $e) {
        echo "Ошибка отправки запроса '$query' к БД: ".$e->getMessage().'<br/>';
        exit;
    }

    $rows=$statement->fetchAll();

    if (empty($rows) || ! is_array($rows)) {
        return '';
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

        $a = "<a title='редактировать' href='#' class='edit'><img src='.\img\\ed.png'></a>";
        $a .= "  <a title='удалить' href='#' class='del'><img src='.\img\drop.png'></a>";
        $str.="<tr id={$row['id']}>";
        $str.="<td>{$row['date_added']}</td><td >{$row['description']}</td><td class=$done title=$title></td><td>$a</td>";
        $str.="</tr>";
    }
    return $str;
}

