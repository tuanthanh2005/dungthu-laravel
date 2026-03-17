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
    </style>
@endpush

@section('content')
    <div class="container py-5 exchange-page">
        <div class="row mb-4" data-aos="fade-down">
            <div class="col-12 text-center">
                <h1 class="fw-bold mb-2">
                    <i class="fas fa-credit-card text-primary me-2"></i>Đổi Thẻ Cào - Được Xử Lý Auto
                </h1>
                <p class="text-muted mb-0">Gửi yêu cầu đổi thẻ và nhận tiền về tài khoản ngân hàng</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7" data-aos="fade-up">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header text-white py-3 exchange-gradient" style="border-radius: 15px 15px 0 0;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-paper-plane me-2"></i>Tạo yêu cầu
                            </h5>
                            <small class="opacity-75">
                                <i class="far fa-clock me-1"></i>Xử lý trong 5–10 phút
                            </small>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger">
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

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Nhập chính xác <strong>seri</strong>, <strong>mã thẻ</strong> và <strong>thông tin ngân hàng</strong> để nhận tiền.
                        </div>

                        <form action="{{ route('card-exchange.store') }}" method="POST" class="row g-3">
                            @csrf

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-sim-card text-primary me-2"></i>Loại thẻ <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg" name="card_type" required>
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
                                <select class="form-select form-select-lg" name="card_value" required>
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
                                    class="form-control form-control-lg"
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
                                    class="form-control form-control-lg"
                                    name="card_code"
                                    placeholder="Nhập mã thẻ"
                                    value="{{ old('card_code') }}"
                                    required
                                >
                            </div>

                            <div class="col-12">
                                <div class="alert alert-success d-flex align-items-center justify-content-between gap-3 mb-0">
                                    <div class="fw-bold">
                                        <i class="fas fa-coins me-2"></i>Khách thực nhận (theo bảng giá)
                                    </div>
                                    <div class="fw-bold fs-5 text-nowrap" id="js-receive-amount">--</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr class="my-2">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-university text-primary me-2"></i>Ngân hàng <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                    name="bank_name"
                                    placeholder="VD: Vietcombank"
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
                                    class="form-control form-control-lg"
                                    name="bank_account_number"
                                    placeholder="Nhập số tài khoản"
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
                                    class="form-control form-control-lg"
                                    name="bank_account_name"
                                    placeholder="NGUYEN VAN A"
                                    value="{{ old('bank_account_name') }}"
                                    required
                                >
                            </div>

                            <div class="col-12">
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Vui lòng kiểm tra kỹ <strong>thông tin ngân hàng</strong> trước khi gửi. Chúng tôi sẽ chuyển tiền vào tài khoản bạn cung cấp.
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex gap-3 flex-wrap mt-2">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-paper-plane me-2"></i>Gửi yêu cầu
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-lg px-4"
                                        data-bs-toggle="modal"
                                        data-bs-target="#contactModal"
                                    >
                                        <i class="fas fa-headset me-2"></i>Hỗ trợ
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-clipboard-check text-primary me-2"></i>Chính sách đổi thẻ
                        </h5>
                        <ul class="text-muted mb-2 ps-3">
                            <li>DungThu.com là website đổi thẻ cào uy tín. Bạn có thể đổi thẻ điện thoại, thẻ game (Garena, Zing, Vcoin...) thành tiền về ngân hàng.</li>
                            <li>Không nhận các thẻ mua từ nguồn không hợp lệ (thẻ Visa/Thẻ tín dụng, thẻ lừa đảo, thẻ trộm cắp...). Vi phạm sẽ bị khóa tài khoản.</li>
                            <li>Vui lòng đọc kỹ điều khoản và chính sách trước khi đổi thẻ.</li>
                        </ul>
                        <div class="small">
                            <a href="javascript:void(0)" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#policyModal">Điều khoản & chính sách</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5" data-aos="fade-up" data-aos-delay="50">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-tags text-primary me-2"></i>Bảng giá (5-10p nhận tiền)
                        </h5>

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
                                    <table class="table table-sm align-middle mb-0 exchange-price-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách thực nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold">8.000</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold">15.900</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold">40.300</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold">80.500</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold">159.000</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold">387.000</td></tr>
                                            <tr><td>1.000.000</td><td class="text-end fw-bold">775.000</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted d-block mt-2">VIETTEL</small>
                            </div>

                            <div class="tab-pane fade" id="pane-mobifone" role="tabpanel" aria-labelledby="tab-mobifone" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 exchange-price-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách thực nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold">7.500</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold">15.000</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold">38.000</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold">77.000</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold">154.000</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold">385.000</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted d-block mt-2">MOBIFONE</small>
                            </div>

                            <div class="tab-pane fade" id="pane-vinaphone" role="tabpanel" aria-labelledby="tab-vinaphone" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 exchange-price-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách thực nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold">7.800</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold">16.400</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold">41.500</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold">85.000</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold">169.000</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold">423.000</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted d-block mt-2">VINAPHONE</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-tag text-primary me-2"></i>Bảng giá game (5-10p nhận tiền)
                        </h5>

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
                                    <table class="table table-sm align-middle mb-0 exchange-price-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách thực nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold">8.000</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold">16.400</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold">41.500</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold">85.000</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold">170.000</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold">432.000</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted d-block mt-2">GARENA</small>
                            </div>

                            <div class="tab-pane fade" id="pane-vcoin" role="tabpanel" aria-labelledby="tab-vcoin" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 exchange-price-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách thực nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold">7.800</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold">16.000</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold">40.500</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold">84.000</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold">168.000</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold">430.000</td></tr>
                                            <tr><td>1.000.000</td><td class="text-end fw-bold">865.000</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted d-block mt-2">VCOIN</small>
                            </div>

                            <div class="tab-pane fade" id="pane-zing" role="tabpanel" aria-labelledby="tab-zing" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 exchange-price-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách thực nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold">7.800</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold">15.800</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold">40.000</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold">83.000</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold">166.000</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold">425.000</td></tr>
                                            <tr><td>1.000.000</td><td class="text-end fw-bold">855.000</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted d-block mt-2">ZING (chậm 10–30 phút)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4" id="card-exchange-history">
            <div class="col-12" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-white py-3" style="border-radius: 15px 15px 0 0;">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-history text-primary me-2"></i>Lịch sử đổi thẻ
                        </h5>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Mã GD</th>
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
                                            <td>
                                                <span class="badge bg-info">{{ $exchange->card_type }}</span>
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
                                            <td colspan="4" class="text-center text-muted py-4">
                                                Chưa có lịch sử đổi thẻ.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-white" style="border-radius: 0 0 15px 15px;">
                        <div class="d-flex justify-content-center">
                            {{ $exchanges->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="contactModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header text-white border-0 exchange-gradient" style="border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-headset me-2"></i>Thông tin liên hệ
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        <strong>Email:</strong> tranthanhtuanfix@gmail.com
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-phone text-primary me-2"></i>
                        <strong>Hotline:</strong> 0772698113
                    </div>
                    <div class="mb-3">
                        <i class="fab fa-facebook text-primary me-2"></i>
                        <strong>Facebook:</strong> https://www.facebook.com/thanh.tuan.378686?locale=vi_VN
                    </div>
                    <div>
                        <i class="fab fa-telegram text-primary me-2"></i>
                        <strong>Telegram:</strong> <a href="https://t.me/dungthucom" target="_blank">Chat ngay</a>
                    </div>
                    <div>
                        <i class="fas fa-envelope text-primary me-2"></i>
                        <strong>Email:</strong> tranthanhtuanfix@gmail.com
                    </div>
                    <div>
                        <i class="fas fa-comments text-success me-2"></i>
                        <strong>Zalo:</strong> <a href="https://zalo.me/0708910952" target="_blank">Chat ngay</a>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="policyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header text-white border-0 exchange-gradient" style="border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-clipboard-check me-2"></i>Điều khoản & chính sách
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <ul class="text-muted mb-0 ps-3">
                        <li>Bảng giá hiển thị là số tiền thực nhận, tính theo đúng loại thẻ và mệnh giá.</li>
                        <li>Không nhận các thẻ mua từ nguồn không hợp lệ (thẻ Visa/Thẻ tín dụng, thẻ lừa đảo, thẻ trộm cắp...). Vi phạm sẽ bị khóa tài khoản.</li>
                        <li>Yêu cầu được ghi nhận ngay sau khi gửi, trạng thái cập nhật trong “Lịch sử đổi thẻ”.</li>
                        <li>Thông tin thẻ và ngân hàng chỉ phục vụ xử lý giao dịch đổi thẻ.</li>
                        <li>Cần xác minh hoặc hỗ trợ thêm, vui lòng liên hệ qua mục “Hỗ trợ”.</li>
                    </ul>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="historyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header text-white border-0 exchange-gradient" style="border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-history me-2"></i>Lịch sử đổi thẻ
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    @if($exchanges->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Mã GD</th>
                                        <th>Loại thẻ</th>
                                        <th>Mệnh giá</th>
                                        <th class="text-nowrap">Ngày gửi</th>
                                        <th class="text-end pe-4">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exchanges as $exchange)
                                        @php
                                            $badgeColor = $exchange->status_badge;
                                            $badgeTextClass = in_array($badgeColor, ['warning', 'light']) ? 'text-dark' : 'text-white';
                                        @endphp
                                        <tr>
                                            <td class="ps-4">
                                                <strong class="text-primary">#{{ $exchange->id }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $exchange->card_type }}</span>
                                            </td>
                                            <td class="fw-bold">{{ number_format($exchange->card_value, 0, ',', '.') }}đ</td>
                                            <td class="text-muted text-nowrap">{{ $exchange->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-end pe-4">
                                                <span class="badge rounded-pill bg-{{ $badgeColor }} {{ $badgeTextClass }}">
                                                    {{ $exchange->status_text }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            Chưa có lịch sử đổi thẻ.
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        AOS.init({ duration: 800, once: true });

        (function () {
            const pricing = {
                Viettel: {
                    10000: 8000,
                    20000: 15900,
                    50000: 40300,
                    100000: 80500,
                    200000: 159000,
                    500000: 387000,
                    1000000: 775000,
                },
                Mobifone: {
                    10000: 7500,
                    20000: 15000,
                    50000: 38000,
                    100000: 77000,
                    200000: 154000,
                    500000: 385000,
                },
                Vinaphone: {
                    10000: 7800,
                    20000: 16400,
                    50000: 41500,
                    100000: 85000,
                    200000: 169000,
                    500000: 423000,
                },
                Garena: {
                    10000: 8000,
                    20000: 16400,
                    50000: 41500,
                    100000: 85000,
                    200000: 170000,
                    500000: 432000,
                },
                Vcoin: {
                    10000: 7800,
                    20000: 16000,
                    50000: 40500,
                    100000: 84000,
                    200000: 168000,
                    500000: 430000,
                    1000000: 865000,
                },
                Zing: {
                    10000: 7800,
                    20000: 15800,
                    50000: 40000,
                    100000: 83000,
                    200000: 166000,
                    500000: 425000,
                    1000000: 855000,
                },
            };

            const formatVnd = (value) => new Intl.NumberFormat('vi-VN').format(value) + 'đ';

            const cardTypeEl = document.querySelector('select[name="card_type"]');
            const cardValueEl = document.querySelector('select[name="card_value"]');
            const receiveAmountEl = document.getElementById('js-receive-amount');

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
                    return;
                }

                const amount = pricing[cardType]?.[cardValue];
                receiveAmountEl.textContent = amount ? formatVnd(amount) : '--';
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
