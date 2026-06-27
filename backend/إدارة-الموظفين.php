<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/employees' => array(
        'GET' => function() {
            // Get all employees
            $stmt = $pdo->prepare('SELECT * FROM employees');
            $stmt->execute();
            $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($employees);
        },
        'POST' => function() {
            // Create new employee
            if (!isset($input['name']) || !isset($input['email']) || !isset($input['role'])) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid input'));
                return;
            }
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
            $role = filter_var($input['role'], FILTER_SANITIZE_STRING);
            $stmt = $pdo->prepare('INSERT INTO employees (name, email, role) VALUES (:name, :email, :role)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);
            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(array('message' => 'Employee created successfully'));
            } else {
                http_response_code(500);
                echo json_encode(array('error' => 'Failed to create employee'));
            }
        }
    ),
    '/employees/:id' => array(
        'GET' => function($id) {
            // Get employee by ID
            $stmt = $pdo->prepare('SELECT * FROM employees WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($employee) {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($employee);
            } else {
                http_response_code(404);
                echo json_encode(array('error' => 'Employee not found'));
            }
        },
        'PUT' => function($id) {
            // Update employee
            if (!isset($input['name']) || !isset($input['email']) || !isset($input['role'])) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid input'));
                return;
            }
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
            $role = filter_var($input['role'], FILTER_SANITIZE_STRING);
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
                return;
            }
            $stmt = $pdo->prepare('UPDATE employees SET name = :name, email = :email, role = :role WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);
            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(array('message' => 'Employee updated successfully'));
            } else {
                http_response_code(500);
                echo json_encode(array('error' => 'Failed to update employee'));
            }
        },
        'DELETE' => function($id) {
            // Delete employee
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
                return;
            }
            $stmt = $pdo->prepare('DELETE FROM employees WHERE id = :id');
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(array('message' => 'Employee deleted successfully'));
            } else {
                http_response_code(500);
                echo json_encode(array('error' => 'Failed to delete employee'));
            }
        }
    )
);

// Get route and method from URL
$route = explode('/', $_SERVER['REQUEST_URI']);
$method = $_SERVER['REQUEST_METHOD'];

// Check if route and method are valid
if (isset($routes[$route[1]]) && isset($routes[$route[1]][$method])) {
    $routes[$route[1]][$method]($route[2]);
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Not found'));
}