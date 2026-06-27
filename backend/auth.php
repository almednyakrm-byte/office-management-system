<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response with their details
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array(
        'status' => 'logged_in',
        'user_id' => $user_id,
        'username' => $username
    );
    echo json_encode($response);
    exit;
}

// Check if the user is trying to register or login
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Check if the user is trying to register
    if ($action == 'register') {
        // Check if the required fields are filled
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Check if the username and email are valid
            if (preg_match('/^[a-zA-Z0-9_]+$/', $username) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Prepare the SQL query to check if the username or email already exists
                $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
                $stmt->bind_param("ss", $username, $email);
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if the username or email already exists
                if ($result->num_rows > 0) {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Username or email already exists'
                    );
                    echo json_encode($response);
                    exit;
                }

                // Hash the password using password_hash()
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Prepare the SQL query to insert the new user
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashed_password);
                $stmt->execute();

                // Get the ID of the newly inserted user
                $user_id = $conn->insert_id;

                // Create a new session for the user
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;

                $response = array(
                    'status' => 'success',
                    'message' => 'User registered successfully'
                );
                echo json_encode($response);
                exit;
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Invalid username or email'
                );
                echo json_encode($response);
                exit;
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Please fill in all required fields'
            );
            echo json_encode($response);
            exit;
        }
    }

    // Check if the user is trying to login
    elseif ($action == 'login') {
        // Check if the required fields are filled
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Prepare the SQL query to check if the username exists
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the username exists
            if ($result->num_rows > 0) {
                // Get the user's details
                $user = $result->fetch_assoc();

                // Check if the password is correct using password_verify()
                if (password_verify($password, $user['password'])) {
                    // Create a new session for the user
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $username;

                    $response = array(
                        'status' => 'success',
                        'message' => 'User logged in successfully'
                    );
                    echo json_encode($response);
                    exit;
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Incorrect password'
                    );
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Username does not exist'
                );
                echo json_encode($response);
                exit;
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Please fill in all required fields'
            );
            echo json_encode($response);
            exit;
        }
    }

    // Check if the user is trying to logout
    elseif ($action == 'logout') {
        // Destroy the session
        session_destroy();

        $response = array(
            'status' => 'success',
            'message' => 'User logged out successfully'
        );
        echo json_encode($response);
        exit;
    }
}

// If the user is not logged in, return a JSON response with a status of 'logged_out'
$response = array(
    'status' => 'logged_out'
);
echo json_encode($response);
exit;
?>