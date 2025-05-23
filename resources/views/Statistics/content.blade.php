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
        }

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
                                    <label for="yearSelect" class="text-white mb-0">Chọn năm:</label>
                                    <select id="yearSelect" class="form-select" style="width: auto;">
                                        <option value="2021">2021</option>
                                        <option value="2022">2022</option>
                                        <option value="2023">2023</option>
                                        <option value="2024" selected>2024</option>
                                        <option value="2025">2025</option>
                                    </select>
                                    <button id="refreshBtn" class="btn btn-light">
                                        <i class="fas fa-sync-alt me-1"></i>Cập nhật
                                    </button>
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
@endsection
