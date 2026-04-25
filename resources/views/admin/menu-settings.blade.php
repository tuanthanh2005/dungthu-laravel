@extends('layouts.app')

@section('title', 'Quản lý Menu - Admin')

@push('styles')
<style>
    .menu-settings-wrapper {
        padding: 40px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin-top: 70px;
    }

    .admin-nav {
        background: white;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    .admin-nav .nav-link {
        color: #4a5568;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 10px;
        transition: all 0.3s ease;
        margin: 0 5px;
    }

    .admin-nav .nav-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .admin-nav .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .settings-card {
        background: white;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    }

    .settings-header {
        margin-bottom: 35px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f5;
    }

    .settings-header h2 {
        font-size: 1.8rem;
        font-weight: 800;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .menu-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-radius: 16px;
        background: #f8f9ff;
        margin-bottom: 14px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .menu-item-row:hover {
        border-color: #667eea40;
        background: #f0f2ff;
        transform: translateX(4px);
    }

    .menu-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .menu-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .menu-label {
        font-weight: 700;
        font-size: 1rem;
        color: #2d3748;
    }

    .menu-desc {
        font-size: 0.82rem;
        color: #718096;
        margin-top: 2px;
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 62px;
        height: 32px;
        flex-shrink: 0;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e0;
        border-radius: 34px;
        transition: 0.35s;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 24px;
        width: 24px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        border-radius: 50%;
        transition: 0.35s;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .toggle-switch input:checked + .toggle-slider {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(30px);
    }

    .toggle-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .toggle-status {
        font-size: 0.82rem;
        font-weight: 700;
        min-width: 40px;
    }

    .status-on  { color: #38a169; }
    .status-off { color: #e53e3e; }

    .save-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 14px;
        padding: 14px 40px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102,126,234,0.4);
    }

    .save-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102,126,234,0.5);
    }

    .alert-success {
        border-radius: 14px;
        border: none;
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        font-weight: 600;
        padding: 14px 20px;
    }

    .preview-badge {
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 20px;
        font-weight: 600;
        margin-left: 8px;
    }
    .badge-mobile { background: #e9ecef; color: #495057; }
    .badge-desktop { background: #cfe2ff; color: #084298; }
    .badge-both { background: #d1ecf1; color: #0c5460; }
</style>
@endpush

@section('content')
<div class="menu-settings-wrapper">
    <div class="container">
        {{-- Admin Nav --}}
        <nav class="admin-nav" data-aos="fade-down">
            <ul class="nav nav-pills justify-content-center flex-wrap">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.products') }}">
                        <i class="fas fa-box me-2"></i>Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.orders') }}">
                        <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.blogs') }}">
                        <i class="fas fa-blog me-2"></i>Bài viết
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.menu-settings') }}">
                        <i class="fas fa-sliders-h me-2"></i>Quản lý Menu
                    </a>
                </li>
            </ul>
        </nav>

        <div class="settings-card" data-aos="fade-up">
            {{-- Header --}}
            <div class="settings-header">
                <h2><i class="fas fa-sliders-h me-2"></i>Quản lý Menu Hiển thị</h2>
                <p class="text-muted mb-0">Bật/tắt từng mục menu trên website. Thay đổi có hiệu lực ngay lập tức.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.menu-settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                @php
                    $menuItems = [
                        'menu_home' => [
                            'label'  => 'Trang chủ',
                            'desc'   => 'Menu chính, trang chủ website',
                            'icon'   => 'fa-house',
                            'color'  => 'linear-gradient(135deg,#667eea,#764ba2)',
                            'where'  => 'both',
                        ],
                        'menu_shop' => [
                            'label'  => 'Cửa hàng',
                            'desc'   => 'Trang danh sách sản phẩm',
                            'icon'   => 'fa-store',
                            'color'  => 'linear-gradient(135deg,#f093fb,#f5576c)',
                            'where'  => 'both',
                        ],
                        'menu_blog' => [
                            'label'  => 'Blog',
                            'desc'   => 'Trang bài viết & tin tức',
                            'icon'   => 'fa-newspaper',
                            'color'  => 'linear-gradient(135deg,#4facfe,#00f2fe)',
                            'where'  => 'both',
                        ],
                        'menu_cart' => [
                            'label'  => 'Giỏ hàng',
                            'desc'   => 'Icon giỏ hàng trên thanh điều hướng',
                            'icon'   => 'fa-cart-shopping',
                            'color'  => 'linear-gradient(135deg,#43e97b,#38f9d7)',
                            'where'  => 'both',
                        ],
                        'menu_webdesign' => [
                            'label'  => 'Thiết kế WS',
                            'desc'   => 'Trang dịch vụ thiết kế website',
                            'icon'   => 'fa-palette',
                            'color'  => 'linear-gradient(135deg,#fa709a,#fee140)',
                            'where'  => 'both',
                        ],
                        'menu_card_exchange' => [
                            'label'  => 'Đổi thẻ cào',
                            'desc'   => 'Trang đổi thẻ cào lấy tiền mặt',
                            'icon'   => 'fa-credit-card',
                            'color'  => 'linear-gradient(135deg,#f7971e,#ffd200)',
                            'where'  => 'both',
                        ],
                        'menu_buff' => [
                            'label'  => 'Buff Mạng XH',
                            'desc'   => 'Trang dịch vụ buff mạng xã hội',
                            'icon'   => 'fa-chart-line',
                            'color'  => 'linear-gradient(135deg,#a18cd1,#fbc2eb)',
                            'where'  => 'desktop',
                        ],
                        'menu_community' => [
                            'label'  => 'Cộng đồng',
                            'desc'   => 'Trang cộng đồng & forum',
                            'icon'   => 'fa-users',
                            'color'  => 'linear-gradient(135deg,#0ba360,#3cba92)',
                            'where'  => 'desktop',
                        ],
                    ];
                @endphp

                <div class="mb-4">
                    @foreach($menuItems as $key => $item)
                    <div class="menu-item-row">
                        <div class="menu-info">
                            <div class="menu-icon" style="background: {{ $item['color'] }}; color: white;">
                                <i class="fa-solid {{ $item['icon'] }}"></i>
                            </div>
                            <div>
                                <div class="menu-label">
                                    {{ $item['label'] }}
                                    @if($item['where'] === 'both')
                                        <span class="preview-badge badge-both">Mobile + Desktop</span>
                                    @elseif($item['where'] === 'desktop')
                                        <span class="preview-badge badge-desktop">Desktop only</span>
                                    @else
                                        <span class="preview-badge badge-mobile">Mobile only</span>
                                    @endif
                                </div>
                                <div class="menu-desc">{{ $item['desc'] }}</div>
                            </div>
                        </div>

                        <div class="toggle-wrap">
                            @php $isOn = \App\Models\SiteSetting::getValue($key, '1') === '1'; @endphp
                            <span class="toggle-status {{ $isOn ? 'status-on' : 'status-off' }}" id="status-{{ $key }}">
                                {{ $isOn ? 'Bật' : 'Tắt' }}
                            </span>
                            <label class="toggle-switch" title="Bật/tắt {{ $item['label'] }}">
                                <input type="checkbox" name="{{ $key }}" value="1"
                                    {{ $isOn ? 'checked' : '' }}
                                    onchange="updateStatus(this, '{{ $key }}')">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>



                {{-- Thêm cài đặt Top Mua Hàng (Fake Orders) --}}
                <div class="settings-header mt-5">
                    <h2><i class="fas fa-crown me-2"></i>Cài đặt Top Mua Hàng</h2>
                    <p class="text-muted mb-0">Điều chỉnh số lượng đơn hàng cộng thêm hiển thị trên bục vinh quang trang chủ.</p>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="menu-item-row flex-column align-items-start">
                            <label class="form-label fw-bold text-warning mb-2"><i class="fas fa-crown"></i> Top 1</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-plus text-muted"></i></span>
                                <input type="number" class="form-control border-start-0 ps-0" name="fake_orders_top1" value="{{ \App\Models\SiteSetting::getValue('fake_orders_top1', '30') }}" min="0">
                                <span class="input-group-text bg-light">đơn</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="menu-item-row flex-column align-items-start">
                            <label class="form-label fw-bold text-secondary mb-2">Top 2</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-plus text-muted"></i></span>
                                <input type="number" class="form-control border-start-0 ps-0" name="fake_orders_top2" value="{{ \App\Models\SiteSetting::getValue('fake_orders_top2', '19') }}" min="0">
                                <span class="input-group-text bg-light">đơn</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="menu-item-row flex-column align-items-start">
                            <label class="form-label fw-bold mb-2" style="color: #CD7F32;">Top 3</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-plus text-muted"></i></span>
                                <input type="number" class="form-control border-start-0 ps-0" name="fake_orders_top3" value="{{ \App\Models\SiteSetting::getValue('fake_orders_top3', '10') }}" min="0">
                                <span class="input-group-text bg-light">đơn</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="save-btn">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });

    function updateStatus(checkbox, key) {
        const statusEl = document.getElementById('status-' + key);
        if (checkbox.checked) {
            statusEl.textContent = 'Bật';
            statusEl.className = 'toggle-status status-on';
        } else {
            statusEl.textContent = 'Tắt';
            statusEl.className = 'toggle-status status-off';
        }
    }
</script>
@endpush
