<?php
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get the user role
$user_role = $_SESSION['role'];

// Check if the user is an admin
$is_admin = ($user_role == 'admin');

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET requests
if ($method == 'GET') {
    // Get the ID parameter
    $id = $_GET['id'] ?? null;

    // Check if the user is an admin to allow GET requests for all records
    if (!$is_admin && !$id) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare the SQL query
    $stmt = $pdo->prepare('SELECT * FROM إدارة_الصرف');
    if ($id) {
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $record = $stmt->fetch();
    } else {
        $stmt->execute();
        $records = $stmt->fetchAll();
    }

    // Output the result
    if ($id) {
        http_response_code(200);
        echo json_encode($record);
    } else {
        http_response_code(200);
        echo json_encode($records);
    }
} elseif ($method == 'POST') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the input
    $required_fields = array('field1', 'field2', 'field3');
    foreach ($required_fields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
    }

    // Sanitize the input
    $data = array_map('trim', $data);

    // Prepare the SQL query
    $stmt = $pdo->prepare('INSERT INTO إدارة_الصرف (field1, field2, field3) VALUES (:field1, :field2, :field3)');
    $stmt->execute($data);

    // Output the result
    http_response_code(201);
    echo json_encode(array('message' => 'Record created successfully'));
} elseif ($method == 'PUT') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the input
    $required_fields = array('id', 'field1', 'field2', 'field3');
    foreach ($required_fields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
    }

    // Sanitize the input
    $data = array_map('trim', $data);

    // Prepare the SQL query
    $stmt = $pdo->prepare('UPDATE إدارة_الصرف SET field1 = :field1, field2 = :field2, field3 = :field3 WHERE id = :id');
    $stmt->execute($data);

    // Output the result
    http_response_code(200);
    echo json_encode(array('message' => 'Record updated successfully'));
} elseif ($method == 'DELETE') {
    // Get the ID parameter
    $id = $_GET['id'] ?? null;

    // Check if the user is an admin to allow DELETE requests
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare the SQL query
    $stmt = $pdo->prepare('DELETE FROM إدارة_الصرف WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Output the result
    http_response_code(200);
    echo json_encode(array('message' => 'Record deleted successfully'));
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}