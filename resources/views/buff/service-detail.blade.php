@extends('layouts.app')

@section('title', $buffService->name . ' - DungThu.com')

@push('styles')
<style>
    .service-detail-hero {
        background: white;
        border-bottom: 1px solid #e0e0e0;
        padding: 1rem 0;
        margin-bottom: 1.5rem;
    }

    .service-detail-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .service-icon-large {
        font-size: 3.5rem;
        min-width: 100px;
    }

    .service-info h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .service-meta {
        display: flex;
        gap: 1.5rem;
        margin-top: 0.5rem;
        font-size: 0.9rem;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
    }

    .meta-label {
        color: #999;
        font-size: 0.8rem;
        margin-bottom: 0.15rem;
    }

    .meta-value {
        font-weight: 600;
        color: #2d3436;
    }

    .form-section {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1rem;
    }

    .form-section h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        font-weight: 600;
        color: #2d3436;
        margin-bottom: 0.3rem;
        display: block;
        font-size: 0.95rem;
    }

    .form-label .required {
        color: #dc3545;
    }

    .form-control {
        border: 1px solid #ddd;
        padding: 0.6rem;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #6c5ce7;
        box-shadow: 0 0 0 2px rgba(108, 92, 231, 0.1);
    }

    .form-check {
        margin-bottom: 0.75rem;
    }

    .emotion-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(70px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .emotion-btn {
        border: 2px solid #ddd;
        background: white;
        padding: 0.75rem;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        font-size: 1.8rem;
    }

    .emotion-btn:hover {
        border-color: #6c5ce7;
        background: #f8f9fa;
    }

    .emotion-btn.selected {
        border-color: #6c5ce7;
        background: rgba(108, 92, 231, 0.1);
    }

    .price-calculator {
        background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        color: white;
        padding: 1.25rem;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .calc-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .calc-total {
        font-size: 1.3rem;
        font-weight: 700;
        border-top: 1px solid rgba(255, 255, 255, 0.3);
        padding-top: 0.75rem;
        margin-top: 0.75rem;
    }

    .btn-submit {
        width: 100%;
        padding: 0.75rem;
        background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 0.75rem;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #5f4ec7 0%, #9080d8 100%);
        transform: scale(1.02);
    }

    .btn-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .helper-text {
        font-size: 0.8rem;
        color: #666;
        margin-top: 0.3rem;
    }

    .warning-box {
        background: #fff3cd;
        border-left: 3px solid #ffc107;
        padding: 0.75rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        color: #856404;
    }

    .loading-spinner {
        display: none;
        text-align: center;
    }

    .loading-spinner.show {
        display: block;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .service-detail-header {
            flex-direction: column;
            gap: 1rem;
        }

        .service-info h1 {
            font-size: 1.3rem;
        }

        .service-meta {
            flex-direction: column;
            gap: 0.75rem;
        }

        .emotion-selector {
            grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
            gap: 0.5rem;
        }

        .emotion-btn {
            font-size: 1.5rem;
            padding: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<main style="margin-top: 100px; margin-bottom: 2rem;">
    <div class="service-detail-hero">
        <div class="container">
            <div class="service-detail-header">
                <div class="service-icon-large">
                    <i class="{{ $buffService->getIcon() }}"></i>
                </div>
                <div class="service-info">
                    <h1>{{ $buffService->name }}</h1>
                    <p class="text-muted" style="margin-bottom: 0.25rem; font-size: 0.9rem;">{{ $buffService->description ?? '' }}</p>
                    <div class="service-meta">
                        <div class="meta-item">
                            <span class="meta-label">Nền tảng</span>
                            <span class="meta-value">{{ ucfirst($buffService->platform) }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Loại</span>
                            <span class="meta-value">{{ ucfirst($buffService->service_type) }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Giá cơ bản</span>
                            <span class="meta-value">{{ number_format($buffService->base_price, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <form id="buffOrderForm" method="POST" action="{{ route('buff.store') }}">
                    @csrf

                    <!-- Server Selection -->
                    <div class="form-section">
                        <h3>1️⃣ Chọn Server</h3>
                        <div class="form-group">
                            @if($servers->isEmpty())
                                <div class="alert alert-warning">
                                    Hiện không có server khả dụng. Vui lòng quay lại sau!
                                </div>
                            @else
                                <select name="server_id" id="serverId" class="form-control" required>
                                    <option value="">-- Chọn server --</option>
                                    @foreach($servers as $server)
                                        <option value="{{ $server->id }}" 
                                            @selected(old('server_id') == $server->id)>
                                            {{ $server->name }}
                                            @if($server->description)
                                                - {{ $server->description }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="helper-text">
                                    💡 Mỗi server sẽ có giá khác nhau. Hãy chọn server có giá tốt nhất cho bạn.
                                </div>
                            @endif
                        </div>
                        @error('server_id')
                            <div class="alert alert-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Social Link -->
                    <div class="form-section">
                        <h3>2️⃣ Nhập Link {{ ucfirst($buffService->platform) }}</h3>
                        <div class="form-group">
                            <label class="form-label">
                                Link
                                <span class="required">*</span>
                            </label>
                            <input type="text" name="social_link" id="socialLink" class="form-control"
                                placeholder="Dán link profile, video, hoặc bài viết của bạn"
                                value="{{ old('social_link') }}" required>
                            <div class="helper-text">
                                @if($buffService->platform === 'facebook')
                                    VD: https://www.facebook.com/yourprofile/ hoặc link bài viết
                                @elseif($buffService->platform === 'tiktok')
                                    VD: https://www.tiktok.com/@yourprofile/ hoặc link video
                                @else
                                    VD: https://www.instagram.com/yourprofile/ hoặc link post
                                @endif
                            </div>
                        </div>
                        @error('social_link')
                            <div class="alert alert-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div class="form-section">
                        <h3>3️⃣ Số Lượng</h3>
                        <div class="form-group">
                            <label class="form-label">
                                Số lượng {{ strtolower($buffService->service_type) }}
                                <span class="required">*</span>
                            </label>
                            <input type="number" name="quantity" id="quantity" class="form-control"
                                min="{{ $buffService->min_amount }}"
                                max="{{ $buffService->max_amount }}"
                                value="{{ old('quantity', $buffService->min_amount) }}" required>
                            <div class="helper-text">
                                Tối thiểu {{ number_format($buffService->min_amount) }} - Tối đa {{ number_format($buffService->max_amount) }}
                            </div>
                        </div>
                        @error('quantity')
                            <div class="alert alert-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Emotion Type (for comments) -->
                    @if($buffService->service_type === 'comment')
                        <div class="form-section">
                            <h3>😊 Chọn Loại Cảm Xúc</h3>
                            <div class="emotion-selector">
                                <button type="button" class="emotion-btn" data-emotion="like" title="Like">
                                    👍
                                </button>
                                <button type="button" class="emotion-btn" data-emotion="love" title="Love">
                                    ❤️
                                </button>
                                <button type="button" class="emotion-btn" data-emotion="haha" title="Haha">
                                    😂
                                </button>
                                <button type="button" class="emotion-btn" data-emotion="wow" title="Wow">
                                    😮
                                </button>
                                <button type="button" class="emotion-btn" data-emotion="sad" title="Sad">
                                    😢
                                </button>
                                <button type="button" class="emotion-btn" data-emotion="angry" title="Angry">
                                    😠
                                </button>
                            </div>
                            <input type="hidden" name="emotion_type" id="emotionType" value="">
                            <div class="helper-text">Chọn loại cảm xúc bạn muốn tăng</div>
                        </div>
                    @endif

                    <!-- Notes -->
                    <div class="form-section">
                        <h3>📝 Ghi Chú (Tùy Chọn)</h3>
                        <div class="form-group">
                            <label class="form-label">Ghi chú thêm</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"
                                placeholder="Ghi chú gì đó nếu cần..."
                                maxlength="1000">{{ old('notes') }}</textarea>
                            <div class="helper-text">Tối đa 1000 ký tự</div>
                        </div>
                    </div>

                    <input type="hidden" name="service_id" value="{{ $buffService->id }}">

                    <!-- Price Calculator -->
                    <div class="price-calculator">
                        <div class="calc-row">
                            <span>Giá cơ bản:</span>
                            <span id="calcBase">{{ number_format($buffService->base_price, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="calc-row">
                            <span id="calcQtyLabel">Số lượng:</span>
                            <span id="calcQty">0đ</span>
                        </div>
                        <div class="calc-total">
                            <div class="calc-row" style="border: none; margin: 0; font-size: 1.3rem;">
                                <span>Tổng cộng:</span>
                                <span id="calcTotal">{{ number_format($buffService->base_price + ($buffService->price_per_unit * $buffService->min_amount), 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Tạo Đơn Hàng →
                    </button>
                </form>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <div class="form-section">
                    <h4 style="font-size: 1rem; margin-bottom: 0.75rem;">📌 Lưu Ý Quan Trọng</h4>
                    <ul class="mb-0" style="padding-left: 1.5rem; font-size: 0.9rem;">
                        <li>✓ Hỗ trợ tài khoản Public/Private</li>
                        <li>✓ An toàn 100% - Không cần mật khẩu</li>
                        <li>✓ Tốc độ buff: 1-24h hoàn thành</li>
                        <li>✓ Hoàn tiền nếu gặp lỗi</li>
                        <li>✓ Hỗ trợ 24/7 qua chat</li>
                    </ul>
                </div>

                <div class="form-section">
                    <h4 style="font-size: 1rem; margin-bottom: 0.75rem;">⚡ Thông Tin Thêm</h4>
                    <p style="margin-bottom: 0.35rem; font-size: 0.9rem;">
                        <strong>Tốc độ:</strong> Tuỳ server
                    </p>
                    <p style="margin-bottom: 0.35rem; font-size: 0.9rem;">
                        <strong>Bảo hành:</strong> 30 ngày giữ nguyên số liệu
                    </p>
                    <p style="margin-bottom: 0; font-size: 0.9rem;">
                        <strong>Hỗ trợ:</strong> Chat & Email 24/7
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceBasePrice = {{ $buffService->base_price }};
    const serverId = document.getElementById('serverId');
    const quantity = document.getElementById('quantity');
    const emotionBtns = document.querySelectorAll('.emotion-btn');
    const emotionType = document.getElementById('emotionType');

    // Emotion selector
    emotionBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            emotionBtns.forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            emotionType.value = this.dataset.emotion;
        });
    });

    // Calculate price
    function updatePrice() {
        const qtyValue = parseInt(quantity.value) || {{ $buffService->min_amount }};
        const serverIdValue = serverId.value;

        if (!serverIdValue) {
            document.getElementById('calcQty').textContent = '0đ';
            document.getElementById('calcTotal').textContent = 
                new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(0);
            return;
        }

        fetch('{{ route("buff.calculate-price") }}?service_id={{ $buffService->id }}&server_id=' + serverIdValue + '&quantity=' + qtyValue)
            .then(res => res.json())
            .then(data => {
                document.getElementById('calcQty').textContent = 
                    new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(data.quantity * data.unit_price);
                document.getElementById('calcTotal').textContent = 
                    new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(data.total_price);
                document.getElementById('calcQtyLabel').textContent = 
                    qtyValue + ' x ' + new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(data.unit_price) + ':';
            });
    }

    serverId.addEventListener('change', updatePrice);
    quantity.addEventListener('change', updatePrice);
    quantity.addEventListener('input', updatePrice);

    // Initial calculation
    updatePrice();

    // Form validation
    document.getElementById('buffOrderForm').addEventListener('submit', function(e) {
        const socialLink = document.getElementById('socialLink').value;
        const serverId = document.getElementById('serverId').value;

        if (!serverId) {
            e.preventDefault();
            alert('Vui lòng chọn server!');
            return false;
        }

        if (!socialLink) {
            e.preventDefault();
            alert('Vui lòng nhập link!');
            return false;
        }

        @if($buffService->service_type === 'comment')
        if (!document.getElementById('emotionType').value) {
            e.preventDefault();
            alert('Vui lòng chọn loại cảm xúc!');
            return false;
        }
        @endif
    });
});
</script>
@endpush
@endsection
