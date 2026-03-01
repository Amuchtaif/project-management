<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= BASEURL; ?>/img/favicon.png">
    <script>
        // Initialize theme before page load
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-login-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-slate-950 min-h-screen flex items-center justify-center p-4 transition-colors">

    <div class="max-w-4xl w-full bg-white dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row transition-colors border border-transparent dark:border-slate-800">
        <!-- Left Side: Form -->
        <div class="w-full md:w-1/2 p-12">
            
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2 transition-colors">Selamat Datang Kembali</h1>
            <p class="text-gray-500 dark:text-gray-400 mb-8 transition-colors">Masuk ke akun Anda untuk mengelola proyek</p>

            <?php Flasher::flashInline(); ?>

            <form action="<?= BASEURL; ?>/login/process" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors">Email</label>
                    <input type="email" name="email" placeholder="masukan email" class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition transition-colors" required>
                </div>
                <div>
                    <div class="flex justify-between mb-2 transition-colors">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors">Kata Sandi</label>
                    </div>
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="masukan kata sandi" class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition transition-colors" required>
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="toggleIcon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition transform hover:scale-[1.02] active:scale-95 shadow-lg shadow-indigo-200">
                    Login
                </button>
            </form>
        </div>

        <!-- Right Side: Marketing/Visual -->
        <div class="hidden md:flex w-1/2 bg-indigo-600 p-12 flex-col items-center justify-center text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-login-gradient opacity-90"></div>
            
            <!-- Abstract Shapes -->
            <div class="absolute top-[-10%] right-[-10%] w-64 h-64 bg-white opacity-10 rounded-full"></div>
            <div class="absolute bottom-[-5%] left-[-5%] w-48 h-48 bg-white opacity-10 rounded-full"></div>

            <div class="relative z-10 w-full max-w-sm">
                <div class="bg-white/10 backdrop-blur-lg border border-white/20 p-8 rounded-3xl shadow-2xl mb-10 transform -rotate-2">
                    <div class="bg-indigo-500 w-full aspect-video rounded-2xl flex items-center justify-center mb-6">
                        <img src="<?= BASEURL; ?>/img/logo.png" alt="Logo" class="w-32 h-32 object-contain">
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Yayasan Assunnah</h2>
                    <p class="text-indigo-100 text-sm leading-relaxed">
                        Membangun masa depan yang lebih baik melalui manajemen projek yang terstruktur dan aman.
                    </p>
                </div>

                <div class="flex flex-wrap justify-center gap-2">
                    <span class="px-4 py-1.5 bg-white/10 backdrop-blur-md rounded-full text-xs text-white font-medium border border-white/10">Manajemen Task</span>
                    <span class="px-4 py-1.5 bg-white/10 backdrop-blur-md rounded-full text-xs text-white font-medium border border-white/10">Analisis Real-time</span>
                    <span class="px-4 py-1.5 bg-white/10 backdrop-blur-md rounded-full text-xs text-white font-medium border border-white/10">Kolaborasi Tim</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // AUTO HIDE NOTIFICATION
        function hideNotification() {
            const notification = document.getElementById('notification-bar');
            if (notification) {
                notification.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => notification.remove(), 300);
            }
        }

        window.onload = () => {
            const notification = document.getElementById('notification-bar');
            if (notification) {
                setTimeout(hideNotification, 3000);
            }
        }

        // PASSWORD VISIBILITY TOGGLE
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.057 10.057 0 012.183-3.183m3.009-3.009A10.055 10.055 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.057 10.057 0 01-2.183 3.183M9.875 9.875A3.333 3.333 0 0112 9c.63 0 1.201.24 1.625.625m2.5 2.5A3.333 3.333 0 0115 12c0 .63-.24 1.201-.625 1.625m-2.5 2.5a3.333 3.333 0 01-3.125-3.125m10.125-10.125L3 3" />`;
            } else {
                passwordInput.type = 'password';
                toggleIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
            }
        }
    </script>

</body>
</html>
