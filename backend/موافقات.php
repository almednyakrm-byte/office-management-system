<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Read inputs from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Sanitize query parameters
    $limit = isset($input['limit']) ? (int)$input['limit'] : 10;
    $offset = isset($input['offset']) ? (int)$input['offset'] : 0;

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM موافقات ORDER BY id LIMIT :limit OFFSET :offset');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch and return results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($results);
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Sanitize input data
    $name = isset($input['name']) ? trim($input['name']) : '';
    $description = isset($input['description']) ? trim($input['description']) : '';

    // Validate input data
    if (empty($name) || empty($description)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO موافقات (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return inserted ID
    $insertedID = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $insertedID]);
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Sanitize input data
    $id = isset($input['id']) ? (int)$input['id'] : 0;
    $name = isset($input['name']) ? trim($input['name']) : '';
    $description = isset($input['description']) ? trim($input['description']) : '';

    // Validate input data
    if (empty($name) || empty($description)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE موافقات SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return updated count
    $updatedCount = $stmt->rowCount();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['updated' => $updatedCount]);
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Sanitize input data
    $id = isset($input['id']) ? (int)$input['id'] : 0;

    // Validate input data
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM موافقات WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Return deleted count
    $deletedCount = $stmt->rowCount();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['deleted' => $deletedCount]);
}

// Return error for unsupported methods
else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}