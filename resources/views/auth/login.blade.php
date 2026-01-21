<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vending Machine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(120deg, #0d1117 0%, #161b22 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .glass-card {
            background: rgba(31, 41, 55, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.4);
        }
        .input-dark {
            background: rgba(17, 24, 39, 0.8);
            border: 1px solid rgba(75, 85, 99, 0.5);
            color: #f3f4f6;
            transition: all 0.3s ease;
        }
        .input-dark:focus {
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
        .btn-google {
            transition: all 0.3s ease;
        }
        .btn-google:hover {
            transform: translateY(-1px);
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="text-gray-200">

    <div class="glass-card w-full max-w-md p-8 m-4">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Selamat Datang</h1>
            <p class="text-gray-400 text-sm">Masuk untuk mengelola Vending Machine</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-3 rounded-lg mb-6 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/50 text-red-500 p-3 rounded-lg mb-6 text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                <input type="email" name="email" id="email" required autofocus
                    class="w-full input-dark rounded-lg px-4 py-2.5 text-sm placeholder-gray-500"
                    placeholder="nama@email.com">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full input-dark rounded-lg px-4 py-2.5 text-sm placeholder-gray-500"
                    placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-gray-400 hover:text-gray-300 cursor-pointer">
                    <input type="checkbox" name="remember" class="mr-2 rounded bg-gray-700 border-gray-600 text-blue-500 focus:ring-blue-500">
                    Ingat Saya
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full btn-primary text-white font-semibold py-3 px-4 rounded-xl shadow-lg">
                Masuk
            </button>
        </form>
</body>
</html>
