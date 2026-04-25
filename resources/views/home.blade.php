@extends('layouts.app')

@section('title', 'Dùng Thử | AI | Blog | Khám Phá')

@section('seo_h1')
    <h1 style="display:none;">Dùng Thử | AI | Blog | Khám Phá</h1>
@endsection

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

        /* Top Buyers Podium */
        .podium-item {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
        }
        
        .avatar-wrap {
            position: relative;
            margin-bottom: 5px;
        }
        
        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border: 2px solid white;
        }
        
        .rank-badge {
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 900;
            color: white;
            border: 1.5px solid white;
            z-index: 2;
        }
        
        .badge-gold { background: #ffca28; color: #fff; }
        .badge-silver { background: #bdbdbd; color: #fff; }
        .badge-bronze { background: #ff8a65; color: #fff; }
        
        .podium-bar {
            width: 100%;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: white;
            font-size: 1.5rem;
            box-shadow: inset 0 -10px 20px rgba(0,0,0,0.05);
        }
        
        .rank-1 .podium-bar { height: 75px; background: #ffca28; border-radius: 8px 8px 0 0; z-index: 3; position: relative; }
        .rank-2 .podium-bar { height: 55px; background: #e0e0e0; border-radius: 8px 0 0 0; }
        .rank-3 .podium-bar { height: 40px; background: #ffab91; border-radius: 0 8px 0 0; }

        /* ====== COMBO AI TAB STYLES ====== */
        .combo-ai-wrapper {
            display: none; /* hidden by default, shown when tab-ai active */
        }
        .combo-ai-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 24px;
            padding: 24px 32px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.2);
            border: 1px solid rgba(255,255,255,0.05);
        }

        .combo-ai-header .ai-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(99, 102, 241, 0.2);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #a5b4fc;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 12px;
        }

        .combo-ai-header h3 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0 0 6px;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .combo-ai-header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
            margin: 0;
            font-weight: 500;
        }

        .combo-ai-header .ai-stats {
            display: flex;
            gap: 30px;
            flex-shrink: 0;
        }

        .combo-ai-header .stat-item {
            text-align: center;
            color: #fff;
            min-width: 80px;
        }

        .combo-ai-header .stat-num {
            font-size: 2rem;
            font-weight: 900;
            color: #818cf8;
            display: block;
            line-height: 1;
            margin-bottom: 4px;
        }

        .combo-ai-header .stat-label {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* Category filter pills */
        .combo-cat-filter {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            padding: 12px 16px;
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .combo-cat-pill {
            background: #f6f7f8;
            border: 1px solid #e0e0e0;
            border-radius: 20px;
            padding: 5px 14px;
            font-size: .8rem;
            font-weight: 700;
            color: #555;
            cursor: pointer;
            transition: all .2s;
            white-space: nowrap;
        }

        .combo-cat-pill:hover {
            background: #e8eaff;
            border-color: #6366f1;
            color: #6366f1;
        }

        .combo-cat-pill.active {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 2px 8px rgba(99,102,241,.35);
        }

        /* Category section */
        .combo-cat-section {
            margin-bottom: 18px;
        }

        .combo-cat-section.hidden {
            display: none;
        }

        .combo-cat-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #4b5563;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }

        .combo-cat-title .cat-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .combo-cat-title .cat-count {
            background: #f3f4f6;
            color: #6b7280;
            font-size: .68rem;
            padding: 1px 7px;
            border-radius: 10px;
            margin-left: auto;
        }

        /* Product grid */
        .combo-product-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        @media(min-width: 640px) {
            .combo-product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media(min-width: 900px) {
            .combo-product-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Product card */
        .combo-prod-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            text-decoration: none;
            color: #1c1c1c;
            transition: transform .2s, box-shadow .2s, border-color .2s;
            display: flex;
            flex-direction: column;
        }

        .combo-prod-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(99,102,241,.15);
            border-color: #6366f1;
            text-decoration: none;
            color: #1c1c1c;
        }

        .combo-prod-card .img-wrap {
            position: relative;
            overflow: hidden;
            background: #f8f9fa;
            aspect-ratio: 4/3;
        }

        .combo-prod-card .img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s;
            display: block;
        }

        .combo-prod-card:hover .img-wrap img {
            transform: scale(1.05);
        }

        .combo-prod-card .ai-label {
            position: absolute;
            top: 7px;
            left: 7px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            font-size: .62rem;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .combo-prod-card .discount-badge {
            position: absolute;
            top: 7px;
            right: 7px;
            background: #ef4444;
            color: #fff;
            font-size: .62rem;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 6px;
        }

        .combo-prod-card .card-body {
            padding: 10px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .combo-prod-card .prod-name {
            font-size: .82rem;
            font-weight: 700;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 6px;
            flex: 1;
        }

        .combo-prod-card .prod-price-row {
            display: flex;
            align-items: baseline;
            gap: 5px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .combo-prod-card .prod-price {
            font-size: .96rem;
            font-weight: 800;
            color: #6366f1;
        }

        .combo-prod-card .prod-price-old {
            font-size: .72rem;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .combo-prod-card .add-cart-btn {
            width: 100%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 7px 10px;
            font-size: .78rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: opacity .2s, transform .1s;
            text-decoration: none;
        }

        .combo-prod-card .add-cart-btn:hover {
            opacity: .9;
            transform: scale(1.02);
        }

        /* View all link */
        .combo-view-all {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 16px;
        }

        .combo-view-all a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            border: 1.5px solid #6366f1;
            color: #6366f1;
            border-radius: 24px;
            padding: 9px 28px;
            font-weight: 700;
            font-size: .85rem;
            text-decoration: none;
            transition: all .2s;
        }

        .combo-view-all a:hover {
            background: #6366f1;
            color: #fff;
        }
    </style>
@endpush

@section('content')
    <div style="background:#dae0e6;">
        <div class="tf-layout">

            {{-- ====== LEFT SIDEBAR ====== --}}
            @php
                $sb_home         = \App\Models\SiteSetting::getValue('menu_home', '1') === '1';
                $sb_blog         = \App\Models\SiteSetting::getValue('menu_blog', '1') === '1';
                $sb_community    = \App\Models\SiteSetting::getValue('menu_community', '1') === '1';
                $sb_webdesign    = \App\Models\SiteSetting::getValue('menu_webdesign', '1') === '1';
                $sb_shop         = \App\Models\SiteSetting::getValue('menu_shop', '1') === '1';
                $sb_cardexchange = \App\Models\SiteSetting::getValue('menu_card_exchange', '1') === '1';
            @endphp
            <aside class="tf-sidebar-left">
                <div class="tf-sticky">
                    <div class="tf-sec-label">Khám Phá</div>
                    @if($sb_home)
                    <a href="{{ route('home') }}" class="tf-side-link active"><i class="fa-solid fa-house"></i> Trang chủ</a>
                    @endif
                    @if($sb_blog)
                    <a href="{{ route('blog.index') }}" class="tf-side-link"><i class="fa-solid fa-fire" style="color:#e53935;"></i> Blog mới</a>
                    @endif
                    @if($sb_community)
                    <a href="{{ route('community.index') }}" class="tf-side-link"><i class="fa-solid fa-users"></i> Cộng đồng</a>
                    @endif
                    @if($sb_webdesign)
                    <a href="{{ route('web-design') }}" class="tf-side-link"><i class="fa-solid fa-globe"></i> Thiết kế web</a>
                    @endif

                    @if($sb_shop)
                    <div class="tf-sec-label">Cửa Hàng</div>
                    <a href="{{ route('shop') }}" class="tf-side-link"><i class="fa-solid fa-tags" style="color:#28a745;"></i> Tất cả sản phẩm</a>
                    @if(isset($categories))
                        @foreach($categories->take(5) as $cat)
                            <a href="{{ route('shop', ['category_id' => $cat->id]) }}" class="tf-side-link">
                                <i class="fa-solid fa-box"></i> {{ $cat->name }}
                            </a>
                        @endforeach
                    @endif
                    @endif
                    @if($sb_cardexchange)
                    <a href="{{ route('card-exchange.index') }}" class="tf-side-link"><i class="fa-solid fa-credit-card" style="color:#f59e0b;"></i> Đổi thẻ cào</a>
                    @endif

                    @auth
                        <div class="tf-sec-label">Tài Khoản</div>
                        <a href="{{ route('user.account') }}" class="tf-side-link"><i class="fa-solid fa-user-circle"></i> Tài khoản</a>
                        <a href="{{ route('user.orders') }}" class="tf-side-link"><i class="fa-solid fa-box"></i> Đơn hàng</a>
                    @endauth

                    {{-- ADSENSE LEFT SIDEBAR --}}
                    <div class="tf-ad mt-4" style="height:600px;">
                        <ins class="adsbygoogle" style="display:block;width:100%;height:600px;"
                            data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"
                            data-ad-format="vertical"></ins>
                        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                    </div>
                </div>
            </aside>

            {{-- ====== MAIN FEED ====== --}}
            <main>

                {{-- Sort bar --}}
                <div class="tf-sort-bar">
                    <button class="tf-sort-btn" id="tab-latest"><i class="fa-solid fa-newspaper"></i> Bài Viết</button>
                    <button class="tf-sort-btn active" id="tab-ai"><i class="fa-solid fa-box-open"></i> Sản Phẩm</button>
                    @if($sb_cardexchange)
                    <a href="{{ route('card-exchange.index') }}" class="tf-sort-btn text-decoration-none">
                        <i class="fa-solid fa-credit-card"></i> Đổi Thẻ
                    </a>
                    @endif
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

                {{-- ====== LATEST PRODUCTS SECTION ====== --}}
                @if(isset($latestProducts) && $latestProducts->count() > 0)
                <div class="combo-ai-wrapper item-shop" style="display:none;">
                    <div class="combo-cat-section">
                        <div class="combo-cat-title" style="border-bottom:none; margin-bottom: 15px; font-size: 1.1rem; padding-bottom: 0;">
                            <i class="fa-solid fa-box-open" style="color:#6366f1;"></i> Sản Phẩm Mới Nhất
                        </div>
                        <div class="combo-product-grid">
                            @foreach($latestProducts as $cp)
                            <a href="{{ route('product.show', $cp->slug) }}" class="combo-prod-card">
                                <div class="img-wrap">
                                    <img src="{{ $cp->image ?? 'https://via.placeholder.com/300x225?text=Product' }}"
                                         alt="{{ $cp->name }}" loading="lazy">
                                    @if($cp->is_on_sale)
                                        <span class="discount-badge">-{{ $cp->discount_percent }}%</span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="prod-name">{{ $cp->name }}</div>
                                    <div class="prod-price-row">
                                        <span class="prod-price">{{ $cp->formatted_price }}</span>
                                        @if($cp->is_on_sale)
                                            <span class="prod-price-old">{{ $cp->formatted_original_price }}</span>
                                        @endif
                                    </div>
                                    <form action="{{ route('cart.add', $cp->id) }}" method="POST"
                                          onclick="event.preventDefault(); this.submit();">
                                        @csrf
                                        <button type="submit" class="add-cart-btn">
                                            <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ
                                        </button>
                                    </form>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- View all --}}
                    <div class="combo-view-all">
                        <a href="{{ route('shop') }}">
                            <i class="fa-solid fa-store"></i> Xem tất cả sản phẩm
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @else
                <div class="combo-ai-wrapper item-shop" style="display:none;">
                    <div class="combo-ai-header" style="justify-content:center;text-align:center;">
                        <div>
                            <div class="ai-badge"><i class="fa-solid fa-box-open"></i> Cửa Hàng</div>
                            <h3>🤖 Chưa có sản phẩm nào</h3>
                            <p>Các sản phẩm sẽ xuất hiện ở đây khi được thêm vào hệ thống</p>
                        </div>
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

                {{-- Mobile-only AdSense (Inline) --}}
                <div class="tf-ad mb-3 d-block d-lg-none" style="min-height:250px;">
                    <ins class="adsbygoogle"
                         style="display:block;width:100%;height:300px;"
                         data-ad-client="ca-pub-3065867660863139"
                         data-ad-slot="4989157975"
                         data-ad-format="rectangle"></ins>
                    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                </div>

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

                    {{-- Top Buyers Widget --}}
                    <div class="tf-widget top-buyers-widget text-center">
                        <div class="d-flex align-items-center gap-2 mb-3 justify-content-center">
                            <i class="fa-solid fa-crown text-warning fs-5"></i>
                            <h2 class="h6 fw-bold mb-0 text-uppercase" style="letter-spacing: 0.5px;">Top Mua Hàng</h2>
                        </div>
                        
                        @php
                            $topBuyers = \App\Models\User::withCount('orders')
                                ->orderByDesc('orders_count')
                                ->take(3)
                                ->get();
                            
                            $rank1 = $topBuyers->get(0);
                            $rank2 = $topBuyers->get(1);
                            $rank3 = $topBuyers->get(2);
                        @endphp
                        
                        <div class="podium-container d-flex justify-content-center align-items-end mb-3 mt-2">
                            <!-- Rank 2 -->
                            @if($rank2)
                            <div class="podium-item rank-2 text-center" style="flex: 1;">
                                <div class="avatar-wrap mx-auto">
                                    <div class="rank-badge badge-silver">2</div>
                                    <div class="avatar bg-secondary text-white mx-auto">{{ strtoupper(substr($rank2->name, 0, 1)) }}</div>
                                </div>
                                <div class="name mt-2 fw-bold text-truncate mx-auto" style="max-width: 60px; font-size: 0.75rem;">{{ explode(' ', trim($rank2->name))[count(explode(' ', trim($rank2->name)))-1] }}</div>
                                <div class="orders text-muted" style="font-size: 0.65rem;">{{ $rank2->orders_count + (int)\App\Models\SiteSetting::getValue('fake_orders_top2', 19) }} đơn</div>
                                <div class="podium-bar bar-silver"></div>
                            </div>
                            @endif
                            
                            <!-- Rank 1 -->
                            @if($rank1)
                            <div class="podium-item rank-1 text-center" style="flex: 1.2; z-index: 5;">
                                <div class="avatar-wrap mx-auto">
                                    <div class="rank-badge badge-gold"><i class="fa-solid fa-crown" style="font-size: 0.55rem; margin-top: -1px;"></i></div>
                                    <div class="avatar bg-warning text-white mx-auto" style="width: 55px; height: 55px; font-size: 1.5rem;">{{ strtoupper(substr($rank1->name, 0, 1)) }}</div>
                                </div>
                                <div class="name mt-2 fw-bold text-truncate text-danger mx-auto" style="max-width: 70px; font-size: 0.85rem;">{{ explode(' ', trim($rank1->name))[count(explode(' ', trim($rank1->name)))-1] }}</div>
                                <div class="orders text-muted" style="font-size: 0.7rem;">{{ $rank1->orders_count + (int)\App\Models\SiteSetting::getValue('fake_orders_top1', 30) }} đơn</div>
                                <div class="podium-bar bar-gold"></div>
                            </div>
                            @endif
                            
                            <!-- Rank 3 -->
                            @if($rank3)
                            <div class="podium-item rank-3 text-center" style="flex: 1;">
                                <div class="avatar-wrap mx-auto">
                                    <div class="rank-badge badge-bronze">3</div>
                                    <div class="avatar bg-info text-white mx-auto">{{ strtoupper(substr($rank3->name, 0, 1)) }}</div>
                                </div>
                                <div class="name mt-2 fw-bold text-truncate mx-auto" style="max-width: 60px; font-size: 0.75rem;">{{ explode(' ', trim($rank3->name))[count(explode(' ', trim($rank3->name)))-1] }}</div>
                                <div class="orders text-muted" style="font-size: 0.65rem;">{{ $rank3->orders_count + (int)\App\Models\SiteSetting::getValue('fake_orders_top3', 10) }} đơn</div>
                                <div class="podium-bar bar-bronze"></div>
                            </div>
                            @endif
                        </div>
                        
                        <a href="{{ route('shop') }}" class="tf-join-btn text-white mt-2 w-100 fw-bold py-2" style="background: linear-gradient(45deg, #ff416c, #ff4b2b); border: none; box-shadow: 0 4px 10px rgba(255, 75, 43, 0.3); border-radius: 8px;">
                            <i class="fa-solid fa-cart-shopping me-2"></i>Mua hàng ngay
                        </a>
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
                    <div class="tf-ad mb-3" style="height:600px;">
                        <ins class="adsbygoogle" style="display:block;width:100%;height:600px;"
                            data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"
                            data-ad-format="vertical"></ins>
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
                blogs.forEach(el => el.style.display = '');
                shops.forEach(el => el.style.display = 'none');
            });

            tabAi?.addEventListener('click', function () {
                document.querySelectorAll('.tf-sort-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                blogs.forEach(el => el.style.display = 'none');
                shops.forEach(el => {
                    // combo-ai-wrapper dùng block, tf-widget dùng block, tf-card-shop dùng flex
                    if (el.classList.contains('combo-ai-wrapper')) {
                        el.style.display = 'block';
                    } else if (el.classList.contains('tf-widget')) {
                        el.style.display = 'block';
                    } else {
                        el.style.display = 'flex';
                    }
                });
            });

            // Set default active tab
            // Trigger tabAi click on load to show products by default
            if(tabAi) {
                tabAi.click();
            }

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

        // Filter Combo AI by category
        window.filterComboCat = function(btn, cat) {
            // Update active pill
            document.querySelectorAll('.combo-cat-pill').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');

            // Show/hide category sections
            const sections = document.querySelectorAll('.combo-cat-section');
            sections.forEach(sec => {
                if (cat === 'all' || sec.dataset.catSection === cat) {
                    sec.classList.remove('hidden');
                } else {
                    sec.classList.add('hidden');
                }
            });
        };
    </script>
@endpush