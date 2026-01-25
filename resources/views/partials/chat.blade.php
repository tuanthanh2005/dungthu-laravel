<!-- Chat Widget -->
@auth
<div id="chatWidget" class="chat-widget">
    <div class="chat-header">
        <h6 class="mb-0">
            <i class="fas fa-comments"></i> Chat với Admin
        </h6>
        <button class="btn-close-chat" onclick="closeChatWidget()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="chat-body" id="chatBody">
        <div class="text-center py-3 text-muted">
            <i class="fas fa-comments fa-2x mb-2"></i>
            <p>Bắt đầu cuộc trò chuyện với admin</p>
        </div>
    </div>
    
    <div class="chat-footer">
        <form id="chatForm" onsubmit="sendMessage(event)">
            <div class="input-group">
                <input type="text" class="form-control" id="chatInput" placeholder="Nhập tin nhắn..." required>
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Chat Button -->
<button class="chat-button" onclick="toggleChatWidget()" id="chatButton">
    <i class="fas fa-comments"></i>
    <span class="unread-badge" id="unreadBadge" style="display: none;">0</span>
    <span class="chat-tooltip" id="chatTooltip">Chat với Admin</span>
</button>

<style>
.chat-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-size: 22px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    z-index: 9999;
}

.chat-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}

.chat-button .unread-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff4444;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    font-size: 12px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
}

.chat-tooltip {
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%) translateY(-10px);
    background: rgba(0, 0, 0, 0.85);
    color: white;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 13px;
    white-space: nowrap;
    pointer-events: none;
    opacity: 0;
    transition: all 0.3s ease;
    margin-bottom: 8px;
    font-weight: 500;
}

.chat-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 6px solid transparent;
    border-top-color: rgba(0, 0, 0, 0.85);
}

.chat-tooltip.show {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

.chat-widget {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 380px;
    max-width: calc(100vw - 40px);
    height: 500px;
    max-height: calc(100vh - 150px);
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    display: none;
    flex-direction: column;
    z-index: 10000;
    overflow: hidden;
}

.chat-widget.active {
    display: flex;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chat-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-header h6 {
    font-weight: 600;
}

.btn-close-chat {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background 0.3s;
}

.btn-close-chat:hover {
    background: rgba(255, 255, 255, 0.2);
}

.chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f8f9fa;
}

.chat-message {
    margin-bottom: 15px;
    display: flex;
    align-items: flex-end;
}

.chat-message.user {
    justify-content: flex-end;
}

.chat-message.admin {
    justify-content: flex-start;
}

.message-content {
    max-width: 75%;
    padding: 10px 15px;
    border-radius: 18px;
    word-wrap: break-word;
}

.chat-message.user .message-content {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

.chat-message.admin .message-content {
    background: white;
    color: #333;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.message-time {
    font-size: 11px;
    opacity: 0.7;
    margin-top: 4px;
}

.chat-message.user .message-time {
    text-align: right;
}

.chat-footer {
    padding: 15px;
    background: white;
    border-top: 1px solid #e9ecef;
}

.chat-footer .input-group {
    gap: 10px;
}

.chat-footer .form-control {
    border-radius: 20px;
    border: 1px solid #ddd;
    padding: 10px 20px;
}

.chat-footer .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.chat-footer .btn {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.chat-footer .btn:hover {
    opacity: 0.9;
    transform: scale(1.05);
}

/* Mobile responsive */
@media (max-width: 768px) {
    .chat-widget {
        width: calc(100vw - 20px);
        height: calc(100vh - 100px);
        left: 10px;
        right: auto;
        bottom: 70px;
    }
    
    .chat-button {
        bottom: 10px;
        left: 10px;
        right: auto;
        width: 50px;
        height: 50px;
        font-size: 20px;
        z-index: 9999;
    }
    
    .chat-button .unread-badge {
        width: 20px;
        height: 20px;
        font-size: 11px;
        top: -3px;
        right: -3px;
    }
    
    .chat-tooltip {
        font-size: 12px;
        padding: 6px 12px;
        white-space: normal;
        max-width: 120px;
        text-align: center;
    }
}

/* Ensure chat doesn't interfere with other fixed elements */
@media (max-width: 576px) {
    .chat-button {
        bottom: 10px;
        left: 10px;
        right: auto;
    }
    
    .chat-widget {
        bottom: 70px;
        left: 10px;
        right: auto;
    }
}
</style>

<script>
let chatWidget = null;
let lastMessageId = 0;
let pollingInterval = null;
let tooltipInterval = null;

document.addEventListener('DOMContentLoaded', function() {
    chatWidget = document.getElementById('chatWidget');
    loadMessages();
    startPolling();
    startTooltipCycle();
});

function toggleChatWidget() {
    if (chatWidget.classList.contains('active')) {
        closeChatWidget();
    } else {
        openChatWidget();
    }
}

function openChatWidget() {
    chatWidget.classList.add('active');
    loadMessages();
    scrollToBottom();
    document.getElementById('chatInput').focus();
}

function closeChatWidget() {
    chatWidget.classList.remove('active');
}

function loadMessages() {
    fetch('{{ route('chat.messages') }}')
        .then(response => response.json())
        .then(data => {
            const chatBody = document.getElementById('chatBody');
            
            if (data.length === 0) {
                chatBody.innerHTML = `
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-comments fa-2x mb-2"></i>
                        <p>Bắt đầu cuộc trò chuyện với admin</p>
                    </div>
                `;
                return;
            }
            
            chatBody.innerHTML = '';
            data.forEach(message => {
                appendMessage(message);
                lastMessageId = Math.max(lastMessageId, message.id);
            });
            scrollToBottom();
        })
        .catch(error => {
            console.error('Error loading messages:', error);
        });
}

function sendMessage(event) {
    event.preventDefault();
    
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    fetch('{{ route('chat.send') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        appendMessage(data);
        lastMessageId = Math.max(lastMessageId, data.id);
        input.value = '';
        scrollToBottom();
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Không thể gửi tin nhắn. Vui lòng thử lại!');
    });
}

function appendMessage(message) {
    const chatBody = document.getElementById('chatBody');
    
    // Remove welcome message if exists
    const welcomeMsg = chatBody.querySelector('.text-center');
    if (welcomeMsg) {
        welcomeMsg.remove();
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
    
    chatBody.appendChild(messageDiv);
}

function scrollToBottom() {
    const chatBody = document.getElementById('chatBody');
    chatBody.scrollTop = chatBody.scrollHeight;
}

function startPolling() {
    pollingInterval = setInterval(() => {
        checkNewMessages();
    }, 3000); // Check every 3 seconds
}

function checkNewMessages() {
    fetch(`{{ route('chat.new') }}?last_id=${lastMessageId}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                let unreadCount = 0;
                data.forEach(message => {
                    if (message.is_admin && !message.is_read) {
                        unreadCount++;
                    }
                    appendMessage(message);
                    lastMessageId = Math.max(lastMessageId, message.id);
                });
                
                scrollToBottom();
                
                // Update unread badge
                if (unreadCount > 0 && !chatWidget.classList.contains('active')) {
                    showUnreadBadge(unreadCount);
                }
            }
        })
        .catch(error => {
            console.error('Error checking new messages:', error);
        });
}

function showUnreadBadge(count) {
    const badge = document.getElementById('unreadBadge');
    const currentCount = parseInt(badge.textContent) || 0;
    const newCount = currentCount + count;
    
    badge.textContent = newCount;
    badge.style.display = 'flex';
}

function hideUnreadBadge() {
    const badge = document.getElementById('unreadBadge');
    badge.textContent = '0';
    badge.style.display = 'none';
}

// Hide badge when chat is opened
const originalOpenChat = openChatWidget;
openChatWidget = function() {
    originalOpenChat();
    hideUnreadBadge();
};

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

// Tooltip auto show/hide cycle
function startTooltipCycle() {
    const tooltip = document.getElementById('chatTooltip');
    
    function showTooltip() {
        tooltip.classList.add('show');
        
        // Hide after 3 seconds
        setTimeout(() => {
            tooltip.classList.remove('show');
        }, 3000);
    }
    
    // Show immediately on load
    setTimeout(showTooltip, 1000);
    
    // Then repeat every 23 seconds (3s show + 20s hide)
    setInterval(showTooltip, 23000);
}

// Stop polling when page is hidden
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
    } else {
        startPolling();
    }
});
</script>
@endauth
