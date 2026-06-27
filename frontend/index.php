<?php
session_start();

// Check if user is authenticated
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
    <title>نظام إدارة المكاتب والموظفين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
        }
        
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .stats-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .quick-links {
            display: flex;
            gap: 20px;
        }
        
        .quick-link {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .quick-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-slate-900">نظام إدارة المكاتب والموظفين</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
        </div>
        
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-slate-900">مرحباً بكم</h2>
            <p class="text-gray-600">نظام إدارة المكاتب والموظفين</p>
        </div>
        
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-slate-900">إحصائيات</h2>
            <div class="stats-grid">
                <div class="stats-card">
                    <h3 class="text-lg font-bold text-slate-900">دفاتر</h3>
                    <p class="text-gray-600" id="stats-documents"></p>
                </div>
                <div class="stats-card">
                    <h3 class="text-lg font-bold text-slate-900">موافقات</h3>
                    <p class="text-gray-600" id="stats-approvals"></p>
                </div>
                <div class="stats-card">
                    <h3 class="text-lg font-bold text-slate-900">موارد</h3>
                    <p class="text-gray-600" id="stats-resources"></p>
                </div>
            </div>
        </div>
        
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-slate-900">روابط سريعة</h2>
            <div class="quick-links">
                <a href="manage-documents.php" class="quick-link bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">دفاتر</a>
                <a href="manage-approvals.php" class="quick-link bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">موافقات</a>
                <a href="manage-resources.php" class="quick-link bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">موارد</a>
            </div>
        </div>
    </div>
    
    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('stats-documents').textContent = data.documents;
                document.getElementById('stats-approvals').textContent = data.approvals;
                document.getElementById('stats-resources').textContent = data.resources;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


Note: This code assumes that you have a backend API set up to fetch the stats data. You'll need to replace `/api/stats` with the actual URL of your API endpoint. Also, make sure to update the `manage-documents.php`, `manage-approvals.php`, and `manage-resources.php` links to point to the actual pages that manage these modules.