@extends('layouts.app')

@section('title', 'Dùng Thử | AI | Blog | Khám Phá')

@section('seo_h1')
    <h1 style="display:none;">Dùng Thử | AI | Blog | Khám Phá</h1>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <style>
        /* =========================================================
        MARQUEE ANNOUNCEMENT & HERO BANNER
        ========================================================= */
        
        /* 1. Marquee Alert Bar styles */
        .marquee-alert-bar {
            background: linear-gradient(135deg, #ff4e00 0%, #ec9f05 100%);
            color: #ffffff;
            overflow: hidden;
            display: flex;
            align-items: center;
            font-size: 13.5px;
            font-weight: 600;
            padding: 10px 0;
            box-shadow: 0 4px 15px rgba(255, 78, 0, 0.15);
            position: relative;
            z-index: 10;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .marquee-track {
            display: flex;
            width: max-content;
            animation: marquee-loop 60s linear infinite;
        }
        
        .marquee-track:hover {
            animation-play-state: paused;
        }
        
        .marquee-item {
            padding: 0 3rem;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .marquee-icon-warning {
            color: #ffe600;
            font-size: 16px;
            animation: pulse-icon 1.5s infinite ease-in-out;
        }

        @keyframes pulse-icon {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.9); }
        }

        @keyframes marquee-loop {
            0% { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-50%, 0, 0); }
        }

        /* 2. Hero Banner styles */
        .hero-banner-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 12px;
        }

        .hero-banner-card {
            background:
                linear-gradient(118deg, rgba(56, 189, 248, 0.08) 0 1px, transparent 1px 18%, rgba(167, 139, 250, 0.1) 18% 19%, transparent 19% 100%),
                linear-gradient(135deg, #08111f 0%, #111827 48%, #172033 100%);
            background-size: 100% 100%, 100% 100%;
            border-radius: 24px;
            padding: 40px 48px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.35);
            isolation: isolate;
            animation: heroTechPulse 8s ease-in-out infinite;
        }

        /* Animated AI grid and data flow */
        .hero-banner-card::before {
            content: '';
            position: absolute;
            inset: -2px;
            background:
                linear-gradient(90deg, rgba(56, 189, 248, 0.14) 1px, transparent 1px),
                linear-gradient(0deg, rgba(129, 140, 248, 0.12) 1px, transparent 1px),
                radial-gradient(circle at 18% 24%, rgba(56, 189, 248, 0.2) 0 2px, transparent 3px),
                radial-gradient(circle at 62% 34%, rgba(167, 139, 250, 0.22) 0 2px, transparent 3px),
                radial-gradient(circle at 84% 70%, rgba(34, 211, 238, 0.18) 0 2px, transparent 3px);
            background-size: 44px 44px, 44px 44px, 180px 130px, 210px 160px, 240px 180px;
            opacity: 0.34;
            transform: translate3d(0, 0, 0);
            animation: aiGridDrift 16s linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        .hero-banner-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(105deg, transparent 0%, transparent 43%, rgba(56, 189, 248, 0.2) 49%, rgba(129, 140, 248, 0.1) 52%, transparent 58%, transparent 100%),
                linear-gradient(90deg, transparent 0%, rgba(34, 211, 238, 0.16) 50%, transparent 100%);
            background-size: 260% 100%, 100% 1px;
            background-position: 160% 0, 0 66%;
            opacity: 0.7;
            animation: aiScanLine 6s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }

        .hero-banner-card > .row {
            position: relative;
            z-index: 2;
        }

        /* Left Side Content */
        .hero-left-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            z-index: 2;
            position: relative;
        }

        .hero-ai-badge {
            align-self: flex-start;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #a5b4fc;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            animation: badgeGlow 3s infinite ease-in-out;
        }

        @keyframes badgeGlow {
            0%, 100% { box-shadow: 0 0 10px rgba(99, 102, 241, 0.1); }
            50% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.3); border-color: rgba(99, 102, 241, 0.5); }
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.25;
            margin-bottom: 18px;
            letter-spacing: -0.025em;
            background: linear-gradient(135deg, #ffffff 40%, #c7d2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 1rem;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 24px;
            max-width: 580px;
        }

        .hero-features {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 28px;
        }

        .hero-feature-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .hero-feature-chip i {
            color: #38bdf8;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .hero-btn-primary {
            background: linear-gradient(135deg, #ff5e00 0%, #ff8e43 100%);
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.92rem;
            padding: 12px 28px;
            border-radius: 14px;
            border: none;
            box-shadow: 0 4px 15px rgba(255, 94, 0, 0.35);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .hero-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 94, 0, 0.5);
        }

        .hero-btn-secondary {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.92rem;
            padding: 12px 28px;
            border-radius: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .hero-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Right Side Showcase */
        .hero-right-showcase {
            position: relative;
            height: 340px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        /* Floating AI Cards Styles */
        .floating-cards-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .floating-cards-wrapper::before,
        .floating-cards-wrapper::after {
            content: '';
            position: absolute;
            inset: 28px 26px;
            border: 1px solid rgba(56, 189, 248, 0.16);
            border-radius: 50%;
            pointer-events: none;
            animation: neuralOrbit 18s linear infinite;
        }

        .floating-cards-wrapper::after {
            inset: 68px 76px;
            border-color: rgba(167, 139, 250, 0.18);
            animation-duration: 13s;
            animation-direction: reverse;
        }

        .floating-ai-card {
            position: absolute;
            width: 170px;
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 14px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            cursor: pointer;
        }

        .floating-ai-card:hover {
            transform: scale(1.08) translateY(-10px) rotateY(-8deg) rotateX(10deg) !important;
            border-color: var(--hover-border);
            box-shadow: 0 20px 35px var(--hover-shadow);
            z-index: 10 !important;
        }

        .floating-ai-card .card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .floating-ai-card .card-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: #fff;
        }

        .floating-ai-card .card-status {
            font-size: 0.65rem;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .floating-ai-card .card-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 2px;
        }

        .floating-ai-card .card-desc {
            font-size: 0.68rem;
            color: #94a3b8;
            margin-bottom: 10px;
        }

        .floating-ai-card .card-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .floating-ai-card .card-price {
            color: #38bdf8;
        }

        /* Float Positions & Animations */
        .card-chatgpt {
            left: 2%;
            top: 12%;
            z-index: 4;
            --hover-border: rgba(16, 185, 129, 0.4);
            --hover-shadow: rgba(16, 185, 129, 0.2);
            animation: float-slow 6s ease-in-out infinite alternate;
        }
        .card-chatgpt .card-icon { background: #10b981; }
        .card-chatgpt .card-status { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .card-chatgpt .card-price { color: #34d399; }

        .card-claude {
            right: 2%;
            top: 5%;
            z-index: 3;
            --hover-border: rgba(249, 115, 22, 0.4);
            --hover-shadow: rgba(249, 115, 22, 0.2);
            animation: float-medium 5s ease-in-out infinite alternate-reverse;
        }
        .card-claude .card-icon { background: #f97316; }
        .card-claude .card-status { background: rgba(249, 115, 22, 0.15); color: #fb923c; }
        .card-claude .card-price { color: #fb923c; }

        .card-vpn {
            left: 20%;
            bottom: 4%;
            z-index: 5;
            --hover-border: rgba(139, 92, 246, 0.4);
            --hover-shadow: rgba(139, 92, 246, 0.2);
            animation: float-fast 4s ease-in-out infinite alternate;
        }
        .card-vpn .card-icon { background: #8b5cf6; }
        .card-vpn .card-status { background: rgba(139, 92, 246, 0.15); color: #a78bfa; }
        .card-vpn .card-price { color: #a78bfa; }

        .card-cursor {
            right: 18%;
            bottom: 26%;
            z-index: 4;
            --hover-border: rgba(56, 189, 248, 0.4);
            --hover-shadow: rgba(56, 189, 248, 0.2);
            animation: float-slow 7s ease-in-out infinite alternate-reverse;
        }
        .card-cursor .card-icon { background: #0ea5e9; }
        .card-cursor .card-status { background: rgba(56, 189, 248, 0.15); color: #38bdf8; }
        .card-cursor .card-price { color: #38bdf8; }

        @keyframes float-slow {
            0% { transform: translateY(0); }
            100% { transform: translateY(-16px); }
        }

        @keyframes float-medium {
            0% { transform: translateY(0); }
            100% { transform: translateY(-12px); }
        }

        @keyframes float-fast {
            0% { transform: translateY(0); }
            100% { transform: translateY(-8px); }
        }

        @keyframes heroTechPulse {
            0%, 100% {
                border-color: rgba(255, 255, 255, 0.08);
                box-shadow: 0 20px 40px rgba(15, 23, 42, 0.35), inset 0 0 0 1px rgba(56, 189, 248, 0.04);
            }
            50% {
                border-color: rgba(56, 189, 248, 0.22);
                box-shadow: 0 22px 46px rgba(15, 23, 42, 0.42), inset 0 0 28px rgba(56, 189, 248, 0.08);
            }
        }

        @keyframes aiGridDrift {
            0% { background-position: 0 0, 0 0, 0 0, 40px 20px, 80px 40px; }
            100% { background-position: 88px 44px, 44px 88px, 180px 130px, 250px 180px, 320px 220px; }
        }

        @keyframes aiScanLine {
            0%, 18% { background-position: 160% 0, 0 66%; opacity: 0; }
            42%, 58% { opacity: 0.7; }
            82%, 100% { background-position: -90% 0, 0 66%; opacity: 0; }
        }

        @keyframes neuralOrbit {
            0% { transform: rotate(0deg) scale(0.98); opacity: 0.32; }
            50% { opacity: 0.62; }
            100% { transform: rotate(360deg) scale(0.98); opacity: 0.32; }
        }

        /* Responsive Hero Banner */
        @media (max-width: 991px) {
            .hero-banner-card {
                padding: 32px;
            }
            .hero-title {
                font-size: 2rem;
            }
            .hero-right-showcase {
                margin-top: 30px;
                height: 280px;
            }
            .floating-ai-card {
                width: 140px;
                padding: 10px;
            }
            .floating-ai-card .card-desc {
                display: none;
            }
            .card-chatgpt { left: 0%; top: 5%; }
            .card-claude { right: 0%; top: 0%; }
            .card-vpn { left: 15%; bottom: 0%; }
            .card-cursor { right: 15%; bottom: 5%; }
        }

        @media (max-width: 575px) {
            .hero-banner-card {
                padding: 24px 20px;
                border-radius: 16px;
            }
            .hero-title {
                font-size: 1.6rem;
            }
            .hero-desc {
                font-size: 0.88rem;
            }
            .hero-actions {
                flex-direction: column;
                gap: 12px;
            }
            .hero-btn-primary, .hero-btn-secondary {
                width: 100%;
                justify-content: center;
            }
            .hero-right-showcase {
                display: none; /* Hide floating cards on small mobiles to save space */
            }
        }

        /* =========================================================
        TECHFEED HOME — matching test.html prototype exactly
        ========================================================= */
        
        /* ---- Body override for this page ---- */

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
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 12px;
        }

        @media(min-width:1024px) {
            .tf-layout {
                /* grid-template-columns: 1fr 300px; */
                grid-template-columns: 1fr;
            }
        }

        @media(min-width:1280px) {
            .tf-layout {
                /* grid-template-columns: 1fr 320px; */
                grid-template-columns: 1fr;
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

        @media(min-width: 1200px) {
            .combo-product-grid {
                grid-template-columns: repeat(6, 1fr);
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
        .combo-prod-card.out-of-stock {
            opacity: 0.85;
        }

        .combo-prod-card.out-of-stock .img-wrap::after {
            content: 'HẾT HÀNG';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            background: rgba(229, 57, 53, 0.9);
            color: #fff;
            padding: 5px 15px;
            font-size: 0.8rem;
            font-weight: 800;
            border-radius: 4px;
            z-index: 5;
            letter-spacing: 1px;
            pointer-events: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .combo-prod-card.out-of-stock .add-cart-btn {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none !important;
            opacity: 0.7;
        }

        /* Official Fanpages Section */
        .official-fanpages-container {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .official-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 14px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .official-header-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .official-header-title h3 {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1f2937;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .official-badge-verify {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .official-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 16px;
        }

        @media (min-width: 640px) {
            .official-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .official-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .official-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            color: inherit;
            position: relative;
            overflow: hidden;
        }

        .official-card:hover {
            transform: translateY(-4px);
            background: #ffffff;
            text-decoration: none;
            color: inherit;
        }

        /* Brand themes for hover glow */
        .official-card.platform-facebook:hover {
            border-color: #1877f2;
            box-shadow: 0 8px 24px rgba(24, 119, 242, 0.12);
        }
        .official-card.platform-zalo:hover {
            border-color: #0068ff;
            box-shadow: 0 8px 24px rgba(0, 104, 255, 0.12);
        }
        .official-card.platform-telegram:hover {
            border-color: #0088cc;
            box-shadow: 0 8px 24px rgba(0, 136, 204, 0.12);
        }
        .official-card.platform-youtube:hover {
            border-color: #ff0000;
            box-shadow: 0 8px 24px rgba(255, 0, 0, 0.12);
        }
        .official-card.platform-tiktok:hover {
            border-color: #010101;
            box-shadow: 0 8px 24px rgba(1, 1, 1, 0.12);
        }
        .official-card.platform-globe:hover {
            border-color: #10b981;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.12);
        }

        .platform-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #ffffff;
            flex-shrink: 0;
        }

        .bg-facebook { background: #1877f2; }
        .bg-zalo { background: #0068ff; }
        .bg-telegram { background: #0088cc; }
        .bg-youtube { background: #ff0000; }
        .bg-tiktok { background: #010101; }
        .bg-globe { background: #10b981; }

        .official-card-info {
            flex: 1;
            min-width: 0;
        }

        .official-card-title {
            font-weight: 700;
            font-size: 0.9rem;
            color: #111827;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .official-card-desc {
            font-size: 0.78rem;
            color: #6b7280;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 6px;
        }

        .official-card-badge-verified {
            color: #10b981;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
        }

        .official-action-link {
            font-size: 0.76rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: color 0.2s;
        }

        .platform-facebook .official-action-link { color: #1877f2; }
        .platform-zalo .official-action-link { color: #0068ff; }
        .platform-telegram .official-action-link { color: #0088cc; }
        .platform-youtube .official-action-link { color: #ff0000; }
        .platform-tiktok .official-action-link { color: #010101; }
        .platform-globe .official-action-link { color: #10b981; }

        .official-card:hover .official-action-link {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <!-- 1. Marquee Alert Bar (Looping announcement) -->
    <div class="marquee-alert-bar">
        <div class="marquee-track">
            <!-- Part 1 -->
            <div class="marquee-item">
                <i class="fa-solid fa-triangle-exclamation marquee-icon-warning"></i>
                <span>CẢNH BÁO: Đang có đối tượng giả danh Fanpage / Admin DungThu.com. Chỉ giao dịch qua website hoặc trang liên hệ chính chủ!</span>
                <span>•</span>
                <i class="fa-solid fa-clock" style="color: #38bdf8;"></i>
                <span>Giờ làm việc hỗ trợ khách hàng: 08:00 - 23:00 hàng ngày.</span>
            </div>
            <div class="marquee-item">
                <i class="fa-solid fa-triangle-exclamation marquee-icon-warning"></i>
                <span>CẢNH BÁO: Đang có đối tượng giả danh Fanpage / Admin DungThu.com. Chỉ giao dịch qua website hoặc trang liên hệ chính chủ!</span>
                <span>•</span>
                <i class="fa-solid fa-clock" style="color: #38bdf8;"></i>
                <span>Giờ làm việc hỗ trợ khách hàng: 08:00 - 23:00 hàng ngày.</span>
            </div>
            <!-- Part 2 (Duplicate for seamless loop) -->
            <div class="marquee-item">
                <i class="fa-solid fa-triangle-exclamation marquee-icon-warning"></i>
                <span>CẢNH BÁO: Đang có đối tượng giả danh Fanpage / Admin DungThu.com. Chỉ giao dịch qua website hoặc trang liên hệ chính chủ!</span>
                <span>•</span>
                <i class="fa-solid fa-clock" style="color: #38bdf8;"></i>
                <span>Giờ làm việc hỗ trợ khách hàng: 08:00 - 23:00 hàng ngày.</span>
            </div>
            <div class="marquee-item">
                <i class="fa-solid fa-triangle-exclamation marquee-icon-warning"></i>
                <span>CẢNH BÁO: Đang có đối tượng giả danh Fanpage / Admin DungThu.com. Chỉ giao dịch qua website hoặc trang liên hệ chính chủ!</span>
                <span>•</span>
                <i class="fa-solid fa-clock" style="color: #38bdf8;"></i>
                <span>Giờ làm việc hỗ trợ khách hàng: 08:00 - 23:00 hàng ngày.</span>
            </div>
        </div>
    </div>

    <div style="background:#dae0e6;">
        <!-- 2. Hero Banner Section -->
        <div class="hero-banner-container d-none d-lg-block">
            <div class="hero-banner-card">
                <div class="row align-items-center g-4">
                    <!-- Left Column: Content & Call to Action -->
                    <div class="col-lg-7">
                        <div class="hero-left-content">
                            <div class="hero-ai-badge">
                                <i class="fa-solid fa-bolt"></i> DungThu.com AI Hub
                            </div>
                            <h2 class="hero-title">Khám Phá Sức Mạnh AI<br>Tối Ưu Hiệu Suất Công Việc</h2>
                            <p class="hero-desc">
                                Cung cấp tài khoản premium giá rẻ, phần mềm chuyên dụng và giải pháp tự động hóa giúp bạn bứt phá năng suất làm việc mỗi ngày. Nhanh chóng - Uy tín - Bảo hành trọn đời.
                            </p>
                            
                            <!-- Lợi ích nổi bật -->
                            <div class="hero-features">
                                <div class="hero-feature-chip">
                                    <i class="fa-solid fa-circle-check"></i> Kích hoạt tức thì
                                </div>
                                <div class="hero-feature-chip">
                                    <i class="fa-solid fa-shield-halved"></i> Bảo hành uy tín
                                </div>
                                <div class="hero-feature-chip">
                                    <i class="fa-solid fa-headset"></i> Hỗ trợ 24/7
                                </div>
                            </div>

                            <!-- Các hành động -->
                            <div class="hero-actions">
                                <a href="{{ route('shop') }}" class="hero-btn-primary">
                                    <i class="fa-solid fa-store"></i> Mua Tài Khoản AI
                                </a>
                                <a href="{{ \App\Models\SiteSetting::getValue('zalo_group_link', 'https://zalo.me/g/ptarfhnomeuotiyk7cot') }}" 
                                   target="_blank" class="hero-btn-secondary">
                                    <i class="fa-solid fa-users"></i> Nhóm Zalo Hỗ Trợ
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: 3D Floating Showcase -->
                    <div class="col-lg-5">
                        <div class="hero-right-showcase">
                            <div class="floating-cards-wrapper">
                                <!-- Card ChatGPT -->
                                <div class="floating-ai-card card-chatgpt" onclick="window.location.href='{{ route('shop') }}?search=ChatGPT'">
                                    <div class="card-head">
                                        <div class="card-icon"><i class="fa-solid fa-message"></i></div>
                                        <div class="card-status">Hot</div>
                                    </div>
                                    <div class="card-name">ChatGPT Plus</div>
                                    <div class="card-desc">Sử dụng GPT-4, DALL-E 3 & các công cụ phân tích nâng cao.</div>
                                    <div class="card-foot">
                                        <span class="text-muted">Giá từ</span>
                                        <span class="card-price">60.000đ</span>
                                    </div>
                                </div>

                                <!-- Card Claude AI -->
                                <div class="floating-ai-card card-claude" onclick="window.location.href='{{ route('shop') }}?search=Claude'">
                                    <div class="card-head">
                                        <div class="card-icon"><i class="fa-solid fa-brain"></i></div>
                                        <div class="card-status">Vip</div>
                                    </div>
                                    <div class="card-name">Claude AI Pro</div>
                                    <div class="card-desc">Sở hữu Claude 3.5 Sonnet với giới hạn chat lớn nhất.</div>
                                    <div class="card-foot">
                                        <span class="text-muted">Giá từ</span>
                                        <span class="card-price">89.000đ</span>
                                    </div>
                                </div>

                                <!-- Card VPN -->
                                <div class="floating-ai-card card-vpn" onclick="window.location.href='{{ route('shop') }}?search=VPN'">
                                    <div class="card-head">
                                        <div class="card-icon"><i class="fa-solid fa-shield-halved"></i></div>
                                        <div class="card-status">New</div>
                                    </div>
                                    <div class="card-name">VPN Premium</div>
                                    <div class="card-desc">Bảo mật thông tin, truy cập website bị chặn & tăng tốc internet.</div>
                                    <div class="card-foot">
                                        <span class="text-muted">Giá từ</span>
                                        <span class="card-price">20.000đ</span>
                                    </div>
                                </div>

                                <!-- Card Cursor Pro -->
                                <div class="floating-ai-card card-cursor" onclick="window.location.href='{{ route('shop') }}?search=Cursor'">
                                    <div class="card-head">
                                        <div class="card-icon"><i class="fa-solid fa-code"></i></div>
                                        <div class="card-status">Sale</div>
                                    </div>
                                    <div class="card-name">Cursor Pro</div>
                                    <div class="card-desc">Trình soạn thảo code AI bứt phá tốc độ lập trình.</div>
                                    <div class="card-foot">
                                        <span class="text-muted">Giá từ</span>
                                        <span class="card-price">150.000đ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tf-layout">


            @php
                $sb_cardexchange = \App\Models\SiteSetting::getValue('menu_card_exchange', '1') === '1';
            @endphp

            {{-- ====== MAIN FEED ====== --}}
            <main>

                {{-- 0. Official Fanpages Verification Section --}}
                @php
                    $fanpagesJson = \App\Models\SiteSetting::getValue('official_fanpages', '[]');
                    $officialFanpages = json_decode($fanpagesJson, true) ?: [];
                @endphp

                @if(count($officialFanpages) > 0)
                    <div class="official-fanpages-container mb-4 d-none d-lg-block">
                        <div class="official-header">
                            <div class="official-header-title">
                                <i class="fa-solid fa-shield-halved text-success fs-5"></i>
                                <h3>Kênh liên hệ & Fanpage chính thức</h3>
                            </div>
                            <div class="official-badge-verify">
                                <i class="fa-solid fa-circle-check"></i> Đã xác minh
                            </div>
                        </div>
                        <div class="official-grid">
                            @foreach($officialFanpages as $fp)
                                @php
                                    $platform = strtolower($fp['platform'] ?? 'facebook');
                                    $iconClass = 'fa-solid fa-globe';
                                    $brandClass = 'bg-globe';
                                    if ($platform === 'facebook') {
                                        $iconClass = 'fa-brands fa-facebook-f';
                                        $brandClass = 'bg-facebook';
                                    } elseif ($platform === 'zalo') {
                                        $iconClass = '';
                                        $brandClass = 'bg-zalo';
                                    } elseif ($platform === 'telegram') {
                                        $iconClass = 'fa-brands fa-telegram';
                                        $brandClass = 'bg-telegram';
                                    } elseif ($platform === 'youtube') {
                                        $iconClass = 'fa-brands fa-youtube';
                                        $brandClass = 'bg-youtube';
                                    } elseif ($platform === 'tiktok') {
                                        $iconClass = 'fa-brands fa-tiktok';
                                        $brandClass = 'bg-tiktok';
                                    }
                                @endphp
                                <a href="{{ $fp['url'] }}" target="_blank" class="official-card platform-{{ $platform }}">
                                    <div class="platform-icon-wrap {{ $brandClass }}">
                                        @if($platform === 'zalo')
                                            <span style="font-weight: 900; font-style: italic; font-size: 1.4rem;">Z</span>
                                        @else
                                            <i class="{{ $iconClass }}"></i>
                                        @endif
                                    </div>
                                    <div class="official-card-info">
                                        <div class="official-card-title">
                                            {{ $fp['name'] }}
                                            <span class="official-card-badge-verified" title="Trang chính chủ đã xác minh">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </span>
                                        </div>
                                        @if(!empty($fp['desc']))
                                            <div class="official-card-desc">{{ $fp['desc'] }}</div>
                                        @else
                                            <div class="official-card-desc">Kênh hỗ trợ và thông báo chính thức của công ty.</div>
                                        @endif
                                        <div class="official-action-link">
                                            Truy cập ngay <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.7rem;"></i>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Sort bar --}}


                {{-- 1. Flash Sale (Ưu tiên) --}}
                {{-- 1. Flash Sale (Ưu tiên) --}}
                @if(\App\Models\SiteSetting::getValue('home_show_flash_sale', '1') === '1' && isset($saleProducts) && $saleProducts->count() > 0)
                    <div class="tf-widget mb-4" id="flash-sale"
                        data-countdown-end="{{ $saleEndsAt?->getTimestamp() * 1000 }}"
                        data-is-expired="{{ $isExpired ? '1' : '0' }}">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <div>
                                <span class="text-danger fw-bold"
                                    style="font-size:.75rem;text-transform:uppercase;letter-spacing:.04em;">⚡ Flash Sale</span>
                                <div class="fw-bold" id="flash-sale-title" style="font-size:1.1rem;">
                                    {{ $isExpired ? 'Sản phẩm gợi ý cho bạn' : 'Giảm giá sốc hôm nay' }}
                                </div>
                            </div>
                            <div id="flash-sale-timer-container"
                                style="background:#fff;border-radius:20px;padding:6px 12px;box-shadow:0 2px 8px rgba(0,0,0,.08);display:flex;align-items:center;gap:8px;font-size:.8rem; {{ $isExpired ? 'opacity: 0.6;' : '' }}">
                                <span style="font-weight:800;">
                                    <span data-unit="hours">00</span>:<span data-unit="minutes">00</span>:<span
                                        data-unit="seconds">00</span>
                                </span>
                            </div>
                        </div>
                        <div class="row row-cols-2 row-cols-md-4 row-cols-xl-6 g-3" id="flash-sale-products-row">
                            @foreach($saleProducts->take(6) as $sp)
                                <div class="col">
                                    <div
                                        style="background:#fff;border:1px solid #edeff1;border-radius:10px;overflow:hidden;position:relative; {{ $sp->stock <= 0 ? 'opacity: 0.7;' : '' }}">
                                        @if($sp->stock <= 0)
                                            <div style="position: absolute; top: 10px; right: 10px; background: #e53935; color: #fff; font-size: 0.65rem; font-weight: 800; padding: 2px 6px; border-radius: 4px; z-index: 10;">HẾT HÀNG</div>
                                        @endif
                                        <img src="{{ $sp->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $sp->name }}"
                                            style="width:100%;height:120px;object-fit:cover;">
                                        <div style="padding:8px;">
                                            <div style="font-size:.8rem;font-weight:700;height:40px;overflow:hidden;">
                                                {{ $sp->name }}</div>
                                            <div style="font-size: 0.72rem; color: #6b7280; font-weight: 600; margin-bottom: 2px;">
                                                Còn: <span class="{{ $sp->stock <= 0 ? 'text-danger' : 'text-success' }}">{{ $sp->stock }}</span>
                                            </div>
                                            <div style="color:#e53935;font-weight:800;">{{ $sp->formatted_price }}</div>
                                        </div>
                                        <a href="{{ route('product.show', $sp->slug) }}" class="stretched-link"></a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="combo-ai-wrapper">
                    {{-- 2. Sản Phẩm Nổi Bật (Hàng đầu tiên) --}}
                    @if(\App\Models\SiteSetting::getValue('home_show_featured', '1') === '1' && isset($featuredProducts) && $featuredProducts->count() > 0)
                    <div class="combo-cat-section">
                        <div class="combo-cat-title" style="border-bottom:none; margin-bottom: 15px; font-size: 1.1rem; padding-bottom: 0;">
                            <i class="fa-solid fa-star" style="color:#ffca28;"></i> Sản Phẩm Nổi Bật
                        </div>
                        <div class="combo-product-grid">
                            @foreach($featuredProducts->take(6) as $fp)
                            <a href="{{ route('product.show', $fp->slug) }}" class="combo-prod-card {{ $fp->stock <= 0 ? 'out-of-stock' : '' }}">
                                <div class="img-wrap">
                                    <img src="{{ $fp->image ?? 'https://via.placeholder.com/300x225?text=Product' }}"
                                         alt="{{ $fp->name }}" loading="lazy">
                                    @if($fp->is_on_sale)
                                        <span class="discount-badge">-{{ $fp->discount_percent }}%</span>
                                    @endif
                                    <span class="ai-label" style="background: linear-gradient(135deg, #ff416c, #ff4b2b);">Hot</span>
                                </div>
                                <div class="card-body">
                                    <div class="prod-name">{{ $fp->name }}</div>
                                    <div style="font-size: 0.72rem; color: #6b7280; font-weight: 600; margin-bottom: 4px;">
                                        Còn: <span class="{{ $fp->stock <= 0 ? 'text-danger' : 'text-success' }}">{{ $fp->stock }}</span>
                                    </div>
                                    <div class="prod-price-row">
                                        <span class="prod-price">{{ $fp->formatted_price }}</span>
                                        @if($fp->is_on_sale)
                                            <span class="prod-price-old">{{ $fp->formatted_original_price }}</span>
                                        @endif
                                    </div>
                                    <form action="{{ route('cart.add', $fp->id) }}" method="POST"
                                          onclick="event.preventDefault(); this.submit();">
                                        @csrf
                                        <button type="submit" class="add-cart-btn" style="background: linear-gradient(135deg, #ff416c, #ff4b2b);">
                                            <i class="fa-solid fa-cart-plus"></i> {{ $fp->stock > 0 ? 'Thêm vào giỏ' : 'Thêm vào giỏ (Đặt trước)' }}
                                        </button>
                                    </form>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- 3. Sản Phẩm Độc Quyền (Hàng thứ 2) --}}
                    @if(\App\Models\SiteSetting::getValue('home_show_exclusive', '1') === '1' && isset($highlightProducts) && $highlightProducts->count() > 0)
                    <div class="combo-cat-section mt-4">
                        <div class="combo-cat-title" style="border-bottom:none; margin-bottom: 15px; font-size: 1.1rem; padding-bottom: 0;">
                            <i class="fa-solid fa-gem" style="color:#8b5cf6;"></i> Sản Phẩm Độc Quyền
                        </div>
                        <div class="combo-product-grid">
                            @foreach($highlightProducts->take(6) as $hp)
                            <a href="{{ route('product.show', $hp->slug) }}" class="combo-prod-card {{ $hp->stock <= 0 ? 'out-of-stock' : '' }}">
                                <div class="img-wrap">
                                    <img src="{{ $hp->image ?? 'https://via.placeholder.com/300x225?text=Product' }}"
                                         alt="{{ $hp->name }}" loading="lazy">
                                    @if($hp->is_on_sale)
                                        <span class="discount-badge">-{{ $hp->discount_percent }}%</span>
                                    @endif
                                    <span class="ai-label" style="background: linear-gradient(135deg, #8b5cf6, #ec4899);">Vip</span>
                                </div>
                                <div class="card-body">
                                    <div class="prod-name">{{ $hp->name }}</div>
                                    <div style="font-size: 0.72rem; color: #6b7280; font-weight: 600; margin-bottom: 4px;">
                                        Còn: <span class="{{ $hp->stock <= 0 ? 'text-danger' : 'text-success' }}">{{ $hp->stock }}</span>
                                    </div>
                                    <div class="prod-price-row">
                                        <span class="prod-price">{{ $hp->formatted_price }}</span>
                                        @if($hp->is_on_sale)
                                            <span class="prod-price-old">{{ $hp->formatted_original_price }}</span>
                                        @endif
                                    </div>
                                    <form action="{{ route('cart.add', $hp->id) }}" method="POST"
                                          onclick="event.preventDefault(); this.submit();">
                                        @csrf
                                        <button type="submit" class="add-cart-btn" style="background: linear-gradient(135deg, #8b5cf6, #ec4899);">
                                            <i class="fa-solid fa-cart-plus"></i> {{ $hp->stock > 0 ? 'Thêm vào giỏ' : 'Thêm vào giỏ (Đặt trước)' }}
                                        </button>
                                    </form>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- 4. Combo AI Giá Rẻ --}}
                    @if(\App\Models\SiteSetting::getValue('home_show_combo_ai', '1') === '1' && isset($latestProducts) && $latestProducts->count() > 0)
                    <div class="combo-cat-section mt-4">
                        <div class="combo-cat-title" style="border-bottom:none; margin-bottom: 15px; font-size: 1.1rem; padding-bottom: 0;">
                            <i class="fa-solid fa-box-open" style="color:#6366f1;"></i> Combo AI Giá Rẻ
                        </div>
                        <div class="combo-product-grid">
                            @foreach($latestProducts->take(12) as $cp)
                            <a href="{{ route('product.show', $cp->slug) }}" class="combo-prod-card {{ $cp->stock <= 0 ? 'out-of-stock' : '' }}">
                                <div class="img-wrap">
                                    <img src="{{ $cp->image ?? 'https://via.placeholder.com/300x225?text=Product' }}"
                                         alt="{{ $cp->name }}" loading="lazy">
                                    @if($cp->is_on_sale)
                                        <span class="discount-badge">-{{ $cp->discount_percent }}%</span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="prod-name">{{ $cp->name }}</div>
                                    <div style="font-size: 0.72rem; color: #6b7280; font-weight: 600; margin-bottom: 4px;">
                                        Còn: <span class="{{ $cp->stock <= 0 ? 'text-danger' : 'text-success' }}">{{ $cp->stock }}</span>
                                    </div>
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
                                            <i class="fa-solid fa-cart-plus"></i> {{ $cp->stock > 0 ? 'Thêm vào giỏ' : 'Thêm vào giỏ (Đặt trước)' }}
                                        </button>
                                    </form>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    {{-- View all --}}
                    <div class="combo-view-all mt-4">
                        <a href="{{ route('shop') }}">
                            <i class="fa-solid fa-store"></i> Xem tất cả sản phẩm
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>





            </main>

            @if(false) {{-- ====== RIGHT SIDEBAR ====== --}}
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
                    @if(\App\Models\SiteSetting::getValue('adsense_enabled', '1') === '1')
                    <div class="ads-wrapper ads-sidebar-260 mb-3">
                        <ins class="adsbygoogle" style="display:block;width:100%;height:260px;"
                            data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"
                            data-ad-format="rectangle"></ins>
                        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                    </div>
                    @endif

                    {{-- Flash Sale sidebar --}}
                    @if(isset($saleProducts) && $saleProducts->count() > 0)
                        <div class="tf-widget">
                            <div class="tf-widget-title" id="sidebar-flash-sale-title">
                                ⚡ {{ $isExpired ? 'Gợi Ý Cho Bạn' : 'Flash Sale' }} &nbsp;
                                <span id="sf-timer" style="color:#e53935;font-size:.72rem;font-weight:700;"></span>
                            </div>
                            <div id="sidebar-flash-sale-products">
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
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="price">{{ $prod->formatted_price }}</div>
                                            @if($prod->stock <= 0)
                                                <span class="text-danger fw-bold" style="font-size: 0.7rem;">Hết hàng</span>
                                            @endif
                                        </div>
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
                    @if(\App\Models\SiteSetting::getValue('adsense_enabled', '1') === '1')
                    <div class="ads-wrapper ads-sidebar-600 mb-3">
                        <ins class="adsbygoogle" style="display:block;width:100%;height:600px;"
                            data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"
                            data-ad-format="vertical"></ins>
                        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                    </div>
                    @endif

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
            @endif

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
            // Tab switching logic removed as sort bar is hidden
            document.querySelectorAll('.item-shop, .item-blog, .combo-ai-wrapper, .tf-widget').forEach(el => {
                el.style.display = 'block';
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
                const isExpired = fs.dataset.isExpired === '1';
                const pad = n => String(n).padStart(2, '0');
                
                const updateTimers = (H, M, S) => {
                    const timeString = `${pad(H)}:${pad(M)}:${pad(S)}`;
                    // Main timer
                    fs.querySelector('[data-unit="hours"]').textContent = pad(H);
                    fs.querySelector('[data-unit="minutes"]').textContent = pad(M);
                    fs.querySelector('[data-unit="seconds"]').textContent = pad(S);
                    // Sidebar timer
                    const sfTimer = document.getElementById('sf-timer');
                    if (sfTimer) {
                        sfTimer.textContent = timeString;
                    }
                };

                if (isExpired) {
                    updateTimers(0, 0, 0);
                } else {
                    const timerInterval = setInterval(() => {
                        let d = end - Date.now();
                        if (d <= 0) {
                            clearInterval(timerInterval);
                            updateTimers(0, 0, 0);
                            
                            // Trigger transition to random products
                            const titleEl = document.getElementById('flash-sale-title');
                            if (titleEl) {
                                titleEl.textContent = 'Sản phẩm gợi ý cho bạn';
                            }
                            const sidebarTitleEl = document.getElementById('sidebar-flash-sale-title');
                            if (sidebarTitleEl) {
                                sidebarTitleEl.innerHTML = `⚡ Gợi Ý Cho Bạn &nbsp;<span id="sf-timer" style="color:#e53935;font-size:.72rem;font-weight:700;">00:00:00</span>`;
                            }
                            const timerContainer = document.getElementById('flash-sale-timer-container');
                            if (timerContainer) {
                                timerContainer.style.opacity = '0.6';
                            }

                            fetch('{{ route('products.random') }}')
                                .then(res => res.json())
                                .then(products => {
                                    // Main Row
                                    const mainRow = document.getElementById('flash-sale-products-row');
                                    if (mainRow && products) {
                                        mainRow.innerHTML = products.map(sp => {
                                            const outOfStockBadge = sp.stock <= 0 ? `<div style="position: absolute; top: 10px; right: 10px; background: #e53935; color: #fff; font-size: 0.65rem; font-weight: 800; padding: 2px 6px; border-radius: 4px; z-index: 10;">HẾT HÀNG</div>` : '';
                                            const opacityStyle = sp.stock <= 0 ? 'opacity: 0.7;' : '';
                                            const stockColorClass = sp.stock <= 0 ? 'text-danger' : 'text-success';
                                            const linkTag = sp.stock > 0 ? `<a href="${sp.show_url}" class="stretched-link"></a>` : '';
                                            
                                            return `
                                                <div class="col">
                                                    <div style="background:#fff;border:1px solid #edeff1;border-radius:10px;overflow:hidden;position:relative; ${opacityStyle}">
                                                        ${outOfStockBadge}
                                                        <img src="${sp.image}" alt="${sp.name}" style="width:100%;height:120px;object-fit:cover;">
                                                        <div style="padding:8px;">
                                                            <div style="font-size:.8rem;font-weight:700;height:40px;overflow:hidden;">${sp.name}</div>
                                                            <div style="font-size: 0.72rem; color: #6b7280; font-weight: 600; margin-bottom: 2px;">
                                                                Còn: <span class="${stockColorClass}">${sp.stock}</span>
                                                            </div>
                                                            <div style="color:#e53935;font-weight:800;">${sp.formatted_price}</div>
                                                        </div>
                                                        ${linkTag}
                                                    </div>
                                                </div>
                                            `;
                                        }).join('');
                                    }

                                    // Sidebar
                                    const sidebarContainer = document.getElementById('sidebar-flash-sale-products');
                                    if (sidebarContainer && products) {
                                        sidebarContainer.innerHTML = products.slice(0, 3).map(sp => {
                                            return `
                                                <a href="${sp.show_url}" class="tf-flash-item">
                                                    <img src="${sp.image}" alt="${sp.name}" loading="lazy">
                                                    <div>
                                                        <div class="fn">${sp.name}</div>
                                                        <div class="d-flex align-items-baseline gap-2 mt-1">
                                                            <span class="fp">${sp.formatted_price}</span>
                                                            <span class="fo">${sp.formatted_original_price}</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            `;
                                        }).join('');
                                    }
                                })
                                .catch(err => console.error('Error fetching random products:', err));
                        } else {
                            const H = Math.floor(d / 3600000); d %= 3600000;
                            const M = Math.floor(d / 60000);
                            const S = Math.floor((d % 60000) / 1000);
                            updateTimers(H, M, S);
                        }
                    }, 1000);
                }
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
