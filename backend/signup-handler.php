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
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '';
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';

    // initialize errors array
    $errors = [];
    $username_regEx = '/^[a-zA-Z0-9_]+$/'; // regex for alphanumeric characters for 1 or more characters (+)
    $name_regEx = '/^[a-zA-Z\s]+$/';


    if (empty($username))
    {
        $errors['username'] = '<i class="material-icons">&#xe001</i>Username is required';
    }
    else if (!preg_match($username_regEx, $username))
    {
        $errors['username'] = '<i class="material-icons">&#xe001</i>Username must not contain special characters or whitespaces';
    }
    else if (strlen($username) < 3 || strlen($username) > 16)
    {
        $errors['username'] = '<i class="material-icons">&#xe001</i>Username must be between 3 and 16 characters long';
    }
    else { // the username's input field is not empty, check for password

        $stmt = $pdo->prepare("SELECT username FROM users WHERE username = '$username'");
        $stmt->execute();
        if ($stmt->rowCount() > 0)
        {
            $errors['username'] = '<i class="material-icons">&#xe001</i>Username already exists';
        }
        if (empty($password))
        {
            $errors['password'] = '<i class="material-icons">&#xe001</i>Password is required';
        }
        else if (strlen($password) < 8 )
        {
            $errors['password'] = '<i class="material-icons">&#xe001</i>Password must be at least 8 characters long';
        }


        if (empty($name))
        {
            $errors['name'] = '<i class="material-icons">&#xe001</i>Name is required';
        }
        else if (!preg_match($name_regEx, $name))
        {
            $errors['name'] = '<i class="material-icons">&#xe001</i>Name must only contain alphabetic characters and spaces';
        }

        if ($gender === "")
        {
            $errors['gender'] = '<i class="material-icons">&#xe001</i>Gender is required';
        }

    }





    if (empty($errors))
    {
        $stmt = $pdo->prepare("
       INSERT INTO users (username,name , password, gender) 
       VALUES (:username, :name, :password, :gender) 
       ");

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':gender', $gender);

        if ($stmt->execute())
        {
            // fetch user info to display
            $stmt = $pdo->prepare("SELECT userID FROM users WHERE username = :username");
            $stmt->bindParam(":username", $username);
            $stmt->execute();

            $_SESSION = [
                'userID' => $stmt->fetch(PDO::FETCH_ASSOC)['userID'],
            ];
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
               'data' => '<i class="material-icons">&#xe001</i>Something went wrong please try again'
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

