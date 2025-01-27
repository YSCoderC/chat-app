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

    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '';
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';

    // initialize errors array
    $errors = [];
    $regEx = '/^[a-zA-Z0-9_]+$/'; // regex for alphanumeric characters for 1 or more characters (+)


    if (empty($username))
    {
        $errors['username'] = "Username is required";
    }
    else if (!preg_match($regEx, $username))
    {
        $errors['username'] = "Username must not contain special characters or whitespaces";
    }
    else if (strlen($username) < 3 || strlen($username) > 16)
    {
        $errors['username'] = "Username must be between 3 and 16 characters long";
    }
    else { // the username's input field is not empty, check for password

        $stmt = $pdo->prepare("SELECT username FROM users WHERE username = '$username'");
        $stmt->execute();
        if ($stmt->rowCount() > 0)
        {
            $errors['username'] = 'Username already exists';
        }
        if (empty($password))
        {
            $errors['password'] = "Password is required";
        }
        else if (strlen($password) < 8 )
        {
            $errors['password'] = "Password must be at least 8 characters long";
        }


        if ($gender === "")
        {
            $errors['gender'] = 'Gender is required';
        }

    }





    if (empty($errors))
    {
        $stmt = $pdo->prepare("
       INSERT INTO users (username, password, gender) 
       VALUES (:username, :password, :gender) 
       ");

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':gender', $gender);

        if ($stmt->execute())
        {
            echo json_encode(
                [
                    'success' => true,
                    'message' => 'signup successful'
                ]
            );
        }
        else
        {
            echo json_encode([
                'success' => false,
               'data' => 'Something went wrong please try again'
            ]);
        }
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

