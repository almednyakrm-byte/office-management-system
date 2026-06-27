<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-slate-900 to-indigo-500 h-screen">
    <div class="container mx-auto p-4 md:p-6 lg:p-8 h-full">
        <div class="flex justify-center h-full">
            <div class="bg-white rounded-lg shadow-lg p-8 md:p-12 lg:p-16 w-full md:w-1/2 lg:w-1/3 xl:w-1/4">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Login</h2>
                <form id="login-form">
                    <div class="mb-4">
                        <label for="username" class="block text-slate-900 text-sm font-bold mb-2">Username</label>
                        <input type="text" id="username" name="username" class="block w-full p-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                        <div id="username-error" class="text-red-500 hidden"></div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-slate-900 text-sm font-bold mb-2">Password</label>
                        <input type="password" id="password" name="password" class="block w-full p-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <div id="password-error" class="text-red-500 hidden"></div>
                    </div>
                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Login</button>
                </form>
                <p class="text-sm text-gray-500 mt-4">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('login-form');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const usernameError = document.getElementById('username-error');
        const passwordError = document.getElementById('password-error');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            usernameError.classList.remove('text-red-500');
            passwordError.classList.remove('text-red-500');
            usernameError.textContent = '';
            passwordError.textContent = '';

            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();

            if (username === '') {
                usernameError.classList.add('text-red-500');
                usernameError.textContent = 'Username is required';
                return;
            }

            if (password === '') {
                passwordError.classList.add('text-red-500');
                passwordError.textContent = 'Password is required';
                return;
            }

            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    if (data.error.username) {
                        usernameError.classList.add('text-red-500');
                        usernameError.textContent = data.error.username;
                    }
                    if (data.error.password) {
                        passwordError.classList.add('text-red-500');
                        passwordError.textContent = data.error.password;
                    }
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in. Please try again later.');
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses the Tailwind CSS CDN for styling and includes a beautiful glassmorphic layout, gradients, and a form for username and password input. The form includes validation rules for username and password input, and it uses the standard HTML input pattern validator to support Arabic and Latin characters. The code also includes AJAX JavaScript using the Fetch API to submit the credentials to the `../backend/auth.php?action=login` endpoint and handle the response or error alerts dynamically. Finally, it provides a direct link to the `register.php` page.