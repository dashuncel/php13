<?php

require_once 'mydata.php';

$host='localhost';

$user=LOGIN;
$password=PASSWD;
/*
$user='root';
$password='';
*/

$database='byankina';
$dbport=3306;

/*
$query=
"CREATE TABLE tasks (
id int(11) NOT NULL AUTO_INCREMENT,
  description text NOT NULL,
  is_done tinyint(4) NOT NULL DEFAULT '0',
  date_added datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
*/

$date = date("Y-m-d H:i:s");
$query = "insert into tasks (description, is_done, date_added) values (:description, :done, :date) ";
$param = ["тестовое задание", 0, $date];

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


try {
    $statement = $pdo->prepare($query);
    $statement->execute();
}
catch (PDOException $e) {
    echo "Ошибка отправки запроса '$query' к БД: ".$e->getMessage().'<br/>';
    exit;
}


?>