<?php
session_start();

include '../backend/retrieve_user_data.php';

$result = getUserData($_SESSION['userID'])

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $result['name'] ?> | Dashboard </title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <div class="container">
        <div class="profile-info">
            <img src="/chat_app/images/default_profile.png" alt="profile-picture">
            <div>
                <h1><?php echo $result['name'] ?></h1>
                <h1 class="username">@<?php echo $result['username'] ?></h1>
            </div>
            <a href="/chat_app/backend/logout.php">Logout</a>
        </div>

        <div class="users-list">
            <!-- Displaying users here -->
            <div class="user-item">
                <div class="user-info">
                    <img src="/chat_app/images/default_profile.png" alt="profile-picture">
                    <div>
                        <h2>test name</h2>
                        <h2 class="username">@test_username</h2>
                    </div>
                </div>
                <a href="#">Chat</a>
            </div>

            <div class="user-item">
                <div class="user-info">
                    <img src="/chat_app/images/default_profile.png" alt="profile-picture">
                    <div>
                        <h2>test name</h2>
                        <h2 class="username">@test_username</h2>
                    </div>
                </div>
                <a href="#">Chat</a>
            </div>

            <div class="user-item">
                <div class="user-info">
                    <img src="/chat_app/images/default_profile.png" alt="profile-picture">
                    <div>
                        <h2>test name</h2>
                        <h2 class="username">@test_username</h2>
                    </div>
                </div>
                <a href="#">Chat</a>
            </div>

            <div class="user-item">
                <div class="user-info">
                    <img src="/chat_app/images/default_profile.png" alt="profile-picture">
                    <div>
                        <h2>test name</h2>
                        <h2 class="username">@test_username</h2>
                    </div>
                </div>
                <a href="#">Chat</a>
            </div>

        </div>
    </div>