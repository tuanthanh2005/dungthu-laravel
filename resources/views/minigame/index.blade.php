@extends('layouts.app')

@section('title', 'Vòng Xoay May Mắn - Mini Game')

@push('styles')
<style>
    .game-page-wrapper {
        background: radial-gradient(circle at 50% 50%, #1a1f38 0%, #0f1123 100%);
        min-height: 100vh;
        padding: 100px 0 60px;
        color: white;
        font-family: 'Inter', sans-serif;
    }

    .ticket-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .ticket-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 94, 0, 0.15) 0%, transparent 60%);
        pointer-events: none;
    }

    .ticket-count {
        font-size: 4rem;
        font-weight: 900;
        background: linear-gradient(135deg, #ff5e00 0%, #ff9e59 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1;
        margin: 10px 0;
        text-shadow: 0 0 20px rgba(255, 94, 0, 0.3);
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .ticket-label {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: rgba(255, 255, 255, 0.6);
        font-weight: 700;
    }

    /* Lucky Wheel Container */
    .wheel-outer-wrap {
        position: relative;
        width: 100%;
        max-width: 440px;
        margin: 0 auto 40px;
        padding: 10px;
    }

    /* Glowing outer ring */
    .wheel-glow {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        border-radius: 50%;
        background: radial-gradient(circle, transparent 65%, rgba(255, 94, 0, 0.4) 100%);
        box-shadow: 0 0 30px rgba(255, 94, 0, 0.2), inset 0 0 30px rgba(255, 94, 0, 0.2);
        pointer-events: none;
        animation: pulseGlow 3s infinite alternate;
    }

    @keyframes pulseGlow {
        0% { transform: scale(0.99); opacity: 0.8; }
        100% { transform: scale(1.01); opacity: 1; }
    }

    /* Wheel pointer */
    .wheel-pointer {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 45px;
        height: 55px;
        z-index: 10;
        filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.5));
    }

    .wheel-pointer::before {
        content: '';
        display: block;
        width: 0; height: 0;
        border-left: 20px solid transparent;
        border-right: 20px solid transparent;
        border-top: 35px solid #ff5e00;
    }

    /* Rotating Wheel */
    .wheel-main {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 50%;
        border: 12px solid #22253f;
        background: #15182d;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
        transition: transform 5s cubic-bezier(0.1, 0.8, 0.1, 1);
        transform: rotate(0deg);
    }

    /* Spin Center Button */
    .spin-center-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 86px;
        height: 86px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ff5e00 0%, #ff8e43 100%);
        border: 5px solid #fff;
        z-index: 5;
        box-shadow: 0 8px 25px rgba(255, 94, 0, 0.6), inset 0 2px 5px rgba(255, 255, 255, 0.5);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1.1rem;
        color: white;
        text-transform: uppercase;
        letter-spacing: 1px;
        user-select: none;
        transition: all 0.2s ease;
    }

    .spin-center-btn:hover {
        transform: translate(-50%, -50%) scale(1.05);
        box-shadow: 0 10px 30px rgba(255, 94, 0, 0.8);
    }

    .spin-center-btn:active {
        transform: translate(-50%, -50%) scale(0.95);
    }

    .spin-center-btn.disabled {
        background: #475569 !important;
        box-shadow: none !important;
        border-color: #64748b !important;
        cursor: not-allowed;
    }

    /* Table styles for history */
    .history-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
    }

    .history-table {
        color: white;
    }

    .history-table th {
        background: rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.6);
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        padding: 15px;
        font-weight: 600;
    }

    .history-table td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 15px;
        vertical-align: middle;
    }

    .coupon-code-badge {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        background: rgba(255, 94, 0, 0.15);
        color: #ff9e59;
        border: 1px dashed rgba(255, 94, 0, 0.4);
        padding: 6px 12px;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .coupon-code-badge:hover {
        background: rgba(255, 94, 0, 0.25);
        transform: translateY(-1px);
    }

    .badge-status {
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 700;
    }
    .badge-unused { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
    .badge-used { background: rgba(148, 163, 184, 0.15); color: #cbd5e1; border: 1px solid rgba(148, 163, 184, 0.3); }

    /* SVG segment styling */
    .slice-text {
        font-weight: 800;
        font-size: 14px;
        fill: white;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }
</style>
@endpush

@section('content')
<div class="game-page-wrapper">
    <div class="container">
        
        <!-- Header -->
        <div class="text-center mb-5" data-aos="fade-down">
            <h1 class="fw-bold mb-2" style="background: linear-gradient(135deg, #ff5e00, #ffb075); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                <i class="fa-solid fa-gift me-2"></i>Vòng Xoay May Mắn
            </h1>
            <p class="opacity-75 lead" style="max-width: 600px; margin: 0 auto;">
                Hoàn thành mỗi đơn hàng thành công để nhận ngay 1 vé quay miễn phí. Cơ hội trúng các thẻ giảm giá giá trị lên tới 50k!
            </p>
        </div>

        <div class="row justify-content-center">
            
            <!-- Wheel Block -->
            <div class="col-lg-6 col-md-8 text-center" data-aos="zoom-in" data-aos-delay="100">
                <div class="wheel-outer-wrap">
                    <div class="wheel-glow"></div>
                    <div class="wheel-pointer"></div>
                    
                    <!-- Spin Button Center -->
                    <div class="spin-center-btn {{ $user->spin_tickets < 1 ? 'disabled' : '' }}" id="spinBtn">
                        Quay
                    </div>

                    <!-- SVG Wheel -->
                    <div class="wheel-main" id="luckyWheel">
                        <svg viewBox="0 0 300 300" width="100%" height="100%">
                            <g transform="translate(150, 150)">
                                <!-- Segment 0 (10k) - 0° to 60° -->
                                <path d="M 0 0 L 130 0 A 130 130 0 0 1 65 112.58 Z" fill="#667eea" />
                                <text transform="rotate(30) translate(75, 5) rotate(-30)" class="slice-text" text-anchor="middle">10K</text>

                                <!-- Segment 1 (5k) - 60° to 120° -->
                                <path d="M 0 0 L 65 112.58 A 130 130 0 0 1 -65 112.58 Z" fill="#f5576c" />
                                <text transform="rotate(90) translate(75, 5) rotate(-90)" class="slice-text" text-anchor="middle">5K</text>

                                <!-- Segment 2 (2k) - 120° to 180° -->
                                <path d="M 0 0 L -65 112.58 A 130 130 0 0 1 -130 0 Z" fill="#38f9d7" fill-opacity="0.9" style="fill: #10b981;" />
                                <text transform="rotate(150) translate(75, 5) rotate(-150)" class="slice-text" text-anchor="middle">2K</text>

                                <!-- Segment 3 (50k) - 180° to 240° -->
                                <path d="M 0 0 L -130 0 A 130 130 0 0 1 -65 -112.58 Z" fill="#f59e0b" />
                                <text transform="rotate(210) translate(75, 5) rotate(-210)" class="slice-text" text-anchor="middle">50K</text>

                                <!-- Segment 4 (25k) - 240° to 300° -->
                                <path d="M 0 0 L -65 -112.58 A 130 130 0 0 1 65 -112.58 Z" fill="#8b5cf6" />
                                <text transform="rotate(270) translate(75, 5) rotate(-270)" class="slice-text" text-anchor="middle">25K</text>

                                <!-- Segment 5 (15k) - 300° to 360° -->
                                <path d="M 0 0 L 65 -112.58 A 130 130 0 0 1 130 0 Z" fill="#ec4899" />
                                <text transform="rotate(330) translate(75, 5) rotate(-330)" class="slice-text" text-anchor="middle">15K</text>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Stats/Ticket Block -->
            <div class="col-lg-5" data-aos="fade-left" data-aos-delay="200">
                
                <!-- Ticket Stats -->
                <div class="ticket-card">
                    <div class="ticket-label">Số vé quay của bạn</div>
                    <div class="ticket-count" id="ticketCount">{{ $user->spin_tickets }}</div>
                    <p class="text-muted small mb-0">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Mua thêm sản phẩm để nhận thêm lượt quay may mắn.
                    </p>
                </div>

                <!-- Rules / Help -->
                <div class="card border-0 bg-white bg-opacity-5 p-4 rounded-4 mb-4">
                    <h5 class="fw-bold mb-3 text-warning">
                        <i class="fa-solid fa-circle-question me-2"></i>Thể Lệ Mini Game
                    </h5>
                    <ul class="ps-3 mb-0 opacity-75 text-start" style="font-size: 0.9rem; line-height: 1.8;">
                        <li>Đơn hàng sau khi được xác nhận <strong>Hoàn thành (Completed)</strong> sẽ tự động tặng cho bạn 1 vé.</li>
                        <li>Vé quay không giới hạn và được tích lũy cộng dồn.</li>
                        <li>Tỷ lệ trúng thưởng cực cao: <strong>Thẻ giảm giá 50k là 5%</strong>, các thẻ khác (2k, 5k, 10k, 15k, 25k) là ngẫu nhiên cơ hội ngang nhau.</li>
                        <li>Mã giảm giá áp dụng được ngay lập tức tại bước Checkout của cửa hàng.</li>
                    </ul>
                </div>

            </div>
        </div>

        <!-- History Section -->
        <div class="row mt-5" data-aos="fade-up">
            <div class="col-lg-11 mx-auto">
                <div class="history-card">
                    <h4 class="fw-bold mb-4 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-warning"></i>
                        Lịch Sử Trúng Thưởng
                    </h4>

                    <div class="table-responsive">
                        <table class="table history-table text-start" id="historyTable">
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Giải thưởng</th>
                                    <th>Mã giảm giá</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</td>
                                    <td class="fw-bold text-white">Thẻ giảm giá {{ number_format($coupon->value, 0, ',', '.') }}đ</td>
                                    <td>
                                        <div class="coupon-code-badge" onclick="copyToClipboard('{{ $coupon->code }}')" title="Bấm để sao chép">
                                            <span>{{ $coupon->code }}</span>
                                            <i class="fa-regular fa-copy"></i>
                                        </div>
                                    </td>
                                    <td>
                                        @if($coupon->is_used)
                                            <span class="badge-status badge-used">Đã dùng</span>
                                        @else
                                            <span class="badge-status badge-unused">Chưa dùng</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr id="no-history-row">
                                    <td colspan="4" class="text-center text-muted py-4">Bạn chưa quay lần nào. Hãy thực hiện lượt quay đầu tiên nhé!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<!-- Load canvas-confetti library for celebrating wins -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
    let isSpinning = false;

    document.getElementById('spinBtn').addEventListener('click', function() {
        if (isSpinning) return;
        
        const spinBtn = this;
        if (spinBtn.classList.contains('disabled')) {
            Swal.fire({
                icon: 'warning',
                title: 'Hết lượt quay!',
                text: 'Bạn cần hoàn thành thêm đơn hàng để nhận thêm vé quay nhé!',
                confirmButtonColor: '#ff5e00',
            });
            return;
        }

        isSpinning = true;
        spinBtn.classList.add('disabled');
        spinBtn.textContent = '...';

        // Fetch reward from backend
        fetch('{{ route('minigame.spin') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            const prize = data.prize;
            const prizeIndex = prize.index;
            const ticketCountEl = document.getElementById('ticketCount');
            const luckyWheel = document.getElementById('luckyWheel');

            // 6 segments, each is 60 degrees.
            // SVG indices are:
            // Slice 0 (10k)
            // Slice 1 (5k)
            // Slice 2 (2k)
            // Slice 3 (50k)
            // Slice 4 (25k)
            // Slice 5 (15k)
            
            // To make the wheel stop at the correct index at the top marker (12 o'clock / 0 degrees):
            // We rotate by (5 * 360) (extra spins for effect) minus (prizeIndex * 60) minus 30 (center of segment)
            const randomOffset = Math.floor(Math.random() * 24) - 12; // ±12 degrees variation
            const targetDegree = (360 * 6) - (prizeIndex * 60) - 30 + randomOffset;
            
            // Apply spin animation
            luckyWheel.style.transition = 'transform 5s cubic-bezier(0.1, 0.8, 0.15, 1)';
            luckyWheel.style.transform = `rotate(${targetDegree}deg)`;

            setTimeout(() => {
                // Animation completed!
                isSpinning = false;
                
                // Update tickets count on UI
                ticketCountEl.textContent = data.spin_tickets;
                
                // Re-enable button if they still have tickets
                if (data.spin_tickets > 0) {
                    spinBtn.classList.remove('disabled');
                    spinBtn.textContent = 'Quay';
                } else {
                    spinBtn.textContent = 'Quay';
                }

                // Celebrate with confetti!
                triggerConfetti();

                // Alert congratulations
                Swal.fire({
                    title: '🎉 CHÚC MỪNG BẠN! 🎉',
                    html: `Bạn đã quay trúng <strong>${prize.label}</strong>!<br><br>Mã giảm giá của bạn:<br><span class="fs-4 fw-bold text-danger px-3 py-2 border border-danger border-dashed rounded d-inline-block mt-2" style="font-family: monospace;">${prize.code}</span>`,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa-solid fa-copy me-1"></i> Sao chép mã',
                    cancelButtonText: 'Đóng',
                    confirmButtonColor: '#ff5e00',
                    cancelButtonColor: '#64748b'
                }).then((result) => {
                    if (result.isConfirmed) {
                        copyToClipboard(prize.code);
                    }
                });

                // Add to history table
                const historyTable = document.getElementById('historyTable').getElementsByTagName('tbody')[0];
                const noHistoryRow = document.getElementById('no-history-row');
                if (noHistoryRow) {
                    noHistoryRow.remove();
                }

                const now = new Date();
                const timeStr = ('0' + now.getDate()).slice(-2) + '/' + ('0' + (now.getMonth() + 1)).slice(-2) + '/' + now.getFullYear() + ' ' + ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2);
                
                const newRow = historyTable.insertRow(0);
                newRow.innerHTML = `
                    <td>${timeStr}</td>
                    <td class="fw-bold text-white">Thẻ giảm giá ${prize.value.toLocaleString('vi-VN')}đ</td>
                    <td>
                        <div class="coupon-code-badge" onclick="copyToClipboard('${prize.code}')" title="Bấm để sao chép">
                            <span>${prize.code}</span>
                            <i class="fa-regular fa-copy"></i>
                        </div>
                    </td>
                    <td><span class="badge-status badge-unused">Chưa dùng</span></td>
                `;

                // Reset wheel transition and angle quickly to allow another spin smoothly
                setTimeout(() => {
                    luckyWheel.style.transition = 'none';
                    // Modulo to keep visual rotation state
                    luckyWheel.style.transform = `rotate(${targetDegree % 360}deg)`;
                }, 500);

            }, 5100);
        })
        .catch(err => {
            isSpinning = false;
            spinBtn.classList.remove('disabled');
            spinBtn.textContent = 'Quay';
            
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: err.message || 'Có lỗi xảy ra, vui lòng thử lại sau!'
            });
        });
    });

    function triggerConfetti() {
        const duration = 2 * 1000;
        const animationEnd = Date.now() + duration;
        const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 99999 };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function() {
            const timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            const particleCount = 50 * (timeLeft / duration);
            // since particles fall down, animate a bit higher than random
            confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
            confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
        }, 250);
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Đã sao chép mã giảm giá!',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
        }).catch(err => {
            console.error('Không thể sao chép: ', err);
        });
    }
</script>
@endpush
