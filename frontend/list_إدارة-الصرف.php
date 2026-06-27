**list_إدارة-الصرف.php**

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
    <title>إدارة الصرف</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f1f1f;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .nav .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .header .nav .links {
            display: flex;
            gap: 1rem;
        }
        .header .nav .links a {
            color: #fff;
            text-decoration: none;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button {
            background-color: #1f1f1f;
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="nav">
            <div class="logo">إدارة الصرف</div>
            <div class="links">
                <a href="index.php">الرئيسية</a>
                <a href="profile.php">ملفي</a>
                <a href="logout.php">تسجيل الخروج</a>
            </div>
        </div>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">إدارة الصرف</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_إدارة-الصرف.php'">إضافة جديد</button>
            <div class="search-bar">
                <input type="search" id="search" placeholder="بحث...">
                <button onclick="searchRecords()">بحث</button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>التاريخ</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/إدارة-الصرف.php'), true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['title'] . '</td>';
                    echo '<td>' . $record['date'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_إدارة-الصرف.php?id=' . $record['id'] . '" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>';
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
            fetch('../backend/إدارة-الصرف.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.title}</td>
                            <td>${record.date}</td>
                            <td>
                                <a href="edit_إدارة-الصرف.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/إدارة-الصرف.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
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
    </script>
</body>
</html>

This code creates a premium Tailwind UI for managing the 'إدارة الصرف' module. It includes session validation, a header navigation bar, a table showing list of records with actions, an 'Add New Item' button, a search bar, and AJAX JavaScript code for fetching and deleting records.