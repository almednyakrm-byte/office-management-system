**list_دفاتر.php**

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
    <title>دفاتر</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            margin: 1rem auto;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">دفاتر</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_دفاتر.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>عنوان</th>
                    <th>وصف</th>
                    <th>تاريخ الإضافة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['title'] . '</td>';
                    echo '<td>' . $record['description'] . '</td>';
                    echo '<td>' . $record['added_at'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_دفاتر.php?id=' . $record['id'] . '" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>';
                    echo '<button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/دفاتر.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.title}</td>
                            <td>${record.description}</td>
                            <td>${record.added_at}</td>
                            <td>
                                <a href="edit_دفاتر.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/دفاتر.php?id=' + id, { method: 'DELETE' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('تم حذف السجل بنجاح');
                            location.reload();
                        } else {
                            alert('حدث خطأ أثناء حذف السجل');
                        }
                    });
            }
        }

        function fetchRecords() {
            return fetch('../backend/دفاتر.php')
                .then(response => response.json())
                .then(data => data.records);
        }
    </script>
</body>
</html>

**backend/دفاتر.php**

<?php
// Fetch records from database
$records = array();
$records = fetchRecordsFromDatabase();

// Search records
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = searchRecordsFromDatabase($search);
}

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    deleteRecordFromDatabase($id);
}

// Output records
header('Content-Type: application/json');
echo json_encode(array('records' => $records));
exit;

function fetchRecordsFromDatabase() {
    // Implement database connection and query
    $db = new PDO('dsn', 'username', 'password');
    $stmt = $db->prepare('SELECT * FROM دفاتر');
    $stmt->execute();
    $records = $stmt->fetchAll();
    return $records;
}

function searchRecordsFromDatabase($search) {
    // Implement database connection and query
    $db = new PDO('dsn', 'username', 'password');
    $stmt = $db->prepare('SELECT * FROM دفاتر WHERE عنوان LIKE :search');
    $stmt->bindParam(':search', $search);
    $stmt->execute();
    $records = $stmt->fetchAll();
    return $records;
}

function deleteRecordFromDatabase($id) {
    // Implement database connection and query
    $db = new PDO('dsn', 'username', 'password');
    $stmt = $db->prepare('DELETE FROM دفاتر WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}
?>

Note: This code assumes a basic understanding of PHP, PDO, and database connections. You should replace the placeholders with your actual database credentials and connection details. Additionally, this code does not include any error handling or security measures, which you should implement in a production environment.