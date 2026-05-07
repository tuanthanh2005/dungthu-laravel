@extends('layouts.app')

@section('title', 'Chat với Admin - DungThu.com')

@push('styles')
<style>
    /* ============================================
       CSS VARIABLES
       ============================================ */
    :root {
        --chat-primary: #6366f1;
        --chat-primary-light: #818cf8;
        --chat-primary-dark: #4f46e5;
        --chat-user-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        --chat-admin-bg: #ffffff;
        --chat-bg: #f8fafc;
        --chat-card-bg: rgba(255, 255, 255, 0.98);
        --nav-height: 64px;
        --bottom-nav-height: 60px;
    }

    /* ============================================
       LAYOUT: FULL-PAGE CHAT
       ============================================ */

    /* Remove default page padding from layout */
    body.chat-page main {
        padding: 0 !important;
    }

    .chat-page-wrapper {
        /* Desktop: centered card */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px 16px;
        min-height: calc(100vh - var(--nav-height));
        margin-top: var(--nav-height);
        background: radial-gradient(circle at top right, #e0e7ff 0%, #f8fafc 50%, #f1f5f9 100%);
        box-sizing: border-box;
    }

    .chat-container {
        width: 100%;
        max-width: 860px;
    }

    .chat-card {
        background: var(--chat-card-bg);
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 24px 60px -10px rgba(0, 0, 0, 0.14), 0 0 0 1px rgba(99,102,241,0.08);
        display: flex;
        flex-direction: column;
        height: 76vh;
        max-height: 780px;
        min-height: 480px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        position: relative;
    }

    /* ============================================
       HEADER
       ============================================ */
    .chat-header {
        padding: 18px 28px;
        background: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
        z-index: 10;
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .admin-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .admin-avatar {
        width: 50px;
        height: 50px;
        border-radius: 18px;
        background: linear-gradient(135deg, #f43f5e 0%, #fb7185 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 22px;
        box-shadow: 0 8px 18px -4px rgba(244, 63, 94, 0.35);
        transform: rotate(-3deg);
        transition: transform 0.3s ease;
    }

    .admin-avatar:hover {
        transform: rotate(0deg);
    }

    .online-indicator {
        position: absolute;
        bottom: -3px;
        right: -3px;
        width: 14px;
        height: 14px;
        background: #10b981;
        border: 2.5px solid white;
        border-radius: 50%;
        animation: pulse-green 2s ease-in-out infinite;
    }

    @keyframes pulse-green {
        0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        50% { box-shadow: 0 0 0 5px rgba(16, 185, 129, 0); }
    }

    .admin-status h5 {
        margin: 0;
        font-weight: 800;
        color: #1e293b;
        font-size: 1.05rem;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .status-text {
        font-size: 12px;
        color: #10b981;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 2px;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-exit {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: 50px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1.5px solid transparent;
    }

    .btn-exit:hover {
        background: #fee2e2;
        color: #ef4444;
        border-color: #fecaca;
        transform: translateY(-1px);
    }

    /* ============================================
       BODY / MESSAGES
       ============================================ */
    .chat-body {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 28px 28px;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 18px;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    .chat-body::-webkit-scrollbar { width: 5px; }
    .chat-body::-webkit-scrollbar-track { background: transparent; }
    .chat-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    .chat-welcome-box {
        text-align: center;
        padding: 40px 20px;
    }

    .chat-welcome-box .wave { font-size: 44px; animation: wave-hand 2s ease-in-out infinite; display: inline-block; }
    @keyframes wave-hand {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(20deg); }
        75% { transform: rotate(-10deg); }
    }

    .chat-welcome-box h5 {
        font-size: 1.2rem;
        font-weight: 800;
        color: #1e293b;
        margin: 12px 0 6px;
    }

    .chat-welcome-box p {
        color: #64748b;
        font-size: 14px;
        margin: 0;
    }

    /* Messages */
    .message {
        max-width: 72%;
        display: flex;
        flex-direction: column;
        animation: msgIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes msgIn {
        from { opacity: 0; transform: translateY(12px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .message.user {
        align-self: flex-end;
        align-items: flex-end;
    }

    .message.admin {
        align-self: flex-start;
        align-items: flex-start;
    }

    .message-content {
        padding: 13px 20px;
        border-radius: 22px;
        font-size: 14.5px;
        line-height: 1.65;
        font-weight: 500;
        word-break: break-word;
        position: relative;
    }

    .message.user .message-content {
        background: var(--chat-user-gradient);
        color: white;
        border-bottom-right-radius: 6px;
        box-shadow: 0 8px 20px -5px rgba(99, 102, 241, 0.28);
    }

    .message.admin .message-content {
        background: white;
        color: #334155;
        border-bottom-left-radius: 6px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid #f1f5f9;
    }

    .message-image {
        max-width: 100%;
        border-radius: 16px;
        margin-top: 8px;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        display: block;
    }

    .message-image:hover {
        transform: scale(1.02);
        box-shadow: 0 14px 28px rgba(0,0,0,0.15);
    }

    .message-time {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 5px;
        font-weight: 600;
        letter-spacing: 0.04em;
    }

    /* ============================================
       FOOTER / INPUT
       ============================================ */
    .chat-footer {
        padding: 16px 22px;
        background: white;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
        flex-shrink: 0;
        /* iOS safe area */
        padding-bottom: calc(16px + env(safe-area-inset-bottom, 0px));
    }

    .input-area-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .image-preview-container {
        display: none;
        padding: 10px 12px;
        background: #f8fafc;
        border-radius: 16px;
        position: relative;
        width: fit-content;
        border: 1.5px dashed #cbd5e1;
    }

    .preview-img {
        height: 80px;
        border-radius: 10px;
        object-fit: cover;
    }

    .remove-preview {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #f43f5e;
        color: white;
        border: 2px solid white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
        transition: transform 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        line-height: 1;
    }

    .remove-preview:hover { transform: scale(1.15); }

    .chat-input-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f1f5f9;
        padding: 6px 6px 6px 18px;
        border-radius: 50px;
        transition: all 0.25s ease;
        border: 2px solid transparent;
    }

    .chat-input-wrapper:focus-within {
        background: white;
        border-color: var(--chat-primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .chat-input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        padding: 10px 0;
        font-size: 15px;
        color: #1e293b;
        font-weight: 500;
        min-width: 0;
    }

    .chat-input::placeholder { color: #94a3b8; }

    .tool-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
        background: transparent;
    }

    .tool-btn:hover {
        background: #e2e8f0;
        color: var(--chat-primary);
    }

    .send-btn {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: var(--chat-user-gradient);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 18px -4px rgba(99, 102, 241, 0.5);
        flex-shrink: 0;
    }

    .send-btn:hover:not(:disabled) {
        transform: scale(1.08) translateY(-1px);
        box-shadow: 0 10px 22px -4px rgba(99, 102, 241, 0.55);
    }

    .send-btn:active:not(:disabled) { transform: scale(0.94); }
    .send-btn:disabled { opacity: 0.55; cursor: not-allowed; }

    /* ============================================
       MOBILE RESPONSIVE  (≤ 768px)
       ============================================ */
    @media (max-width: 768px) {
        .chat-page-wrapper {
            padding: 0;
            margin-top: 0; /* handled by padding-top on card */
            min-height: 100dvh; /* dynamic viewport for mobile browsers */
            align-items: stretch;
            background: #f8fafc;
        }

        .chat-container {
            max-width: 100%;
            height: 100dvh;
            display: flex;
            flex-direction: column;
        }

        .chat-card {
            border-radius: 0;
            box-shadow: none;
            border: none;
            height: 100%;
            max-height: none;
            min-height: 0;
            /* Push content below the fixed navbar */
            padding-top: var(--nav-height);
        }

        .chat-header {
            padding: 10px 14px;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            font-size: 18px;
            border-radius: 12px;
        }

        .admin-status h5 {
            font-size: 0.9rem;
        }

        .chat-body {
            padding: 14px 12px;
            gap: 12px;
        }

        .message {
            max-width: 88%;
        }

        .message-content {
            padding: 10px 15px;
            font-size: 14px;
            border-radius: 18px;
        }

        .chat-footer {
            padding: 10px 12px;
            padding-bottom: calc(10px + env(safe-area-inset-bottom, 0px));
        }

        .chat-input-wrapper {
            padding: 4px 4px 4px 14px;
            border-radius: 50px;
        }

        .chat-input {
            font-size: 16px; /* Prevent iOS zoom */
            padding: 8px 0;
        }

        .tool-btn {
            width: 36px;
            height: 36px;
            font-size: 15px;
        }

        .send-btn {
            width: 42px;
            height: 42px;
            font-size: 17px;
        }

        .message-image {
            border-radius: 12px;
        }

        .btn-exit span {
            display: none; /* hide text, keep icon on small screens */
        }

        .btn-exit {
            padding: 8px 10px;
        }
    }

    /* ============================================
       VERY SMALL SCREENS  (≤ 375px)
       ============================================ */
    @media (max-width: 375px) {
        .chat-body { padding: 10px 8px; }
        .chat-footer { padding: 8px; }
        .message { max-width: 92%; }
    }

    /* ============================================
       DESKTOP LARGE  (≥ 1200px)
       ============================================ */
    @media (min-width: 1200px) {
        .chat-card {
            height: 78vh;
        }

        .chat-body {
            padding: 32px 36px;
            gap: 20px;
        }

        .chat-header {
            padding: 20px 36px;
        }

        .chat-footer {
            padding: 18px 36px;
        }
    }
</style>
@endpush

@section('content')
<div class="chat-page-wrapper">
    <div class="chat-container">
        <div class="chat-card">
            <!-- Header -->
            <div class="chat-header">
                <div class="chat-header-info">
                    <div class="admin-avatar-wrap">
                        <div class="admin-avatar">
                            <i class="fas fa-headset"></i>
                        </div>
                        <span class="online-indicator"></span>
                    </div>
                    <div class="admin-status">
                        <h5>DungThu Support</h5>
                        <div class="status-text">
                            <i class="fas fa-circle" style="font-size: 7px;"></i>
                            <span>Online - Sẵn sàng hỗ trợ</span>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="{{ route('home') }}" class="btn-exit">
                        <i class="fas fa-arrow-left" style="font-size: 13px;"></i>
                        <span>Thoát</span>
                    </a>
                </div>
            </div>

            <!-- Body -->
            <div class="chat-body" id="chatBody">
                <div class="chat-welcome-box">
                    <span class="wave">👋</span>
                    <h5>Xin chào, {{ Auth::user()->name ?? 'bạn' }}!</h5>
                    <p>Hãy để lại tin nhắn, Admin sẽ phản hồi ngay lập tức.</p>
                </div>

                @foreach($messages as $msg)
                    <div class="message {{ $msg->is_admin ? 'admin' : 'user' }}" data-id="{{ $msg->id }}">
                        <div class="message-content">
                            @if($msg->message)
                                <div>{{ $msg->message }}</div>
                            @endif
                            @if($msg->image)
                                <img src="{{ asset($msg->image) }}" class="message-image" onclick="window.open(this.src)" loading="lazy">
                            @endif
                        </div>
                        <div class="message-time">{{ $msg->created_at->format('H:i') }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Footer -->
            <div class="chat-footer">
                <div class="input-area-container">
                    <div id="imagePreviewContainer" class="image-preview-container">
                        <img id="imagePreview" class="preview-img" alt="preview">
                        <button class="remove-preview" onclick="clearImagePreview()" type="button" title="Xóa ảnh">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="chatForm" onsubmit="return false;" autocomplete="off">
                        @csrf
                        <div class="chat-input-wrapper">
                            <label for="chatImage" class="tool-btn" title="Gửi ảnh">
                                <i class="fas fa-image"></i>
                                <input type="file" id="chatImage" name="image" hidden accept="image/*" onchange="previewImage(this)">
                            </label>
                            <input
                                type="text"
                                id="chatInput"
                                name="message"
                                class="chat-input"
                                placeholder="Nhập tin nhắn..."
                                autocomplete="off"
                                maxlength="1000"
                                enterkeyhint="send"
                            >
                            <button type="button" class="send-btn" id="sendBtn" onclick="handleChatSubmit()">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Notification Sound --}}
<audio id="notifSound" preload="auto">
    <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
</audio>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.body.classList.add('chat-page');

    const chatBody    = document.getElementById('chatBody');
    const chatForm    = document.getElementById('chatForm');
    const chatInput   = document.getElementById('chatInput');
    const chatImage   = document.getElementById('chatImage');
    const imgPreviewC = document.getElementById('imagePreviewContainer');
    const imgPreview  = document.getElementById('imagePreview');
    const sendBtn     = document.getElementById('sendBtn');

    let lastId = {{ $messages->last()->id ?? 0 }};
    let pollingInterval = null;

    // ── Scroll to bottom ────────────────────────────
    function scrollToBottom(smooth = false) {
        chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: smooth ? 'smooth' : 'instant' });
    }
    scrollToBottom();

    // ── Image Preview ────────────────────────────────
    window.previewImage = function (input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imgPreview.src = e.target.result;
                imgPreviewC.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    window.clearImagePreview = function () {
        chatImage.value = '';
        imgPreviewC.style.display = 'none';
        imgPreview.src = '';
    };

    // ── Send Message ─────────────────────────────────
    window.handleChatSubmit = function () {
        const msg      = chatInput.value.trim();
        const hasImage = chatImage.files.length > 0;
        if (!msg && !hasImage) return;

        // Disable UI
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        const formData = new FormData(chatForm);

        fetch('{{ route('chat.send') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => {
            if (!res.ok) throw new Error('Server error: ' + res.status);
            return res.json();
        })
        .then(data => {
            if (data && data.id) {
                chatInput.value = '';
                clearImagePreview();
                appendMessage(data);
                lastId = Math.max(lastId, data.id);
            }
        })
        .catch(err => {
            console.error('Send error:', err);
            alert('Lỗi gửi tin nhắn: ' + err.message);
        })
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            chatInput.focus();
        });
    };

    // ── Enter key ────────────────────────────────────
    chatInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleChatSubmit();
        }
    });

    // ── Append Message ───────────────────────────────
    function appendMessage(msg, playSound = false) {
        if (document.querySelector(`.message[data-id="${msg.id}"]`)) return;

        // Remove welcome box if present
        const wb = chatBody.querySelector('.chat-welcome-box');
        if (wb) wb.remove();

        if (playSound && msg.is_admin) {
            const audio = document.getElementById('notifSound');
            if (audio) { audio.currentTime = 0; audio.play().catch(() => {}); }
        }

        const div = document.createElement('div');
        div.className = `message ${msg.is_admin ? 'admin' : 'user'}`;
        div.dataset.id = msg.id;

        const date = new Date(msg.created_at);
        const time = date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });

        let contentHtml = '';
        if (msg.message)  contentHtml += `<div>${escapeHtml(msg.message)}</div>`;
        if (msg.image) {
            const src = msg.image.startsWith('http') ? msg.image : `{{ asset('') }}${msg.image}`;
            contentHtml += `<img src="${src}" class="message-image" onclick="window.open(this.src)" loading="lazy">`;
        }

        div.innerHTML = `
            <div class="message-content">${contentHtml}</div>
            <div class="message-time">${time}</div>
        `;

        chatBody.appendChild(div);
        scrollToBottom(true);
    }

    function escapeHtml(text) {
        const d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    // ── Polling ──────────────────────────────────────
    function pollMessages() {
        fetch(`{{ route('chat.new') }}?last_id=${lastId}`)
            .then(res => res.json())
            .then(msgs => {
                if (msgs && msgs.length > 0) {
                    msgs.forEach(m => {
                        if (m.id > lastId) {
                            appendMessage(m, true);
                            lastId = Math.max(lastId, m.id);
                        }
                    });
                }
            })
            .catch(err => console.warn('Poll error:', err));
    }

    pollingInterval = setInterval(pollMessages, 3000);

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            clearInterval(pollingInterval);
        } else {
            pollingInterval = setInterval(pollMessages, 3000);
            fetch('{{ route('chat.mark-read') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).catch(() => {});
        }
    });

    // ── iOS keyboard: keep input visible ─────────────
    if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
        chatInput.addEventListener('focus', () => {
            setTimeout(() => scrollToBottom(), 350);
        });
    }
});
</script>
@endpush
