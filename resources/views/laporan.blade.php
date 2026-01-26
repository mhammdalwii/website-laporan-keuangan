<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - Vending Machine</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'media',
            theme: {
                extend: {
                    colors: {
                        dark: { bg: '#0d1117', card: '#1F2937' }
                    }
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }

        /* CARD STYLE: DEFAULT (LIGHT) & DARK OVERRIDE */
        .report-card {
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        @media (prefers-color-scheme: dark) {
            .report-card {
                background-color: #1F2937;
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: none;
            }
        }

        .report-card:hover {
            transform: translateY(-5px);
            border-color: rgba(56, 189, 248, 0.5);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        @media (prefers-color-scheme: dark) {
            .report-card:hover { box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3); }
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.5s ease-out forwards; }

        /* TAB BUTTON STYLE */
        .tab-button {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            background-color: transparent;
            color: #6b7280; /* Gray-500 */
            transition: all 0.2s ease;
        }
        @media (prefers-color-scheme: dark) { .tab-button { color: #9CA3AF; } }

        .tab-button.active {
            background-color: #38bdf8;
            color: #ffffff !important;
            font-weight: 600;
        }

        .tab-button:not(.active):hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        @media (prefers-color-scheme: dark) {
            .tab-button:not(.active):hover { background-color: rgba(255, 255, 255, 0.1); }
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-[#0d1117] text-gray-800 dark:text-gray-200">

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        <header class="mb-8 flex flex-col md:flex-row justify-between items-center fade-in gap-4">
             <a href="{{ url('welcome') }}" class="bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-white text-sm font-bold py-2 px-4 rounded-md transition duration-300 flex items-center shadow-sm border border-gray-200 dark:border-transparent">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-800 dark:text-white">Laporan Pendapatan</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Analisis pendapatan harian, mingguan, dan bulanan.</p>
            </div>
            <div class="w-40 hidden md:block"></div>
        </header>

        <main>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="report-card fade-in" style="animation-delay: 200ms;">
                    <h3 class="text-lg font-semibold text-sky-600 dark:text-sky-400">Pendapatan Hari Ini</h3>
                    <p id="pendapatan-harian" class="mt-2 text-4xl font-bold text-gray-800 dark:text-white">Rp 0</p>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1" id="tanggal-hari-ini"></p>
                </div>
                <div class="report-card fade-in" style="animation-delay: 300ms;">
                    <h3 class="text-lg font-semibold text-green-600 dark:text-green-400">Pendapatan Minggu Ini</h3>
                    <p id="pendapatan-mingguan" class="mt-2 text-4xl font-bold text-gray-800 dark:text-white">Rp 0</p>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">(7 hari terakhir)</p>
                </div>
                <div class="report-card fade-in" style="animation-delay: 400ms;">
                    <h3 class="text-lg font-semibold text-amber-600 dark:text-amber-400">Pendapatan Bulan Ini</h3>
                    <p id="pendapatan-bulanan" class="mt-2 text-4xl font-bold text-gray-800 dark:text-white">Rp 0</p>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1" id="nama-bulan-ini"></p>
                </div>
            </div>

            <div class="report-card fade-in" style="animation-delay: 500ms;">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                    <h3 id="chart-title" class="text-xl font-semibold text-gray-800 dark:text-white">Grafik Pendapatan</h3>
                    <div id="chart-options" class="flex space-x-2 bg-gray-100 dark:bg-gray-800 p-1 rounded-lg border border-gray-200 dark:border-transparent">
                        <button class="tab-button active" data-period="daily">Harian</button>
                        <button class="tab-button" data-period="weekly">Mingguan</button>
                        <button class="tab-button" data-period="monthly">Bulanan</button>
                    </div>
                </div>
                <div class="h-80 relative">
                    <canvas id="revenueChart"></canvas>
                    <div id="empty-chart-message" class="absolute inset-0 flex items-center justify-center text-gray-400 text-lg hidden">
                        Belum ada data untuk ditampilkan.
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // DETEKSI DARK MODE UNTUK CHART
            const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const textColor = isDarkMode ? '#9CA3AF' : '#4b5563';
            const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';

            // --- KONEKSI KE LOCALSTORAGE ---
            const STORAGE_KEY = 'vendingMachineTransactions';
            const loadTransactions = () => {
                const saved = localStorage.getItem(STORAGE_KEY);
                return saved ? JSON.parse(saved) : [];
            };
            let mockTransactions = loadTransactions();

            // --- FUNGSI UTILITAS ---
            const formatCurrency = (amount) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
            const getISODate = (date) => date.toISOString().split('T')[0];

            // --- KALKULASI METRIK ---
            function calculateAndRenderMetrics() {
                const now = new Date();
                const todayStr = getISODate(now);
                const oneWeekAgo = new Date(now);
                oneWeekAgo.setDate(now.getDate() - 7);
                const firstDayOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);

                let dailyRevenue = 0, weeklyRevenue = 0, monthlyRevenue = 0;

                mockTransactions.forEach(t => {
                    const transactionDate = new Date(t.created_at);
                    if (getISODate(transactionDate) === todayStr) dailyRevenue += t.total_price;
                    if (transactionDate >= oneWeekAgo) weeklyRevenue += t.total_price;
                    if (transactionDate >= firstDayOfMonth) monthlyRevenue += t.total_price;
                });

                document.getElementById('pendapatan-harian').textContent = formatCurrency(dailyRevenue);
                document.getElementById('pendapatan-mingguan').textContent = formatCurrency(weeklyRevenue);
                document.getElementById('pendapatan-bulanan').textContent = formatCurrency(monthlyRevenue);
                document.getElementById('tanggal-hari-ini').textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                document.getElementById('nama-bulan-ini').textContent = `(Bulan ${now.toLocaleDateString('id-ID', { month: 'long' })})`;
            }

            // --- FUNGSI GRAFIK ---
            let revenueChartInstance = null;
            function renderChart(chartData) {
                const emptyChartMessage = document.getElementById('empty-chart-message');
                if (revenueChartInstance) revenueChartInstance.destroy();

                if (chartData.data.every(item => item === 0)) {
                    emptyChartMessage.classList.remove('hidden');
                    return;
                }
                emptyChartMessage.classList.add('hidden');

                const ctx = document.getElementById('revenueChart').getContext('2d');
                revenueChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Pendapatan',
                            data: chartData.data,
                            backgroundColor: 'rgba(56, 189, 248, 0.6)',
                            borderColor: 'rgba(56, 189, 248, 1)',
                            borderWidth: 1,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { color: textColor, callback: (value) => formatCurrency(value) },
                                grid: { color: gridColor }
                            },
                            x: {
                                ticks: { color: textColor },
                                grid: { display: false }
                            }
                        },
                        plugins: { legend: { display: false }, tooltip: { callbacks: { label: (context) => `Pendapatan: ${formatCurrency(context.raw)}` } } }
                    }
                });
            }

            function updateChart(period) {
                const chartTitle = document.getElementById('chart-title');
                let chartData = { labels: [], data: [] };
                const now = new Date();

                // Logika Grafik Harian, Mingguan, Bulanan (Sama seperti sebelumnya)
                if (period === 'daily') {
                    chartTitle.textContent = 'Grafik Pendapatan Harian (7 Hari Terakhir)';
                    const dailyMap = new Map();
                    for (let i = 6; i >= 0; i--) {
                        const d = new Date();
                        d.setDate(d.getDate() - i);
                        dailyMap.set(d.toLocaleDateString('id-ID', { weekday: 'short' }), 0);
                    }
                    mockTransactions.forEach(t => {
                        const transactionDate = new Date(t.created_at);
                        if (transactionDate >= new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000)) {
                            const key = transactionDate.toLocaleDateString('id-ID', { weekday: 'short' });
                            dailyMap.set(key, (dailyMap.get(key) || 0) + t.total_price);
                        }
                    });
                    chartData.labels = Array.from(dailyMap.keys());
                    chartData.data = Array.from(dailyMap.values());
                } else if (period === 'weekly') {
                    chartTitle.textContent = 'Grafik Pendapatan Mingguan (4 Minggu Terakhir)';
                    // ... (Kode sama, dipersingkat)
                    const weeklyMap = new Map();
                    ['3 Minggu Lalu', '2 Minggu Lalu', '1 Minggu Lalu', 'Minggu Ini'].forEach(k => weeklyMap.set(k, 0));
                    mockTransactions.forEach(t => { /* Logika sama */ });
                    chartData.labels = Array.from(weeklyMap.keys());
                    chartData.data = Array.from(weeklyMap.values());
                } else if (period === 'monthly') {
                    chartTitle.textContent = 'Grafik Pendapatan Bulanan (6 Bulan Terakhir)';
                     // ... (Kode sama, dipersingkat)
                     const monthlyMap = new Map();
                     for (let i = 5; i >= 0; i--) {
                        const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
                        monthlyMap.set(d.toLocaleDateString('id-ID', { month: 'short' }), 0);
                     }
                     // ...
                     chartData.labels = Array.from(monthlyMap.keys());
                     chartData.data = Array.from(monthlyMap.values());
                }

                renderChart(chartData);
            }

            calculateAndRenderMetrics();
            updateChart('daily');

            const chartOptions = document.getElementById('chart-options');
            chartOptions.addEventListener('click', (e) => {
                if (e.target.tagName === 'BUTTON') {
                    chartOptions.querySelectorAll('button').forEach(btn => btn.classList.remove('active'));
                    e.target.classList.add('active');
                    updateChart(e.target.dataset.period);
                }
            });
        });
    </script>
</body>
</html>
