@extends('layouts.app')

@section('title', $buffService->name . ' - DungThu.com')

@push('styles')
    <style>
        :root {
            --buff-primary: #4F46E5;
            --buff-gradient: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            --buff-bg: #F8FAFC;
            --buff-border: #E2E8F0;
            --buff-text: #1E293B;
            --buff-muted: #64748B;
        }

        body {
            background-color: var(--buff-bg);
        }

        .service-detail-hero {
            background: white;
            border-bottom: 1px solid var(--buff-border);
            padding: 30px 0;
            margin-bottom: 30px;
            margin-top: 70px;
        }

        .service-detail-header {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .service-icon-large {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: #f1f5f9;
            color: var(--buff-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            flex-shrink: 0;
        }

        .service-info h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--buff-text);
            margin-bottom: 8px;
        }

        .service-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin-top: 12px;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
        }

        .meta-label {
            color: var(--buff-muted);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .meta-value {
            font-weight: 700;
            color: var(--buff-primary);
            font-size: 1.1rem;
        }

        .form-section {
            background: white;
            border: 1px solid var(--buff-border);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .form-section h3 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--buff-text);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .step-badge {
            background: var(--buff-primary);
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 700;
            color: var(--buff-text);
            margin-bottom: 8px;
            display: block;
            font-size: 0.95rem;
        }

        .form-label .required {
            color: #ef4444;
        }

        .form-control,
        .form-select {
            border: 2px solid var(--buff-border);
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: #f8fafc;
            width: 100%;
            max-width: 100%;
        }

        select.form-control {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding-right: 30px;
        }

        select.form-control option {
            max-width: 100%;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--buff-primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        .emotion-selector {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .emotion-btn {
            border: 2px solid var(--buff-border);
            background: white;
            width: 60px;
            height: 60px;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .emotion-btn:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }

        .emotion-btn.selected {
            border-color: var(--buff-primary);
            background: rgba(79, 70, 229, 0.05);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
        }

        .price-calculator {
            background: var(--buff-gradient);
            color: white;
            padding: 24px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.2);
        }

        .calc-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 1rem;
            opacity: 0.9;
        }

        .calc-total {
            font-size: 1.5rem;
            font-weight: 800;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 16px;
            margin-top: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: var(--buff-text);
            color: white;
            border: none;
            border-radius: 14px;
            font-weight: 800;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover:not(:disabled) {
            background: black;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-submit:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
        }

        .helper-text {
            font-size: 0.85rem;
            color: var(--buff-muted);
            margin-top: 6px;
            display: flex;
            align-items: flex-start;
            gap: 6px;
        }

        .sidebar-widget {
            background: white;
            border: 1px solid var(--buff-border);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .sidebar-widget h4 {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--buff-text);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
            color: #475569;
            font-size: 0.95rem;
        }

        .sidebar-list li i {
            color: #10b981;
            margin-top: 4px;
        }

        @media (max-width: 768px) {
            .service-detail-header {
                flex-direction: column;
                text-align: center;
            }

            .service-meta {
                justify-content: center;
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
                        <p class="text-muted" style="margin-bottom: 0.25rem; font-size: 0.9rem;">
                            {{ $buffService->description ?? '' }}</p>
                        <div class="service-meta">
                            <div class="meta-item">
                                <span class="meta-label">Nền tảng</span>
                                <span class="meta-value">{{ ucfirst($buffService->platform) }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Loại</span>
                                <span class="meta-value">{{ ucfirst($buffService->service_type) }}</span>
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
                            <h3><span class="step-badge">1</span> Chọn Server</h3>
                            <div class="form-group">
                                @if($servers->isEmpty())
                                    <div class="alert alert-warning">
                                        Hiện không có server khả dụng. Vui lòng quay lại sau!
                                    </div>
                                @else
                                    <select name="server_id" id="serverId" class="form-control" required>
                                        <option value="">-- Chọn server --</option>
                                        @foreach($servers as $server)
                                            <option value="{{ $server->id }}" @selected(old('server_id') == $server->id)
                                                data-fulltext="{{ $server->name }}{{ $server->description ? ' - ' . $server->description : '' }}"
                                                title="{{ $server->name }}{{ $server->description ? ' - ' . $server->description : '' }}">
                                                {{ $server->name }}{{ $server->description ? ' - ' . $server->description : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="selectedServerInfo" class="mt-2 text-primary fw-bold" style="font-size: 0.85rem;">
                                    </div>
                                    <div class="helper-text mt-1">
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
                            <h3><span class="step-badge">2</span> Nhập Link {{ ucfirst($buffService->platform) }}</h3>
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
                            <h3><span class="step-badge">3</span> Số Lượng</h3>
                            <div class="form-group">
                                <label class="form-label">
                                    Số lượng {{ strtolower($buffService->service_type) }}
                                    <span class="required">*</span>
                                </label>
                                <input type="number" name="quantity" id="quantity" class="form-control"
                                    min="{{ $buffService->min_amount }}" max="{{ $buffService->max_amount }}"
                                    value="{{ old('quantity', $buffService->min_amount) }}" required>
                                <div class="helper-text">
                                    Tối thiểu {{ number_format($buffService->min_amount) }} - Tối đa
                                    {{ number_format($buffService->max_amount) }}
                                </div>
                            </div>
                            @error('quantity')
                                <div class="alert alert-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Emotion Type (for comments) -->
                        @if($buffService->service_type === 'comment')
                            <div class="form-section">
                                <h3><span class="step-badge"><i class="fas fa-smile"></i></span> Chọn Loại Cảm Xúc</h3>
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
                            <h3><span class="step-badge"><i class="fas fa-pen"></i></span> Ghi Chú (Tùy Chọn)</h3>
                            <div class="form-group">
                                <label class="form-label">Ghi chú thêm</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3"
                                    placeholder="Ghi chú gì đó nếu cần..." maxlength="1000">{{ old('notes') }}</textarea>
                                <div class="helper-text"><i class="fas fa-info-circle"></i> Tối đa 1000 ký tự</div>
                            </div>
                        </div>

                        <input type="hidden" name="service_id" value="{{ $buffService->id }}">

                        <!-- Price Calculator -->
                        <div class="price-calculator">
                            <div class="calc-row">
                                <span id="calcQtyLabel">Số lượng:</span>
                                <span id="calcQty">0đ</span>
                            </div>
                            <div class="calc-total">
                                <span>Tổng thanh toán:</span>
                                <span
                                    id="calcTotal">{{ number_format(($buffService->price_per_unit * $buffService->min_amount), 0, ',', '.') }}đ</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-rocket"></i> Tạo Đơn Hàng Ngay
                        </button>
                    </form>
                </div>

                <!-- Sidebar Info -->
                <div class="col-lg-4">
                    <div class="sidebar-widget">
                        <h4><i class="fas fa-thumbtack text-primary"></i> Lưu Ý Quan Trọng</h4>
                        <ul class="sidebar-list">
                            <li><i class="fas fa-check-circle"></i> Hỗ trợ tài khoản Public/Private</li>
                            <li><i class="fas fa-check-circle"></i> An toàn 100% - Không cần mật khẩu</li>
                            <li><i class="fas fa-check-circle"></i> Tốc độ buff: 1-24h hoàn thành</li>
                            <li><i class="fas fa-check-circle"></i> Hoàn tiền nếu gặp lỗi</li>
                            <li><i class="fas fa-check-circle"></i> Hỗ trợ 24/7 qua chat</li>
                        </ul>
                    </div>

                    <div class="sidebar-widget">
                        <h4><i class="fas fa-info-circle text-info"></i> Thông Tin Thêm</h4>
                        <ul class="sidebar-list">
                            <li><strong>Tốc độ:</strong> Tuỳ server</li>
                            <li><strong>Bảo hành:</strong> 10 ngày giữ nguyên số liệu</li>
                            <li><strong>Hỗ trợ:</strong> Chat & Email 24/7</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const serverId = document.getElementById('serverId');
                const quantity = document.getElementById('quantity');
                const emotionBtns = document.querySelectorAll('.emotion-btn');
                const emotionType = document.getElementById('emotionType');

                // Emotion selector
                emotionBtns.forEach(btn => {
                    btn.addEventListener('click', function (e) {
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
                            new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(0);
                        return;
                    }

                    fetch('{{ route("buff.calculate-price") }}?service_id={{ $buffService->id }}&server_id=' + serverIdValue + '&quantity=' + qtyValue)
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('calcQty').textContent =
                                new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(data.quantity * data.unit_price);
                            document.getElementById('calcTotal').textContent =
                                new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(data.total_price);
                            document.getElementById('calcQtyLabel').textContent =
                                qtyValue + ' x ' + new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(data.unit_price) + ':';
                        });
                }

                serverId.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const infoDiv = document.getElementById('selectedServerInfo');

                    if (selectedOption && selectedOption.value) {
                        infoDiv.textContent = selectedOption.getAttribute('data-fulltext');
                        infoDiv.style.display = 'block';
                    } else {
                        infoDiv.textContent = '';
                        infoDiv.style.display = 'none';
                    }

                    updatePrice();
                });

                quantity.addEventListener('change', updatePrice);
                quantity.addEventListener('input', updatePrice);

                // Initial load
                if (serverId.value) {
                    const selectedOption = serverId.options[serverId.selectedIndex];
                    const infoDiv = document.getElementById('selectedServerInfo');
                    if (selectedOption && selectedOption.value) {
                        infoDiv.textContent = selectedOption.getAttribute('data-fulltext');
                        infoDiv.style.display = 'block';
                    }
                }
                updatePrice();

                // Form validation
                document.getElementById('buffOrderForm').addEventListener('submit', function (e) {
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