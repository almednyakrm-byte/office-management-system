**list_تعاملات.php**

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
    <title>تعاملات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            color: #f7f7f7;
            padding: 1rem;
            text-align: center;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
        }
        .table th {
            background-color: #1f2937;
            color: #f7f7f7;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تعاملات</h1>
        <nav>
            <a href="index.php">الرئيسية</a>
            <span class="text-indigo-500">|</span>
            <span><?= $_SESSION['username'] ?></span>
            <span class="text-indigo-500">|</span>
            <a href="logout.php">تسجيل خروج</a>
        </nav>
    </div>
    <div class="container mx-auto p-4">
        <button class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_تعاملات.php'">إضافة عنصر جديد</button>
        <div class="flex justify-center mt-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
        </div>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>عنوان</th>
                    <th>تاريخ</th>
                    <th>حالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const records = document.getElementById('records').getElementsByTagName('tr');
            for (let i = 0; i < records.length; i++) {
                const record = records[i];
                const title = record.cells[0].textContent.toLowerCase();
                if (title.includes(searchValue)) {
                    record.style.display = 'table-row';
                } else {
                    record.style.display = 'none';
                }
            }
        });

        // Fetch records from backend
        fetch('../backend/تعاملات.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const records = document.getElementById('records');
            data.forEach(record => {
                const row = records.insertRow();
                row.insertCell().textContent = record.title;
                row.insertCell().textContent = record.date;
                row.insertCell().textContent = record.status;
                row.insertCell().innerHTML = `
                    <a href="edit_تعاملات.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded ml-2" onclick="deleteRecord(${record.id})">حذف</button>
                `;
            });
        })
        .catch(error => console.error(error));

        // Delete record
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                fetch('../backend/تعاملات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف العنصر بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف العنصر');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

Note: This code assumes that you have a backend PHP file `backend/تعاملات.php` that handles GET and DELETE requests for retrieving and deleting records, respectively. You will need to create this file and implement the necessary logic to interact with your database.