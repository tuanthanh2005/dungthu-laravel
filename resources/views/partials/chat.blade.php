<style>
/* ============================================
   MODERN CHAT SYSTEM - PREMIUM DESIGN
   ============================================ */

:root {
    --chat-primary: #6366f1;
    --chat-primary-dark: #4f46e5;
    --chat-secondary: #8b5cf6;
    --chat-ai-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --chat-admin-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --chat-user-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --chat-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    --chat-shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* ============================================
   FLOATING CHAT BUTTONS
   ============================================ */

.chat-fab-container {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9998;
    display: flex;
    flex-direction: column;
    gap: 16px;
    align-items: flex-end;
}

.chat-fab {
    position: relative;
    width: 64px;
    height: 64px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    box-shadow: var(--chat-shadow);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.chat-fab::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: inherit;
    filter: blur(20px);
    opacity: 0.6;
    z-index: -1;
    transition: opacity 0.3s ease;
}

.chat-fab:hover {
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 24px 70px rgba(0, 0, 0, 0.4);
}

.chat-fab:hover::before {
    opacity: 0.8;
}

.chat-fab:active {
    transform: translateY(-2px) scale(1.02);
}

.chat-fab.ai-bot {
    background: var(--chat-ai-gradient);
}

.chat-fab.admin-chat {
    background: var(--chat-admin-gradient);
}

.chat-fab .fab-icon {
    position: relative;
    z-index: 1;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.chat-fab .unread-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    min-width: 24px;
    height: 24px;
    background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
    color: white;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
    border: 3px solid white;
    box-shadow: 0 4px 12px rgba(255, 65, 108, 0.5);
    animation: bounce 1s ease-in-out infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-4px); }
}

.chat-fab .fab-tooltip {
    position: absolute;
    right: 76px;
    background: rgba(0, 0, 0, 0.9);
    color: white;
    padding: 10px 16px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
    pointer-events: none;
    opacity: 0;
    transform: translateX(10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(10px);
}

.chat-fab .fab-tooltip::after {
    content: '';
    position: absolute;
    right: -6px;
    top: 50%;
    transform: translateY(-50%);
    border: 6px solid transparent;
    border-left-color: rgba(0, 0, 0, 0.9);
}

.chat-fab:hover .fab-tooltip {
    opacity: 1;
    transform: translateX(0);
}

/* ============================================
   CHAT WIDGET CONTAINER
   ============================================ */

.chat-widget {
    position: fixed;
    bottom: 110px;
    right: 24px;
    width: 420px;
    height: 640px;
    max-width: calc(100vw - 48px);
    max-height: calc(100vh - 150px);
    background: white;
    border-radius: 24px;
    box-shadow: var(--chat-shadow);
    display: none;
    flex-direction: column;
    z-index: 9999;
    overflow: hidden;
    transform: scale(0.9) translateY(20px);
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.chat-widget.active {
    display: flex;
    transform: scale(1) translateY(0);
    opacity: 1;
}

.chat-widget.ai-bot {
    --widget-gradient: var(--chat-ai-gradient);
    --widget-color: #667eea;
}

.chat-widget.admin-chat {
    --widget-gradient: var(--chat-admin-gradient);
    --widget-color: #f5576c;
}

/* ============================================
   CHAT HEADER
   ============================================ */

.chat-header {
    background: var(--widget-gradient);
    padding: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}

.chat-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.chat-header-content {
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
    z-index: 1;
}

.chat-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    border: 3px solid rgba(255, 255, 255, 0.3);
}

.chat-header-text h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: white;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.chat-header-text p {
    margin: 4px 0 0;
    font-size: 13px;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
}

.chat-status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.chat-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #4ade80;
    box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.3);
    animation: pulse-dot 2s ease-in-out infinite;
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.chat-close-btn {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: white;
    font-size: 20px;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.chat-close-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

/* ============================================
   CHAT BODY
   ============================================ */

.chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
    background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
    position: relative;
}

.chat-body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.03) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.03) 0%, transparent 50%);
    pointer-events: none;
}

.chat-body::-webkit-scrollbar {
    width: 6px;
}

.chat-body::-webkit-scrollbar-track {
    background: transparent;
}

.chat-body::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 3px;
}

.chat-body::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.3);
}

.chat-welcome {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
}

.chat-welcome i {
    font-size: 64px;
    background: var(--widget-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 20px;
    display: block;
}

.chat-welcome h4 {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 12px;
}

.chat-welcome p {
    font-size: 15px;
    color: #6b7280;
    margin: 0;
}

/* ============================================
   CHAT MESSAGES
   ============================================ */

.chat-message {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    animation: messageSlideIn 0.3s ease;
}

@keyframes messageSlideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chat-message.user {
    align-items: flex-end;
}

.chat-message.bot,
.chat-message.admin {
    align-items: flex-start;
}

.message-bubble {
    max-width: 75%;
    position: relative;
}

.message-content {
    padding: 14px 18px;
    border-radius: 20px;
    font-size: 15px;
    line-height: 1.5;
    word-wrap: break-word;
    position: relative;
    box-shadow: var(--chat-shadow-sm);
}

.chat-message.user .message-content {
    background: var(--chat-user-gradient);
    color: white;
    border-bottom-right-radius: 6px;
}

.chat-message.bot .message-content,
.chat-message.admin .message-content {
    background: white;
    color: #1f2937;
    border-bottom-left-radius: 6px;
    border: 1px solid #e5e7eb;
}

.message-time {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 6px;
    font-weight: 500;
}

.chat-message.user .message-time {
    text-align: right;
}

.typing-indicator {
    display: flex;
    gap: 6px;
    padding: 14px 18px;
    background: white;
    border-radius: 20px;
    border-bottom-left-radius: 6px;
    width: fit-content;
    box-shadow: var(--chat-shadow-sm);
}

.typing-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--widget-color);
    animation: typingBounce 1.4s infinite;
}

.typing-dot:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dot:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typingBounce {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-10px); }
}

/* ============================================
   CHAT FOOTER
   ============================================ */

.chat-footer {
    padding: 20px 24px;
    background: white;
    border-top: 1px solid #e5e7eb;
}

.chat-input-wrapper {
    display: flex;
    gap: 12px;
    align-items: center;
}

.chat-input {
    flex: 1;
    border: 2px solid #e5e7eb;
    border-radius: 24px;
    padding: 14px 20px;
    font-size: 15px;
    outline: none;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.chat-input:focus {
    border-color: var(--widget-color);
    background: white;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}

.chat-send-btn {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    border: none;
    background: var(--widget-gradient);
    color: white;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: var(--chat-shadow-sm);
}

.chat-send-btn:hover:not(:disabled) {
    transform: scale(1.1);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
}

.chat-send-btn:active:not(:disabled) {
    transform: scale(0.95);
}

.chat-send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.chat-disclaimer {
    margin-top: 12px;
    font-size: 12px;
    color: #9ca3af;
    text-align: center;
    line-height: 1.4;
}

/* ============================================
   MOBILE RESPONSIVE
   ============================================ */

@media (max-width: 768px) {
    .chat-fab-container {
        bottom: 16px;
        right: 16px;
        gap: 12px;
    }

    .chat-fab {
        width: 56px;
        height: 56px;
        font-size: 24px;
    }

    .chat-fab .fab-tooltip {
        display: none;
    }

    .chat-widget {
        bottom: 90px;
        right: 16px;
        left: 16px;
        width: auto;
        height: calc(100vh - 120px);
        border-radius: 20px;
    }

    .chat-header {
        padding: 20px;
    }

    .chat-avatar {
        width: 48px;
        height: 48px;
        font-size: 24px;
    }

    .chat-header-text h3 {
        font-size: 18px;
    }

    .chat-header-text p {
        font-size: 12px;
    }

    .chat-body {
        padding: 16px;
    }

    .message-bubble {
        max-width: 85%;
    }

    .chat-footer {
        padding: 16px;
    }

    .chat-input {
        padding: 12px 16px;
        font-size: 14px;
    }

    .chat-send-btn {
        width: 48px;
        height: 48px;
        font-size: 18px;
    }
}

@media (max-width: 480px) {
    .chat-widget {
        bottom: 0;
        right: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        max-height: 100vh;
        border-radius: 0;
    }

    .chat-fab-container {
        bottom: 20px;
        right: 20px;
    }
}
</style>

<!-- AI Chatbot Widget -->
<div id="aiChatWidget" class="chat-widget ai-bot">
    <div class="chat-header">
        <div class="chat-header-content">
            <div class="chat-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="chat-header-text">
                <h3>Trợ lý AI</h3>
                <p class="chat-status-indicator">
                    <span class="chat-status-dot"></span>
                    Đang hoạt động
                </p>
            </div>
        </div>
        <button class="chat-close-btn" onclick="closeAiChat()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="chat-body" id="aiChatBody">
        <div class="chat-welcome">
            <i class="fas fa-robot"></i>
            <h4>Xin chào! 👋</h4>
            <p>Tôi là trợ lý AI của DungThu.com<br>Bạn cần tư vấn gì về sản phẩm?</p>
        </div>
    </div>

    <div class="chat-footer">
        <form id="aiChatForm" onsubmit="sendAiMessage(event)">
            <div class="chat-input-wrapper">
                <input 
                    type="text" 
                    class="chat-input" 
                    id="aiChatInput" 
                    placeholder="Nhập câu hỏi của bạn..."
                    autocomplete="off"
                    required
                >
                <button class="chat-send-btn" type="submit" id="aiChatSendBtn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
        <div class="chat-disclaimer">
            💡 AI có thể tư vấn sai. Đăng nhập để chat với admin để được hỗ trợ chính xác hơn.
        </div>
    </div>
</div>

<!-- Admin Chat Widget (for authenticated users) -->
@auth
@if(auth()->user()->role !== 'admin')
<div id="adminChatWidget" class="chat-widget admin-chat">
    <div class="chat-header">
        <div class="chat-header-content">
            <div class="chat-avatar">
                <i class="fas fa-headset"></i>
            </div>
            <div class="chat-header-text">
                <h3>Chat với Admin</h3>
                <p class="chat-status-indicator">
                    <span class="chat-status-dot"></span>
                    Hỗ trợ 24/7
                </p>
            </div>
        </div>
        <button class="chat-close-btn" onclick="closeAdminChat()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="chat-body" id="adminChatBody">
        <div class="chat-welcome">
            <i class="fas fa-headset"></i>
            <h4>Chào {{ auth()->user()->name }}! 👋</h4>
            <p>Bắt đầu cuộc trò chuyện với admin<br>Chúng tôi luôn sẵn sàng hỗ trợ bạn</p>
        </div>
    </div>

    <div class="chat-footer">
        <form id="adminChatForm" onsubmit="sendAdminMessage(event)">
            <div class="chat-input-wrapper">
                <input 
                    type="text" 
                    class="chat-input" 
                    id="adminChatInput" 
                    placeholder="Nhập tin nhắn..."
                    autocomplete="off"
                    required
                >
                <button class="chat-send-btn" type="submit" id="adminChatSendBtn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endauth

<!-- Floating Action Buttons -->
<div class="chat-fab-container">
    <!-- AI Chatbot Button -->
    <button class="chat-fab ai-bot" onclick="toggleAiChat()" id="aiChatFab">
        <i class="fas fa-robot fab-icon"></i>
        <span class="fab-tooltip">Trợ lý AI tư vấn</span>
    </button>

    <!-- Admin Chat Button (for authenticated users) -->
    @auth
    @if(auth()->user()->role !== 'admin')
    <button class="chat-fab admin-chat" onclick="toggleAdminChat()" id="adminChatFab">
        <i class="fas fa-headset fab-icon"></i>
        <span class="unread-badge" id="adminUnreadBadge" style="display: none;">0</span>
        <span class="fab-tooltip">Chat với Admin</span>
    </button>
    @endif
    @endauth
</div>

<script>
// ============================================
// AI CHATBOT FUNCTIONALITY
// ============================================

let aiChatOpen = false;

function toggleAiChat() {
    const widget = document.getElementById('aiChatWidget');
    if (aiChatOpen) {
        closeAiChat();
    } else {
        openAiChat();
    }
}

function openAiChat() {
    const widget = document.getElementById('aiChatWidget');
    const adminWidget = document.getElementById('adminChatWidget');
    
    // Close admin chat if open
    if (adminWidget && adminWidget.classList.contains('active')) {
        closeAdminChat();
    }
    
    widget.classList.add('active');
    aiChatOpen = true;
    document.getElementById('aiChatInput').focus();
}

function closeAiChat() {
    const widget = document.getElementById('aiChatWidget');
    widget.classList.remove('active');
    aiChatOpen = false;
}

function sendAiMessage(event) {
    event.preventDefault();
    
    const input = document.getElementById('aiChatInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Add user message
    appendAiMessage('user', message);
    input.value = '';
    
    // Show typing indicator
    showTypingIndicator('aiChatBody');
    
    // Disable send button
    const sendBtn = document.getElementById('aiChatSendBtn');
    sendBtn.disabled = true;
    
    // Send to server
    fetch('{{ route('guest-chat.send') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message })
    })
    .then(res => res.json())
    .then(data => {
        removeTypingIndicator('aiChatBody');
        if (data && data.reply) {
            appendAiMessage('bot', data.reply);
        } else {
            appendAiMessage('bot', 'Xin lỗi, hiện tại tôi không thể trả lời. Vui lòng thử lại sau hoặc chat với admin để được hỗ trợ tốt hơn.');
        }
    })
    .catch(() => {
        removeTypingIndicator('aiChatBody');
        appendAiMessage('bot', 'Đã có lỗi xảy ra. Vui lòng thử lại sau.');
    })
    .finally(() => {
        sendBtn.disabled = false;
    });
}

function appendAiMessage(type, content) {
    const chatBody = document.getElementById('aiChatBody');
    
    // Remove welcome message
    const welcome = chatBody.querySelector('.chat-welcome');
    if (welcome) welcome.remove();
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `chat-message ${type}`;
    
    const time = new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
    
    messageDiv.innerHTML = `
        <div class="message-bubble">
            <div class="message-content">${escapeHtml(content)}</div>
            <div class="message-time">${time}</div>
        </div>
    `;
    
    chatBody.appendChild(messageDiv);
    chatBody.scrollTop = chatBody.scrollHeight;
}

// ============================================
// ADMIN CHAT FUNCTIONALITY
// ============================================

@auth
@if(auth()->user()->role !== 'admin')
let adminChatOpen = false;
let lastMessageId = 0;
let pollingInterval = null;

document.addEventListener('DOMContentLoaded', function() {
    loadAdminMessages();
    startAdminPolling();
    refreshUnreadCount();
});

function toggleAdminChat() {
    if (adminChatOpen) {
        closeAdminChat();
    } else {
        openAdminChat();
    }
}

function openAdminChat() {
    const widget = document.getElementById('adminChatWidget');
    const aiWidget = document.getElementById('aiChatWidget');
    
    // Close AI chat if open
    if (aiWidget && aiWidget.classList.contains('active')) {
        closeAiChat();
    }
    
    widget.classList.add('active');
    adminChatOpen = true;
    document.getElementById('adminChatInput').focus();
    loadAdminMessages();
    markAllAsRead();
}

function closeAdminChat() {
    const widget = document.getElementById('adminChatWidget');
    widget.classList.remove('active');
    adminChatOpen = false;
}

function sendAdminMessage(event) {
    event.preventDefault();
    
    const input = document.getElementById('adminChatInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    const sendBtn = document.getElementById('adminChatSendBtn');
    sendBtn.disabled = true;
    
    fetch('{{ route('chat.send') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message })
    })
    .then(res => res.json())
    .then(data => {
        appendAdminMessage(data);
        lastMessageId = Math.max(lastMessageId, data.id);
        input.value = '';
    })
    .catch(() => {
        alert('Không thể gửi tin nhắn. Vui lòng thử lại!');
    })
    .finally(() => {
        sendBtn.disabled = false;
    });
}

function loadAdminMessages() {
    fetch('{{ route('chat.messages') }}')
        .then(res => res.json())
        .then(data => {
            const chatBody = document.getElementById('adminChatBody');
            
            if (data.length === 0) {
                return;
            }
            
            // Remove welcome message
            const welcome = chatBody.querySelector('.chat-welcome');
            if (welcome) welcome.remove();
            
            chatBody.innerHTML = '';
            data.forEach(message => {
                appendAdminMessage(message);
                lastMessageId = Math.max(lastMessageId, message.id);
            });
            chatBody.scrollTop = chatBody.scrollHeight;
        })
        .catch(err => console.error('Error loading messages:', err));
}

function appendAdminMessage(message) {
    const chatBody = document.getElementById('adminChatBody');
    
    // Remove welcome message
    const welcome = chatBody.querySelector('.chat-welcome');
    if (welcome) welcome.remove();
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `chat-message ${message.is_admin ? 'admin' : 'user'}`;
    
    const date = new Date(message.created_at);
    const time = date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
    
    messageDiv.innerHTML = `
        <div class="message-bubble">
            <div class="message-content">${escapeHtml(message.message)}</div>
            <div class="message-time">${time}</div>
        </div>
    `;
    
    chatBody.appendChild(messageDiv);
    chatBody.scrollTop = chatBody.scrollHeight;
}

function startAdminPolling() {
    pollingInterval = setInterval(() => {
        if (adminChatOpen) {
            checkNewAdminMessages();
        } else {
            refreshUnreadCount();
        }
    }, 3000);
}

function checkNewAdminMessages() {
    fetch(`{{ route('chat.new') }}?last_id=${lastMessageId}`)
        .then(res => res.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(message => {
                    appendAdminMessage(message);
                    lastMessageId = Math.max(lastMessageId, message.id);
                });
                markAllAsRead();
            }
        })
        .catch(err => console.error('Error checking messages:', err));
}

function refreshUnreadCount() {
    fetch('{{ route('chat.unread-count') }}')
        .then(res => res.json())
        .then(data => {
            if (data && typeof data.unread !== 'undefined' && !adminChatOpen) {
                const badge = document.getElementById('adminUnreadBadge');
                if (data.unread > 0) {
                    badge.textContent = data.unread;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        })
        .catch(err => console.error('Error fetching unread count:', err));
}

function markAllAsRead() {
    fetch('{{ route('chat.mark-read') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({})
    })
    .then(() => {
        const badge = document.getElementById('adminUnreadBadge');
        badge.style.display = 'none';
    })
    .catch(err => console.error('Error marking read:', err));
}
@endif
@endauth

// ============================================
// SHARED UTILITIES
// ============================================

function showTypingIndicator(bodyId) {
    const chatBody = document.getElementById(bodyId);
    const indicator = document.createElement('div');
    indicator.className = 'typing-indicator';
    indicator.id = 'typingIndicator';
    indicator.innerHTML = `
        <div class="typing-dot"></div>
        <div class="typing-dot"></div>
        <div class="typing-dot"></div>
    `;
    chatBody.appendChild(indicator);
    chatBody.scrollTop = chatBody.scrollHeight;
}

function removeTypingIndicator(bodyId) {
    const chatBody = document.getElementById(bodyId);
    const indicator = chatBody.querySelector('#typingIndicator');
    if (indicator) indicator.remove();
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text || '').replace(/[&<>"']/g, m => map[m]);
}

// Close chat when clicking outside
document.addEventListener('click', function(event) {
    const aiWidget = document.getElementById('aiChatWidget');
    const aiBtn = document.getElementById('aiChatFab');
    const adminWidget = document.getElementById('adminChatWidget');
    const adminBtn = document.getElementById('adminChatFab');
    
    if (aiChatOpen && !aiWidget.contains(event.target) && !aiBtn.contains(event.target)) {
        closeAiChat();
    }
    
    if (adminChatOpen && adminWidget && !adminWidget.contains(event.target) && adminBtn && !adminBtn.contains(event.target)) {
        closeAdminChat();
    }
});

// Prevent chat from closing when clicking inside
document.addEventListener('DOMContentLoaded', function() {
    const widgets = document.querySelectorAll('.chat-widget');
    widgets.forEach(widget => {
        widget.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
});
</script>
