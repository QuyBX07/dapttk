@extends('welcome')
@section('styles')
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            /* giữ border-radius của css cũ */
        }

        .dashboard-header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-icon {
            font-size: 1.5rem;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .legend-custom {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .legend-color {
            width: 20px;
            height: 12px;
            border-radius: 4px;
        }

        .revenue-color {
            background: linear-gradient(135deg, #64B5F6, #2196F3);
        }

        .import-color {
            background: linear-gradient(135deg, #F48FB1, #E91E63);
        }

        .year-selector {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .btn-year {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-year:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-year.active {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            box-shadow: 0 4px 15px rgba(33, 150, 243, 0.4);
        }

        /* === Phần biểu đồ: CSS mới thay thế === */

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .stats-card {
            background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
            color: white;
        }

        .stats-card .card-body {
            padding: 1.5rem;
        }

        .stats-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        .chart-container {
            position: relative;
            height: 400px;
            padding: 20px;
        }

        .chart-section {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .pie-chart-container,
        .line-chart-container {
            position: relative;
            height: 350px;
        }

        .daily-stats {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            color: white;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .product-legend {
            max-height: 300px;
            overflow-y: auto;
        }

        .legend-item {
            display: flex;
            align-items: center;
            padding: 0.3rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 3px;
            margin-right: 10px;
        }

        .summary-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }

        .table th {
            background-color: #6c757d;
            color: white;
            border: none;
        }

        .profit-positive {
            color: #28a745;
            font-weight: bold;
        }

        .profit-negative {
            color: #dc3545;
            font-weight: bold;
        }

        .month-detail-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .month-detail-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .detail-modal .modal-dialog {
            max-width: 90%;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="dashboard-header">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1 class="mb-0"><i class="fas fa-chart-bar me-3"></i>Báo Cáo Thống Kê Kinh Doanh</h1>
                                <p class="mb-0 mt-2">Báo cáo doanh thu và nhập hàng năm <span
                                        id="selectedYearText">2024</span></p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="d-flex align-items-center justify-content-end gap-3">
                                    <label for="yearSelect" class=" mb-0">Chọn năm: </label>
                                    <select id="yearSelect" class="form-select" style="width: auto;">
                                        <option value="2021">2021</option>
                                        <option value="2022">2022</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025" selected>2025</option>
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main content -->
                <div class="container">
                    <!-- Thống kê tổng quan -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card stats-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-coins stats-icon mb-2"></i>
                                    <h5 class="card-title">Tổng Doanh Thu</h5>
                                    <h3 id="totalRevenue" class="mb-0">0 VNĐ</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stats-card"
                                style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <div class="card-body text-center">
                                    <i class="fas fa-truck stats-icon mb-2"></i>
                                    <h5 class="card-title">Tổng Nhập Hàng</h5>
                                    <h3 id="totalPurchase" class="mb-0">0 VNĐ</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stats-card"
                                style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-line stats-icon mb-2"></i>
                                    <h5 class="card-title">Lợi Nhuận</h5>
                                    <h3 id="totalProfit" class="mb-0">0 VNĐ</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stats-card"
                                style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                <div class="card-body text-center">
                                    <i class="fas fa-percentage stats-icon mb-2"></i>
                                    <h5 class="card-title">Tỷ Suất LN</h5>
                                    <h3 id="profitMargin" class="mb-0">0%</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Biểu đồ cột chồng -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-chart-column me-2"></i>Biểu Đồ So Sánh Doanh Thu và
                                        Nhập
                                        Hàng Theo Tháng</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="stackedChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng chi tiết -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card summary-table">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>Bảng Chi Tiết Theo Tháng</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Tháng</th>
                                                    <th>Doanh Thu (VNĐ)</th>
                                                    <th>Nhập Hàng (VNĐ)</th>
                                                    <th>Lợi Nhuận (VNĐ)</th>
                                                    <th>Tỷ Suất LN (%)</th>
                                                    <th>Chi Tiết</th>
                                                </tr>
                                            </thead>
                                            <tbody id="summaryTableBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let chartInstance = null;

        function renderDashboard(data) {
            if (chartInstance) {
                chartInstance.destroy();
                chartInstance = null;
            }

            const {
                year,
                totalRevenue,
                totalImportCost,
                monthlyRevenue,
                monthlyImport
            } = data;

            document.getElementById("selectedYearText").textContent = year;
            document.getElementById("totalRevenue").textContent = formatCurrency(totalRevenue);
            document.getElementById("totalPurchase").textContent = formatCurrency(totalImportCost);

            const profit = totalRevenue - totalImportCost;
            document.getElementById("totalProfit").textContent = formatCurrency(profit);
            const margin = totalRevenue === 0 ? 0 : ((profit / totalRevenue) * 100).toFixed(2);
            document.getElementById("profitMargin").textContent = margin + "%";

            const months = Array.from({
                length: 12
            }, (_, i) => `Tháng ${i + 1}`);
            const revenueData = months.map((_, i) => parseFloat(monthlyRevenue[i + 1] ?? 0));
            const importData = months.map((_, i) => parseFloat(monthlyImport[i + 1] ?? 0));

            const ctx = document.getElementById('stackedChart').getContext('2d');
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                            label: 'Doanh Thu',
                            data: revenueData,
                            backgroundColor: '#36d1dc'
                        },
                        {
                            label: 'Nhập Hàng',
                            data: importData,
                            backgroundColor: '#f093fb'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: `So Sánh Doanh Thu và Nhập Hàng Năm ${year}`
                        }
                    },
                    scales: {
                        x: {
                            stacked: false
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Bảng chi tiết
            const tbody = document.getElementById('summaryTableBody');
            tbody.innerHTML = "";
            for (let i = 1; i <= 12; i++) {
                const r = parseFloat(monthlyRevenue[i] ?? 0);
                const im = parseFloat(monthlyImport[i] ?? 0);
                const p = r - im;
                const m = r === 0 ? 0 : ((p / r) * 100).toFixed(2);
                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td>Tháng ${i}</td>
            <td>${formatCurrency(r)}</td>
            <td>${formatCurrency(im)}</td>
            <td>${formatCurrency(p)}</td>
            <td>${m}%</td>
            <td><button onclick="alert('Chi tiết tháng ${i}')">Chi tiết</button></td>
        `;
                tbody.appendChild(tr);
            }
        }

        document.getElementById('yearSelect').addEventListener('change', function() {
            const selectedYear = this.value;
            document.getElementById("selectedYearText").textContent = selectedYear;

            axios.get('/statistics/data', {
                    params: {
                        year: selectedYear
                    }
                })
                .then(response => {
                    console.log(response.data);
                    renderDashboard(response.data);
                })
                .catch(error => {
                    console.error('Lỗi lấy dữ liệu thống kê:', error);
                });
        });

        // Khởi tạo lần đầu khi DOM load
        document.addEventListener('DOMContentLoaded', () => {
            renderDashboard({
                year,
                totalRevenue,
                totalImportCost,
                monthlyRevenue,
                monthlyImport
            });
        });


        function formatCurrency(value) {
            if (value == null || isNaN(value)) {
                return "0 VNĐ";
            }

            return Number(value).toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            });
        }
        const year = @json($year);
        const month = @json($month);
        const totalRevenue = parseFloat(@json($totalRevenue));
        const totalImportCost = parseFloat(@json($totalImportCost));
        const monthlyRevenue = @json($monthlyRevenue);
        const monthlyImport = @json($monthlyImport);

        // Hàm format tiền (VNĐ)
        function formatCurrency(value) {
            return value.toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            });
        }

        // Cập nhật các ô thông tin tổng quan
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById("totalRevenue").textContent = formatCurrency(totalRevenue);
            document.getElementById("totalPurchase").textContent = formatCurrency(totalImportCost);

            const totalProfit = totalRevenue - totalImportCost;
            document.getElementById("totalProfit").textContent = formatCurrency(totalProfit);

            const profitMargin = totalRevenue === 0 ? 0 : ((totalProfit / totalRevenue) * 100).toFixed(2);
            document.getElementById("profitMargin").textContent = profitMargin + "%";

            // Tạo biểu đồ cột kép
            const ctx = document.getElementById('stackedChart').getContext('2d');

            const months = Array.from({
                length: 12
            }, (_, i) => `Tháng ${i + 1}`);

            const revenueData = months.map((_, i) => parseFloat(monthlyRevenue[i + 1] ?? 0));
            const importData = months.map((_, i) => parseFloat(monthlyImport[i + 1] ?? 0));

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                            label: 'Doanh Thu',
                            data: revenueData,
                            backgroundColor: '#36d1dc'
                        },
                        {
                            label: 'Nhập Hàng',
                            data: importData,
                            backgroundColor: '#f093fb'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: `So Sánh Doanh Thu và Nhập Hàng Năm ${year}`
                        }
                    },
                    scales: {
                        x: {
                            stacked: false, // quan trọng, để không chồng cột
                        },
                        y: {
                            stacked: false,
                            beginAtZero: true
                        }
                    }
                }
            });


            // Tạo bảng chi tiết theo tháng
            const tbody = document.getElementById('summaryTableBody');
            tbody.innerHTML = "";

            for (let i = 1; i <= 12; i++) {
                const revenue = parseFloat(monthlyRevenue[i] ?? 0);
                const importCost = parseFloat(monthlyImport[i] ?? 0);
                const profit = revenue - importCost;
                const margin = revenue === 0 ? 0 : ((profit / revenue) * 100).toFixed(2);

                const tr = document.createElement('tr');
                tr.innerHTML = `
                <td>Tháng ${i}</td>
                <td>${formatCurrency(revenue)}</td>
                <td>${formatCurrency(importCost)}</td>
                <td>${formatCurrency(profit)}</td>
                <td>${margin}%</td>
                <td><button class="month-detail-btn" onclick="alert('Xem chi tiết tháng ${i}')">Chi tiết</button></td>
            `;
                tbody.appendChild(tr);
            }


            // tạo theo năm


        });
    </script>
@endsection
