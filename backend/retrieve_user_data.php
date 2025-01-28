<?php

function getUserData($userID): array
{
    global $pdo;
    require_once 'DB_connection.php';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE userID = :userID");
    $stmt->bindParam(':userID', $userID);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
