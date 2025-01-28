<?php
session_start();

include '../backend/retrieve_user_data.php';

$result = getUserData($_SESSION['userID'])

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $result['username'] ?> | Dashboard </title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <div class="container">
        <div class="profile-info">
            <img src="/chat_app/images/default_profile.png" alt="profile-picture">
            <div>
                <h1><?php echo $result['username'] ?></h1>
                <h1>Status</h1>
            </div>
            <a href="/chat_app/backend/logout.php">Logout</a>
        </div>

        <div class="users-list"></div>
    </div>