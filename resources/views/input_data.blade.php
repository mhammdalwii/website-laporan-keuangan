<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Pengeluaran - Vending Machine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(120deg, #0d1117 0%, #161b22 100%);
        }
        .glass-container {
            background: rgba(31, 41, 55, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        .input-glass {
            background: rgba(17, 24, 39, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            color: #f3f4f6;
            transition: all 0.3s ease;
        }
        .input-glass:focus {
            outline: none;
            border-color: rgba(96, 165, 250, 0.8);
            box-shadow: 0 0 0 2px rgba(96, 165, 250, 0.5);
        }
        .btn-glow {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-glow:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.5s ease;
        }
        .btn-glow:hover:before {
            transform: translate(-50%, -50%) scale(1);
        }
        .btn-glow:hover {
            box-shadow: 0 0 15px 5px rgba(96, 165, 250, 0.3);
        }
        .notification-popup {
            animation: slide-in-out 5s forwards;
        }

        @keyframes slide-in-out {
            0% {
                transform: translateY(100%);
                opacity: 0;
            }
            10% {
                transform: translateY(0);
                opacity: 1;
            }
            90% {
                transform: translateY(0);
                opacity: 1;
            }
            100% {
                transform: translateY(100%);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="text-gray-200">

    @if(session('success'))
    <div id="notification-popup" class="notification-popup fixed bottom-5 left-1/2 -translate-x-1/2 w-auto max-w-sm z-50">
        <div class="glass-container !p-4 flex items-center gap-4">
            <i class="fas fa-check-circle text-green-400 text-2xl"></i>
            <p class="text-green-300">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="mx-auto p-4 sm:p-6 lg:p-8 max-w-4xl">
        <header class="mb-10 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-white tracking-tight">Manajemen Keuangan</h1>
            <p class="text-gray-400 mt-3 max-w-xl mx-auto">Catat pengeluaran bahan baku dengan mudah.</p>
            <a href="{{ url('/') }}" class="mt-6 inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2 px-5 rounded-lg transition-transform transform hover:scale-105 duration-300 btn-glow">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </header>

        <div class="max-w-2xl mx-auto">
            <main class="glass-container mb-12">
                <h2 class="text-2xl font-bold text-white mb-6 text-center"><i class="fas fa-shopping-cart mr-2"></i> Input Pengeluaran</h2>
                <form action="{{ route('transactions.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- BAGIAN SELECT OPTION SUDAH DIHAPUS DI SINI --}}

                    <div class="relative">
                        <i class="fas fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="name" name="name" placeholder="Nama Item / Bahan Baku" required class="w-full input-glass pl-10">
                    </div>
                    <div class="relative">
                         <i class="fas fa-dollar-sign absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="number" id="total_price" name="total_price" placeholder="Total Biaya (Rp)" required min="0" class="w-full input-glass pl-10">
                    </div>
                    <button type="submit" class="w-full btn-glow bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-600 hover:to-cyan-600 text-white font-bold py-3 px-4 rounded-lg">
                        Simpan Pengeluaran
                    </button>
                </form>
                 @if ($errors->any() && $errors->hasBag('default'))
                    <div class="mt-4 bg-red-900/50 border border-red-500 text-red-300 px-4 py-3 rounded-md">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </main>
        </div>

        <section class="glass-container">
            <h2 class="text-2xl font-bold text-white mb-6">Riwayat Pengeluaran</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-800/50">
                        <tr>
                            <th scope="col" class="py-3.5 px-4 text-left text-sm font-semibold text-gray-300">Item / Bahan</th>
                            <th scope="col" class="py-3.5 px-4 text-left text-sm font-semibold text-gray-300 hidden sm:table-cell">Total Biaya</th>
                            <th scope="col" class="py-3.5 px-4 text-left text-sm font-semibold text-gray-300">Tanggal</th>
                            <th scope="col" class="py-3.5 px-4 text-left text-sm font-semibold text-gray-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-800/40 transition-colors duration-200">
                                {{-- Pastikan Controller mengirimkan nama produk, jika tidak ada relasi gunakan fallback --}}
                                <td class="whitespace-nowrap py-4 px-4 text-sm text-gray-300">{{ $transaction->product->name ?? $transaction->name ?? 'N/A' }}</td>
                                <td class="whitespace-nowrap py-4 px-4 text-sm text-gray-300 hidden sm:table-cell">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap py-4 px-4 text-sm text-gray-400">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                <td class="whitespace-nowrap py-4 px-4 text-sm">
                                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors duration-200">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 px-4 text-sm text-center text-gray-500">
                                    <i class="fas fa-box-open fa-2x mb-2"></i><br>
                                    Belum ada pengeluaran tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</body>
</html>
