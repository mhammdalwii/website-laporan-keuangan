<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - Vending Machine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0d1117;
        }
        .report-card {
            background-color: #1F2937;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            border-color: rgba(56, 189, 248, 0.5);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        .tab-button {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid transparent;
            background-color: transparent;
            color: #9CA3AF;
            transition: all 0.2s ease;
        }
        .tab-button.active {
            background-color: #38bdf8;
            color: #ffffff;
            font-weight: 600;
        }
        .tab-button:not(.active):hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="text-gray-200">

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        <!-- Header -->
        <header class="mb-8 flex justify-between items-center fade-in">
            <a href="/" class="bg-gray-700 hover:bg-gray-600 text-white text-sm font-bold py-2 px-4 rounded-md transition duration-300 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dasbor
            </a>
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white">Laporan Pendapatan</h1>
                <p class="text-gray-400 mt-2">Analisis pendapatan harian, mingguan, dan bulanan.</p>
            </div>
            <div class="w-40"></div> <!-- Placeholder to balance the header -->
        </header>

        <!-- Main Content -->
        <main>
            <!-- Kartu Metrik Pendapatan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="report-card fade-in" style="animation-delay: 200ms;">
                    <h3 class="text-lg font-semibold text-sky-400">Pendapatan Hari Ini</h3>
                    <p id="pendapatan-harian" class="mt-2 text-4xl font-bold text-white">Rp 0</p>
                    <p class="text-gray-500 text-sm mt-1" id="tanggal-hari-ini"></p>
                </div>
                <div class="report-card fade-in" style="animation-delay: 300ms;">
                    <h3 class="text-lg font-semibold text-green-400">Pendapatan Minggu Ini</h3>
                    <p id="pendapatan-mingguan" class="mt-2 text-4xl font-bold text-white">Rp 0</p>
                    <p class="text-gray-500 text-sm mt-1">(7 hari terakhir)</p>
                </div>
                <div class="report-card fade-in" style="animation-delay: 400ms;">
                    <h3 class="text-lg font-semibold text-amber-400">Pendapatan Bulan Ini</h3>
                    <p id="pendapatan-bulanan" class="mt-2 text-4xl font-bold text-white">Rp 0</p>
                    <p class="text-gray-500 text-sm mt-1" id="nama-bulan-ini"></p>
                </div>
            </div>

            <!-- Grafik Pendapatan -->
            <div class="report-card fade-in" style="animation-delay: 500ms;">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="chart-title" class="text-xl font-semibold text-white">Grafik Pendapatan</h3>
                    <div id="chart-options" class="flex space-x-2 bg-gray-800 p-1 rounded-lg">
                        <button class="tab-button active" data-period="daily">Harian</button>
                        <button class="tab-button" data-period="weekly">Mingguan</button>
                        <button class="tab-button" data-period="monthly">Bulanan</button>
                    </div>
                </div>
                <div class="h-80 relative">
                    <canvas id="revenueChart"></canvas>
                    <div id="empty-chart-message" class="absolute inset-0 flex items-center justify-center text-gray-500 text-lg hidden">
                        Belum ada data untuk ditampilkan.
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- KONEKSI KE LOCALSTORAGE ---
            const STORAGE_KEY = 'vendingMachineTransactions';

            // Fungsi untuk memuat data dari localStorage
            const loadTransactions = () => {
                const saved = localStorage.getItem(STORAGE_KEY);
                // Jika tidak ada data, kembalikan array kosong
                return saved ? JSON.parse(saved) : [];
            };

            let mockTransactions = loadTransactions();

            // --- FUNGSI UTILITAS ---
            const formatCurrency = (amount) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
            const getISODate = (date) => date.toISOString().split('T')[0];

            // --- FUNGSI KALKULASI & RENDER KARTU METRIK ---
            function calculateAndRenderMetrics() {
                const now = new Date();
                const todayStr = getISODate(now);
                const oneWeekAgo = new Date(now);
                oneWeekAgo.setDate(now.getDate() - 7);
                const firstDayOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);

                let dailyRevenue = 0;
                let weeklyRevenue = 0;
                let monthlyRevenue = 0;

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
                if (revenueChartInstance) {
                    revenueChartInstance.destroy();
                }

                // Tampilkan pesan jika tidak ada data
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
                            y: { beginAtZero: true, ticks: { color: '#9CA3AF', callback: (value) => formatCurrency(value) }, grid: { color: 'rgba(255, 255, 255, 0.1)' } },
                            x: { ticks: { color: '#9CA3AF' }, grid: { display: false } }
                        },
                        plugins: { legend: { display: false }, tooltip: { callbacks: { label: (context) => `Pendapatan: ${formatCurrency(context.raw)}` } } }
                    }
                });
            }

            function updateChart(period) {
                const chartTitle = document.getElementById('chart-title');
                let chartData = { labels: [], data: [] };
                const now = new Date();

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
                    const weeklyMap = new Map();
                    weeklyMap.set('3 Minggu Lalu', 0);
                    weeklyMap.set('2 Minggu Lalu', 0);
                    weeklyMap.set('1 Minggu Lalu', 0);
                    weeklyMap.set('Minggu Ini', 0);

                    mockTransactions.forEach(t => {
                        const transactionDate = new Date(t.created_at);
                        const diffDays = Math.floor((now - transactionDate) / (1000 * 60 * 60 * 24));
                        if (diffDays < 7) weeklyMap.set('Minggu Ini', weeklyMap.get('Minggu Ini') + t.total_price);
                        else if (diffDays < 14) weeklyMap.set('1 Minggu Lalu', weeklyMap.get('1 Minggu Lalu') + t.total_price);
                        else if (diffDays < 21) weeklyMap.set('2 Minggu Lalu', weeklyMap.get('2 Minggu Lalu') + t.total_price);
                        else if (diffDays < 28) weeklyMap.set('3 Minggu Lalu', weeklyMap.get('3 Minggu Lalu') + t.total_price);
                    });
                    chartData.labels = Array.from(weeklyMap.keys());
                    chartData.data = Array.from(weeklyMap.values());
                } else if (period === 'monthly') {
                    chartTitle.textContent = 'Grafik Pendapatan Bulanan (6 Bulan Terakhir)';
                    const monthlyMap = new Map();
                    for (let i = 5; i >= 0; i--) {
                        const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
                        monthlyMap.set(d.toLocaleDateString('id-ID', { month: 'short' }), 0);
                    }
                    mockTransactions.forEach(t => {
                        const transactionDate = new Date(t.created_at);
                        if (transactionDate >= new Date(now.getFullYear(), now.getMonth() - 5, 1)) {
                            const key = transactionDate.toLocaleDateString('id-ID', { month: 'short' });
                            monthlyMap.set(key, (monthlyMap.get(key) || 0) + t.total_price);
                        }
                    });
                    chartData.labels = Array.from(monthlyMap.keys());
                    chartData.data = Array.from(monthlyMap.values());
                }

                renderChart(chartData);
            }

            // --- INISIALISASI & EVENT LISTENERS ---
            calculateAndRenderMetrics();
            updateChart('daily'); // Tampilkan grafik harian saat pertama kali dimuat

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
