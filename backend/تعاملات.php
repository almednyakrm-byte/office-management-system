<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Read inputs from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate user role
    if ($userRole !== 'admin' && $userRole !== 'user') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Prepare SQL query to select all rows from تعاملات table
    $stmt = $pdo->prepare('SELECT * FROM تعاملات');
    $stmt->execute();

    // Fetch and return all rows
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rows);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user role
    if ($userRole !== 'admin' && $userRole !== 'user') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate input data
    if (!isset($input['title']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $title = $pdo->quote($input['title']);
    $description = $pdo->quote($input['description']);

    // Prepare SQL query to insert new row into تعاملات table
    $stmt = $pdo->prepare('INSERT INTO تعاملات (title, description) VALUES (:title, :description)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return new row ID
    $newRowID = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $newRowID]);
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate user role
    if ($userRole !== 'admin' && $userRole !== 'user') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate input data
    if (!isset($input['id']) || !isset($input['title']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = $input['id'];
    $title = $pdo->quote($input['title']);
    $description = $pdo->quote($input['description']);

    // Prepare SQL query to update existing row in تعاملات table
    $stmt = $pdo->prepare('UPDATE تعاملات SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return updated row ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id]);
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate user role
    if ($userRole !== 'admin' && $userRole !== 'user') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = $input['id'];

    // Prepare SQL query to delete existing row from تعاملات table
    $stmt = $pdo->prepare('DELETE FROM تعاملات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return deleted row ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id]);
    exit;
}