<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Pengeluaran - Vending Machine</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'media', // Otomatis ikut settingan HP/Laptop
            theme: {
                extend: {
                    colors: {
                        dark: {
                            bg: '#0d1117',
                            card: '#1F2937'
                        }
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Background Default (Light Mode) */
            background: linear-gradient(120deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #1f2937;
            transition: background 0.3s, color 0.3s;
        }

        /* Override Background untuk Dark Mode */
        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(120deg, #0d1117 0%, #161b22 100%);
                color: #e5e7eb;
            }
        }

        /* GLASS CONTAINER ADAPTIF */
        .glass-container {
            /* Light Mode Style */
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        @media (prefers-color-scheme: dark) {
            .glass-container {
                /* Dark Mode Style */
                background: rgba(31, 41, 55, 0.5);
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            }
        }

        /* INPUT FIELD ADAPTIF */
        .input-glass {
            background: #ffffff;
            border: 1px solid #d1d5db;
            color: #1f2937;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        @media (prefers-color-scheme: dark) {
            .input-glass {
                background: rgba(17, 24, 39, 0.6);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: #f3f4f6;
            }
        }

        .input-glass:focus {
            outline: none;
            border-color: rgba(96, 165, 250, 0.8);
            box-shadow: 0 0 0 2px rgba(96, 165, 250, 0.5);
        }

        /* BUTTON GLOW EFFECT */
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
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
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
@php
    use Illuminate\Support\Facades\Storage;
@endphp

<body class="antialiased">

    @if (session('success'))
        <div id="notification-popup"
            class="notification-popup fixed bottom-5 left-1/2 -translate-x-1/2 w-auto max-w-sm z-50">
            <div
                class="glass-container !p-4 flex items-center gap-4 border-l-4 border-green-500 bg-white dark:bg-gray-800">
                <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                <p class="text-green-700 dark:text-green-300 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="mx-auto p-4 sm:p-6 lg:p-8 max-w-4xl">
        <header class="mb-10 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 dark:text-white tracking-tight">Manajemen Keuangan
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-3 max-w-xl mx-auto">Catat pengeluaran bahan baku dengan mudah.
            </p>
            <a href="{{ route('welcome') }}"
                class="mt-6 inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2 px-5 rounded-lg transition-transform transform hover:scale-105 duration-300 btn-glow shadow-md">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </header>

        <div class="max-w-2xl mx-auto">
            <main class="glass-container mb-12">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 text-center"><i
                        class="fas fa-shopping-cart mr-2 text-sky-500"></i> Input Pengeluaran</h2>

                <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <div class="relative">
                        <label for="name" class="sr-only">Nama Item</label>
                        <input type="text" id="name" name="name" placeholder="Nama Item / Bahan Baku"
                            required class="w-full input-glass pl-4 placeholder-gray-400 dark:placeholder-gray-500">
                    </div>

                    <div class="relative">
                        <label for="total_price" class="sr-only">Total Biaya</label>
                        <input type="number" id="total_price" name="total_price" placeholder="Total Biaya (Rp)"
                            required min="0"
                            class="w-full input-glass pl-4 placeholder-gray-400 dark:placeholder-gray-500">
                    </div>

                    <div>
                        <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium text-sm">Upload Nota
                            Pembelian</label>
                        <input type="file" name="image"
                            class="block w-full text-sm text-gray-500 dark:text-gray-300
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-sky-100 file:text-sky-700
                            dark:file:bg-sky-600 dark:file:text-white
                            hover:file:bg-sky-200 dark:hover:file:bg-sky-700
                            cursor-pointer transition-colors">
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full btn-glow bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-600 hover:to-cyan-600 text-white font-bold py-3 px-4 rounded-lg shadow-lg transform active:scale-95 transition-transform">
                        Simpan Pengeluaran
                    </button>
                </form>

                @if ($errors->any() && $errors->hasBag('default'))
                    <div
                        class="mt-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-500 text-red-600 dark:text-red-300 px-4 py-3 rounded-md text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </main>
        </div>

        <section class="glass-container">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Riwayat Pengeluaran</h2>
                <span
                    class="px-3 py-1 text-xs font-semibold bg-sky-100 text-sky-700 dark:bg-sky-900 dark:text-sky-300 rounded-full">Terbaru</span>
            </div>

            <div class="overflow-x-auto rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800/50">
                        <tr>
                            <th scope="col"
                                class="py-3.5 px-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Item / Bahan</th>
                            <th scope="col"
                                class="py-3.5 px-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">
                                Biaya</th>
                            <th scope="col"
                                class="py-3.5 px-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Bukti</th>
                            <th scope="col"
                                class="py-3.5 px-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tanggal</th>
                            <th scope="col"
                                class="py-3.5 px-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors duration-200">
                                <td
                                    class="whitespace-nowrap py-4 px-4 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $transaction->product->name ?? ($transaction->name ?? 'N/A') }}
                                </td>
                                <td
                                    class="whitespace-nowrap py-4 px-4 text-sm text-gray-700 dark:text-gray-300 hidden sm:table-cell font-mono">
                                    Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap py-4 px-4">
                                    @if ($transaction->image && file_exists(public_path($transaction->image)))
                                        <a href="{{ asset($transaction->image) }}" target="_blank">
                                            <img src="{{ asset($transaction->image) }}"
                                                class="w-10 h-10 object-cover rounded border">
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap py-4 px-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $transaction->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="whitespace-nowrap py-4 px-4 text-sm">
                                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST"
                                        onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200 p-1 rounded hover:bg-red-50 dark:hover:bg-red-900/20">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="py-10 px-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded-full mb-3">
                                            <i class="fas fa-receipt text-gray-400 text-xl"></i>
                                        </div>
                                        <p>Belum ada pengeluaran tercatat.</p>
                                    </div>
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
