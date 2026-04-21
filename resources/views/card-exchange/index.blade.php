@extends('layouts.app')

@section('title', 'Đổi Thẻ Cào - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .exchange-page {
            margin-top: 80px;
        }

        .exchange-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .exchange-step {
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #eef2f7;
            padding: 14px 16px;
        }

        .pricing-tabs {
            border-bottom: 1px solid #eef2f7;
            flex-wrap: nowrap;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        
        .pricing-tabs::-webkit-scrollbar {
            display: none;
        }

        .pricing-tabs .nav-link {
            border: 1px solid transparent;
            border-bottom: 0;
            color: #495057;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 10px 10px 0 0;
            margin-right: 8px;
        }

        .pricing-tabs .nav-link.active {
            background: #ffffff;
            border-color: #eef2f7;
            color: #0f172a;
            box-shadow: 0 -1px 0 #eef2f7;
        }

        .exchange-price-table thead th {
            background: #f8fafc;
            font-weight: 700;
        }

        .exchange-price-table td,
        .exchange-price-table th {
            border-color: #eef2f7;
            padding: 10px 12px;
        }

        .exchange-price-table tbody tr:last-child td {
            border-bottom: 0;
        }
        
        /* Mobile Card Style for History */
        .history-card-mobile {
            display: none;
        }

        @media (max-width: 768px) {
            .exchange-page {
                margin-top: 70px;
                padding-top: 10px !important;
            }
            .card {
                border-radius: 16px !important;
            }
            .card-header {
                border-radius: 16px 16px 0 0 !important;
                padding: 15px !important;
            }
            .form-control-lg, .form-select-lg {
                font-size: 1rem;
                padding: 0.75rem 1rem;
            }
            .btn-lg {
                width: 100%;
                margin-bottom: 10px;
                font-size: 1rem;
            }
            .history-table-desktop {
                display: none;
            }
            .history-card-mobile {
                display: block;
            }
            .mobile-exchange-item {
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 15px;
                margin-bottom: 12px;
                background: #fff;
            }
            .mobile-exchange-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
                padding-bottom: 10px;
                border-bottom: 1px dashed #e2e8f0;
            }
            .mobile-exchange-body {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container py-5 exchange-page">
        <div class="row mb-4" data-aos="fade-down">
            <div class="col-12 text-center">
                <h1 class="fw-bold mb-2 fs-3 fs-md-1">
                    <i class="fas fa-credit-card text-primary me-2"></i>Đổi Thẻ Cào Auto
                </h1>
                <p class="text-muted mb-0 fs-6">Gửi yêu cầu đổi thẻ và nhận tiền về tài khoản ngân hàng</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7" data-aos="fade-up">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-white py-3 exchange-gradient">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h5 class="mb-0 fw-bold fs-5">
                                <i class="fas fa-paper-plane me-2"></i>Tạo yêu cầu
                            </h5>
                            <small class="opacity-75">
                                <i class="far fa-clock me-1"></i>Xử lý 5–10 phút
                            </small>
                        </div>
                    </div>

                    <div class="card-body p-3 p-md-4">
                        @if($errors->any())
                            <div class="alert alert-danger rounded-3">
                                <div class="fw-bold mb-2">
                                    <i class="fas fa-triangle-exclamation me-2"></i>Vui lòng kiểm tra lại
                                </div>
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="alert alert-info rounded-3 text-sm" style="font-size: 0.9rem;">
                            <i class="fas fa-info-circle me-2"></i>
                            Nhập chính xác <strong>seri</strong>, <strong>mã thẻ</strong> và <strong>thông tin ngân hàng</strong> để nhận tiền.
                        </div>

                        <form action="{{ route('card-exchange.store') }}" method="POST" class="row g-3">
                            @csrf

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-sim-card text-primary me-2"></i>Loại thẻ <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg bg-light" name="card_type" required>
                                    <option value="">-- Chọn loại thẻ --</option>
                                    <option value="Viettel" {{ old('card_type') == 'Viettel' ? 'selected' : '' }}>Viettel</option>
                                    <option value="Mobifone" {{ old('card_type') == 'Mobifone' ? 'selected' : '' }}>Mobifone</option>
                                    <option value="Vinaphone" {{ old('card_type') == 'Vinaphone' ? 'selected' : '' }}>Vinaphone</option>
                                    <option value="Garena" {{ old('card_type') == 'Garena' ? 'selected' : '' }}>Garena</option>
                                    <option value="Vcoin" {{ old('card_type') == 'Vcoin' ? 'selected' : '' }}>Vcoin</option>
                                    <option value="Zing" {{ old('card_type') == 'Zing' ? 'selected' : '' }}>Zing</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-money-bill-wave text-primary me-2"></i>Mệnh giá <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg bg-light" name="card_value" required>
                                    <option value="">-- Chọn mệnh giá --</option>
                                    <option value="10000" {{ old('card_value') == '10000' ? 'selected' : '' }}>10,000đ</option>
                                    <option value="20000" {{ old('card_value') == '20000' ? 'selected' : '' }}>20,000đ</option>
                                    <option value="30000" {{ old('card_value') == '30000' ? 'selected' : '' }}>30,000đ</option>
                                    <option value="50000" {{ old('card_value') == '50000' ? 'selected' : '' }}>50,000đ</option>
                                    <option value="100000" {{ old('card_value') == '100000' ? 'selected' : '' }}>100,000đ</option>
                                    <option value="200000" {{ old('card_value') == '200000' ? 'selected' : '' }}>200,000đ</option>
                                    <option value="300000" {{ old('card_value') == '300000' ? 'selected' : '' }}>300,000đ</option>
                                    <option value="500000" {{ old('card_value') == '500000' ? 'selected' : '' }}>500,000đ</option>
                                    <option value="1000000" {{ old('card_value') == '1000000' ? 'selected' : '' }}>1,000,000đ</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-barcode text-primary me-2"></i>Seri thẻ <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg bg-light"
                                    name="card_serial"
                                    placeholder="Nhập seri thẻ"
                                    value="{{ old('card_serial') }}"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-key text-primary me-2"></i>Mã thẻ <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg bg-light"
                                    name="card_code"
                                    placeholder="Nhập mã thẻ"
                                    value="{{ old('card_code') }}"
                                    required
                                >
                            </div>

                            <div class="col-12 mt-4">
                                <div class="alert alert-success d-flex align-items-center justify-content-between gap-2 mb-0 rounded-3 shadow-sm border-0" style="background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);">
                                    <div class="fw-bold text-dark fs-6">
                                        <i class="fas fa-coins me-2 text-success"></i>Thực nhận
                                    </div>
                                    <div class="fw-bold fs-4 text-dark text-nowrap" id="js-receive-amount">--</div>
                                </div>
                                <input type="hidden" name="exchange_amount" value="">
                            </div>

                            <div class="col-12">
                                <hr class="my-3 opacity-25">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-university text-primary me-2"></i>Ngân hàng <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg bg-light"
                                    name="bank_name"
                                    placeholder="VD: Vietcombank, Momo..."
                                    value="{{ old('bank_name') }}"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-hashtag text-primary me-2"></i>Số tài khoản <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg bg-light"
                                    name="bank_account_number"
                                    placeholder="Nhập số tài khoản/SĐT"
                                    value="{{ old('bank_account_number') }}"
                                    required
                                >
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user text-primary me-2"></i>Tên chủ tài khoản <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg bg-light"
                                    name="bank_account_name"
                                    placeholder="NGUYEN VAN A"
                                    value="{{ old('bank_account_name') }}"
                                    required
                                >
                            </div>

                            <div class="col-12 mt-4">
                                <div class="d-flex flex-column flex-md-row gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1 fw-bold rounded-pill shadow-sm">
                                        <i class="fas fa-paper-plane me-2"></i>Gửi yêu cầu
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-lg fw-bold rounded-pill"
                                        data-bs-toggle="modal"
                                        data-bs-target="#contactModal"
                                    >
                                        <i class="fas fa-headset"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
                    <div class="card-body p-3 p-md-4">
                        <h6 class="fw-bold mb-3 fs-6">
                            <i class="fas fa-clipboard-check text-primary me-2"></i>Chính sách đổi thẻ
                        </h6>
                        <ul class="text-muted mb-2 ps-3" style="font-size: 0.85rem;">
                            <li>DungThu.com là website đổi thẻ cào uy tín. Bạn có thể đổi thẻ điện thoại, thẻ game thành tiền về ngân hàng.</li>
                            <li>Không nhận các thẻ mua từ nguồn không hợp lệ. Vi phạm sẽ bị khóa tài khoản.</li>
                        </ul>
                        <div class="small">
                            <a href="javascript:void(0)" class="text-decoration-none fw-bold" data-bs-toggle="modal" data-bs-target="#policyModal">Đọc toàn bộ chính sách</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5" data-aos="fade-up" data-aos-delay="50">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-3 p-md-4">
                        <h6 class="fw-bold mb-3 fs-6">
                            <i class="fas fa-tags text-primary me-2"></i>Bảng giá Điện thoại (5-10p)
                        </h6>

                        <ul class="nav nav-tabs mb-3 pricing-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-viettel" data-bs-toggle="tab" data-bs-target="#pane-viettel" type="button" role="tab">
                                    VIETTEL
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-mobifone" data-bs-toggle="tab" data-bs-target="#pane-mobifone" type="button" role="tab">
                                    MOBIFONE
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-vinaphone" data-bs-toggle="tab" data-bs-target="#pane-vinaphone" type="button" role="tab">
                                    VINAPHONE
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="pane-viettel" role="tabpanel" aria-labelledby="tab-viettel" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 exchange-price-table" style="font-size: 0.9rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold text-success">7.200</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold text-success">14.310</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold text-success">36.270</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold text-success">72.450</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold text-success">143.100</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold text-success">348.300</td></tr>
                                            <tr><td>1.000.000</td><td class="text-end fw-bold text-success">697.500</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pane-mobifone" role="tabpanel" aria-labelledby="tab-mobifone" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 exchange-price-table" style="font-size: 0.9rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold text-success">6.750</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold text-success">13.500</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold text-success">34.200</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold text-success">69.300</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold text-success">138.600</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold text-success">346.500</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pane-vinaphone" role="tabpanel" aria-labelledby="tab-vinaphone" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 exchange-price-table" style="font-size: 0.9rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold text-success">7.020</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold text-success">14.760</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold text-success">37.350</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold text-success">76.500</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold text-success">152.100</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold text-success">380.700</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-3 p-md-4">
                        <h6 class="fw-bold mb-3 fs-6">
                            <i class="fas fa-gamepad text-primary me-2"></i>Bảng giá Game (5-10p)
                        </h6>

                        <ul class="nav nav-tabs mb-3 pricing-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-garena" data-bs-toggle="tab" data-bs-target="#pane-garena" type="button" role="tab">
                                    GARENA
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-vcoin" data-bs-toggle="tab" data-bs-target="#pane-vcoin" type="button" role="tab">
                                    VCOIN
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-zing" data-bs-toggle="tab" data-bs-target="#pane-zing" type="button" role="tab">
                                    ZING
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="pane-garena" role="tabpanel" aria-labelledby="tab-garena" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 exchange-price-table" style="font-size: 0.9rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold text-success">7.200</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold text-success">14.760</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold text-success">37.350</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold text-success">76.500</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold text-success">153.000</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold text-success">388.800</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pane-vcoin" role="tabpanel" aria-labelledby="tab-vcoin" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 exchange-price-table" style="font-size: 0.9rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold text-success">7.020</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold text-success">14.400</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold text-success">36.450</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold text-success">75.600</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold text-success">151.200</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold text-success">387.000</td></tr>
                                            <tr><td>1.000.000</td><td class="text-end fw-bold text-success">778.500</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pane-zing" role="tabpanel" aria-labelledby="tab-zing" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 exchange-price-table" style="font-size: 0.9rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold text-success">7.020</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold text-success">14.220</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold text-success">36.000</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold text-success">74.700</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold text-success">149.400</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold text-success">382.500</td></tr>
                                            <tr><td>1.000.000</td><td class="text-end fw-bold text-success">769.500</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted d-block mt-2" style="font-size: 0.8rem;">ZING (chậm 10–30 phút)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2" id="card-exchange-history">
            <div class="col-12" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold fs-5">
                            <i class="fas fa-history text-primary me-2"></i>Lịch sử đổi thẻ
                        </h6>
                    </div>

                    <div class="card-body p-0">
                        <!-- Desktop Table -->
                        <div class="table-responsive history-table-desktop">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Mã GD</th>
                                        <th>Thời gian</th>
                                        <th>Loại thẻ</th>
                                        <th>Mệnh giá</th>
                                        <th class="text-end pe-4">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($exchanges as $exchange)
                                        @php
                                            $badgeColor = $exchange->status_badge;
                                            $badgeTextClass = in_array($badgeColor, ['warning', 'light']) ? 'text-dark' : 'text-white';
                                        @endphp
                                        <tr>
                                            <td class="ps-4">
                                                <strong class="text-primary">#{{ $exchange->id }}</strong>
                                            </td>
                                            <td class="text-muted small">
                                                {{ $exchange->created_at->format('d/m/y H:i') }}
                                            </td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info">{{ $exchange->card_type }}</span>
                                            </td>
                                            <td class="fw-bold">{{ number_format($exchange->card_value, 0, ',', '.') }}đ</td>
                                            <td class="text-end pe-4">
                                                <span class="badge rounded-pill bg-{{ $badgeColor }} {{ $badgeTextClass }}">
                                                    {{ $exchange->status_text }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-5">
                                                <i class="fas fa-inbox fs-1 mb-3 opacity-25"></i>
                                                <p class="mb-0">Chưa có lịch sử đổi thẻ.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Mobile Cards -->
                        <div class="history-card-mobile p-3">
                            @forelse($exchanges as $exchange)
                                @php
                                    $badgeColor = $exchange->status_badge;
                                    $badgeTextClass = in_array($badgeColor, ['warning', 'light']) ? 'text-dark' : 'text-white';
                                @endphp
                                <div class="mobile-exchange-item shadow-sm">
                                    <div class="mobile-exchange-header">
                                        <div>
                                            <strong class="text-primary fs-6">#{{ $exchange->id }}</strong>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $exchange->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                        <span class="badge rounded-pill bg-{{ $badgeColor }} {{ $badgeTextClass }}">
                                            {{ $exchange->status_text }}
                                        </span>
                                    </div>
                                    <div class="mobile-exchange-body">
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info px-3 py-2 fs-6">
                                            {{ $exchange->card_type }}
                                        </span>
                                        <span class="fw-bold fs-5 text-success">
                                            {{ number_format($exchange->card_value, 0, ',', '.') }}đ
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fs-1 mb-3 opacity-25"></i>
                                    <p class="mb-0">Chưa có lịch sử đổi thẻ.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    @if($exchanges->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $exchanges->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="contactModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header text-white border-0 exchange-gradient">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-headset me-2"></i>Hỗ trợ cấp tốc
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="d-flex flex-column gap-3">
                        <a href="https://t.me/dungthucom" target="_blank" class="text-decoration-none bg-white p-3 rounded-3 shadow-sm d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="fab fa-telegram fa-fw fs-4 text-primary"></i>
                            </div>
                            <div class="text-dark">
                                <h6 class="fw-bold mb-0">Telegram</h6>
                                <small class="text-muted">Chat ngay với Admin (24/7)</small>
                            </div>
                        </a>
                        
                        <a href="https://zalo.me/0708910952" target="_blank" class="text-decoration-none bg-white p-3 rounded-3 shadow-sm d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="fas fa-comments fa-fw fs-4 text-success"></i>
                            </div>
                            <div class="text-dark">
                                <h6 class="fw-bold mb-0">Zalo Hỗ Trợ</h6>
                                <small class="text-muted">Giờ hành chính</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="policyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header text-white border-0 exchange-gradient">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-clipboard-check me-2"></i>Điều khoản & chính sách
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="bg-white p-4 rounded-3 shadow-sm text-dark">
                        <ul class="mb-0 ps-3" style="line-height: 1.8;">
                            <li>Bảng giá hiển thị là số tiền thực nhận, tính theo đúng loại thẻ và mệnh giá.</li>
                            <li>Không nhận các thẻ mua từ nguồn không hợp lệ (thẻ Visa/Thẻ tín dụng, thẻ lừa đảo, thẻ trộm cắp...). Vi phạm sẽ bị khóa tài khoản.</li>
                            <li>Yêu cầu được ghi nhận ngay sau khi gửi, trạng thái cập nhật trong “Lịch sử đổi thẻ”.</li>
                            <li>Thông tin thẻ và ngân hàng chỉ phục vụ xử lý giao dịch đổi thẻ.</li>
                            <li>Cần xác minh hoặc hỗ trợ thêm, vui lòng liên hệ qua mục “Hỗ trợ”.</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Đã hiểu</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({ duration: 800, once: true });
            }
        });

        (function () {
            const pricing = {
                Viettel: {
                    10000: 7200, 20000: 14310, 50000: 36270, 100000: 72450, 200000: 143100, 500000: 348300, 1000000: 697500,
                },
                Mobifone: {
                    10000: 6750, 20000: 13500, 50000: 34200, 100000: 69300, 200000: 138600, 500000: 346500,
                },
                Vinaphone: {
                    10000: 7020, 20000: 14760, 50000: 37350, 100000: 76500, 200000: 152100, 500000: 380700,
                },
                Garena: {
                    10000: 7200, 20000: 14760, 50000: 37350, 100000: 76500, 200000: 153000, 500000: 388800,
                },
                Vcoin: {
                    10000: 7020, 20000: 14400, 50000: 36450, 100000: 75600, 200000: 151200, 500000: 387000, 1000000: 778500,
                },
                Zing: {
                    10000: 7020, 20000: 14220, 50000: 36000, 100000: 74700, 200000: 149400, 500000: 382500, 1000000: 769500,
                },
            };

            const formatVnd = (value) => new Intl.NumberFormat('vi-VN').format(value) + 'đ';

            const cardTypeEl = document.querySelector('select[name="card_type"]');
            const cardValueEl = document.querySelector('select[name="card_value"]');
            const receiveAmountEl = document.getElementById('js-receive-amount');
            const exchangeAmountInputEl = document.querySelector('input[name="exchange_amount"]');

            if (!cardTypeEl || !cardValueEl || !receiveAmountEl) return;

            const syncValueOptions = (cardType) => {
                const allowedMap = pricing[cardType] || {};
                const allowedValues = new Set(Object.keys(allowedMap).map((v) => String(v)));

                Array.from(cardValueEl.options).forEach((opt) => {
                    if (!opt.value) return;
                    const isAllowed = allowedValues.has(String(opt.value));
                    opt.disabled = !isAllowed;
                    opt.hidden = !isAllowed;
                });

                if (cardValueEl.value && (cardValueEl.selectedOptions[0]?.disabled || cardValueEl.selectedOptions[0]?.hidden)) {
                    cardValueEl.value = '';
                }
            };

            const updateReceiveAmount = () => {
                const cardType = cardTypeEl.value;
                const cardValue = parseInt(cardValueEl.value, 10);

                if (!cardType || !cardValue) {
                    receiveAmountEl.textContent = '--';
                    if (exchangeAmountInputEl) exchangeAmountInputEl.value = '';
                    return;
                }

                const amount = pricing[cardType]?.[cardValue];
                receiveAmountEl.textContent = amount ? formatVnd(amount) : '--';
                if (exchangeAmountInputEl && amount) exchangeAmountInputEl.value = amount;
            };

            const onChange = () => {
                syncValueOptions(cardTypeEl.value);
                updateReceiveAmount();
            };

            cardTypeEl.addEventListener('change', onChange);
            cardValueEl.addEventListener('change', onChange);
            onChange();
        })();
    </script>
@endpush
