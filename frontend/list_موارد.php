**list_موارد.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>موارد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b6ecf;
        }
    </style>
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4">
        <header class="bg-slate-900 p-4 flex justify-between items-center">
            <a href="index.php" class="text-indigo-500 hover:text-white">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-indigo-500">مرحباً <?= $_SESSION['username'] ?></span>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='logout.php'">تسجيل الخروج</button>
            </div>
        </header>
        <main class="bg-slate-900 p-4">
            <h1 class="text-3xl text-indigo-500">موارد</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='create_موارد.php'">إضافة جديد</button>
            <div class="flex justify-between items-center mt-4">
                <input type="search" id="search" class="bg-slate-900 text-indigo-500 p-2 rounded" placeholder="بحث...">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
            </div>
            <table class="w-full mt-4">
                <thead>
                    <tr>
                        <th class="px-4 py-2">اسم</th>
                        <th class="px-4 py-2">وصف</th>
                        <th class="px-4 py-2">حذف</th>
                        <th class="px-4 py-2">تعديل</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <!-- records will be loaded here -->
                </tbody>
            </table>
        </main>
    </div>

    <script>
        // Search functionality
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/موارد.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                })
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-4 py-2">${record.name}</td>
                            <td class="px-4 py-2">${record.description}</td>
                            <td class="px-4 py-2">
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                            <td class="px-4 py-2">
                                <a href="edit_موارد.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
            } else {
                fetch('../backend/موارد.php')
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-4 py-2">${record.name}</td>
                            <td class="px-4 py-2">${record.description}</td>
                            <td class="px-4 py-2">
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                            <td class="px-4 py-2">
                                <a href="edit_موارد.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
            }
        }

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/موارد.php', {
                    method: 'DELETE',
                    params: { id }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }

        // Load records on page load
        searchRecords();
    </script>
</body>
</html>

**backend/موارد.php**

<?php
// Database connection
$conn = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

// Search functionality
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $stmt = $conn->prepare('SELECT * FROM records WHERE name LIKE :search OR description LIKE :search');
    $stmt->bindParam(':search', '%' . $searchQuery . '%');
    $stmt->execute();
    $data = $stmt->fetchAll();
} else {
    $stmt = $conn->prepare('SELECT * FROM records');
    $stmt->execute();
    $data = $stmt->fetchAll();
}

// Delete record functionality
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare('DELETE FROM records WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
}

// Return records
echo json_encode($data);
?>