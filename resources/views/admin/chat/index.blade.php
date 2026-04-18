@extends('layouts.app')

@section('title', 'Quản Lý Chat - Admin')

@push('styles')
<style>
    .admin-wrapper {
        padding: 40px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin-top: 70px;
    }

    .chat-admin-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .chat-admin-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        height: calc(100vh - 160px);
        display: flex;
    }

    .users-sidebar {
        width: 300px;
        border-right: 1px solid #e9ecef;
        display: flex;
        flex-direction: column;
    }

    .users-header {
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
    }

    .users-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .users-subheader {
        padding: 12px 20px;
        border-top: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
        background: #fbfcfe;
        font-size: 13px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .users-list {
        flex: 1;
        overflow-y: auto;
    }

    .user-item {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-item:hover {
        background: #f8f9fa;
    }

    .user-item.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .user-item.active .user-avatar {
        background: white;
        color: #667eea;
    }

    .user-info {
        flex: 1;
    }

    .user-name {
        font-weight: 600;
        margin-bottom: 2px;
    }

    .user-email {
        font-size: 12px;
        opacity: 0.7;
    }

    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .chat-header-main {
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #f8f9fa;
        min-height: 0;
    }

    .chat-message {
        margin-bottom: 15px;
        display: flex;
    }

    .chat-message.user {
        justify-content: flex-start;
    }

    .chat-message.admin {
        justify-content: flex-end;
    }

    .message-content {
        max-width: 70%;
        padding: 12px 18px;
        border-radius: 18px;
        word-wrap: break-word;
    }

    .chat-message.user .message-content {
        background: white;
        color: #333;
        border-bottom-left-radius: 4px;
    }

    .chat-message.admin .message-content {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message-time {
        font-size: 11px;
        opacity: 0.7;
        margin-top: 4px;
    }

    .chat-input-area {
        padding: 20px;
        border-top: 1px solid #e9ecef;
        background: white;
        position: relative;
        z-index: 2;
    }

    .chat-input-area .input-group {
        gap: 10px;
        align-items: center;
    }

    .chat-input-area .form-control {
        border-radius: 999px;
        padding: 12px 18px;
        background: #f5f6f8;
        border: 1px solid #e5e7eb;
    }

    .chat-input-area .form-control:focus {
        background: #fff;
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.2);
    }

    .chat-input-area .btn {
        width: 44px;
        height: 44px;
        padding: 0;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #999;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .chat-admin-card {
            flex-direction: column;
        }

        .users-sidebar {
            width: 100%;
            height: 200px;
            border-right: none;
            border-bottom: 1px solid #e9ecef;
        }

        .message-content {
            max-width: 85%;
        }
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="chat-admin-container">
        <div class="mb-4">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light">
                <i class="fas fa-arrow-left"></i> Quay lại Dashboard
            </a>
        </div>

        <div class="chat-admin-card">
            <div class="users-sidebar">
                <div class="users-header">
                    <h5><i class="fas fa-users"></i> Danh sách Chat</h5>
                </div>
                <div class="users-list" id="usersList">
                    <div class="users-subheader">Tin nhắn gần đây</div>
                    @forelse($recentChats as $item)
                        <div class="user-item" data-id="{{ $item->id }}" data-type="{{ $item->type }}" 
                             onclick="selectTarget(event, {{ $item->id }}, '{{ addslashes($item->name) }}', '{{ $item->email }}', '{{ $item->type }}')">
                            <div class="user-avatar" style="{{ $item->type === 'affiliate' ? 'background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);' : '' }}">
                                {{ strtoupper(substr($item->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <div class="user-name">
                                    {{ $item->name }}
                                    @if($item->type === 'affiliate')
                                        <span class="badge bg-info ms-1" style="font-size: 9px;">CTV</span>
                                    @endif
                                </div>
                                <div class="user-email">{{ $item->email }}</div>
                            </div>
                            @if(isset($item->unread_count) && $item->unread_count > 0)
                                <span class="badge bg-danger rounded-pill">{{ $item->unread_count }}</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">Chưa có tin nhắn nào</div>
                    @endforelse

                    <div class="users-subheader">Cộng tác viên mới</div>
                    @foreach($allAffiliates as $aff)
                        @if(!$recentChats->where('id', $aff->id)->where('type', 'affiliate')->first())
                        <div class="user-item" data-id="{{ $aff->id }}" data-type="affiliate" 
                             onclick="selectTarget(event, {{ $aff->id }}, '{{ addslashes($aff->name) }}', '{{ $aff->email }}', 'affiliate')">
                            <div class="user-avatar" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                {{ strtoupper(substr($aff->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <div class="user-name">{{ $aff->name }} <span class="badge bg-info ms-1" style="font-size: 9px;">CTV</span></div>
                                <div class="user-email">{{ $aff->email }}</div>
                            </div>
                        </div>
                        @endif
                    @endforeach

                    <div class="users-subheader">Người dùng mới</div>
                    @foreach($allUsers as $u)
                        @if(!$recentChats->where('id', $u->id)->where('type', 'user')->first())
                        <div class="user-item" data-id="{{ $u->id }}" data-type="user" 
                             onclick="selectTarget(event, {{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $u->email }}', 'user')">
                            <div class="user-avatar">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <div class="user-name">{{ $u->name }}</div>
                                <div class="user-email">{{ $u->email }}</div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="chat-main">
                <div class="chat-header-main" id="chatHeaderMain">
                    <h5 class="mb-0 text-muted">
                        <i class="fas fa-comments"></i> Chọn đối tượng để chat
                    </h5>
                </div>

                <div class="chat-messages" id="chatMessages">
                    <div class="empty-state">
                        <i class="fas fa-comments"></i>
                        <p>Chọn một tài khoản để bắt đầu trò chuyện</p>
                    </div>
                </div>

                <div class="chat-input-area" id="chatInputArea" style="display: none;">
                    <div id="adminImagePreview" class="mb-2" style="display: none;">
                        <div class="position-relative d-inline-block">
                            <img id="imgPreview" src="" style="height: 100px; border-radius: 10px; border: 2px solid #667eea;">
                            <button class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle" onclick="clearPreview()" style="width: 24px; height: 24px; padding: 0;">&times;</button>
                        </div>
                    </div>
                    <form id="adminChatForm" onsubmit="sendAdminMessage(event)">
                        <div class="input-group">
                            <label for="adminFile">
                                <span class="btn btn-light rounded-circle me-1"><i class="fas fa-image"></i></span>
                                <input type="file" id="adminFile" hidden accept="image/*" onchange="previewImage(this)">
                            </label>
                            <input type="text" class="form-control" id="adminChatInput" placeholder="Nhập tin nhắn..." autocomplete="off">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedId = null;
let selectedType = 'user';
let lastMessageId = 0;
let pollingInterval = null;

function selectTarget(evt, id, name, email, type) {
    selectedId = id;
    selectedType = type;
    lastMessageId = 0;
    
    document.querySelectorAll('.user-item').forEach(item => item.classList.remove('active'));
    evt.currentTarget.classList.add('active');
    
    document.getElementById('chatHeaderMain').innerHTML = `
        <h5 class="mb-0">
            <i class="fas fa-user"></i> ${name}
            ${type === 'affiliate' ? '<span class="badge bg-info ms-1">CTV</span>' : ''}
            <small class="text-muted d-block" style="font-size: 11px;">${email}</small>
        </h5>
    `;
    
    document.getElementById('chatInputArea').style.display = 'block';
    loadMessages();
    
    if (pollingInterval) clearInterval(pollingInterval);
    pollingInterval = setInterval(checkNewMessages, 3000);
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imgPreview').src = e.target.result;
            document.getElementById('adminImagePreview').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function clearPreview() {
    document.getElementById('adminFile').value = '';
    document.getElementById('adminImagePreview').style.display = 'none';
}

function loadMessages() {
    fetch(`/admin/chat/messages/${selectedId}?type=${selectedType}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
        .then(data => {
            const chatBox = document.getElementById('chatMessages');
            chatBox.innerHTML = '';
            if (data.length === 0) {
                chatBox.innerHTML = '<div class="empty-state"><i class="fas fa-comments"></i><p>Chưa có tin nhắn</p></div>';
                return;
            }
            data.forEach(msg => {
                appendMsg(msg);
                lastMessageId = Math.max(lastMessageId, msg.id);
            });
            scrollToBottom();
        });
}

function sendAdminMessage(event) {
    if (event) event.preventDefault();
    if (!selectedId) return;
    
    const input = document.getElementById('adminChatInput');
    const fileInput = document.getElementById('adminFile');
    const message = input.value.trim();
    
    if (!message && !fileInput.files[0]) return;

    // Helper to send JSON (text-only)
    function sendJson(payload) {
        return fetch(`/admin/chat/reply/${selectedId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(async (res) => {
            if (res.status === 403) {
                const pin = window.prompt('Nhập mã xác nhận (3 số) để tiếp tục:');
                if (pin === null) return null;
                return sendJson({ ...payload, admin_pin: pin });
            }
            if (!res.ok) {
                const err = await res.json();
                throw new Error(err.error || 'Lỗi gửi tin nhắn');
            }
            return res.json();
        });
    }

    // Helper to send FormData (with images)
    function sendFormData(formData) {
        return fetch(`/admin/chat/reply/${selectedId}`, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async (res) => {
            if (res.status === 403) {
                const pin = window.prompt('Nhập mã xác nhận (3 số) để tiếp tục:');
                if (pin === null) return null;
                formData.set('admin_pin', pin); // Use set instead of append to avoid duplicates on retry
                return sendFormData(formData);
            }
            if (!res.ok) {
                const err = await res.json();
                throw new Error(err.error || 'Lỗi gửi tin nhắn');
            }
            return res.json();
        });
    }

    if (fileInput.files[0]) {
        const formData = new FormData();
        formData.append('message', message);
        formData.append('type', selectedType);
        formData.append('image', fileInput.files[0]);

        sendFormData(formData)
            .then(data => {
                if (!data) return;
                appendMsg(data);
                lastMessageId = Math.max(lastMessageId, data.id);
                input.value = '';
                clearPreview();
                scrollToBottom();
            })
            .catch(err => alert(err.message));
    } else {
        sendJson({ message, type: selectedType })
            .then(data => {
                if (!data) return;
                appendMsg(data);
                lastMessageId = Math.max(lastMessageId, data.id);
                input.value = '';
                scrollToBottom();
            })
            .catch(err => alert(err.message));
    }
}

function appendMsg(msg) {
    const chatBox = document.getElementById('chatMessages');
    const empty = chatBox.querySelector('.empty-state');
    if (empty) empty.remove();
    
    const div = document.createElement('div');
    div.className = `chat-message ${msg.is_admin ? 'admin' : 'user'}`;
    const time = new Date(msg.created_at).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
    
    let content = msg.message ? `<div class="message-content">${escapeHtml(msg.message)}</div>` : '';
    if (msg.image) content += `<img src="/${msg.image}" style="max-width: 200px; border-radius: 10px; margin-top: 5px; cursor: pointer;" onclick="window.open('/${msg.image}')">`;

    div.innerHTML = `<div>${content}<div class="message-time">${time}</div></div>`;
    chatBox.appendChild(div);
}

function checkNewMessages() {
    if (!selectedId) return;
    fetch(`/admin/chat/messages/${selectedId}?type=${selectedType}&last_id=${lastMessageId}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
        .then(data => {
            const fresh = data.filter(m => m.id > lastMessageId);
            if (fresh.length > 0) {
                fresh.forEach(m => {
                    appendMsg(m);
                    lastMessageId = Math.max(lastMessageId, m.id);
                });
                scrollToBottom();
            }
        });
}

function scrollToBottom() {
    const box = document.getElementById('chatMessages');
    box.scrollTop = box.scrollHeight;
}

function escapeHtml(t) {
    const m = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return t.replace(/[&<>"']/g, s => m[s]);
}

// Stop polling when page is hidden
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
    } else if (selectedId) {
        pollingInterval = setInterval(checkNewMessages, 3000);
    }
});
</script>
@endsection
