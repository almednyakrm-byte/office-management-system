**create_إدارة-الصرف.php**

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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    // Check if form data is valid
    if (!empty($name) && !empty($description) && !empty($status)) {
        // Insert new record into database
        $sql = "INSERT INTO إدارة_الصرف (name, description, status) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $description, $status]);

        // Redirect back to list page
        header('Location: list_إدارة-الصرف.php');
        exit;
    }
}

// Include header and navigation
require_once '../includes/header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة إدارة الصرف جديدة</h2>
        <form id="create-form" method="POST" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label for="name" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">اسم الإدارة</label>
                    <input id="name" type="text" name="name" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label for="description" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">وصف الإدارة</label>
                    <textarea id="description" name="description" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label for="status" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">حالة الإدارة</label>
                    <select id="status" name="status" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <option value="">اختر حالة</option>
                        <option value="active">نشط</option>
                        <option value="inactive">مغلق</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/إدارة-الصرف.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_إدارة-الصرف.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

**backend/إدارة-الصرف.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    // Check if form data is valid
    if (!empty($name) && !empty($description) && !empty($status)) {
        // Insert new record into database
        $sql = "INSERT INTO إدارة_الصرف (name, description, status) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $description, $status]);

        // Return success message
        echo 'success';
    } else {
        // Return error message
        echo 'Error: Invalid form data';
    }
}
?>