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
        <!-- Modal Chi Tiết Tháng -->
        <div class="modal fade detail-modal" id="monthDetailModal" tabindex="-1" aria-labelledby="monthDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="monthDetailModalLabel">
                        <i class="fas fa-calendar-alt me-2"></i>Chi Tiết Báo Cáo Tháng
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Thống kê tháng -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="daily-stats">
                                <h6>Doanh Thu Tháng</h6>
                                <h4 id="monthRevenue"> VNĐ</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="daily-stats"
                                style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                                <h6>Chi Phí Nhập Hàng</h6>
                                <h4 id="monthPurchase"> VNĐ</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="daily-stats"
                                style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                <h6>Lợi Nhuận</h6>
                                <h4 id="monthProfit"> VNĐ</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="daily-stats"
                                style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                                <h6>Tỷ Suất Lợi Nhuận</h6>
                                <h4 id="businessDays"></h4>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <!-- Biểu đồ tròn - Mặt hàng bán -->
                        <div class="col-md-6">
                            <div class="chart-section">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-chart-pie me-2"></i>Cơ Cấu Doanh Thu Theo Mặt Hàng
                                </h6>
                                <div class="pie-chart-container">
                                    <canvas id="salesPieChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Biểu đồ tròn - Mặt hàng nhập -->
                        <div class="col-md-6">
                            <div class="chart-section">
                                <h6 class="text-success mb-3">
                                    <i class="fas fa-chart-pie me-2"></i>Cơ Cấu Chi Phí Nhập Hàng
                                </h6>
                                <div class="pie-chart-container">
                                    <canvas id="purchasePieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Biểu đồ đường - Doanh thu theo ngày -->
                    <div class="row">
                        <div class="col-12">
                            <div class="chart-section">
                                <h6 class="text-info mb-3">
                                    <i class="fas fa-chart-line me-2"></i>Doanh Thu Theo Từng Ngày Trong Tháng
                                </h6>
                                <div class="line-chart-container">
                                    <canvas id="dailyRevenueChart"></canvas>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
                <td>
                    <button class="btn btn-sm btn-info month-detail-btn" onclick="showMonthDetail(${i})">
                        <i class="fas fa-eye me-1"></i> Xem
                    </button>
                </td>
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
        });


        // Biến toàn cục để giữ biểu đồ nếu cần sau này
let salesPieChart = null;

// Hàm hiển thị chi tiết tháng
function showMonthDetail(monthIndex) {
    const revenue = parseFloat(monthlyRevenue[monthIndex] ?? 0);
    const importCost = parseFloat(monthlyImport[monthIndex] ?? 0);
    const profit = revenue - importCost;
    const margin = revenue === 0 ? 0 : ((profit / revenue) * 100).toFixed(2);

    document.getElementById('monthRevenue').textContent = formatCurrency(revenue);
    document.getElementById('monthPurchase').textContent = formatCurrency(importCost);
    document.getElementById('monthProfit').textContent = formatCurrency(profit);
    document.getElementById('businessDays').textContent = `${margin}%`;

    document.getElementById(
          "monthDetailModalLabel"
        ).innerHTML = `<i class="fas fa-calendar-alt me-2"></i>Chi Tiết Báo Cáo Tháng ${month}/${year}`;

    const modalEl = document.getElementById('monthDetailModal');
    modalEl.dataset.monthIndex = monthIndex
    const modal = new bootstrap.Modal(modalEl);
    modal.show(); // chỉ show thôi, không thêm listener ở đây nữa
}

    function createSalesPieChart(productData) {
    const ctx = document.getElementById('salesPieChart').getContext('2d');
    const defaultColors = [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
    ];

    if (salesPieChart) {
        salesPieChart.destroy();
    }

    salesPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: productData.labels,
            datasets: [{
                data: productData.data,
                backgroundColor: productData.labels.map((_, i) => defaultColors[i % defaultColors.length]),
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}
// Biến toàn cục để giữ biểu đồ nhập hàng nếu cần sau này
let purchasePieChart;
// Hàm tạo biểu đồ tròn cho chi phí nhập hàng
function createPurchasePieChart(importData) {
    const ctx = document.getElementById('purchasePieChart').getContext('2d');
    const colors = ['#A569BD', '#5DADE2', '#58D68D', '#F5B041', '#EC7063', '#AF7AC5'];

    if (purchasePieChart) {
        purchasePieChart.destroy();
    }

    purchasePieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: importData.labels,
            datasets: [{
                data: importData.data,
                backgroundColor: importData.labels.map((_, i) => colors[i % colors.length]),
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}



document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('monthDetailModal');

    modalEl.addEventListener('shown.bs.modal', function () {
        const monthIndex = parseInt(this.dataset.monthIndex, 10);
        console.log("✅ Modal đã hiển thị");
        console.log("📅 Tháng được chọn:", monthIndex);

        const apiMonth = monthIndex;
        // Lấy dữ liệu doanh thu theo mặt hàng
        fetch(`http://127.0.0.1:8000/api/revenue-by-category?month=${apiMonth}`)
            .then(response => {
                if (!response.ok) throw new Error('Lỗi lấy dữ liệu từ API');
                return response.json();
            })
            .then(data => {
                console.log("📊 Dữ liệu từ API:", data);
                createSalesPieChart(data);
            })
            .catch(error => {
                console.error('❌ Lỗi khi tải dữ liệu biểu đồ:', error);
            });
        // Lấy dữ liệu chi phí nhập hàng
        fetch(`http://127.0.0.1:8000/api/import-cost-by-category?month=${apiMonth}`)
            .then(response => {
                if (!response.ok) throw new Error('Lỗi lấy dữ liệu chi phí nhập');
                return response.json();
            })
            .then(data => {
                console.log("📦 Dữ liệu chi phí nhập:", data);
                createPurchasePieChart(data);
            })
            .catch(error => {
                console.error('❌ Lỗi khi tải dữ liệu nhập hàng:', error);
            });
        
        
        // Lấy dữ liệu doanh thu theo ngày
        fetch(`http://127.0.0.1:8000/api/daily-revenue?month=${month}&year=${year}`)
            .then(res => {
            if (!res.ok) throw new Error('Lỗi lấy dữ liệu');
            return res.json();
            })
            .then(data => {
                console.log("📦 Dữ liệu chi phí nhập:", data);
            createDailyRevenueChart(data, month, year);
            })
            .catch(err => {
            console.error(err);
            alert('Không thể tải dữ liệu doanh thu theo ngày');
            });
    });
});


// xử lý doanh thu ngày
let dailyChart = null;
// Hàm tạo biểu đồ đường cho doanh thu theo ngày
function createDailyRevenueChart(dailyData, month, year) {
  const ctx = document.getElementById("dailyRevenueChart").getContext("2d");

  if (dailyChart) {
    dailyChart.destroy();
  }

  dailyChart = new Chart(ctx, {
    type: "line",
    data: {
      labels: dailyData.labels,
      datasets: [
        {
          label: "Doanh Thu Ngày",
          data: dailyData.data,
          borderColor: "rgba(54, 162, 235, 1)",
          backgroundColor: "rgba(54, 162, 235, 0.1)",
          borderWidth: 3,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: "rgba(54, 162, 235, 1)",
          pointBorderColor: "#fff",
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 8,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        intersect: false,
        mode: "index",
      },
      plugins: {
        title: {
          display: true,
          text: `Doanh Thu Hàng Ngày - Tháng ${month}/${year}`,
          font: {
            size: 14,
            weight: "bold",
          },
        },
        legend: {
          display: false,
        },
        tooltip: {
          callbacks: {
            title: function (context) {
              return `Ngày ${context[0].label}/${month}/${year}`;
            },
            label: function (context) {
              return (
                "Doanh thu: " +
                context.parsed.y.toLocaleString("vi-VN") +
                " triệu VNĐ"
              );
            },
          },
        },
      },
      scales: {
        x: {
          title: {
            display: true,
            text: "Ngày trong tháng",
            font: {
              weight: "bold",
            },
          },
          grid: {
            display: false,
          },
        },
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Doanh thu (Triệu VNĐ)",
            font: {
              weight: "bold",
            },
          },
          ticks: {
            callback: function (value) {
              return value.toLocaleString("vi-VN");
            },
          },
        },
      },
    },
  });
}

// Hàm hiển thị chi tiết ngày
function fetchAndShowDailyRevenueChart(month, year) {
  fetch(`http://127.0.0.1:8000/api/daily-revenue?month=${month}&year=${year}`)
    .then(res => {
      if (!res.ok) throw new Error('Lỗi lấy dữ liệu');
      return res.json();
    })
    .then(data => {
      createDailyRevenueChart(data, month, year);
    })
    .catch(err => {
      console.error(err);
      alert('Không thể tải dữ liệu doanh thu theo ngày');
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection
