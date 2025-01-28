<?php

$host = "localhost";
$user = "root";
$pass = "";
$db_name = 'chat_app';


try
{
    $pdo = new PDO("mysql: host=$host;  dbname=$db_name", "$user", "$pass");

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // set the PDO::ATTR_ERRMODE ==> PDO::ERRMODE_EXCEPTION to
    // handle database errors better and to make debugging easier.
}
catch (PDOException $e)
{
    die("Connection failed: " . $e->getMessage());
}
