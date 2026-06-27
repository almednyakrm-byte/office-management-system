**create_موافقات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Check if fields are not empty
    if (empty($name) || empty($description)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert new record into database
        $sql = "INSERT INTO موافقات (name, description) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $description]);

        // Redirect back to list page
        header('Location: list_موافقات.php');
        exit;
    }
}

// Define mod slug
$mod_slug = 'موافقات';

// Include header and navigation
require_once '../includes/header.php';
?>

<!-- Create new record form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create New موافقات</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-slate-900 text-sm font-bold mb-2">Description:</label>
            <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/موافقات.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_موافقات.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>


**موافقات.php (backend)**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Insert new record into database
    $sql = "INSERT INTO موافقات (name, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$name, $description]);

    // Return success response
    echo json_encode(['success' => true]);
} else {
    // Return error response
    echo json_encode(['error' => 'Invalid request']);
}
?>