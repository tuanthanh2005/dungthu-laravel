@extends('layouts.app')

@section('title', 'Chat với Admin - DungThu.com')

@push('styles')
<style>
    :root {
        --chat-primary: #6366f1;
        --chat-primary-dark: #4f46e5;
        --chat-user-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --chat-admin-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --chat-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .chat-page-wrapper {
        padding: 40px 0;
        background: linear-gradient(135deg, #f6f9fc 0%, #edf2f7 100%);
        min-height: calc(100vh - 70px);
        margin-top: 70px;
        display: flex;
        align-items: center;
    }

    .chat-container {
        max-width: 900px;
        width: 100%;
        margin: 0 auto;
        padding: 0 15px;
    }

    .chat-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: var(--chat-shadow);
        height: 700px;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .chat-header {
        padding: 20px 30px;
        background: white;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .admin-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--chat-admin-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3);
    }

    .admin-status h5 {
        margin: 0;
        font-weight: 700;
        color: #2d3436;
        font-size: 1.1rem;
    }

    .status-badge {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #4ade80;
        font-weight: 600;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #4ade80;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.5); opacity: 0.5; }
        100% { transform: scale(1); opacity: 1; }
    }

    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 30px;
        background: #f8faff;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .chat-body::-webkit-scrollbar {
        width: 6px;
    }

    .chat-body::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .message {
        max-width: 80%;
        display: flex;
        flex-direction: column;
    }

    .message.user {
        align-self: flex-end;
    }

    .message.admin {
        align-self: flex-start;
    }

    .message-content {
        padding: 14px 20px;
        border-radius: 20px;
        font-size: 15px;
        line-height: 1.5;
        position: relative;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }

    .message.user .message-content {
        background: var(--chat-user-gradient);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message.admin .message-content {
        background: white;
        color: #2d3436;
        border-bottom-left-radius: 4px;
        border: 1px solid #eef2f7;
    }

    .message-image {
        max-width: 100%;
        border-radius: 15px;
        margin-top: 8px;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .message-image:hover {
        transform: scale(1.02);
    }

    .message-time {
        font-size: 11px;
        color: #a0aec0;
        margin-top: 5px;
        font-weight: 500;
    }

    .message.user .message-time {
        text-align: right;
    }

    .chat-footer {
        padding: 20px 30px;
        background: white;
        border-top: 1px solid #f0f0f0;
    }

    .chat-input-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
        background: #f1f5f9;
        padding: 8px 10px 8px 20px;
        border-radius: 30px;
        transition: all 0.3s;
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
        color: #2d3436;
    }

    .tool-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
    }

    .tool-btn:hover {
        background: #e2e8f0;
        color: var(--chat-primary);
    }

    .send-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--chat-primary);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
    }

    .send-btn:hover {
        transform: scale(1.1);
        background: var(--chat-primary-dark);
    }

    .image-preview-container {
        display: none;
        padding: 10px;
        background: #f8fafc;
        border-radius: 15px;
        margin-bottom: 10px;
        position: relative;
    }

    .preview-img {
        height: 80px;
        border-radius: 10px;
        object-fit: cover;
    }

    .remove-preview {
        position: absolute;
        top: 5px;
        left: 85px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .chat-page-wrapper {
            padding: 10px 0;
            margin-top: 60px;
        }
        .chat-card {
            height: calc(100vh - 150px);
            border-radius: 0;
        }
        .message {
            max-width: 90%;
        }
    }
</style>
@endpush

@section('content')
<div class="chat-page-wrapper">
    <div class="chat-container">
        <div class="chat-card" data-aos="fade-up">
            <!-- Header -->
            <div class="chat-header">
                <div class="chat-header-info">
                    <div class="admin-avatar">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="admin-status">
                        <h5>Hỗ trợ trực tuyến</h5>
                        <div class="status-badge">
                            <span class="status-dot"></span>
                            Đang hoạt động
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="{{ route('home') }}" class="btn btn-sm btn-light rounded-pill px-3">
                        <i class="fas fa-times me-1"></i> Đóng
                    </a>
                </div>
            </div>

            <!-- Body -->
            <div class="chat-body" id="chatBody">
                <div class="text-center py-4">
                    <p class="text-muted small">Chào mừng bạn! Admin sẽ phản hồi bạn sớm nhất có thể.</p>
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
                <div id="imagePreviewContainer" class="image-preview-container">
                    <img id="imagePreview" class="preview-img">
                    <button class="remove-preview" onclick="clearImagePreview()">&times;</button>
                </div>

                <form id="chatForm" onsubmit="sendMessage(event)">
                    <div class="chat-input-wrapper">
                        <label for="chatImage" class="tool-btn" title="Gửi ảnh">
                            <i class="fas fa-image"></i>
                            <input type="file" id="chatImage" hidden accept="image/*" onchange="previewImage(this)">
                        </label>
                        <input type="text" id="chatInput" class="chat-input" placeholder="Nhập nội dung tin nhắn..." autocomplete="off">
                        <button type="submit" class="send-btn" id="sendBtn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
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

    function sendMessage(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        const hasImage = chatImage.files.length > 0;

        if (!message && !hasImage) return;

        const formData = new FormData();
        formData.append('message', message);
        if (hasImage) {
            formData.append('image', chatImage.files[0]);
        }

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
        .catch(err => console.error('Error sending message:', err));
    }

    function appendMessage(msg) {
        const div = document.createElement('div');
        div.className = `message ${msg.is_admin ? 'admin' : 'user'}`;
        div.dataset.id = msg.id;

        const time = new Date(msg.created_at).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
        
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
                            lastId = msg.id;
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
            fetch('{{ route('chat.mark-read') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
        }
    });
</script>
@endpush
