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
                    <h5><i class="fas fa-users"></i> Danh sách Users</h5>
                </div>
                <div class="users-list" id="usersList">
                    <div class="users-subheader">Users da chat</div>
                    @forelse($users as $user)
                        <div class="user-item" data-user-id="{{ $user->id }}" onclick="selectUser(event, {{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}')">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                            @if(isset($user->unread_count) && $user->unread_count > 0)
                                <span class="badge bg-danger rounded-pill">{{ $user->unread_count }}</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <p>Chưa có tin nhắn nào</p>
                        </div>
                    @endforelse
                    <div class="users-subheader">Users chua chat</div>
                    @forelse($allUsers as $user)
                        <div class="user-item" data-user-id="{{ $user->id }}" onclick="selectUser(event, {{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}')">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                            <span class="badge bg-secondary rounded-pill">Moi</span>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted">
                            <p>Khong co user moi</p>
                        </div>
                    @endforelse

                </div>
            </div>

            <div class="chat-main">
                <div class="chat-header-main" id="chatHeaderMain">
                    <h5 class="mb-0 text-muted">
                        <i class="fas fa-comments"></i> Chọn user để xem tin nhắn
                    </h5>
                </div>

                <div class="chat-messages" id="chatMessages">
                    <div class="empty-state">
                        <i class="fas fa-comments"></i>
                        <p>Chọn một user để bắt đầu trò chuyện</p>
                    </div>
                </div>

                <div class="chat-input-area" id="chatInputArea" style="display: none;">
                    <form id="adminChatForm" onsubmit="sendAdminMessage(event)">
                        <div class="input-group">
                            <input type="text" class="form-control" id="adminChatInput" placeholder="Nhập tin nhắn..." required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i> Gửi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedUserId = null;
let lastMessageId = 0;
let pollingInterval = null;

function selectUser(evt, userId, userName, userEmail) {
    selectedUserId = userId;
    lastMessageId = 0;
    
    // Update active state
    document.querySelectorAll('.user-item').forEach(item => {
        item.classList.remove('active');
    });
    evt.currentTarget.classList.add('active');
    
    // Update header
    document.getElementById('chatHeaderMain').innerHTML = `
        <h5 class="mb-0">
            <i class="fas fa-user"></i> ${userName}
            <small class="text-muted" style="font-size: 14px;">(${userEmail})</small>
        </h5>
    `;
    
    // Show input area
    document.getElementById('chatInputArea').style.display = 'block';
    
    // Load messages
    loadUserMessages(userId);
    
    // Start polling
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
    pollingInterval = setInterval(() => {
        checkNewAdminMessages();
    }, 3000);
}

function loadUserMessages(userId) {
    fetch(`/admin/chat/messages/${userId}`)
        .then(response => response.json())
        .then(data => {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.innerHTML = '';
            
            if (data.length === 0) {
                chatMessages.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-comments"></i>
                        <p>Chưa có tin nhắn nào</p>
                    </div>
                `;
                return;
            }
            
            data.forEach(message => {
                appendAdminMessage(message);
                lastMessageId = Math.max(lastMessageId, message.id);
            });
            
            scrollToBottom();
        })
        .catch(error => {
            console.error('Error loading messages:', error);
        });
}

function sendAdminMessage(event) {
    event.preventDefault();
    
    if (!selectedUserId) return;
    
    const input = document.getElementById('adminChatInput');
    const message = input.value.trim();
    
    if (!message) return;

    function send(payload) {
        return fetch(`/admin/chat/reply/${selectedUserId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(async (response) => {
            const contentType = response.headers.get('content-type') || '';

            let data = null;
            if (contentType.includes('application/json')) {
                data = await response.json();
            } else {
                const text = await response.text();
                const err = new Error(text || `HTTP ${response.status}`);
                err.status = response.status;
                throw err;
            }

            if (!response.ok) {
                const err = new Error(data?.message || `HTTP ${response.status}`);
                err.status = response.status;
                throw err;
            }

            return data;
        });
    }

    send({ message })
        .then(data => {
            appendAdminMessage(data);
            lastMessageId = Math.max(lastMessageId, data.id);
            input.value = '';
            scrollToBottom();
        })
        .catch(error => {
            console.error('Error sending message:', error);

            const msg = error?.message || 'Không thể gửi tin nhắn. Vui lòng thử lại!';
            const status = error?.status;

            // Nếu server yêu cầu mã xác nhận, mở ô nhập và gửi lại kèm admin_pin
            if (status === 403 && /mã xác nhận/i.test(msg)) {
                const pin = window.prompt('Nhập mã xác nhận 3 số (vd: 123):');
                if (pin === null) return;
                if (!/^\d{3}$/.test(pin)) {
                    alert('Mã xác nhận phải đúng 3 số.');
                    return;
                }

                send({ message, admin_pin: pin })
                    .then(data => {
                        appendAdminMessage(data);
                        lastMessageId = Math.max(lastMessageId, data.id);
                        input.value = '';
                        scrollToBottom();
                    })
                    .catch(err2 => {
                        console.error('Error sending message after pin:', err2);
                        alert(err2?.message || 'Không thể gửi tin nhắn. Vui lòng thử lại!');
                    });

                return;
            }

            alert(msg);
        });
}

function appendAdminMessage(message) {
    const chatMessages = document.getElementById('chatMessages');
    
    // Remove empty state if exists
    const emptyState = chatMessages.querySelector('.empty-state');
    if (emptyState) {
        emptyState.remove();
    }
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `chat-message ${message.is_admin ? 'admin' : 'user'}`;
    
    const date = new Date(message.created_at);
    const timeStr = date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
    
    messageDiv.innerHTML = `
        <div>
            <div class="message-content">
                ${escapeHtml(message.message)}
            </div>
            <div class="message-time">${timeStr}</div>
        </div>
    `;
    
    chatMessages.appendChild(messageDiv);
}

function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function checkNewAdminMessages() {
    if (!selectedUserId) return;
    
    fetch(`/admin/chat/messages/${selectedUserId}?last_id=${lastMessageId}`)
        .then(response => response.json())
        .then(data => {
            const newMessages = data.filter(msg => msg.id > lastMessageId);
            
            if (newMessages.length > 0) {
                newMessages.forEach(message => {
                    appendAdminMessage(message);
                    lastMessageId = Math.max(lastMessageId, message.id);
                });
                scrollToBottom();
            }
        })
        .catch(error => {
            console.error('Error checking new messages:', error);
        });
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Stop polling when page is hidden
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
    } else if (selectedUserId) {
        pollingInterval = setInterval(() => {
            checkNewAdminMessages();
        }, 3000);
    }
});
</script>
@endsection
