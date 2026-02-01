<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vending Machine</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'media', // Otomatis ikut settingan HP/Laptop
            theme: {
                extend: {
                    colors: {
                        dark: { bg: '#0d1117', card: '#1F2937' }
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Background Default (Light Mode) */
            background: linear-gradient(120deg, #f3f4f6 0%, #e5e7eb 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            transition: background 0.3s;
        }

        /* Override Background untuk Dark Mode */
        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(120deg, #0d1117 0%, #161b22 100%);
            }
        }

        /* CARD STYLE ADAPTIF */
        .glass-card {
            /* Light Mode */
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(12px);
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        @media (prefers-color-scheme: dark) {
            .glass-card {
                /* Dark Mode */
                background: rgba(31, 41, 55, 0.6);
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.4);
            }
        }

        /* INPUT FIELD ADAPTIF */
        .input-adaptive {
            /* Light Mode */
            background: #ffffff;
            border: 1px solid #d1d5db;
            color: #1f2937;
            transition: all 0.3s ease;
        }

        @media (prefers-color-scheme: dark) {
            .input-adaptive {
                /* Dark Mode */
                background: rgba(17, 24, 39, 0.8);
                border: 1px solid rgba(75, 85, 99, 0.5);
                color: #f3f4f6;
            }
        }

        .input-adaptive:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
        }

        .btn-primary {
            background: linear-gradient(to right, #2563eb, #06b6d4);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
    </style>
</head>
<body class="antialiased">

    <div class="glass-card w-full max-w-md p-8 m-4">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">Selamat Datang</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Masuk untuk mengelola Vending Machine</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 dark:bg-red-500/10 dark:border-red-500/50 dark:text-red-400 p-3 rounded-lg mb-6 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 dark:bg-red-500/10 dark:border-red-500/50 dark:text-red-500 p-3 rounded-lg mb-6 text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input type="email" name="email" id="email" required autofocus
                    class="w-full input-adaptive rounded-lg px-4 py-2.5 text-sm placeholder-gray-400 dark:placeholder-gray-500"
                    placeholder="nama@email.com">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full input-adaptive rounded-lg px-4 py-2.5 text-sm placeholder-gray-400 dark:placeholder-gray-500"
                    placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300 cursor-pointer transition-colors">
                    <input type="checkbox" name="remember" class="mr-2 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 bg-white dark:bg-gray-700">
                    Ingat Saya
                </label>
            </div>

            <button type="submit" class="w-full btn-primary text-white font-semibold py-3 px-4 rounded-xl shadow-lg">
                Masuk
            </button>
        </form>
    </div>

</body>
</html>
