<?php


session_start();

header('Access-Control-Allow-Origin: http://localhost');
header('Access-Control-Allow-Origin: http://localhost:63342');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow specific HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow specific headers
header('Content-Type: application/json'); // set header to return JSON


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /// Database connection
    global $pdo;
    require_once "DB_connection.php";


    ///// Validation

    $username = $_POST["username"];
    $password = $_POST["password"];

    // initialize errors array
    $errors = [];


    if (empty($username)) {
        $errors['username'] = "Username is required";
    }
    else {// if the username input is not empty, verify if the user exists

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username);

        if (!$stmt->execute()) { // if errors occur during query execution
            $errors['data'] = 'Error fetching data, please try again';
        }
        else {
            if ($stmt->rowCount() === 0) { // user does not exist in the database
                $errors['username'] = 'Username not found';
            }
            else {// check for password

                if (empty($password)) {
                    $errors['password'] = "Password is required";
                }
                else {
                    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = :username");
                    $stmt->bindParam(":username", $username);
                    if (!$stmt->execute()) {
                        $errors['data'] = 'Error fetching data, please try again';
                    }
                    else {
                        $DB_password = $stmt->fetch(PDO::FETCH_ASSOC)['password'];

                        if (!password_verify($password, $DB_password)) {
                            $errors['password'] = 'Password is incorrect';
                        }
                    }
                }

            }
        }

    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION = [
            'userID' => $user['userID'],
            'username' => $user['username'],
            'password' => $user['password'],
            'gender' => $user['gender'],
            ];

        echo json_encode([
            'success' => true,
        ]);
    }
    else
    {
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
    }
    exit();
}