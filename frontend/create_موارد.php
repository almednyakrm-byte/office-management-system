**create_موارد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $query = "INSERT INTO موارد (name, description) VALUES ('$name', '$description')";
    $result = mysqli_query($conn, $query);

    // Check if data has been inserted successfully
    if ($result) {
        // Redirect back to list page
        header('Location: list_موارد.php');
        exit;
    } else {
        // Display error message
        $error = 'Error inserting data';
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">Create New مورد</h1>
    <form action="" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>


**create_موارد.js**
javascript
$(document).ready(function() {
    // Submit form using AJAX
    $('form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '../backend/موارد.php',
            data: $(this).serialize(),
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'list_موارد.php';
                } else {
                    alert('Error creating مورد');
                }
            }
        });
    });
});


**backend/موارد.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been submitted
if (isset($_POST['name']) && isset($_POST['description'])) {
    // Insert data into database
    $query = "INSERT INTO موارد (name, description) VALUES ('".$_POST['name']."', '".$_POST['description']."')";
    $result = mysqli_query($conn, $query);

    // Check if data has been inserted successfully
    if ($result) {
        // Output success message
        echo 'success';
    } else {
        // Output error message
        echo 'Error inserting data';
    }
}
?>