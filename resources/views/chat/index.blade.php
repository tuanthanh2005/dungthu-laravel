@extends('layouts.app')

@section('title', 'Chat với Admin - DungThu.com')

@push('styles')
<style>
    :root {
        --chat-primary: #6366f1;
        --chat-primary-light: #818cf8;
        --chat-primary-dark: #4f46e5;
        --chat-user-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        --chat-admin-gradient: linear-gradient(135deg, #f43f5e 0%, #fb7185 100%);
        --chat-bg: #f8fafc;
        --chat-card-bg: rgba(255, 255, 255, 0.95);
    }

    .chat-page-wrapper {
        padding: 60px 0;
        background: radial-gradient(circle at top right, #e0e7ff 0%, #f8fafc 50%, #f1f5f9 100%);
        min-height: calc(100vh - 70px);
        margin-top: 70px;
        display: flex;
        align-items: center;
    }

    .chat-container {
        max-width: 1000px;
        width: 100%;
        margin: 0 auto;
        padding: 0 20px;
    }

    .chat-card {
        background: var(--chat-card-bg);
        backdrop-filter: blur(20px);
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        height: 750px;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
    }

    .chat-header {
        padding: 24px 40px;
        background: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 10;
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .admin-avatar-wrap {
        position: relative;
    }

    .admin-avatar {
        width: 56px;
        height: 56px;
        border-radius: 20px;
        background: var(--chat-admin-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 26px;
        box-shadow: 0 10px 20px -5px rgba(244, 63, 94, 0.4);
        transform: rotate(-3deg);
    }

    .online-indicator {
        position: absolute;
        bottom: -4px;
        right: -4px;
        width: 16px;
        height: 16px;
        background: #10b981;
        border: 3px solid white;
        border-radius: 50%;
    }

    .admin-status h5 {
        margin: 0;
        font-weight: 800;
        color: #1e293b;
        font-size: 1.2rem;
        letter-spacing: -0.02em;
    }

    .status-text {
        font-size: 13px;
        color: #10b981;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 40px;
        background: rgba(248, 250, 252, 0.5);
        display: flex;
        flex-direction: column;
        gap: 24px;
        scroll-behavior: smooth;
    }

    .chat-body::-webkit-scrollbar {
        width: 6px;
    }

    .chat-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .message {
        max-width: 75%;
        display: flex;
        flex-direction: column;
        animation: messageFadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes messageFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
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
        padding: 16px 24px;
        border-radius: 24px;
        font-size: 15px;
        line-height: 1.6;
        position: relative;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        font-weight: 500;
    }

    .message.user .message-content {
        background: var(--chat-user-gradient);
        color: white;
        border-bottom-right-radius: 6px;
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.3);
    }

    .message.admin .message-content {
        background: white;
        color: #334155;
        border-bottom-left-radius: 6px;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .message-image {
        max-width: 100%;
        border-radius: 20px;
        margin-top: 10px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .message-image:hover {
        transform: scale(1.03);
        box-shadow: 0 20px 30px rgba(0,0,0,0.15);
    }

    .message-time {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .chat-footer {
        padding: 30px 40px;
        background: white;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .input-area-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .chat-input-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f8fafc;
        padding: 10px 10px 10px 24px;
        border-radius: 24px;
        transition: all 0.3s;
        border: 2px solid #f1f5f9;
    }

    .chat-input-wrapper:focus-within {
        background: white;
        border-color: var(--chat-primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.08);
    }

    .chat-input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        padding: 12px 0;
        font-size: 16px;
        color: #1e293b;
        font-weight: 500;
    }

    .tool-btn {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .tool-btn:hover {
        background: #f1f5f9;
        color: var(--chat-primary);
        transform: translateY(-2px);
    }

    .send-btn {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        background: var(--chat-user-gradient);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
    }

    .send-btn:hover {
        transform: scale(1.05) translateY(-2px);
        box-shadow: 0 15px 25px -5px rgba(99, 102, 241, 0.5);
    }

    .send-btn:active {
        transform: scale(0.95);
    }

    .image-preview-container {
        display: none;
        padding: 15px;
        background: #f8fafc;
        border-radius: 20px;
        position: relative;
        width: fit-content;
        border: 2px dashed #e2e8f0;
    }

    .preview-img {
        height: 100px;
        border-radius: 12px;
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
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
        .chat-page-wrapper {
            padding: 0;
            margin-top: 65px;
        }
        .chat-card {
            height: calc(100vh - 65px);
            border-radius: 0;
            border: none;
        }
        .chat-header {
            padding: 15px 20px;
        }
        .chat-body {
            padding: 20px;
        }
        .chat-footer {
            padding: 20px;
        }
        .message {
            max-width: 85%;
        }
    }
</style>
@endpush

@section('content')
<div class="chat-page-wrapper">
    <div class="chat-container">
        <div class="chat-card" data-aos="zoom-in">
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
                            <i class="fas fa-circle" style="font-size: 8px;"></i> Online
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="{{ route('home') }}" class="btn btn-sm btn-light rounded-pill px-4 fw-bold">
                        <i class="fas fa-times me-2"></i>Thoát
                    </a>
                </div>
            </div>

            <!-- Body -->
            <div class="chat-body" id="chatBody">
                <div class="text-center py-5">
                    <div class="mb-3" style="font-size: 40px;">👋</div>
                    <h5 class="fw-bold mb-1">Xin chào!</h5>
                    <p class="text-muted small">Hãy để lại tin nhắn, Admin sẽ phản hồi bạn ngay lập tức.</p>
                </div>

                @foreach($messages as $msg)
                    <div class="message {{ $msg->is_admin ? 'admin' : 'user' }}" data-id="{{ $msg->id }}">
                        <div class="message-content">
                            @if($msg->message)
                                <div>{{ $msg->message }}</div>
                            @endif
                            @if($msg->image)
                                <img src="{{ asset($msg->image) }}" class="message-image" onclick="window.open(this.src)">
                            @endif
                        </div>
                        <div class="message-time">
                            {{ $msg->created_at->format('H:i') }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer -->
            <div class="chat-footer">
                <div class="input-area-container">
                    <div id="imagePreviewContainer" class="image-preview-container">
                        <img id="imagePreview" class="preview-img">
                        <button class="remove-preview" onclick="clearImagePreview()">&times;</button>
                    </div>

                    <form id="chatForm" onsubmit="handleChatSubmit(event)">
                        <div class="chat-input-wrapper">
                            <label for="chatImage" class="tool-btn" title="Gửi ảnh">
                                <i class="fas fa-image"></i>
                                <input type="file" id="chatImage" hidden accept="image/*" onchange="previewImage(this)">
                            </label>
                            <input type="text" id="chatInput" class="chat-input" placeholder="Nhập tin nhắn..." autocomplete="off">
                            <button type="submit" class="send-btn" id="sendBtn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chatBody = document.getElementById('chatBody');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const chatImage = document.getElementById('chatImage');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const sendBtn = document.getElementById('sendBtn');
    
    let lastId = {{ $messages->last()->id ?? 0 }};
    let pollingInterval = null;

    // Scroll to bottom on load
    chatBody.scrollTop = chatBody.scrollHeight;

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearImagePreview() {
        chatImage.value = '';
        imagePreviewContainer.style.display = 'none';
        imagePreview.src = '';
    }

    function handleChatSubmit(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        const message = chatInput.value.trim();
        const hasImage = chatImage.files.length > 0;

        if (!message && !hasImage) return false;

        // Disable button while sending
        sendBtn.disabled = true;
        sendBtn.style.opacity = '0.7';

        const formData = new FormData();
        formData.append('message', message);
        if (hasImage) {
            formData.append('image', chatImage.files[0]);
        }

        // Reset inputs immediately for responsive feel
        chatInput.value = '';
        clearImagePreview();
        
        fetch('{{ route('chat.send') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.id) {
                appendMessage(data);
                lastId = data.id;
            }
        })
        .catch(err => {
            console.error('Error sending message:', err);
            // Re-fill input on error
            chatInput.value = message;
        })
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.style.opacity = '1';
        });

        return false;
    }

    function appendMessage(msg) {
        // Prevent duplicates
        if (document.querySelector(`.message[data-id="${msg.id}"]`)) return;

        const div = document.createElement('div');
        div.className = `message ${msg.is_admin ? 'admin' : 'user'}`;
        div.dataset.id = msg.id;

        const date = new Date(msg.created_at);
        const time = date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
        
        let contentHtml = '';
        if (msg.message) contentHtml += `<div>${escapeHtml(msg.message)}</div>`;
        if (msg.image) {
            const imgSrc = msg.image.startsWith('http') ? msg.image : `{{ asset('') }}${msg.image}`;
            contentHtml += `<img src="${imgSrc}" class="message-image" onclick="window.open(this.src)">`;
        }

        div.innerHTML = `
            <div class="message-content">${contentHtml}</div>
            <div class="message-time">${time}</div>
        `;

        chatBody.appendChild(div);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function pollMessages() {
        fetch(`{{ route('chat.new') }}?last_id=${lastId}`)
            .then(res => res.json())
            .then(messages => {
                if (messages.length > 0) {
                    messages.forEach(msg => {
                        if (msg.id > lastId) {
                            appendMessage(msg);
                            lastId = Math.max(lastId, msg.id);
                        }
                    });
                }
            })
            .catch(err => console.error('Polling error:', err));
    }

    // Polling every 3 seconds
    pollingInterval = setInterval(pollMessages, 3000);

    // Stop polling when tab inactive
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            clearInterval(pollingInterval);
        } else {
            pollingInterval = setInterval(pollMessages, 3000);
            // Also mark read when returning to tab
            fetch('{{ route('chat.mark-read') }}', { 
                method: 'POST', 
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                } 
            });
        }
    });
</script>
@endpush
