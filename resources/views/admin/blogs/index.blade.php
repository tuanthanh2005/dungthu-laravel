@extends('layouts.admin')

@section('title', 'Quản Lý Blog & Auto AI Bot')

@section('page_title', 'Quản Lý Blog')

@push('styles')
    <style>
        .admin-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
        }

        /* ── Auto AI Hero Card ── */
        .auto-blog-card {
            background: linear-gradient(135deg, #0b0f19 0%, #1e1b4b 45%, #2e1065 100%);
            border-radius: 22px;
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 20px 45px -10px rgba(76, 29, 149, 0.35);
            margin-bottom: 30px;
        }

        .auto-blog-card::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .robot-glow-icon {
            width: 58px;
            height: 58px;
            background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: white;
            box-shadow: 0 0 25px rgba(236, 72, 153, 0.45);
            flex-shrink: 0;
            animation: pulse-robot 3s infinite ease-in-out;
        }

        @keyframes pulse-robot {
            0%, 100% { transform: scale(1); box-shadow: 0 0 20px rgba(236, 72, 153, 0.4); }
            50% { transform: scale(1.05); box-shadow: 0 0 35px rgba(168, 85, 247, 0.7); }
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-pill.active {
            background: rgba(34, 197, 94, 0.18);
            color: #4ade80;
            border: 1px solid rgba(74, 222, 128, 0.35);
        }

        .status-pill.paused {
            background: rgba(234, 179, 8, 0.18);
            color: #facc15;
            border: 1px solid rgba(250, 204, 21, 0.35);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-pill.active .status-dot {
            background: #4ade80;
            box-shadow: 0 0 10px #4ade80;
            animation: blink 1.6s infinite;
        }

        .status-pill.paused .status-dot {
            background: #facc15;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.35; }
        }

        .stat-glass-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 18px 20px;
            transition: all 0.3s;
        }

        .stat-glass-card:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .stat-glass-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #cbd5e1;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .stat-glass-val {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
        }

        .btn-trigger-auto {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 50%, #f43f5e 100%);
            border: none;
            color: white;
            font-weight: 700;
            padding: 14px 28px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(244, 63, 94, 0.35);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-trigger-auto:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 14px 30px rgba(244, 63, 94, 0.5);
            color: white;
        }

        .btn-trigger-auto:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none !important;
        }

        /* ── Table & Filter Tabs ── */
        .blog-table {
            width: 100%;
        }

        .blog-table th {
            background: #f8fafc;
            padding: 15px;
            font-weight: 600;
            color: #334155;
            border-bottom: 2px solid #e2e8f0;
        }

        .blog-table td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .blog-table tr:hover {
            background: #f8fafc;
        }

        .blog-img {
            width: 85px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 22px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 9px 18px;
            border-radius: 20px;
            border: 2px solid #e2e8f0;
            background: white;
            color: #475569;
            text-decoration: none;
            transition: all 0.25s;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }

        .filter-tab:hover {
            background: #f8fafc;
            border-color: #7c3aed;
            color: #7c3aed;
        }

        .filter-tab.active {
            background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 14px rgba(124, 58, 237, 0.3);
        }

        .badge-auto-ai {
            background: linear-gradient(135deg, #ede9fe 0%, #f3e8ff 100%);
            color: #6b21a8;
            border: 1px solid #d8b4fe;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-angle {
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- ======================================================== --}}
    {{--  🤖 HERO CONTROL CARD: HỆ THỐNG AUTO BLOG AI (GEMINI)    --}}
    {{-- ======================================================== --}}
    @php
        $autoStatus = $autoBlogStatus ?? app(\App\Services\AutoBlogService::class)->getQueueStatus();
        $isEnabled = $autoStatus['is_enabled'];
        $nextProduct = $autoStatus['next_product'];
        $lastProduct = $autoStatus['last_product'];
        $totalProducts = max(1, $autoStatus['total_products']);
        $currentPos = $autoStatus['current_position'];
        $progressPercent = min(100, round(($currentPos / $totalProducts) * 100));
        $cronToken = \App\Models\SiteSetting::getValue('auto_blog_cron_token') ?: 'dungthu_auto_' . substr(md5(config('app.key')), 0, 12);
        $webCronUrl = url('/cron/auto-blog?token=' . $cronToken);
    @endphp

    <div class="auto-blog-card">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="robot-glow-icon">
                    <i class="fas fa-robot"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h4 class="fw-bold mb-0 text-white">
                            Hệ Thống Viết Blog Tự Động (Auto AI Gemini Bot)
                        </h4>
                        <span class="status-pill {{ $isEnabled ? 'active' : 'paused' }}" id="autoBotStatusPill">
                            <span class="status-dot"></span>
                            <span id="autoBotStatusText">{{ $isEnabled ? 'ĐANG CHẠY TỰ ĐỘNG (15P / BÀI)' : 'ĐANG TẠM DỪNG' }}</span>
                        </span>
                    </div>
                    <small class="text-white-50 d-block mt-1">
                        Tự động viết bài quảng bá sản phẩm giá rẻ • Xoay vòng từ SP 1 đến SP cuối • Tự động tạo ảnh phù hợp & tự Index Google
                    </small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Nút bật/tắt tự động nhanh -->
                <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold" id="btnToggleAutoState" onclick="toggleAutoBlogState({{ $isEnabled ? 0 : 1 }})">
                    <i class="fas {{ $isEnabled ? 'fa-pause' : 'fa-play' }} me-1"></i>
                    <span id="btnToggleAutoText">{{ $isEnabled ? 'Tạm Dừng Tự Động' : 'Bật Tự Động' }}</span>
                </button>

                <!-- Nút mở rộng cài đặt -->
                <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#autoBlogSettingsCollapse">
                    <i class="fas fa-cog me-1"></i> Cài Đặt AI
                </button>
            </div>
        </div>

        <!-- 4 Thẻ chỉ số trạng thái hàng đợi -->
        <div class="row g-3 mb-4">
            <!-- Thẻ 1: Chu kỳ & Đã tạo -->
            <div class="col-md-3 col-6">
                <div class="stat-glass-card">
                    <div class="stat-glass-label"><i class="fas fa-sync-alt me-1 text-info"></i> Chu kỳ xoay vòng</div>
                    <div class="stat-glass-val text-white" id="statCycleCount">
                        Vòng #{{ $autoStatus['cycle_count'] + 1 }}
                    </div>
                    <small class="text-white-50" id="statTotalGenerated">Đã tạo: <strong>{{ $autoStatus['total_generated'] }}</strong> bài</small>
                </div>
            </div>

            <!-- Thẻ 2: Tiến độ sản phẩm -->
            <div class="col-md-3 col-6">
                <div class="stat-glass-card">
                    <div class="stat-glass-label"><i class="fas fa-list-ol me-1 text-warning"></i> Tiến độ sản phẩm</div>
                    <div class="stat-glass-val text-white" id="statQueuePosition">
                        SP #{{ $currentPos }} <span class="fs-6 text-white-50">/ {{ $totalProducts }}</span>
                    </div>
                    <div class="progress mt-2" style="height: 6px; background: rgba(255,255,255,0.15);">
                        <div class="progress-bar bg-warning" id="statProgressBar" role="progressbar" style="width: {{ $progressPercent }}%;"></div>
                    </div>
                </div>
            </div>

            <!-- Thẻ 3: Sản phẩm kế tiếp sẽ viết -->
            <div class="col-md-3 col-6">
                <div class="stat-glass-card">
                    <div class="stat-glass-label"><i class="fas fa-bullseye me-1 text-danger"></i> SP tiếp theo sẽ viết</div>
                    <div class="stat-glass-val text-truncate text-warning" id="statNextProductName" title="{{ $nextProduct?->name ?? 'Hết sản phẩm' }}">
                        {{ $nextProduct ? Str::limit($nextProduct->name, 22) : 'Chưa có SP' }}
                    </div>
                    <small class="text-white-50" id="statNextProductPrice">
                        @if($nextProduct)
                            <strong class="text-warning">{{ $autoStatus['next_product_price_formatted'] ?? (number_format($nextProduct->effective_price, 0, ',', '.') . 'đ') }}</strong>
                            @if(!empty($autoStatus['next_product_original_price_formatted']) && !empty($autoStatus['next_product_is_on_sale']))
                                <del class="text-white-50 ms-1" style="font-size: 11px;">{{ $autoStatus['next_product_original_price_formatted'] }}</del>
                                <span class="badge bg-danger text-white rounded-pill ms-1 px-1 py-0" style="font-size: 9px;">-{{ $autoStatus['next_product_save_percent'] }}%</span>
                            @endif
                        @else
                            --
                        @endif
                    </small>
                </div>
            </div>

            <!-- Thẻ 4: Lần chạy gần nhất -->
            <div class="col-md-3 col-6">
                <div class="stat-glass-card position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-glass-label"><i class="fas fa-clock me-1 text-success"></i> Chạy gần nhất</div>
                        <button type="button" class="btn btn-sm btn-outline-light border-0 py-0 px-2 text-white-50" title="Đặt lại mốc thời gian về Vừa xong" onclick="resetLastRunTime()" style="font-size: 11px; border-radius: 6px; background: rgba(255,255,255,0.08);">
                            <i class="fas fa-sync-alt me-1"></i>Đặt lại
                        </button>
                    </div>
                    <div class="stat-glass-val text-white" id="statLastRunAt">
                        {{ $autoStatus['last_run_at'] ? $autoStatus['last_run_at']->diffForHumans() : 'Chưa chạy' }}
                    </div>
                    <small class="text-white-50">Chu kỳ tự động: <strong>15 phút/lần</strong></small>
                </div>
            </div>
        </div>

        <!-- Khung Kích Hoạt Viết Ngay & Trạng thái Live -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pt-2 border-top border-white border-opacity-10">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn-trigger-auto" id="btnTriggerAutoBlog" onclick="triggerAutoBlogRunNow()">
                    <i class="fas fa-bolt"></i>
                    <span>⚡ VIẾT NGAY 1 BÀI CHO SẢN PHẨM TIẾP THEO</span>
                </button>
                <small class="text-white-50 ms-2">
                    <i class="fas fa-info-circle me-1"></i>Hệ thống tự động kích hoạt mỗi 15 phút. Bạn cũng có thể bấm nút trên để AI viết ngay 1 bài mà không cần chờ.
                </small>
            </div>

            <div class="d-flex align-items-center gap-2">
                @if($autoStatus['has_api_key'])
                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-3 py-2 rounded-pill">
                        <i class="fas fa-check-circle me-1"></i>Gemini Key: {{ $autoStatus['masked_key'] }}
                    </span>
                @else
                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 px-3 py-2 rounded-pill">
                        <i class="fas fa-exclamation-triangle me-1"></i>Chưa cài Gemini Key!
                    </span>
                @endif
            </div>
        </div>

        <!-- Box Loading real-time khi bấm Viết bài -->
        <div id="autoBlogProgressBox" class="d-none mt-3 p-3 rounded-3 text-center" style="background: rgba(255, 255, 255, 0.08); border: 1px dashed rgba(255,255,255,0.25);">
            <div class="spinner-border text-warning me-2" role="status" style="width: 1.6rem; height: 1.6rem;"></div>
            <strong class="text-white" id="autoBlogProgressText">
                AI Gemini đang phân tích sản phẩm, viết bài SEO chuẩn giá rẻ và tạo ảnh bìa...
            </strong>
            <p class="text-white-50 small mb-0 mt-1">Quá trình này mất khoảng 8 - 15 giây. Vui lòng đợi trong giây lát.</p>
        </div>

        <!-- Box thông báo kết quả bài viết vừa sinh ra thành công -->
        <div id="autoBlogSuccessAlert" class="d-none mt-3 p-3 rounded-3" style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(74, 222, 128, 0.4);">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div class="d-flex gap-3">
                    <div id="newBlogImagePreview" style="width: 70px; height: 50px; border-radius: 8px; background: #334155; overflow: hidden; flex-shrink: 0;"></div>
                    <div>
                        <div class="badge bg-success text-white mb-1"><i class="fas fa-check-circle me-1"></i> Xuất bản thành công & Đã gửi Index Google!</div>
                        <h6 class="fw-bold text-white mb-1" id="newBlogTitleText"></h6>
                        <small class="text-white-50" id="newBlogExcerptText"></small>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-shrink-0">
                    <a href="#" target="_blank" id="btnViewNewBlog" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">
                        <i class="fas fa-external-link-alt me-1"></i> Xem Bài Viết
                    </a>
                    <a href="#" target="_blank" id="btnEditNewBlog" class="btn btn-sm btn-outline-light rounded-pill px-3">
                        <i class="fas fa-edit me-1"></i> Sửa
                    </a>
                </div>
            </div>
        </div>

        {{-- Collapsible: Cấu hình nhanh & Hướng dẫn Cron --}}
        <div class="collapse mt-4 pt-3 border-top border-white border-opacity-10" id="autoBlogSettingsCollapse">
            <div class="p-3 rounded-3" style="background: rgba(0, 0, 0, 0.35); border: 1px solid rgba(255,255,255,0.1);">
                <h6 class="fw-bold text-warning mb-3">
                    <i class="fas fa-sliders-h me-1"></i> Cấu Hình Nhanh Auto Blog AI:
                </h6>

                <div class="row g-3">
                    <!-- API Key -->
                    <div class="col-md-5">
                        <label class="form-label text-white small fw-bold">Gemini API Key:</label>
                        <div class="input-group input-group-sm">
                            <input type="password" id="inputAutoGeminiKey" class="form-control" 
                                   placeholder="AIzaSy..." 
                                   value="{{ \App\Models\SiteSetting::getValue('gemini_api_key', '') }}">
                            <button class="btn btn-warning fw-bold px-3" type="button" onclick="saveAutoBlogKey()">
                                <i class="fas fa-save me-1"></i> Lưu Key
                            </button>
                        </div>
                        <small class="text-white-50">Dùng chung cho cả trợ lý viết tay và tự động.</small>
                    </div>

                    <!-- Model -->
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Model Gemini:</label>
                        <select id="selectAutoBlogModel" class="form-select form-select-sm" onchange="saveAutoBlogModel(this.value)">
                            <option value="gemini-3.8-flash" {{ in_array(($autoStatus['model'] ?? ''), ['gemini-3.8-flash', 'gemini-3.8', '3.8'], true) ? 'selected' : '' }}>Gemini 3.8 Flash (Mới nhất 2026 & Thông minh 👑)</option>
                            <option value="gemini-3.1-flash-lite" {{ in_array(($autoStatus['model'] ?? ''), ['gemini-3.1-flash-lite', 'gemini-3.1', '3.1'], true) ? 'selected' : '' }}>Gemini 3.1 Flash-Lite (Tốc độ cao 🚀)</option>
                            <option value="gemini-3.5-flash" {{ in_array(($autoStatus['model'] ?? ''), ['gemini-3.5-flash', 'gemini-3.5', '3.5'], true) ? 'selected' : '' }}>Gemini 3.5 Flash</option>
                        </select>
                        <small class="text-white-50">Tự động chuyển đổi sang model dự phòng (3.8 / 3.1 / 2.5) nếu bận.</small>
                    </div>

                    <!-- Reset Con Trỏ SP -->
                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Bắt đầu lại từ SP:</label>
                        <div class="input-group input-group-sm">
                            <input type="number" id="inputResetProductId" class="form-control" placeholder="ID (0 = từ đầu)" value="0">
                            <button class="btn btn-outline-light px-2" type="button" onclick="resetAutoBlogPointer()">
                                Reset
                            </button>
                        </div>
                        <small class="text-white-50">Nhập 0 để bắt đầu lại từ SP #1.</small>
                    </div>
                </div>

                <!-- Đồng Bộ Khung Giá & Ảnh Cho Toàn Bộ Bài Viết Đã Tạo -->
                <div class="mt-3 pt-3 border-top border-white border-opacity-10 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <span class="text-white fw-bold small"><i class="fas fa-tags text-warning me-1"></i> Đồng bộ giá giảm & Banner logo chuẩn đẹp:</span>
                        <small class="text-white-50 d-block">Cập nhật lại giá ưu đãi và thay toàn bộ ảnh bài viết bằng banner logo thương hiệu sang xịn mịn (như tấm Zoom Pro / ExpressVPN).</small>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-bold" onclick="refreshAutoBlogPrices()" id="btnRefreshPrices">
                            <i class="fas fa-sync-alt me-1"></i> Cập Nhật Lại Giá Giảm
                        </button>
                        <button type="button" class="btn btn-sm btn-warning rounded-pill px-3 fw-bold text-dark shadow-sm" onclick="refreshAutoBlogImages()" id="btnRefreshImages">
                            <i class="fas fa-image me-1"></i> Tạo Lại Banner Logo Đẹp Cho Tất Cả Bài
                        </button>
                    </div>
                </div>

                <!-- Webhook Cron URL -->
                <div class="mt-3 pt-3 border-top border-white border-opacity-10">
                    <label class="form-label text-white small fw-bold">
                        <i class="fas fa-network-wired text-info me-1"></i> URL Webhook Cron (Dành cho cPanel / UptimeRobot / Cron-Job.org gọi mỗi 15 phút):
                    </label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control bg-dark text-white border-secondary font-monospace" id="inputWebCronUrl" value="{{ $webCronUrl }}" readonly>
                        <button class="btn btn-outline-info" type="button" onclick="copyWebCronUrl()">
                            <i class="fas fa-copy me-1"></i> Copy URL
                        </button>
                    </div>
                    <small class="text-white-50 d-block mt-1">
                        Cấu hình UptimeRobot hoặc EasyCron ping URL này mỗi 15 phút để đảm bảo server luôn chạy đúng giờ kể cả khi không có terminal SSH daemon.
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{--  📋 BẢNG DANH SÁCH BÀI VIẾT                              --}}
    {{-- ======================================================== --}}
    <div class="admin-card">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-0">
                    <i class="fas fa-blog text-primary me-2"></i>Danh Sách Bài Viết Blog
                </h3>
                <small class="text-muted">Quản lý toàn bộ bài viết trên website và theo dõi bài viết do AI tự động tạo</small>
            </div>
            <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i>Thêm Blog Thủ Công
            </a>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="{{ route('admin.blogs') }}"
                class="filter-tab {{ (!request('category') || request('category') == 'all') && !request('auto') ? 'active' : '' }}">
                <i class="fas fa-list me-2"></i>Tất cả ({{ \App\Models\Blog::count() }})
            </a>
            <a href="{{ route('admin.blogs', ['auto' => 1]) }}"
                class="filter-tab {{ request('auto') == '1' ? 'active' : '' }}" style="border-color: #c084fc;">
                <i class="fas fa-robot text-purple me-2"></i>🤖 Bài Viết Tự Động AI ({{ $autoBlogsCount ?? 0 }})
            </a>
            <a href="{{ route('admin.blogs', ['category' => 'tech']) }}"
                class="filter-tab {{ request('category') == 'tech' ? 'active' : '' }}">
                <i class="fas fa-laptop-code me-2"></i>Công nghệ
            </a>
            <a href="{{ route('admin.blogs', ['category' => 'lifestyle']) }}"
                class="filter-tab {{ request('category') == 'lifestyle' ? 'active' : '' }}">
                <i class="fas fa-heart me-2"></i>Lifestyle
            </a>
            <a href="{{ route('admin.blogs', ['category' => 'business']) }}"
                class="filter-tab {{ request('category') == 'business' ? 'active' : '' }}">
                <i class="fas fa-briefcase me-2"></i>Kinh doanh
            </a>
            <a href="{{ route('admin.blogs', ['category' => 'other']) }}"
                class="filter-tab {{ request('category') == 'other' ? 'active' : '' }}">
                <i class="fas fa-ellipsis-h me-2"></i>Khác
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($blogs->count() > 0)
            <div class="table-responsive">
                <table class="blog-table">
                    <thead>
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th style="width: 10%">Hình ảnh</th>
                            <th style="width: 36%">Tiêu đề & Nguồn</th>
                            <th style="width: 12%">Danh mục</th>
                            <th style="width: 8%">Lượt xem</th>
                            <th style="width: 15%">Ngày xuất bản</th>
                            <th style="width: 14%" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="blogTableBody">
                        @foreach($blogs as $blog)
                            <tr id="blogRow-{{ $blog->id }}">
                                <td class="fw-bold text-muted">#{{ $blog->id }}</td>
                                <td>
                                    @if($blog->image)
                                        <img src="{{ $blog->image }}" alt="{{ $blog->title }}" class="blog-img">
                                    @else
                                        <div class="blog-img d-flex align-items-center justify-content-center bg-light text-muted">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        @if($blog->is_auto_generated)
                                            <span class="badge-auto-ai">
                                                <i class="fas fa-robot"></i> Auto AI
                                                @if($blog->product)
                                                    • SP #{{ $blog->product_id }}: {{ Str::limit($blog->product->name, 18) }}
                                                @endif
                                            </span>
                                        @endif
                                        @if($blog->auto_tone)
                                            <span class="badge-angle">
                                                {{ ucfirst(str_replace('_', ' ', $blog->auto_tone)) }}
                                            </span>
                                        @endif
                                        @if($blog->is_featured)
                                            <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>Nổi bật</span>
                                        @endif
                                    </div>
                                    <a href="{{ url('/blog/' . $blog->slug) }}" target="_blank" class="fw-bold text-dark text-decoration-none hover-primary d-block">
                                        {{ Str::limit($blog->title, 60) }}
                                        <i class="fas fa-external-link-alt ms-1 text-muted small" style="font-size: 11px;"></i>
                                    </a>
                                    <small class="text-muted d-block mt-1">{{ Str::limit($blog->excerpt, 80) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 rounded-pill">
                                        {{ ucfirst($blog->category) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="fas fa-eye text-primary me-1"></i>{{ number_format($blog->views) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted d-block">
                                        {{ $blog->published_at ? $blog->published_at->format('d/m/Y H:i') : $blog->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ url('/blog/' . $blog->slug) }}" target="_blank"
                                           class="btn btn-outline-info" title="Xem trên web">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.blogs.edit', $blog) }}"
                                            class="btn btn-outline-primary" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.blogs.delete', $blog) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $blogs->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-blog fa-4x text-muted mb-3 opacity-50"></i>
                <h5 class="text-muted">Chưa có bài viết nào phù hợp</h5>
                <p class="text-muted small">Hãy bấm nút <strong>"⚡ VIẾT NGAY 1 BÀI CHO SẢN PHẨM TIẾP THEO"</strong> ở trên để AI tạo bài ngay lập tức!</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    // 1. Kích hoạt viết bài ngay lập tức
    function triggerAutoBlogRunNow() {
        const btn = document.getElementById('btnTriggerAutoBlog');
        const progressBox = document.getElementById('autoBlogProgressBox');
        const successAlert = document.getElementById('autoBlogSuccessAlert');

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> ĐANG VIẾT BÀI & TẠO ẢNH...';
        progressBox.classList.remove('d-none');
        successAlert.classList.add('d-none');

        fetch('{{ route("admin.blogs.auto.run") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-bolt"></i> <span>⚡ VIẾT NGAY 1 BÀI CHO SẢN PHẨM TIẾP THEO</span>';
            progressBox.classList.add('d-none');

            if (res.success) {
                // Hiển thị thông tin bài viết vừa tạo
                successAlert.classList.remove('d-none');
                document.getElementById('newBlogTitleText').innerText = res.data.blog.title;
                document.getElementById('newBlogExcerptText').innerText = res.data.blog.excerpt;
                document.getElementById('btnViewNewBlog').href = res.data.blog.url;
                document.getElementById('btnEditNewBlog').href = res.data.blog.edit_url;

                if (res.data.blog.image) {
                    document.getElementById('newBlogImagePreview').innerHTML = `<img src="${res.data.blog.image}" class="w-100 h-100" style="object-fit: cover;">`;
                }

                // Cập nhật lại các thẻ thông tin hàng đợi
                if (res.queue) {
                    document.getElementById('statQueuePosition').innerHTML = `SP #${res.queue.current_position} <span class="fs-6 text-white-50">/ ${res.queue.total_products}</span>`;
                    document.getElementById('statTotalGenerated').innerHTML = `Đã tạo: <strong>${res.queue.total_generated}</strong> bài`;
                    document.getElementById('statCycleCount').innerText = `Vòng #${res.queue.cycle_count + 1}`;
                    
                    const percent = Math.min(100, Math.round((res.queue.current_position / res.queue.total_products) * 100));
                    document.getElementById('statProgressBar').style.width = percent + '%';

                    if (res.queue.next_product) {
                        document.getElementById('statNextProductName').innerText = res.queue.next_product.name;
                        document.getElementById('statNextProductPrice').innerText = res.queue.next_product.price;
                    }
                    document.getElementById('statLastRunAt').innerText = 'Vừa xong';
                }

                // Cuộn nhẹ tới kết quả
                successAlert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                alert('Lỗi: ' + (res.message || 'Không thể tạo bài viết'));
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-bolt"></i> <span>⚡ VIẾT NGAY 1 BÀI CHO SẢN PHẨM TIẾP THEO</span>';
            progressBox.classList.add('d-none');
            alert('Lỗi kết nối máy chủ: ' + err.message);
        });
    }

    // 2. Bật / Tắt trạng thái Auto Blog
    function toggleAutoBlogState(newState) {
        const btn = document.getElementById('btnToggleAutoState');
        btn.disabled = true;

        fetch('{{ route("admin.blogs.auto.settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ auto_blog_enabled: newState })
        })
        .then(res => res.json())
        .then(res => {
            btn.disabled = false;
            if (res.success) {
                const isNowEnabled = newState === 1 || newState === '1';
                const pill = document.getElementById('autoBotStatusPill');
                const text = document.getElementById('autoBotStatusText');
                const btnText = document.getElementById('btnToggleAutoText');

                if (isNowEnabled) {
                    pill.className = 'status-pill active';
                    text.innerText = 'ĐANG CHẠY TỰ ĐỘNG (15P / BÀI)';
                    btnText.innerText = 'Tạm Dừng Tự Động';
                    btn.setAttribute('onclick', 'toggleAutoBlogState(0)');
                    btn.innerHTML = '<i class="fas fa-pause me-1"></i> <span id="btnToggleAutoText">Tạm Dừng Tự Động</span>';
                } else {
                    pill.className = 'status-pill paused';
                    text.innerText = 'ĐANG TẠM DỪNG';
                    btnText.innerText = 'Bật Tự Động';
                    btn.setAttribute('onclick', 'toggleAutoBlogState(1)');
                    btn.innerHTML = '<i class="fas fa-play me-1"></i> <span id="btnToggleAutoText">Bật Tự Động</span>';
                }
            } else {
                alert('Không thể lưu trạng thái');
            }
        })
        .catch(err => {
            btn.disabled = false;
            alert('Lỗi: ' + err.message);
        });
    }

    // 3. Lưu Gemini API Key
    function saveAutoBlogKey() {
        const key = document.getElementById('inputAutoGeminiKey').value.trim();
        if (!key) {
            alert('Vui lòng nhập API Key');
            return;
        }

        fetch('{{ route("admin.blogs.auto.settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ api_key: key })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                alert('Đã lưu Gemini API Key thành công!');
                location.reload();
            } else {
                alert('Lỗi: ' + res.message);
            }
        });
    }

    // 4. Lưu Model AI
    function saveAutoBlogModel(modelVal) {
        fetch('{{ route("admin.blogs.auto.settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ auto_blog_model: modelVal })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                // Đã lưu ngầm thành công
            }
        });
    }

    // 5. Reset con trỏ sản phẩm
    function resetAutoBlogPointer() {
        const pid = document.getElementById('inputResetProductId').value;
        if (!confirm(`Bạn có chắc muốn đặt lại con trỏ bắt đầu từ sản phẩm ID ${pid}?`)) {
            return;
        }

        fetch('{{ route("admin.blogs.auto.settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ reset_product_id: pid })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                alert('Đã cập nhật lại con trỏ sản phẩm!');
                location.reload();
            }
        });
    }

    // 6. Copy URL Webhook Cron
    function copyWebCronUrl() {
        const input = document.getElementById('inputWebCronUrl');
        input.select();
        navigator.clipboard.writeText(input.value).then(() => {
            alert('Đã sao chép URL Cron vào Clipboard!\nBạn có thể dán vào UptimeRobot, EasyCron hoặc Cron-Job.org để ping mỗi 15 phút.');
        });
    }

    // 6.2. Đồng bộ giá giảm cho toàn bộ bài viết đã tạo
    function refreshAutoBlogPrices() {
        const btn = document.getElementById('btnRefreshPrices');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang đồng bộ...';
        }

        fetch('{{ route("admin.blogs.auto.refresh_prices") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Cập Nhật Giá Giảm Cho Các Bài Đã Viết';
            }
            alert(res.message || 'Đã đồng bộ giá bài viết thành công!');
            location.reload();
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Cập Nhật Giá Giảm Cho Các Bài Đã Viết';
            }
            alert('Lỗi khi đồng bộ giá bài viết: ' + err.message);
        });
    }

    // 6.3. Tạo lại toàn bộ ảnh banner logo chuẩn đẹp cho bài viết
    function refreshAutoBlogImages() {
        if (!confirm('Bạn có chắc muốn tạo lại toàn bộ ảnh banner logo thương hiệu (như mẫu Zoom Pro / ExpressVPN) cho tất cả bài viết không?')) {
            return;
        }

        const btn = document.getElementById('btnRefreshImages');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang tạo lại banner...';
        }

        fetch('{{ route("admin.blogs.auto.refresh_images") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-image me-1"></i> Tạo Lại Banner Logo Đẹp Cho Tất Cả Bài';
            }
            alert(res.message || 'Đã tạo lại banner logo cho bài viết thành công!');
            location.reload();
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-image me-1"></i> Tạo Lại Banner Logo Đẹp Cho Tất Cả Bài';
            }
            alert('Lỗi khi tạo lại ảnh: ' + err.message);
        });
    }

    // 7. Đặt lại mốc thời gian chạy gần nhất về Vừa xong
    function resetLastRunTime() {
        const el = document.getElementById('statLastRunAt');
        const origText = el ? el.innerText : '';
        if (el) el.innerText = 'Đang đặt lại...';

        fetch('{{ route("admin.blogs.auto.settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ reset_last_run: 1 })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                if (el) el.innerText = 'Vừa xong';
                const statusPill = document.getElementById('autoBotStatusPill');
                const statusText = document.getElementById('autoBotStatusText');
                if (statusText) statusText.innerText = 'ĐANG CHẠY TỰ ĐỘNG (15P / BÀI)';
                if (statusPill) statusPill.className = 'status-pill active';
            } else {
                if (el) el.innerText = origText;
            }
        })
        .catch(() => {
            if (el) el.innerText = 'Vừa xong';
        });
    }

    // 8. Live Polling cập nhật trạng thái tiến độ bài viết
    let isAutoTriggering = false;
    let currentBlogTotal = {{ (int) $autoStatus['total_generated'] }};

    function pollAutoBlogStatus() {
        if (isAutoTriggering) return;

        fetch('{{ route("admin.blogs.auto.status") }}', {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(res => {
            if (!res.success || !res.data) return;
            const d = res.data;

            // Cập nhật thẻ chạy gần nhất
            const lastRunEl = document.getElementById('statLastRunAt');
            if (lastRunEl && d.last_run_human) {
                lastRunEl.innerText = d.last_run_human;
            }

            // Cập nhật sản phẩm tiếp theo
            if (d.next_product) {
                const nextNameEl = document.getElementById('statNextProductName');
                const nextPriceEl = document.getElementById('statNextProductPrice');
                if (nextNameEl) nextNameEl.innerText = d.next_product.name;
                if (nextPriceEl) {
                    if (d.next_product.has_sale && d.next_product.original_price) {
                        nextPriceEl.innerHTML = `<strong class="text-warning">${d.next_product.price}</strong> <del class="text-white-50 ms-1" style="font-size: 11px;">${d.next_product.original_price}</del> <span class="badge bg-danger text-white rounded-pill ms-1 px-1 py-0" style="font-size: 9px;">-${d.next_product.save_percent}%</span>`;
                    } else {
                        nextPriceEl.innerHTML = `<strong class="text-warning">${d.next_product.price}</strong>`;
                    }
                }
            }

            // Cập nhật tổng số bài đã tạo
            if (d.total_generated !== undefined) {
                const totalEl = document.getElementById('statTotalGenerated');
                if (totalEl) totalEl.innerHTML = `Đã tạo: <strong>${d.total_generated}</strong> bài`;

                // Nếu backend vừa tạo xong bài mới
                if (d.total_generated > currentBlogTotal) {
                    currentBlogTotal = d.total_generated;
                    setTimeout(() => location.reload(), 1500);
                    return;
                }
            }
        })
        .catch(() => {});
    }

    // Tự động kiểm tra ngay sau khi load 2.5s, sau đó lặp lại mỗi 30s
    setTimeout(pollAutoBlogStatus, 2500);
    setInterval(pollAutoBlogStatus, 30000);
</script>
@endpush
@endsection