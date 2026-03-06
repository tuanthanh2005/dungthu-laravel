@if(!empty($recentPurchases) && count($recentPurchases) > 0)
    <style>
        /* Recent purchase toast (social proof) */
        .recent-purchase-toast {
            position: fixed;
            left: 18px;
            bottom: 18px;
            width: min(420px, calc(100vw - 36px));
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 18px 50px rgba(0,0,0,0.18);
            border: 1px solid rgba(0,0,0,0.06);
            padding: 14px 14px 12px;
            z-index: 2000;
            opacity: 0;
            transform: translateY(16px);
            pointer-events: none;
            transition: opacity .25s ease, transform .25s ease;
        }
        .recent-purchase-toast.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        .rpt-header {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .rpt-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 13px;
            line-height: 1;
        }
        .rpt-pill.buy { background: #12b76a; color: #fff; }
        .rpt-pill.verify { background: rgba(16,185,129,0.12); color: #0f766e; border: 1px solid rgba(16,185,129,0.25); }
        .rpt-close {
            margin-left: auto;
            border: none;
            background: transparent;
            font-size: 20px;
            line-height: 1;
            color: #6b7280;
            padding: 0 6px;
            cursor: pointer;
        }
        .rpt-body {
            display: flex;
            gap: 12px;
            margin-top: 12px;
        }
        .rpt-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #12b76a, #00cec9);
            color: #fff;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            position: relative;
        }
        .rpt-avatar .rpt-badge {
            position: absolute;
            right: -2px;
            bottom: -2px;
            width: 18px;
            height: 18px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
        }
        .rpt-name { font-weight: 800; font-size: 16px; }
        .rpt-sub { color: #6b7280; font-size: 13px; display: flex; align-items: center; gap: 6px; margin-top: 2px; }
        .rpt-product {
            display: block;
            margin-top: 10px;
            background: rgba(16,185,129,0.12);
            border: 1px solid rgba(16,185,129,0.20);
            border-radius: 12px;
            padding: 10px 12px;
            text-decoration: none;
            color: inherit;
        }
        .rpt-product-title {
            font-weight: 800;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .rpt-product-meta {
            color: #6b7280;
            font-size: 13px;
            margin-top: 4px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        @media (max-width: 576px) {
            .recent-purchase-toast { left: 8px; bottom: 8px; width: min(240px, calc(100vw - 16px)); }
            .recent-purchase-toast { padding: 5px 5px 5px; border-radius: 10px; }
            .rpt-pill { padding: 3px 6px; font-size: 10px; }
            .rpt-close { font-size: 16px; padding: 0 3px; }
            .rpt-body { gap: 6px; margin-top: 6px; }
            .rpt-avatar { width: 26px; height: 26px; font-size: 11px; }
            .rpt-avatar .rpt-badge { width: 12px; height: 12px; right: -1px; bottom: -1px; }
            .rpt-name { font-size: 11px; }
            .rpt-sub { font-size: 9px; }
            .rpt-product { margin-top: 5px; padding: 5px 7px; border-radius: 9px; }
            .rpt-product-title { font-size: 10px; }
            .rpt-product-meta { font-size: 9px; gap: 4px; }
        }
    </style>

    <script type="application/json" id="recentPurchaseData">@json($recentPurchases)</script>
    <div id="recentPurchaseToast" class="recent-purchase-toast" role="status" aria-live="polite" aria-atomic="true">
        <div class="rpt-header">
            <span class="rpt-pill buy">
                <i class="fas fa-star"></i> Vừa mua
            </span>
            <span class="rpt-pill verify">
                <i class="fas fa-check-circle"></i> Đã xác minh
            </span>
            <button type="button" class="rpt-close" id="recentPurchaseClose" aria-label="Đóng">&times;</button>
        </div>

        <div class="rpt-body">
            <div class="rpt-avatar">
                <span id="rptAvatarLetter">N</span>
                <span class="rpt-badge">
                    <i class="fas fa-check-circle" style="font-size: 12px; color: #12b76a;"></i>
                </span>
            </div>

            <div style="min-width:0;flex:1;">
                <div class="rpt-name" id="rptName">Khách hàng</div>
                <div class="rpt-sub">
                    <i class="fas fa-check-circle"></i>
                    <span id="rptAction">vừa mua thành công</span>
                </div>

                <a href="#shop" class="rpt-product" id="rptProductLink">
                    <div class="rpt-product-title" id="rptProductTitle">Sản phẩm</div>
                    <div class="rpt-product-meta">
                        <span id="rptTime">vừa xong</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
             const dataEl = document.getElementById('recentPurchaseData');
             const toastEl = document.getElementById('recentPurchaseToast');
             const closeEl = document.getElementById('recentPurchaseClose');

             if (!dataEl || !toastEl) return;

             const storageKey = 'recentPurchaseToastDismissedUntil';
             const dismissedUntil = parseInt(localStorage.getItem(storageKey) || '0', 10);
             if (dismissedUntil && Date.now() < dismissedUntil) return;

             let purchases = [];
             try {
                 purchases = JSON.parse(dataEl.textContent || '[]');
             } catch (e) {
                 return;
             }
             if (!Array.isArray(purchases) || purchases.length === 0) return;

             const els = {
                 avatarLetter: document.getElementById('rptAvatarLetter'),
                 name: document.getElementById('rptName'),
                 action: document.getElementById('rptAction'),
                 productLink: document.getElementById('rptProductLink'),
                 productTitle: document.getElementById('rptProductTitle'),
                 time: document.getElementById('rptTime'),
             };

             let index = 0;
             let stop = false;
             let hideTimer = null;
             let nextTimer = null;
             const SHOW_MS = 2500; // how long toast stays visible
             const INTERVAL_MS = 10000; // show a new one every 10s

             function getAvatarLetter(name) {
                 const s = String(name || '').trim();
                 const m = s.match(/[A-Za-zÀ-ỹĐđ]/u);
                 return (m ? m[0] : 'K').toUpperCase();
             }

             function getProductText(p) {
                 const base = p?.product_name ? String(p.product_name) : 'Sản phẩm';
                 const extra = Number(p?.extra_items || 0);
                 return extra > 0 ? `${base} +${extra} SP` : base;
             }

             function render(p) {
                 const customerName = p?.customer_name ? String(p.customer_name) : 'Khách hàng';
                 const verbValue = String(p?.verb || '').toLowerCase();
                 const verb = verbValue === 'mua' ? 'mua' : (verbValue === 'đổi' ? 'đổi' : 'đặt');
                 const timeAgo = p?.time_ago ? String(p.time_ago) : '';
                 const productText = getProductText(p);
                 const productUrl = p?.product_url ? String(p.product_url) : '#shop';

                 if (els.avatarLetter) els.avatarLetter.textContent = getAvatarLetter(customerName);
                 if (els.name) els.name.textContent = customerName;
                 if (els.action) els.action.textContent = `vừa ${verb} thành công`;
                 if (els.productTitle) els.productTitle.textContent = productText;
                 if (els.time) els.time.textContent = timeAgo;
                 if (els.productLink) els.productLink.setAttribute('href', productUrl);
             }

             function show() {
                 toastEl.classList.add('show');
             }

             function hide() {
                 toastEl.classList.remove('show');
             }

             function cycle() {
                 if (stop) return;
                 const p = purchases[index % purchases.length];
                 index += 1;

                 render(p);
                 show();

                 if (hideTimer) clearTimeout(hideTimer);
                 if (nextTimer) clearTimeout(nextTimer);

                 hideTimer = setTimeout(function () {
                     hide();
                 }, SHOW_MS);

                 nextTimer = setTimeout(cycle, INTERVAL_MS);
             }

             if (closeEl) {
                 closeEl.addEventListener('click', function () {
                     stop = true;
                     hide();
                     localStorage.setItem(storageKey, String(Date.now() + 60 * 1000));
                 });
             }

             toastEl.addEventListener('mouseenter', function () {
                 if (hideTimer) clearTimeout(hideTimer);
                 if (nextTimer) clearTimeout(nextTimer);
             });
             toastEl.addEventListener('mouseleave', function () {
                 if (stop) return;
                 if (hideTimer) clearTimeout(hideTimer);
                 hideTimer = setTimeout(function () {
                     hide();
                 }, 900);
             });

             setTimeout(cycle, 800);
        });
    </script>
@endif
