@extends('layouts.admin')

@section('title', 'Quản Lý Chat')

@push('styles')
<style>
    .chat-admin-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        height: calc(100vh - 180px);
        display: flex;
        margin: 20px;
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
        overflow: hidden;
    }

    .user-name {
        font-weight: 600;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-email {
        font-size: 12px;
        opacity: 0.8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #f4f7f6;
    }

    .chat-header-main {
        padding: 15px 25px;
        background: white;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-messages {
        flex: 1;
        padding: 25px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .chat-message {
        max-width: 70%;
        display: flex;
        flex-direction: column;
    }

    .chat-message.admin {
        align-self: flex-end;
    }

    .chat-message.user {
        align-self: flex-start;
    }

    .message-bubble {
        padding: 12px 18px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.5;
        position: relative;
    }

    .chat-message.admin .message-bubble {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .chat-message.user .message-bubble {
        background: white;
        color: #333;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .message-time {
        font-size: 10px;
        color: #999;
        margin-top: 5px;
    }

    .chat-message.admin .message-time {
        text-align: right;
    }

    .chat-input-area {
        padding: 20px 25px;
        background: white;
        border-top: 1px solid #e9ecef;
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
        font-size: 50px;
        margin-bottom: 15px;
        opacity: 0.3;
    }
</style>
@endpush

@section('content')
<div class="chat-admin-card">
    <div class="users-sidebar">
        <div class="users-header">
            <h5><i class="fas fa-users"></i> Danh sách Chat</h5>
        </div>
        <div class="users-list">
            <div class="users-subheader">Gần đây</div>
            @if(isset($recentChats))
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
            @endif

            <div class="users-subheader">Cộng tác viên mới</div>
            @if(isset($allAffiliates))
                @foreach($allAffiliates as $aff)
                    @php 
                        $exists = false;
                        if(isset($recentChats)) {
                            $exists = $recentChats->where('id', $aff->id)->where('type', 'affiliate')->first();
                        }
                    @endphp
                    @if(!$exists)
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
            @endif

            <div class="users-subheader">Người dùng mới</div>
            @if(isset($allUsers))
                @foreach($allUsers as $u)
                    @php 
                        $existsUser = false;
                        if(isset($recentChats)) {
                            $existsUser = $recentChats->where('id', $u->id)->where('type', 'user')->first();
                        }
                    @endphp
                    @if(!$existsUser)
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
            @endif
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
            <form id="adminChatForm" onsubmit="return false;">
                @csrf
                <div class="input-group">
                    <label for="adminFile">
                        <span class="btn btn-light rounded-circle me-1"><i class="fas fa-image"></i></span>
                        <input type="file" id="adminFile" name="image" hidden accept="image/*" onchange="previewImage(this)">
                    </label>
                    <input type="text" class="form-control" id="adminChatInput" name="message" placeholder="Nhập tin nhắn..." autocomplete="off">
                    <button class="btn btn-primary" type="button" id="adminSendBtn" onclick="sendAdminMessage()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
    if (!selectedId) return;
    fetch(`/admin/chat/messages/${selectedId}?type=${selectedType}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        const chatBox = document.getElementById('chatMessages');
        chatBox.innerHTML = '';
        if (!data || data.length === 0) {
            chatBox.innerHTML = '<div class="empty-state"><i class="fas fa-comments"></i><p>Chưa có tin nhắn</p></div>';
            return;
        }
        data.forEach(msg => {
            appendMsg(msg);
            lastMessageId = Math.max(lastMessageId, msg.id);
        });
        scrollToBottom();
    })
    .catch(err => console.error('Load messages error:', err));
}

function sendAdminMessage() {
    if (!selectedId) return;
    
    const input = document.getElementById('adminChatInput');
    const sendBtn = document.getElementById('adminSendBtn');
    const form = document.getElementById('adminChatForm');
    
    if (!input.value.trim() && !document.getElementById('adminFile').files[0]) return;

    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    const formData = new FormData(form);
    formData.append('type', selectedType);

    fetch(`/admin/chat/reply/${selectedId}`, {
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
            if (pin === null) throw new Error('Bị hủy');
            formData.set('admin_pin', pin);
            return fetch(`/admin/chat/reply/${selectedId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            }).then(r => r.json());
        }
        if (!res.ok) {
            const err = await res.json();
            throw new Error(err.error || 'Lỗi gửi tin nhắn');
        }
        return res.json();
    })
    .then(data => {
        if (data && data.id) {
            appendMsg(data);
            lastMessageId = Math.max(lastMessageId, data.id);
            input.value = '';
            clearPreview();
            scrollToBottom();
        }
    })
    .catch(err => {
        if (err.message !== 'Bị hủy') alert(err.message);
    })
    .finally(() => {
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
    });
}

// Handle Enter
document.getElementById('adminChatInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        sendAdminMessage();
    }
});

function appendMsg(msg) {
    const chatBox = document.getElementById('chatMessages');
    if (!chatBox) return;
    const empty = chatBox.querySelector('.empty-state');
    if (empty) empty.remove();
    
    const div = document.createElement('div');
    div.className = `chat-message ${msg.is_admin ? 'admin' : 'user'}`;
    const time = new Date(msg.created_at).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
    
    let content = msg.message ? `<div class="message-bubble">${escapeHtml(msg.message)}</div>` : '';
    if (msg.image) {
        const imageUrl = msg.image.startsWith('http') ? msg.image : `{{ asset('') }}${msg.image}`;
        content += `<img src="${imageUrl}" style="max-width: 200px; border-radius: 10px; margin-top: 5px; cursor: pointer;" onclick="window.open('${imageUrl}')">`;
    }

    div.innerHTML = `<div>${content}<div class="message-time">${time}</div></div>`;
    chatBox.appendChild(div);
    scrollToBottom();
}

function checkNewMessages() {
    if (!selectedId) return;
    fetch(`/admin/chat/messages/${selectedId}?type=${selectedType}&last_id=${lastMessageId}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data && data.length > 0) {
            const fresh = data.filter(m => m.id > lastMessageId);
            if (fresh.length > 0) {
                fresh.forEach(m => {
                    appendMsg(m);
                    lastMessageId = Math.max(lastMessageId, m.id);
                });
            }
        }
    })
    .catch(err => console.error('Check new messages error:', err));
}

function scrollToBottom() {
    const box = document.getElementById('chatMessages');
    if (box) box.scrollTop = box.scrollHeight;
}

function escapeHtml(t) {
    if (!t) return '';
    const m = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return t.replace(/[&<>"']/g, s => m[s]);
}

document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        if (pollingInterval) clearInterval(pollingInterval);
    } else if (selectedId) {
        pollingInterval = setInterval(checkNewMessages, 3000);
    }
});
</script>
@endpush
