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
    </style>
@endpush

@section('content')
    <div class="container py-5 exchange-page">
        <div class="row mb-4" data-aos="fade-down">
            <div class="col-12 text-center">
                <h1 class="fw-bold mb-2">
                    <i class="fas fa-credit-card text-primary me-2"></i>Đổi Thẻ Cào
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
                                <small class="text-muted d-block mt-2">
                                    Số tiền thực nhận được tính theo bảng giá (khớp theo loại thẻ & mệnh giá).
                                </small>
                            </div>

                            <div class="col-12">
                                <hr class="my-2">
                            </div>

                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <h6 class="fw-bold mb-0">
                                        <i class="fas fa-building-columns text-primary me-2"></i>Thông tin ngân hàng
                                    </h6>
                                    <small class="text-muted">Vui lòng nhập đúng để nhận tiền</small>
                                </div>
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
            </div>

            <div class="col-lg-5" data-aos="fade-up" data-aos-delay="50">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-tags text-primary me-2"></i>Bảng giá (Khách thực nhận)
                        </h5>

                        <ul class="nav nav-tabs mb-3" role="tablist">
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
                                    <table class="table table-sm align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách thực nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold">8.300</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold">16.500</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold">41.800</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold">83.500</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold">165.000</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold">402.000</td></tr>
                                            <tr><td>1.000.000</td><td class="text-end fw-bold">805.000</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted d-block mt-2">VIETTEL</small>
                            </div>

                            <div class="tab-pane fade" id="pane-mobifone" role="tabpanel" aria-labelledby="tab-mobifone" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách thực nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold">7.800</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold">15.600</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold">39.500</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold">80.000</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold">160.000</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold">400.000</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted d-block mt-2">MOBIFONE</small>
                            </div>

                            <div class="tab-pane fade" id="pane-vinaphone" role="tabpanel" aria-labelledby="tab-vinaphone" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mệnh giá</th>
                                                <th class="text-end">Khách thực nhận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>10.000</td><td class="text-end fw-bold">8.100</td></tr>
                                            <tr><td>20.000</td><td class="text-end fw-bold">17.000</td></tr>
                                            <tr><td>50.000</td><td class="text-end fw-bold">43.000</td></tr>
                                            <tr><td>100.000</td><td class="text-end fw-bold">88.000</td></tr>
                                            <tr><td>200.000</td><td class="text-end fw-bold">175.000</td></tr>
                                            <tr><td>500.000</td><td class="text-end fw-bold">438.000</td></tr>
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
                            <i class="fas fa-list-check text-primary me-2"></i>Hướng dẫn nhanh
                        </h5>

                        <div class="exchange-step mb-3">
                            <div class="d-flex gap-3 align-items-start">
                                <span class="badge rounded-pill bg-primary">1</span>
                                <div>
                                    <div class="fw-bold">Chọn loại thẻ & mệnh giá</div>
                                    <div class="text-muted small">Ví dụ: Viettel 100,000đ</div>
                                </div>
                            </div>
                        </div>

                        <div class="exchange-step mb-3">
                            <div class="d-flex gap-3 align-items-start">
                                <span class="badge rounded-pill bg-primary">2</span>
                                <div>
                                    <div class="fw-bold">Nhập seri & mã thẻ</div>
                                    <div class="text-muted small">Nhập đúng ký tự, không khoảng trắng</div>
                                </div>
                            </div>
                        </div>

                        <div class="exchange-step">
                            <div class="d-flex gap-3 align-items-start">
                                <span class="badge rounded-pill bg-primary">3</span>
                                <div>
                                    <div class="fw-bold">Nhập thông tin ngân hàng</div>
                                    <div class="text-muted small">Để nhận tiền đúng người, đúng số tài khoản</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-shield-heart text-primary me-2"></i>Lưu ý
                        </h5>
                        <ul class="text-muted mb-3 ps-3">
                            <li>Yêu cầu sẽ được xử lý theo thứ tự.</li>
                            <li>Nếu có lỗi, hệ thống sẽ cập nhật trạng thái trong lịch sử.</li>
                            <li>Cần hỗ trợ? Nhấn “Hỗ trợ” để xem thông tin liên hệ.</li>
                        </ul>
                        <button
                            type="button"
                            class="btn btn-outline-primary w-100"
                            data-bs-toggle="modal"
                            data-bs-target="#contactModal"
                        >
                            <i class="fas fa-headset me-2"></i>Liên hệ hỗ trợ
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if($exchanges->count() > 0)
            <div class="row mt-4">
                <div class="col-12" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                        <div class="card-header bg-white py-3" style="border-radius: 15px 15px 0 0;">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-history text-primary me-2"></i>Lịch sử đổi thẻ
                            </h5>
                        </div>

                        <div class="card-body p-0">
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
                        </div>

                        <div class="card-footer bg-white" style="border-radius: 0 0 15px 15px;">
                            <div class="d-flex justify-content-center">
                                {{ $exchanges->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
                        <strong>Email:</strong> support@dungthu.com
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-phone text-primary me-2"></i>
                        <strong>Hotline:</strong> 0123.456.789
                    </div>
                    <div class="mb-3">
                        <i class="fab fa-facebook text-primary me-2"></i>
                        <strong>Facebook:</strong> fb.com/dungthu
                    </div>
                    <div>
                        <i class="fab fa-telegram text-primary me-2"></i>
                        <strong>Telegram:</strong> @dungthu_support
                    </div>
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
                    10000: 8300,
                    20000: 16500,
                    50000: 41800,
                    100000: 83500,
                    200000: 165000,
                    500000: 402000,
                    1000000: 805000,
                },
                Mobifone: {
                    10000: 7800,
                    20000: 15600,
                    50000: 39500,
                    100000: 80000,
                    200000: 160000,
                    500000: 400000,
                },
                Vinaphone: {
                    10000: 8100,
                    20000: 17000,
                    50000: 43000,
                    100000: 88000,
                    200000: 175000,
                    500000: 438000,
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
