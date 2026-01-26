<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Keuangan - Vending Machine</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'media', // 'media' = Otomatis ikut pengaturan HP/Laptop
            theme: {
                extend: {
                    colors: {
                        // Kita simpan warna gelap asli Anda di sini
                        dark: {
                            bg: '#0d1117',
                            card: 'rgba(31, 41, 55, 0.5)',
                            border: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 288px;
        }

        body {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s, color 0.3s; /* Animasi halus saat ganti tema */
        }

        .sidebar-custom {
            width: var(--sidebar-width);
        }

        .content-custom {
            margin-left: var(--sidebar-width);
        }

        /* 2. UPDATE STYLE KARTU AGAR MENDUKUNG 2 TEMA */
        .metric-card,
        .chart-container,
        .table-container {
            /* Default (Light) */
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(0, 0, 0, 0.1);
            color: #1f2937;

            border-radius: 0.75rem;
            padding: 1.5rem;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        /* Dark Mode Override (Lewat Media Query CSS) */
        @media (prefers-color-scheme: dark) {
            .metric-card,
            .chart-container,
            .table-container {
                background: rgba(31, 41, 55, 0.5); /* Warna asli Anda */
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: #e5e7eb;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            }
        }

        .metric-card:hover {
            transform: translateY(-5px) scale(1.02);
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in { animation: fadeIn 0.5s ease-out forwards; }

        .sidebar { transition: transform 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-content-expanded { margin-left: 0 !important; }

        /* Custom Scrollbar (Adaptive) */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @media (prefers-color-scheme: dark) {
            ::-webkit-scrollbar-track { background: #1f2937; }
            ::-webkit-scrollbar-thumb { background: #4b5563; }
            ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-[#0d1117] text-gray-800 dark:text-gray-200">

    <div class="flex">
        <aside id="sidebar"
            class="sidebar-custom bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-4 space-y-4 fixed top-0 left-0 h-full z-40 sidebar border-r border-gray-200 dark:border-gray-700/50 flex-shrink-0 shadow-lg dark:shadow-none">

            <h2 class="text-gray-800 dark:text-white text-xl font-bold px-4">Menu</h2>

            <nav class="space-y-2">
                <a href="/laporan"
                    class="flex items-center px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-white rounded-md transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17v-2a4 4 0 00-4-4h-2a4 4 0 00-4 4v2"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 004-4h2a4 4 0 004 4v2"></path>
                    </svg>
                    Laporan
                </a>
                <a href="/input_data"
                    class="flex items-center px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-white rounded-md transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Input Data
                </a>
                <a href="#" id="view-all-btn"
                    class="w-full flex items-center px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-white rounded-md transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                    Semua
                </a>
                <a href="#" id="clear-history-btn"
                    class="w-full flex items-center px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-white rounded-md transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Bersihkan
                </a>
                <a href="#" id="export-excel-btn"
                    class="w-full flex items-center px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-white rounded-md transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-blue-500/20 hover:text-red-600 dark:hover:text-white rounded-md transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Log Out
                    </button>
                </form>
            </nav>
        </aside>

        <div id="content-wrapper" class="flex-1 content-custom transition-all duration-300 ease-in-out">
            <header class="bg-white/80 dark:bg-gray-800/50 backdrop-blur-sm shadow-sm dark:shadow-md p-4 flex items-center sticky top-0 z-30 border-b border-gray-200 dark:border-transparent">
                <button id="sidebar-toggle" class="text-gray-600 dark:text-white focus:outline-none mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">Dashboard</h1>
            </header>

            <div class="flex-1">
                <div class="mx-auto p-4 sm:p-6 lg:p-8">
                    <main class="flex flex-col gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                            <div class="metric-card fade-in" style="animation-delay: 300ms;">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pendapatan</h3>
                                    <span class="p-2 bg-green-100 dark:bg-green-500/10 rounded-full">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01"></path>
                                        </svg>
                                    </span>
                                </div>
                                <p id="metric-revenue" class="mt-2 text-3xl font-bold text-gray-800 dark:text-white font-semibold">Rp 0</p>
                            </div>

                            <div class="metric-card fade-in" style="animation-delay: 400ms;">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Laba Kotor</h3>
                                    <span class="p-2 bg-sky-100 dark:bg-sky-500/10 rounded-full">
                                        <svg class="w-6 h-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </span>
                                </div>
                                <p id="metric-profit" class="mt-2 text-3xl font-bold text-gray-800 dark:text-white font-semibold">Rp 0</p>
                            </div>

                            <div class="metric-card fade-in" style="animation-delay: 500ms;">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Unit Terjual</h3>
                                    <span class="p-2 bg-amber-100 dark:bg-amber-500/10 rounded-full">
                                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </span>
                                </div>
                                <p id="metric-transactions" class="mt-2 text-3xl font-bold text-gray-800 dark:text-white font-semibold">0</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                            <div class="chart-container fade-in" style="animation-delay: 600ms;">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Tren Pendapatan (7 Hari Terakhir)</h3>
                                <div class="h-72"><canvas id="revenueChart"></canvas></div>
                            </div>
                            <div class="chart-container fade-in" style="animation-delay: 700ms;">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Produk Terlaris (Unit)</h3>
                                <div class="h-72"><canvas id="productChart"></canvas></div>
                            </div>
                        </div>

                        <div class="table-container fade-in" style="animation-delay: 800ms;">
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-4 gap-4 sm:gap-0">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Riwayat Transaksi Terakhir</h3>
                            </div>
                            <div id="collapsible-table" class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-100 dark:bg-gray-900/50">
                                        <tr>
                                            <th class="px-4 py-3">Waktu</th>
                                            <th class="px-4 py-3">Produk</th>
                                            <th class="px-4 py-3">Jumlah</th>
                                            <th class="px-4 py-3">Harga Satuan</th>
                                            <th class="px-4 py-3">Total Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transactions as $transaction)
                                            <tr class="border-b border-gray-200 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                <td class="px-4 py-3">
                                                    {{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-200">
                                                    {{ $transaction->product->name ?? 'N/A' }}</td>
                                                <td class="px-4 py-3">{{ $transaction->quantity }}</td>
                                                <td class="px-4 py-3">Rp
                                                    {{ number_format($transaction->product->price ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Rp
                                                    {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-6 px-4">Belum ada riwayat
                                                    transaksi.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const contentWrapper = document.getElementById('content-wrapper');
            const sidebarToggle = document.getElementById('sidebar-toggle');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-hidden');
                contentWrapper.classList.toggle('main-content-expanded');
            });
        });
    </script>
</body>

</html>
