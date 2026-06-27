<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$userRole = $_SESSION['role'];

// Check if user is admin
$isAdmin = ($userRole == 'admin');

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method == 'GET') {
    // Validate and sanitize input
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    // Check if user is admin to allow retrieval of all records
    if (!$isAdmin && $id === null) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM مورد WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch and return result
    $result = $stmt->fetch();
    if ($result) {
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Not Found'));
    }
} elseif ($method == 'POST') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = isset($input['name']) ? trim($input['name']) : null;
    $description = isset($input['description']) ? trim($input['description']) : null;

    // Check if input is valid
    if (!$name || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO مورد (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return result
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created'));
} elseif ($method == 'PUT') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = isset($input['id']) ? intval($input['id']) : null;
    $name = isset($input['name']) ? trim($input['name']) : null;
    $description = isset($input['description']) ? trim($input['description']) : null;

    // Check if user is admin to allow edits
    if (!$isAdmin) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Check if input is valid
    if (!$id || !$name || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE مورد SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return result
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated'));
} elseif ($method == 'DELETE') {
    // Validate and sanitize input
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    // Check if user is admin to allow deletions
    if (!$isAdmin) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Check if input is valid
    if (!$id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM مورد WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return result
    http_response_code(204);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted'));
}