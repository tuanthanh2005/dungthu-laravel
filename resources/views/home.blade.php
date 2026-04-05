@extends('layouts.app')

@section('title', 'DungThu.com - Tin Công Nghệ & Mua Sắm')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <style>
        /* =========================================================
       TECHFEED HOME — matching test.html prototype exactly
       ========================================================= */

        /* ---- Body override for this page ---- */
        body {
            background-color: #dae0e6;
            overflow-y: scroll;
        }

        /* ---- NAVBAR override ---- */
        .navbar-techfeed {
            background-color: #fff !important;
            border-bottom: 1px solid #ccc !important;
            height: 56px;
        }

        /* ---- 3-column grid ---- */
        .tf-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            max-width: 1280px;
            margin: 0 auto;
            padding: 20px 12px;
        }

        @media(min-width:1024px) {
            .tf-layout {
                grid-template-columns: 220px 1fr 300px;
            }
        }

        @media(min-width:1280px) {
            .tf-layout {
                grid-template-columns: 240px 1fr 320px;
            }
        }

        /* ---- LEFT SIDEBAR ---- */
        .tf-sidebar-left {
            display: none;
        }

        @media(min-width:1024px) {
            .tf-sidebar-left {
                display: block;
            }
        }

        .tf-sticky {
            position: sticky;
            top: 66px;
            max-height: calc(100vh - 70px);
            overflow-y: auto;
            scrollbar-width: none;
        }

        .tf-sticky::-webkit-scrollbar {
            display: none;
        }

        .tf-sec-label {
            font-size: .7rem;
            font-weight: 700;
            color: #787c7e;
            text-transform: uppercase;
            letter-spacing: .6px;
            padding: 0 14px;
            margin: 14px 0 4px;
        }

        .tf-sec-label:first-child {
            margin-top: 0;
        }

        .tf-side-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            color: #1c1c1c;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: .9rem;
            margin-bottom: 2px;
            transition: background .15s;
        }

        .tf-side-link i {
            width: 22px;
            font-size: 1.05rem;
            text-align: center;
        }

        .tf-side-link:hover,
        .tf-side-link.active {
            background: #f6f7f8;
            color: #1c1c1c;
        }

        .tf-side-link.active {
            font-weight: 700;
        }

        /* ---- MAIN FEED ---- */
        /* Sort bar */
        .tf-sort-bar {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 12px;
            display: flex;
            gap: 6px;
        }

        .tf-sort-btn {
            background: none;
            border: none;
            border-radius: 20px;
            padding: 6px 14px;
            font-weight: 700;
            font-size: .84rem;
            color: #787c7e;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background .15s;
        }

        .tf-sort-btn:hover {
            background: #f6f7f8;
        }

        .tf-sort-btn.active {
            background: #f0f2f5;
            color: #1c1c1c;
        }

        /* Feed card */
        .tf-card {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 12px;
            margin-bottom: 12px;
            display: flex;
            overflow: hidden;
            transition: border-color .15s;
        }

        .tf-card:hover {
            border-color: #898989;
        }

        /* Vote column - REMOVED; kept empty rule for compat */
        .tf-vote {
            display: none !important;
        }

        .tf-vote-shop {
            display: none !important;
        }

        /* Post body */
        .tf-body {
            padding: 12px 14px;
            flex: 1;
            min-width: 0;
        }

        .tf-meta {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .78rem;
            color: #787c7e;
            margin-bottom: 6px;
            flex-wrap: wrap;
        }

        .tf-meta img {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            object-fit: cover;
        }

        .tf-meta .cat {
            font-weight: 700;
            color: #1c1c1c;
            text-decoration: none;
        }

        .tf-meta .cat:hover {
            text-decoration: underline;
        }

        .tf-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1c1c1c;
            text-decoration: none;
            display: block;
            line-height: 1.4;
            margin-bottom: 10px;
        }

        .tf-title:hover {
            color: #ff4500;
        }

        .tf-excerpt {
            font-size: .87rem;
            color: #3c3c3c;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .tf-media {
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            background: #eee;
            margin-bottom: 10px;
        }

        .tf-media img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            display: block;
        }

        .tf-actions {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        .tf-action {
            background: none;
            border: none;
            color: #787c7e;
            font-weight: 700;
            font-size: .78rem;
            padding: 5px 10px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background .15s;
        }

        .tf-action:hover {
            background: #f6f7f8;
            color: #1c1c1c;
        }

        /* ---- SHOP CARD variant ---- */
        .tf-card-shop {
            border: 1px solid #b2ebf2;
        }

        .tf-shop-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #e0f7fa;
            color: #00838f;
            padding: 2px 9px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .tf-shop-badge.flash {
            background: #fff3e0;
            color: #e65100;
        }

        .tf-shop-badge.hot {
            background: #fce4ec;
            color: #c62828;
        }

        .tf-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: #e53935;
        }

        .tf-price-old {
            font-size: .88rem;
            color: #787c7e;
            text-decoration: line-through;
        }

        .tf-buy-btn {
            background: #e53935;
            color: #fff;
            border: none;
            border-radius: 24px;
            padding: 9px 0;
            width: 100%;
            font-weight: 700;
            font-size: .86rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background .15s;
        }

        .tf-buy-btn:hover {
            background: #c62828;
        }

        .tf-buy-btn.green {
            background: #2e7d32;
        }

        .tf-buy-btn.green:hover {
            background: #1b5e20;
        }

        /* ---- AD SLOT ---- */
        .tf-ad {
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* ---- RIGHT SIDEBAR ---- */
        .tf-sidebar-right {
            display: none;
        }

        @media(min-width:1024px) {
            .tf-sidebar-right {
                display: block;
            }
        }

        .tf-widget {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 14px;
        }

        .tf-widget-title {
            font-size: .78rem;
            font-weight: 800;
            color: #787c7e;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 12px;
        }

        /* Top sell list */
        .tf-top-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #f5f5f5;
            text-decoration: none;
            color: #1c1c1c;
            transition: opacity .15s;
        }

        .tf-top-item:last-child {
            border-bottom: none;
        }

        .tf-top-item:hover {
            opacity: .78;
        }

        .tf-top-item img {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .tf-top-item .name {
            font-size: .83rem;
            font-weight: 700;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .tf-top-item .price {
            font-size: .8rem;
            font-weight: 700;
            color: #e53935;
            margin-top: 2px;
        }

        /* Blog list sidebar */
        .tf-blog-item {
            display: flex;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #f5f5f5;
            text-decoration: none;
            color: #1c1c1c;
            transition: opacity .15s;
        }

        .tf-blog-item:last-child {
            border-bottom: none;
        }

        .tf-blog-item:hover {
            opacity: .78;
        }

        .tf-blog-item img {
            width: 60px;
            height: 48px;
            object-fit: cover;
            border-radius: 6px;
            flex-shrink: 0;
        }

        .tf-blog-item .title {
            font-size: .82rem;
            font-weight: 600;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .tf-blog-item .time {
            font-size: .7rem;
            color: #787c7e;
            margin-top: 3px;
        }

        /* Community widget stats */
        .tf-stat {
            text-align: center;
        }

        .tf-stat .num {
            font-size: 1.2rem;
            font-weight: 800;
            color: #1c1c1c;
        }

        .tf-stat .lbl {
            font-size: .74rem;
            color: #787c7e;
        }

        .tf-join-btn {
            background: #1c1c1c;
            color: #fff;
            border: none;
            border-radius: 24px;
            width: 100%;
            padding: 9px;
            font-weight: 700;
            font-size: .9rem;
            cursor: pointer;
            transition: background .2s;
            margin-top: 12px;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .tf-join-btn:hover {
            background: #333;
            color: #fff;
        }

        /* Flash sale widget */
        .tf-flash-item {
            display: flex;
            gap: 10px;
            align-items: center;
            padding: 8px;
            background: #fff5f2;
            border-radius: 8px;
            margin-bottom: 8px;
            text-decoration: none;
            color: #1c1c1c;
            border: 1px solid rgba(255, 69, 0, .1);
            transition: border-color .15s;
        }

        .tf-flash-item:hover {
            border-color: #ff4500;
        }

        .tf-flash-item img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 7px;
            flex-shrink: 0;
        }

        .tf-flash-item .fn {
            font-size: .82rem;
            font-weight: 700;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .tf-flash-item .fp {
            font-size: .86rem;
            font-weight: 800;
            color: #e53935;
        }

        .tf-flash-item .fo {
            font-size: .72rem;
            color: #787c7e;
            text-decoration: line-through;
        }

        /* Recent purchase toast */
        .rpt-toast {
            position: fixed;
            left: 16px;
            bottom: 16px;
            width: min(360px, calc(100vw - 32px));
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 36px rgba(0, 0, 0, .18);
            border-left: 4px solid #12b76a;
            padding: 12px 14px;
            z-index: 2000;
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
            transition: opacity .25s, transform .25s;
        }

        .rpt-toast.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .rpt-toast .inner {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .rpt-toast .ava {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #12b76a, #00cec9);
            color: #fff;
            font-weight: 800;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .rpt-toast .rn {
            font-weight: 800;
            font-size: .85rem;
        }

        .rpt-toast .rs {
            font-size: .75rem;
            color: #6b7280;
        }

        .rpt-toast .rp {
            font-size: .78rem;
            font-weight: 700;
            color: #ff4500;
            display: block;
            margin-top: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 220px;
            text-decoration: none;
        }

        .rpt-toast .rc {
            position: absolute;
            top: 8px;
            right: 8px;
            background: none;
            border: none;
            font-size: 16px;
            color: #9ca3af;
            cursor: pointer;
        }

        /* Mobile adjusts */
        @media(max-width:1023px) {
            .tf-vote {
                display: none;
            }

            .tf-body {
                padding: 10px 12px;
            }

            .tf-title {
                font-size: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div style="background:#dae0e6;">
        <div class="tf-layout">

            {{-- ====== LEFT SIDEBAR ====== --}}
            <aside class="tf-sidebar-left">
                <div class="tf-sticky">
                    <div class="tf-sec-label">Khám Phá</div>
                    <a href="{{ route('home') }}" class="tf-side-link active"><i class="fa-solid fa-house"></i> Trang
                        chủ</a>
                    <a href="{{ route('blog.index') }}" class="tf-side-link"><i class="fa-solid fa-fire"
                            style="color:#e53935;"></i> Blog mới</a>
                    <a href="{{ route('community.index') }}" class="tf-side-link"><i class="fa-solid fa-users"></i> Cộng
                        đồng</a>
                    <a href="{{ route('web-design') }}" class="tf-side-link"><i class="fa-solid fa-globe"></i> Thiết kế
                        web</a>

                    <div class="tf-sec-label">Cửa Hàng</div>
                    <a href="{{ route('shop') }}" class="tf-side-link"><i class="fa-solid fa-tags"
                            style="color:#28a745;"></i> Tất cả sản phẩm</a>
                    @if(isset($categories))
                        @foreach($categories->take(5) as $cat)
                            <a href="{{ route('shop', ['category_id' => $cat->id]) }}" class="tf-side-link">
                                <i class="fa-solid fa-box"></i> {{ $cat->name }}
                            </a>
                        @endforeach
                    @endif
                    <a href="{{ route('card-exchange.index') }}" class="tf-side-link"><i class="fa-solid fa-credit-card"
                            style="color:#f59e0b;"></i> Đổi thẻ cào</a>

                    @auth
                        <div class="tf-sec-label">Tài Khoản</div>
                        <a href="{{ route('user.account') }}" class="tf-side-link"><i class="fa-solid fa-user-circle"></i> Tài
                            khoản</a>
                        <a href="{{ route('user.orders') }}" class="tf-side-link"><i class="fa-solid fa-box"></i> Đơn hàng</a>
                    @endauth

                    {{-- ADSENSE LEFT SIDEBAR --}}
                    <div class="tf-ad mt-4" style="height:250px;">
                        <ins class="adsbygoogle" style="display:block;width:100%;height:250px;"
                            data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"
                            data-ad-format="rectangle"></ins>
                        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                    </div>
                </div>
            </aside>

            {{-- ====== MAIN FEED ====== --}}
            <main>

                {{-- Sort bar --}}
                <div class="tf-sort-bar">
                    <button class="tf-sort-btn active" id="tab-latest"><i class="fa-solid fa-newspaper"></i> Mới
                        nhất</button>
                    <button class="tf-sort-btn" id="tab-ai"><i class="fa-solid fa-robot"></i> Combo AI</button>
                    <a href="{{ route('card-exchange.index') }}" class="tf-sort-btn text-decoration-none">
                        <i class="fa-solid fa-credit-card"></i> Đổi Thẻ
                    </a>
                </div>

                {{-- Flash Sale (Chỉ hiện ở tab AI/Shop) --}}
                @if(isset($saleProducts) && $saleProducts->count() > 0)
                    <div class="tf-widget mb-3 item-shop" id="flash-sale"
                        data-countdown-end="{{ $saleEndsAt?->getTimestamp() * 1000 }}" style="display:none;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <div>
                                <span class="text-danger fw-bold"
                                    style="font-size:.75rem;text-transform:uppercase;letter-spacing:.04em;">⚡ Flash Sale</span>
                                <div class="fw-bold" style="font-size:1rem;">Giảm giá sốc hôm nay</div>
                            </div>
                            <div
                                style="background:#fff;border-radius:20px;padding:6px 12px;box-shadow:0 2px 8px rgba(0,0,0,.08);display:flex;align-items:center;gap:8px;font-size:.8rem;">
                                <span style="font-weight:800;">
                                    <span data-unit="hours">00</span>:<span data-unit="minutes">00</span>:<span
                                        data-unit="seconds">00</span>
                                </span>
                            </div>
                        </div>
                        <div class="row row-cols-2 row-cols-md-4 g-3">
                            @foreach($saleProducts->take(4) as $sp)
                                <div class="col">
                                    <div
                                        style="background:#fff;border:1px solid #edeff1;border-radius:10px;overflow:hidden;position:relative;">
                                        <img src="{{ $sp->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $sp->name }}"
                                            style="width:100%;height:120px;object-fit:cover;">
                                        <div style="padding:8px;">
                                            <div style="font-size:.8rem;font-weight:700;height:40px;overflow:hidden;">
                                                {{ $sp->name }}</div>
                                            <div style="color:#e53935;font-weight:800;">{{ $sp->formatted_price }}</div>
                                        </div>
                                        <a href="{{ route('product.show', $sp->slug) }}" class="stretched-link"></a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Blog #1 --}}
                @if(isset($latestBlogs[0]))
                    @php $b = $latestBlogs[0]; @endphp
                    <article class="tf-card item-blog">
                        {{-- Bỏ vote div --}}
                        <div class="tf-body">
                            <div class="tf-meta">
                                <img src="https://ui-avatars.com/api/?name=Tech&background=ff4500&color=fff" alt="">
                                <a href="{{ route('blog.index') }}" class="cat">c/TinCongNghe</a>
                                <span>•</span> <span>{{ $b->created_at->diffForHumans() }}</span>
                            </div>
                            <a href="{{ route('blog.show', $b->slug) }}" class="tf-title">{{ $b->title }}</a>
                            @if($b->image)
                                <div class="tf-media">
                                    <a href="{{ route('blog.show', $b->slug) }}">
                                        <img src="{{ $b->image }}" alt="{{ $b->title }}">
                                    </a>
                                </div>
                            @endif
                            <p class="tf-excerpt">{{ Str::limit(strip_tags(html_entity_decode($b->content)), 140) }}</p>
                            <div class="tf-actions">
                                <a href="{{ route('blog.show', $b->slug) }}" class="tf-action"><i
                                        class="fa-regular fa-comment"></i> Đọc thêm</a>
                                <button class="tf-action" onclick="copyLink(this)"><i class="fa-solid fa-share"></i> Chia
                                    sẻ</button>
                            </div>
                        </div>
                    </article>
                @endif

                {{-- Shop Product #1 --}}
                @if(isset($featuredProducts[0]))
                    @php $p = $featuredProducts[0]; @endphp
                    <article class="tf-card tf-card-shop item-shop" style="display:none;">
                        <div class="tf-body">
                            <div class="tf-meta">
                                <img src="https://ui-avatars.com/api/?name=Shop&background=E53935&color=fff" alt="">
                                <a href="{{ route('shop') }}" class="cat">Cửa Hàng</a>
                                <span class="tf-shop-badge flash">Sản phẩm nổi bật</span>
                            </div>
                            <a href="{{ route('product.show', $p->slug) }}" class="tf-title">{{ $p->name }}</a>
                            <div class="row g-3 align-items-center">
                                <div class="col-sm-4">
                                    <img src="{{ $p->image ?? 'https://via.placeholder.com/400' }}" alt=""
                                        style="width:100%;border-radius:8px;">
                                </div>
                                <div class="col-sm-8">
                                    <div class="mb-2"><span class="tf-price">{{ $p->formatted_price }}</span></div>
                                    <form action="{{ route('cart.add', $p->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="tf-buy-btn"><i class="fa-solid fa-cart-plus"></i> Thêm vào
                                            giỏ</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                @endif

                {{-- Blog #2 --}}
                @if(isset($latestBlogs[1]))
                    @php $b = $latestBlogs[1]; @endphp
                    <article class="tf-card item-blog">
                        <div class="tf-body">
                            <div class="tf-meta">
                                <img src="https://ui-avatars.com/api/?name=Review&background=2E7D32&color=fff" alt="">
                                <a href="{{ route('blog.index') }}" class="cat">c/DanhGia</a>
                                <span>•</span> <span>{{ $b->created_at->diffForHumans() }}</span>
                            </div>
                            <a href="{{ route('blog.show', $b->slug) }}" class="tf-title">{{ $b->title }}</a>
                            @if($b->image)
                                <div class="tf-media">
                                    <a href="{{ route('blog.show', $b->slug) }}">
                                        <img src="{{ $b->image }}" alt="{{ $b->title }}">
                                    </a>
                                </div>
                            @endif
                            <p class="tf-excerpt">{{ Str::limit(strip_tags(html_entity_decode($b->content)), 140) }}</p>
                            <div class="tf-actions">
                                <a href="{{ route('blog.show', $b->slug) }}" class="tf-action"><i
                                        class="fa-regular fa-comment"></i> Đọc thêm</a>
                                <button class="tf-action" onclick="copyLink(this)"><i class="fa-solid fa-share"></i> Chia
                                    sẻ</button>
                            </div>
                        </div>
                    </article>
                @endif

                {{-- Shop Product #2 --}}
                @if(isset($featuredProducts[1]))
                    @php $p = $featuredProducts[1]; @endphp
                    <article class="tf-card tf-card-shop item-shop" style="display:none;">
                        <div class="tf-body">
                            <div class="tf-meta">
                                <img src="https://ui-avatars.com/api/?name=DT&background=2E7D32&color=fff" alt="">
                                <a href="{{ route('shop') }}" class="cat">Sản Phẩm</a>
                                <span class="tf-shop-badge hot">Hot pick</span>
                            </div>
                            <a href="{{ route('product.show', $p->slug) }}" class="tf-title">{{ $p->name }}</a>
                            <div class="row g-3 align-items-center">
                                <div class="col-sm-4">
                                    <img src="{{ $p->image ?? 'https://via.placeholder.com/400' }}" alt=""
                                        style="width:100%;border-radius:8px;">
                                </div>
                                <div class="col-sm-8">
                                    <div class="mb-2"><span class="tf-price"
                                            style="color:#2e7d32;">{{ $p->formatted_price }}</span></div>
                                    @if($p->isInStock())
                                        <form action="{{ route('cart.add', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="tf-buy-btn green"><i class="fa-solid fa-cart-plus"></i>
                                                Thêm vào giỏ</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @endif

                {{-- Remaining blogs loop --}}
                @if(isset($latestBlogs))
                    @foreach($latestBlogs->skip(2) as $idx => $b)
                        <article class="tf-card item-blog">
                            <div class="tf-body">
                                <div class="tf-meta">
                                    <img src="https://ui-avatars.com/api/?name=KB&background=0D8ABC&color=fff" alt="">
                                    <a href="{{ route('blog.index') }}" class="cat">c/KienThuc</a>
                                    <span>•</span> <span>{{ $b->created_at->diffForHumans() }}</span>
                                </div>
                                <a href="{{ route('blog.show', $b->slug) }}" class="tf-title">{{ $b->title }}</a>
                                @if($b->image)
                                    <div class="tf-media">
                                        <a href="{{ route('blog.show', $b->slug) }}">
                                            <img src="{{ $b->image }}" alt="{{ $b->title }}">
                                        </a>
                                    </div>
                                @endif
                                <p class="tf-excerpt">{{ Str::limit(strip_tags(html_entity_decode($b->content)), 120) }}</p>
                                <div class="tf-actions">
                                    <a href="{{ route('blog.show', $b->slug) }}" class="tf-action"><i
                                            class="fa-regular fa-comment"></i> Đọc thêm</a>
                                    <button class="tf-action" onclick="copyLink(this)"><i class="fa-solid fa-share"></i> Chia
                                        sẻ</button>
                                </div>
                            </div>
                        </article>

                        {{-- Inline small product every 2 blogs --}}
                        @if($idx % 2 === 0 && isset($featuredProducts[$idx + 2]))
                            @php $ip = $featuredProducts[$idx + 2]; @endphp
                            <article class="tf-card tf-card-shop item-shop" style="display:none;">
                                <div class="tf-body">
                                    <div class="tf-meta">
                                        <img src="https://ui-avatars.com/api/?name=DT&background=ff4500&color=fff" alt="">
                                        <a href="{{ route('shop') }}" class="cat">Cửa hàng</a>
                                    </div>
                                    <div class="d-flex gap-3 align-items-start">
                                        <img src="{{ $ip->image ?? 'https://via.placeholder.com/150' }}" alt=""
                                            style="width:80px;height:60px;border-radius:8px;">
                                        <div>
                                            <a href="{{ route('product.show', $ip->slug) }}" class="tf-title"
                                                style="font-size:.9rem;">{{ $ip->name }}</a>
                                            <div class="tf-price" style="font-size:1rem;">{{ $ip->formatted_price }}</div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endif
                    @endforeach
                @endif

                <div class="text-center py-3">
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary rounded-pill px-5 fw-bold">
                        <i class="fa-solid fa-chevron-down me-1"></i> Xem thêm bài viết
                    </a>
                </div>

            </main>

            {{-- ====== RIGHT SIDEBAR ====== --}}
            <aside class="tf-sidebar-right">
                <div class="tf-sticky">

                    {{-- About Widget --}}
                    <div class="tf-widget">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fa-solid fa-circle-info text-primary fs-5"></i>
                            <h2 class="h6 fw-bold mb-0">Về DungThu.com</h2>
                        </div>
                        <p class="small text-muted mb-3" style="font-size:.83rem;line-height:1.55;">Nền tảng chia sẻ kiến
                            thức công nghệ kết hợp mua sắm trực tuyến. Tìm thấy mọi thông tin hữu ích và sản phẩm chất
                            lượng.</p>
                        <hr class="my-2 opacity-25">
                        <div class="d-flex justify-content-around mt-2">
                            <div class="tf-stat">
                                <div class="num">5k+</div>
                                <div class="lbl">Thành viên</div>
                            </div>
                            <div class="tf-stat">
                                <div class="num" style="color:#28a745;"><span
                                        style="width:7px;height:7px;background:#28a745;border-radius:50%;display:inline-block;margin-right:4px;"></span>24/7
                                </div>
                                <div class="lbl">Online</div>
                            </div>
                        </div>
                        @auth
                            <a href="{{ route('blog.index') }}" class="tf-join-btn">Đọc thêm bài viết</a>
                        @else
                            <a href="{{ route('login') }}" class="tf-join-btn">Đăng nhập để tham gia</a>
                        @endauth
                    </div>

                    {{-- AdSense 300x250 --}}
                    <div class="tf-ad mb-3" style="height:260px;">
                        <ins class="adsbygoogle" style="display:block;width:100%;height:260px;"
                            data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"
                            data-ad-format="rectangle"></ins>
                        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                    </div>

                    {{-- Flash Sale sidebar --}}
                    @if(isset($saleProducts) && $saleProducts->count() > 0)
                        <div class="tf-widget">
                            <div class="tf-widget-title">
                                ⚡ Flash Sale &nbsp;
                                <span id="sf-timer" style="color:#e53935;font-size:.72rem;font-weight:700;"></span>
                            </div>
                            @foreach($saleProducts->take(3) as $sp)
                                <a href="{{ route('product.show', $sp->slug) }}" class="tf-flash-item">
                                    <img src="{{ $sp->image ?? 'https://via.placeholder.com/100' }}" alt="{{ $sp->name }}"
                                        loading="lazy">
                                    <div>
                                        <div class="fn">{{ $sp->name }}</div>
                                        <div class="d-flex align-items-baseline gap-2 mt-1">
                                            <span class="fp">{{ $sp->formatted_price }}</span>
                                            <span class="fo">{{ $sp->formatted_original_price }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{-- Top Selling --}}
                    @if(isset($featuredProducts) && $featuredProducts->count() > 0)
                        <div class="tf-widget">
                            <div class="tf-widget-title">🔥 Bán Chạy Tuần Này</div>
                            @foreach($featuredProducts->take(5) as $ri => $prod)
                                <a href="{{ route('product.show', $prod->slug) }}" class="tf-top-item">
                                    <img src="{{ $prod->image ?? 'https://via.placeholder.com/100' }}" alt="{{ $prod->name }}"
                                        loading="lazy">
                                    <div>
                                        <div class="name">{{ $prod->name }}</div>
                                        <div class="price">{{ $prod->formatted_price }}</div>
                                    </div>
                                </a>
                            @endforeach
                            <a href="{{ route('shop') }}"
                                class="btn btn-outline-primary btn-sm rounded-pill w-100 mt-3 fw-bold">Xem tất cả cửa hàng</a>
                        </div>
                    @endif

                    {{-- Blog Sidebar --}}
                    @if(isset($latestBlogs) && $latestBlogs->count() > 0)
                        <div class="tf-widget">
                            <div class="tf-widget-title">📰 Bài Viết Mới Nhất</div>
                            @foreach($latestBlogs->take(3) as $b)
                                <a href="{{ route('blog.show', $b->slug) }}" class="tf-blog-item">
                                    @if($b->image)
                                        <img src="{{ $b->image }}" alt="{{ $b->title }}" loading="lazy">
                                    @else
                                        <div
                                            style="width:60px;height:48px;background:#f5f5f5;border-radius:6px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                            <i class="fa-solid fa-newspaper text-muted"></i></div>
                                    @endif
                                    <div>
                                        <div class="title">{{ $b->title }}</div>
                                        <div class="time">{{ $b->created_at->diffForHumans() }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{-- AdSense 2nd --}}
                    <div class="tf-ad mb-3" style="height:200px;">
                        <ins class="adsbygoogle" style="display:block;width:100%;height:200px;"
                            data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"
                            data-ad-format="rectangle"></ins>
                        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                    </div>

                    {{-- Footer mini --}}
                    <div class="d-flex flex-wrap gap-2 px-1" style="font-size:.74rem;color:#787c7e;">
                        <a href="#" class="text-muted text-decoration-none" data-bs-toggle="modal"
                            data-bs-target="#aboutModal">Điều khoản</a>·
                        <a href="#" class="text-muted text-decoration-none" data-bs-toggle="modal"
                            data-bs-target="#privacyModal">Bảo mật</a>·
                        <a href="#" class="text-muted text-decoration-none" data-bs-toggle="modal"
                            data-bs-target="#advertisingModal">Quảng cáo</a>·
                        <a href="#" class="text-muted text-decoration-none" data-bs-toggle="modal"
                            data-bs-target="#contactModal">Liên hệ</a>
                        <div class="w-100 mt-1">DungThu.com © {{ date('Y') }}</div>
                    </div>

                </div>
            </aside>

        </div>{{-- tf-layout --}}
    </div>{{-- bg wrapper --}}

    {{-- Recent Purchase Toast --}}
    @if(!empty($recentPurchases) && count($recentPurchases) > 0)
        <script type="application/json" id="rptData">@json($recentPurchases)</script>
        <div id="rptToast" class="rpt-toast" role="status" aria-live="polite">
            <button class="rc" id="rptClose">&times;</button>
            <div class="inner">
                <div class="ava" id="rptAva">K</div>
                <div>
                    <div class="rn" id="rptName">Khách hàng</div>
                    <div class="rs" id="rptSub">vừa mua thành công</div>
                    <a href="#" class="rp" id="rptProd">Sản phẩm</a>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabLatest = document.getElementById('tab-latest');
            const tabAi = document.getElementById('tab-ai');
            const blogs = document.querySelectorAll('.item-blog');
            const shops = document.querySelectorAll('.item-shop');

            tabLatest?.addEventListener('click', function () {
                document.querySelectorAll('.tf-sort-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                blogs.forEach(el => el.style.display = 'flex');
                shops.forEach(el => el.style.display = 'none');
            });

            tabAi?.addEventListener('click', function () {
                document.querySelectorAll('.tf-sort-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                blogs.forEach(el => el.style.display = 'none');
                shops.forEach(el => el.style.display = 'flex');
                // Đối với card shop nhỏ dùng block
                shops.forEach(el => {
                    if (el.classList.contains('tf-widget')) el.style.display = 'block';
                });
            });

            // Copy share link
            window.copyLink = function (btn) {
                navigator.clipboard?.writeText(window.location.href).then(() => {
                    const orig = btn.innerHTML;
                    btn.innerHTML = '<i class="fa-solid fa-check"></i> Đã sao chép!';
                    setTimeout(() => { btn.innerHTML = orig; }, 2000);
                });
            }

            // Flash sale countdown logic...
            const fs = document.getElementById('flash-sale');
            if (fs) {
                const end = parseInt(fs.dataset.countdownEnd || '0');
                const pad = n => String(n).padStart(2, '0');
                setInterval(() => {
                    let d = Math.max(0, end - Date.now());
                    const H = Math.floor(d / 3600000); d %= 3600000;
                    const M = Math.floor(d / 60000);
                    const S = Math.floor((d % 60000) / 1000);
                    fs.querySelector('[data-unit="hours"]').textContent = pad(H);
                    fs.querySelector('[data-unit="minutes"]').textContent = pad(M);
                    fs.querySelector('[data-unit="seconds"]').textContent = pad(S);
                }, 1000);
            }
        });
    </script>
@endpush