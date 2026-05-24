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
    --chat-support-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    --chat-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    --chat-shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* User support widget */
.chat-widget.user-support {
    --widget-gradient: var(--chat-support-gradient);
    --widget-color: #6366f1;
}
.chat-fab.user-support {
    background: var(--chat-support-gradient);
}

/* ============================================
   FLOATING CHAT BUTTONS
   ============================================ */

.chat-fab-container {
    position: fixed;
    bottom: 80px; /* Default above mobile nav */
    right: 24px;
    z-index: 100000;
    display: flex;
    flex-direction: column;
    gap: 16px;
    align-items: flex-end;
    touch-action: none; /* Critical for dragging on touch */
    transition: opacity 0.3s ease, transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@media (min-width: 992px) {
    .chat-fab-container {
        bottom: 24px;
    }
}

/* Hide FABs when chat widget is active to prevent overlapping */
.chat-widget.active ~ .chat-fab-container {
    opacity: 0;
    pointer-events: none;
    transform: translateY(15px);
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
    align-items: flex-end;
}

.chat-input {
    flex: 1;
    border: 2px solid #e5e7eb;
    border-radius: 24px;
    padding: 12px 20px;
    font-size: 15px;
    outline: none;
    transition: border-color 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
    background: #f9fafb;
    resize: none;
    height: 48px;
    max-height: 120px;
    overflow-y: hidden;
    box-sizing: border-box;
    font-family: inherit;
    line-height: 20px;
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
        bottom: 80px;
        right: 16px;
        gap: 10px;
    }

    .chat-fab {
        width: 44px;
        height: 44px;
        font-size: 20px;
    }

    /* Scaling Zalo custom icon elements for mobile */
    .chat-fab .position-relative {
        width: 30px !important;
        height: 30px !important;
    }

    .chat-fab .position-relative i.fa-comment {
        font-size: 28px !important;
    }

    .chat-fab .position-relative span {
        font-size: 14px !important;
    }

    .chat-fab .fab-tooltip {
        display: none;
    }

    .chat-widget {
        bottom: 140px; 
        right: 16px;
        left: auto;
        width: 300px; 
        height: 480px; 
        max-height: calc(100vh - 200px);
        border-radius: 16px;
    }

    .chat-header {
        padding: 12px 16px;
    }

    .chat-avatar {
        width: 40px;
        height: 40px;
        font-size: 20px;
    }

    .chat-header-text h3 {
        font-size: 16px;
    }

    .chat-header-text p {
        font-size: 11px;
    }

    .chat-body {
        padding: 12px;
    }

    .message-bubble {
        max-width: 85%;
    }

    .chat-footer {
        padding: 12px;
    }

    .chat-input {
        padding: 10px 14px;
        font-size: 14px;
        height: 44px;
        line-height: 20px;
    }

    .chat-send-btn {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
}

@media (max-width: 480px) {
    .chat-widget {
        bottom: 140px;
        right: 10px;
        left: 10px;
        width: auto;
        height: 420px;
        border-radius: 16px;
    }

    .chat-fab-container {
        bottom: 80px;
        right: 16px;
    }
}

/* Chat Tools & Image Preview */
.chat-tool-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s;
    border-radius: 50%;
}
.chat-tool-btn:hover {
    background: #f3f4f6;
    color: var(--widget-color);
}
.chat-input-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
#imagePreviewContainer, #affiliateImagePreviewContainer, #userImagePreviewContainer {
    padding: 8px;
    background: #f9fafb;
    border-radius: 12px;
}
.preview-item {
    position: relative;
    display: inline-block;
}
.preview-item img {
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
}
.preview-item button {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.message-image {
    max-width: 100%;
    border-radius: 12px;
    margin-top: 8px;
    cursor: pointer;
}
</style>


<!-- Affiliate Chat Widget -->
@if(Auth::guard('affiliate')->check() && Auth::guard('affiliate')->user()->status === 'approved')
<div id="affiliateChatWidget" class="chat-widget admin-chat">
    <div class="chat-header">
        <div class="chat-header-content">
            <div class="chat-avatar">
                <i class="fas fa-headset"></i>
            </div>
            <div class="chat-header-text">
                <h3>Chat với Admin</h3>
                <p class="chat-status-indicator">
                    <span class="chat-status-dot"></span>
                    Hỗ trợ CTV
                </p>
            </div>
        </div>
        <button class="chat-close-btn" onclick="closeAffiliateChat()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="chat-body" id="affiliateChatBody">
        <div class="chat-welcome">
            <i class="fas fa-headset"></i>
            <h4>Chào {{ Auth::guard('affiliate')->user()->name }}! 👋</h4>
            <p>Admin sẽ phản hồi ngay khi có thể<br>Vui lòng để lại tin nhắn hoặc ảnh bill</p>
        </div>
    </div>

    <div class="chat-footer">
        <form id="affiliateChatForm" onsubmit="sendAffiliateMessage(event)">
            <div class="chat-input-wrapper">
                <label for="affiliateChatImage" class="chat-tool-btn">
                    <i class="fas fa-image"></i>
                    <input type="file" id="affiliateChatImage" hidden accept="image/*" onchange="previewAffiliateImage(this)">
                </label>
                <div class="chat-input-container">
                    <div id="affiliateImagePreviewContainer" style="display: none;">
                        <span class="preview-item">
                            <img id="affiliateImagePreview" src="" alt="preview">
                            <button type="button" onclick="clearAffiliateImagePreview()"><i class="fas fa-times"></i></button>
                        </span>
                    </div>
                    <textarea 
                        class="chat-input" 
                        id="affiliateChatInput" 
                        placeholder="Nhập tin nhắn..."
                        autocomplete="off"
                        maxlength="1000"
                        rows="1"
                        style="resize: none; overflow-y: hidden;"
                    ></textarea>
                </div>
                <button class="chat-send-btn" type="submit" id="affiliateChatSendBtn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ============================================================
     USER SUPPORT CHAT WIDGET
     Chỉ hiển thị cho user thường (đã đăng nhập, không phải affiliate)
     ============================================================ --}}
@auth
@if(!Auth::guard('affiliate')->check())
<div id="userChatWidget" class="chat-widget user-support">
    <div class="chat-header">
        <div class="chat-header-content">
            <div class="chat-avatar">
                <i class="fas fa-comments"></i>
            </div>
            <div class="chat-header-text">
                <h3>Admin Hổ trợ</h3>
                <p class="chat-status-indicator">
                    <span class="chat-status-dot"></span>
                    Admin sẽ rep tin của bạn sớm nhất
                </p>
            </div>
        </div>
        <button class="chat-close-btn" onclick="closeUserChat()" aria-label="Đóng">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="chat-body" id="userChatBody">
        <div class="chat-welcome" id="userChatWelcome">
            <i class="fas fa-comments"></i>
            <h4>Xin chào, {{ Auth::user()->name ?? 'bạn' }}! 👋</h4>
            <p>Chúng tôi luôn ở đây để hỗ trợ bạn.<br>Hãy gửi tin nhắn bên dưới nhé!</p>
        </div>
    </div>

    <div class="chat-footer">
        <form id="userChatForm" onsubmit="sendUserMessage(event)" autocomplete="off">
            <div class="chat-input-wrapper">
                <label for="userChatImage" class="chat-tool-btn" title="Gửi ảnh">
                    <i class="fas fa-image"></i>
                    <input type="file" id="userChatImage" hidden accept="image/*" onchange="previewUserImage(this)">
                </label>
                <div class="chat-input-container">
                    <div id="userImagePreviewContainer" style="display: none;">
                        <span class="preview-item">
                            <img id="userImagePreview" src="" alt="preview">
                            <button type="button" onclick="clearUserImagePreview()"><i class="fas fa-times"></i></button>
                        </span>
                    </div>
                    <textarea
                        class="chat-input"
                        id="userChatInput"
                        placeholder="Nhập tin nhắn..."
                        autocomplete="off"
                        maxlength="1000"
                        rows="1"
                        style="resize: none; overflow-y: hidden;"
                    ></textarea>
                </div>
                <button class="chat-send-btn" type="submit" id="userChatSendBtn" aria-label="Gửi">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endauth


<div class="chat-fab-container">

    <!-- Zalo Chat Button -->
    <a href="https://zalo.me/0772698113" target="_blank" class="chat-fab d-flex" style="background: #0068ff; text-decoration: none !important;" aria-label="Chat Zalo">
        <div class="position-relative d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fa-solid fa-comment" style="color: #ffffff; font-size: 38px;"></i>
            <span style="position: absolute; color: #0068ff; font-family: 'Inter', 'Segoe UI', Arial, sans-serif; font-weight: 900; font-size: 19px; font-style: italic; top: 44%; left: 47%; transform: translate(-50%, -50%);">z</span>
        </div>
        <span class="fab-tooltip">Chat Zalo</span>
    </a>

    <!-- Telegram Chat Button -->
    <a href="https://t.me/specademy" target="_blank" class="chat-fab d-flex" style="background: #0088cc; text-decoration: none !important;" aria-label="Chat Telegram">
        <i class="fab fa-telegram fab-icon"></i>
        <span class="fab-tooltip">Chat Telegram</span>
    </a>

    <!-- User Support Chat Button (user thường, không phải affiliate) -->
    @auth
    @if(!Auth::guard('affiliate')->check())
    <button class="chat-fab user-support" onclick="toggleUserChat()" id="userChatFab" aria-label="Hỗ trợ khách hàng">
        <i class="fas fa-comments fab-icon"></i>
        <span class="unread-badge" id="userUnreadBadge" style="display: none;">0</span>
        <span class="fab-tooltip">Hỗ trợ khách hàng</span>
    </button>
    @endif
    @endauth

    <!-- Admin Chat Button (Only for Approved Affiliates) -->
    @if(Auth::guard('affiliate')->check() && Auth::guard('affiliate')->user()->status === 'approved')
    <button class="chat-fab admin-chat" onclick="toggleAffiliateChat()" id="affiliateChatFab">
        <i class="fas fa-headset fab-icon"></i>
        <span class="unread-badge" id="affiliateUnreadBadge" style="display: none;">0</span>
        <span class="fab-tooltip">Chat với Admin</span>
    </button>
    @endif
</div>


<script>
// ============================================
// GLOBAL STATE & UTILITIES
// ============================================
let adminChatOpen = false;
let affiliateChatOpen = false;
let lastMessageId = 0;
let lastAffiliateMessageId = 0;
let pollingInterval = null;
let affiliatePollingInterval = null;
let prevUserUnreadCount = null;
let prevAffiliateUnreadCount = null;

function autoGrowTextarea(textarea) {
    textarea.style.height = 'auto';
    const borderHeight = 4; // 2px border top + 2px border bottom
    const newHeight = textarea.scrollHeight + borderHeight;
    if (newHeight > 120) {
        textarea.style.height = '120px';
        textarea.style.overflowY = 'auto';
    } else {
        textarea.style.height = newHeight + 'px';
        textarea.style.overflowY = 'hidden';
    }
}

function escapeHtml(text) {
    const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return String(text || '').replace(/[&<>"']/g, m => map[m]);
}

function showTypingIndicator(bodyId) {
    const chatBody = document.getElementById(bodyId);
    if (!chatBody) return;
    const indicator = document.createElement('div');
    indicator.className = 'typing-indicator';
    indicator.id = 'typingIndicator';
    indicator.innerHTML = '<div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>';
    chatBody.appendChild(indicator);
    chatBody.scrollTop = chatBody.scrollHeight;
}

function removeTypingIndicator(bodyId) {
    const chatBody = document.getElementById(bodyId);
    if (!chatBody) return;
    const indicator = chatBody.querySelector('#typingIndicator');
    if (indicator) indicator.remove();
}

// ============================================
// AFFILIATE CHAT LOGIC
// ============================================
function toggleAffiliateChat() {
    const widget = document.getElementById('affiliateChatWidget');
    if (!widget) return;
    
    if (affiliateChatOpen) {
        widget.classList.remove('active');
        affiliateChatOpen = false;
    } else {
        // Close others
        const adminWidget = document.getElementById('adminChatWidget');
        if (adminWidget) {
            adminWidget.classList.remove('active');
            adminChatOpen = false;
        }
        
        widget.classList.add('active');
        affiliateChatOpen = true;
        const input = document.getElementById('affiliateChatInput');
        if (input) input.focus();
        loadAffiliateMessages();
        markAffiliateAsRead();
    }
}

function closeAffiliateChat() {
    const widget = document.getElementById('affiliateChatWidget');
    if (widget) {
        widget.classList.remove('active');
        affiliateChatOpen = false;
    }
}

function loadAffiliateMessages() {
    const body = document.getElementById('affiliateChatBody');
    if (!body) return;
    
    fetch('{{ route('affiliate.chat.messages') }}')
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) return;
            const welcome = body.querySelector('.chat-welcome');
            if (welcome) welcome.remove();
            body.innerHTML = '';
            data.forEach(msg => {
                appendAffiliateMessage(msg);
                lastAffiliateMessageId = Math.max(lastAffiliateMessageId, msg.id);
            });
            body.scrollTop = body.scrollHeight;
        });
}

function sendAffiliateMessage(event) {
    event.preventDefault();
    const input = document.getElementById('affiliateChatInput');
    const imageInput = document.getElementById('affiliateChatImage');
    const msg = input.value.trim();
    if (!msg && (!imageInput || !imageInput.files[0])) return;
    
    const sendBtn = document.getElementById('affiliateChatSendBtn');
    if (sendBtn) sendBtn.disabled = true;
    
    const formData = new FormData();
    formData.append('message', msg);
    if (imageInput && imageInput.files[0]) formData.append('image', imageInput.files[0]);
    
    fetch('{{ route('affiliate.chat.send') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        appendAffiliateMessage(data);
        lastAffiliateMessageId = Math.max(lastAffiliateMessageId, data.id);
        input.value = '';
        autoGrowTextarea(input);
        if (typeof clearAffiliateImagePreview === 'function') clearAffiliateImagePreview();
    })
    .finally(() => { if (sendBtn) sendBtn.disabled = false; });
}

function appendAffiliateMessage(message, playSound = false) {
    const body = document.getElementById('affiliateChatBody');
    if (!body) return;

    if (document.getElementById('aff-msg-' + message.id)) return;

    if (playSound && message.is_admin) {
        try {
            const a = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
            a.volume = 0.4;
            a.play().catch(() => {});
        } catch(e) {}
    }

    const welcome = body.querySelector('.chat-welcome');
    if (welcome) welcome.remove();
    
    const div = document.createElement('div');
    div.id = 'aff-msg-' + message.id;
    div.className = `chat-message ${message.is_admin ? 'admin' : 'user'}`;
    const date = new Date(message.created_at);
    const time = date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
    
    let content = message.message ? `<div class="message-content">${escapeHtml(message.message)}</div>` : '';
    if (message.image) {
        const url = message.image.startsWith('http') ? message.image : `{{ asset('') }}${message.image}`;
        content += `<img src="${url}" class="message-image" onclick="window.open('${url}')">`;
    }
    div.innerHTML = `<div class="message-bubble">${content}<div class="message-time">${time}</div></div>`;
    body.appendChild(div);
    body.scrollTop = body.scrollHeight;
}

function startAffiliatePolling() {
    affiliatePollingInterval = setInterval(() => {
        if (affiliateChatOpen) checkNewAffiliateMessages();
        else refreshAffiliateUnreadCount();
    }, 3000);
}

function checkNewAffiliateMessages() {
    fetch(`{{ route('affiliate.chat.new') }}?last_id=${lastAffiliateMessageId}`)
        .then(res => res.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(msg => {
                    if (msg.id > lastAffiliateMessageId) {
                        appendAffiliateMessage(msg, true);
                        lastAffiliateMessageId = Math.max(lastAffiliateMessageId, msg.id);
                    }
                });
                markAffiliateAsRead();
            }
        });
}

function refreshAffiliateUnreadCount() {
    const badge = document.getElementById('affiliateUnreadBadge');
    if (!badge) return;
    fetch('{{ route('affiliate.chat.unread-count') }}')
        .then(res => res.json())
        .then(data => {
            if (data.unread > 0) {
                if (prevAffiliateUnreadCount !== null && data.unread > prevAffiliateUnreadCount) {
                    try {
                        const a = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                        a.volume = 0.4;
                        a.play().catch(() => {});
                    } catch(e) {}
                }
                prevAffiliateUnreadCount = data.unread;
                badge.textContent = data.unread;
                badge.style.display = 'flex';
            } else {
                prevAffiliateUnreadCount = 0;
                badge.style.display = 'none';
            }
        });
}

function markAffiliateAsRead() {
    prevAffiliateUnreadCount = 0;
    fetch('{{ route('affiliate.chat.mark-read') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }).then(() => {
        const badge = document.getElementById('affiliateUnreadBadge');
        if (badge) badge.style.display = 'none';
    });
}

function previewAffiliateImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('affiliateImagePreview').src = e.target.result;
            document.getElementById('affiliateImagePreviewContainer').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearAffiliateImagePreview() {
    const input = document.getElementById('affiliateChatImage');
    if (input) input.value = '';
    const container = document.getElementById('affiliateImagePreviewContainer');
    if (container) container.style.display = 'none';
}

// ============================================
// DRAGGABLE LOGIC
// ============================================
(function() {
    const fabContainer = document.querySelector('.chat-fab-container');
    if (fabContainer) {
        let isDraggingFab = false;
        let startX, startY, initialRight, initialBottom;
        fabContainer.addEventListener('mousedown', e => fabDragStart(e)); // Wrapping for safety
        fabContainer.addEventListener('touchstart', e => fabDragStart(e), { passive: false });

        function fabDragStart(e) {
            const touch = e.type === 'touchstart';
            if (touch && e.touches.length > 1) return;
            startX = touch ? e.touches[0].clientX : e.clientX;
            startY = touch ? e.touches[0].clientY : e.clientY;
            const style = window.getComputedStyle(fabContainer);
            initialRight = parseInt(style.right);
            initialBottom = parseInt(style.bottom);
            document.addEventListener(touch ? 'touchmove' : 'mousemove', fabDrag, touch ? { passive: false } : false);
            document.addEventListener(touch ? 'touchend' : 'mouseup', fabDragEnd);
            isDraggingFab = false;
        }
        function fabDrag(e) {
            const touch = e.type === 'touchmove';
            const dx = startX - (touch ? e.touches[0].clientX : e.clientX);
            const dy = startY - (touch ? e.touches[0].clientY : e.clientY);
            if (Math.abs(dx) > 5 || Math.abs(dy) > 5) isDraggingFab = true;
            if (isDraggingFab) {
                if (touch) e.preventDefault();
                let nR = initialRight + dx, nB = initialBottom + dy;
                fabContainer.style.right = Math.max(10, Math.min(nR, window.innerWidth - 70)) + 'px';
                fabContainer.style.bottom = Math.max(10, Math.min(nB, window.innerHeight - 150)) + 'px';
            }
        }
        function fabDragEnd() {
            document.removeEventListener('mousemove', fabDrag);
            document.removeEventListener('touchmove', fabDrag);
            document.removeEventListener('mouseup', fabDragEnd);
            document.removeEventListener('touchend', fabDragEnd);
        }
        fabContainer.querySelectorAll('.chat-fab').forEach(fab => {
            fab.addEventListener('click', e => { if (isDraggingFab) { e.stopImmediatePropagation(); e.preventDefault(); } }, true);
        });
    }

    // Draggable Widgets
    document.querySelectorAll('.chat-widget').forEach(widget => {
        const header = widget.querySelector('.chat-header');
        if (!header) return;
        let startX, startY, initialRight, initialBottom;
        header.style.cursor = 'move';
        header.addEventListener('mousedown', wDragStart);
        header.addEventListener('touchstart', wDragStart, { passive: false });
        function wDragStart(e) {
            const touch = e.type === 'touchstart';
            startX = touch ? e.touches[0].clientX : e.clientX;
            startY = touch ? e.touches[0].clientY : e.clientY;
            const style = window.getComputedStyle(widget);
            initialRight = parseInt(style.right);
            initialBottom = parseInt(style.bottom);
            document.addEventListener(touch ? 'touchmove' : 'mousemove', wDrag, touch ? { passive: false } : false);
            document.addEventListener(touch ? 'touchend' : 'mouseup', wDragEnd);
        }
        function wDrag(e) {
            const touch = e.type === 'touchmove';
            if (touch) e.preventDefault();
            const dx = startX - (touch ? e.touches[0].clientX : e.clientX);
            const dy = startY - (touch ? e.touches[0].clientY : e.clientY);
            widget.style.right = (initialRight + dx) + 'px';
            widget.style.bottom = (initialBottom + dy) + 'px';
            widget.style.left = 'auto';
        }
        function wDragEnd() {
            document.removeEventListener('mousemove', wDrag);
            document.removeEventListener('touchmove', wDrag);
        }
    });
})();

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    @if(Auth::guard('affiliate')->check() && Auth::guard('affiliate')->user()->status === 'approved')
        startAffiliatePolling();
        refreshAffiliateUnreadCount();
    @endif

    @auth
    @if(!Auth::guard('affiliate')->check())
        _startUserPolling();
        _refreshUserUnreadCount();
    @endif
    @endauth

    // Close on click outside
    document.addEventListener('click', function(event) {
        // Affiliate widget
        const affiliateWidget = document.getElementById('affiliateChatWidget');
        const affiliateBtn = document.getElementById('affiliateChatFab');
        if (affiliateChatOpen && affiliateWidget && !affiliateWidget.contains(event.target) && !affiliateBtn?.contains(event.target)) {
            closeAffiliateChat();
        }
        // User widget
        const userWidget = document.getElementById('userChatWidget');
        const userBtn = document.getElementById('userChatFab');
        if (userChatOpen && userWidget && !userWidget.contains(event.target) && !userBtn?.contains(event.target)) {
            closeUserChat();
        }
    });

    const widgets = document.querySelectorAll('.chat-widget');
    widgets.forEach(widget => {
        widget.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
});


// ============================================
// USER SUPPORT CHAT LOGIC
// ============================================
let userChatOpen = false;
let lastUserMessageId = 0;
let userPollingInterval = null;

function toggleUserChat() {
    const widget = document.getElementById('userChatWidget');
    if (!widget) return;

    if (userChatOpen) {
        closeUserChat();
    } else {
        // Close affiliate if open
        const affWidget = document.getElementById('affiliateChatWidget');
        if (affWidget && affiliateChatOpen) {
            affWidget.classList.remove('active');
            affiliateChatOpen = false;
        }
        widget.classList.add('active');
        userChatOpen = true;
        loadUserMessages();
        markUserAsRead();
        const input = document.getElementById('userChatInput');
        if (input) setTimeout(() => input.focus(), 300);
    }
}

function closeUserChat() {
    const widget = document.getElementById('userChatWidget');
    if (widget) {
        widget.classList.remove('active');
        userChatOpen = false;
    }
}

function loadUserMessages() {
    const body = document.getElementById('userChatBody');
    if (!body) return;

    fetch('{{ route('chat.messages') }}')
        .then(res => res.json())
        .then(data => {
            if (!Array.isArray(data) || data.length === 0) return;
            const welcome = document.getElementById('userChatWelcome');
            if (welcome) welcome.remove();
            body.innerHTML = '';
            data.forEach(msg => {
                _appendUserMsg(msg, false);
                lastUserMessageId = Math.max(lastUserMessageId, msg.id);
            });
            body.scrollTop = body.scrollHeight;
        })
        .catch(err => console.warn('Load user messages error:', err));
}

function sendUserMessage(event) {
    event.preventDefault();
    const input    = document.getElementById('userChatInput');
    const imgInput = document.getElementById('userChatImage');
    const sendBtn  = document.getElementById('userChatSendBtn');
    const msg = (input.value || '').trim();
    if (!msg && (!imgInput || !imgInput.files[0])) return;

    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    const formData = new FormData();
    if (msg) formData.append('message', msg);
    if (imgInput && imgInput.files[0]) formData.append('image', imgInput.files[0]);

    fetch('{{ route('chat.send') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    })
    .then(res => {
        if (!res.ok) throw new Error('Server error ' + res.status);
        return res.json();
    })
    .then(data => {
        if (data && data.id) {
            input.value = '';
            autoGrowTextarea(input);
            clearUserImagePreview();
            _appendUserMsg(data, false);
            lastUserMessageId = Math.max(lastUserMessageId, data.id);
        }
    })
    .catch(err => {
        console.error('Send user message error:', err);
    })
    .finally(() => {
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
        input.focus();
    });
}

function _appendUserMsg(msg, playSound) {
    const body = document.getElementById('userChatBody');
    if (!body) return;
    if (document.getElementById('user-msg-' + msg.id)) return;

    const welcome = document.getElementById('userChatWelcome');
    if (welcome) welcome.remove();

    if (playSound && msg.is_admin) {
        try {
            const a = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
            a.volume = 0.4;
            a.play().catch(() => {});
        } catch(e) {}
    }

    const div = document.createElement('div');
    div.id = 'user-msg-' + msg.id;
    div.className = `chat-message ${msg.is_admin ? 'admin' : 'user'}`;

    const date = new Date(msg.created_at);
    const time = date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });

    let content = '';
    if (msg.message) content += `<div class="message-content">${escapeHtml(msg.message)}</div>`;
    if (msg.image) {
        const src = msg.image.startsWith('http') ? msg.image : `{{ asset('') }}${msg.image}`;
        content += `<img src="${src}" class="message-image" onclick="window.open('${src}')" loading="lazy">`;
    }
    div.innerHTML = `<div class="message-bubble">${content}<div class="message-time">${time}</div></div>`;
    body.appendChild(div);
    body.scrollTop = body.scrollHeight;
}

function _startUserPolling() {
    userPollingInterval = setInterval(() => {
        if (userChatOpen) {
            _checkNewUserMessages();
        } else {
            _refreshUserUnreadCount();
        }
    }, 3500);
}

function _checkNewUserMessages() {
    fetch(`{{ route('chat.new') }}?last_id=${lastUserMessageId}`)
        .then(res => res.json())
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(msg => {
                    if (msg.id > lastUserMessageId) {
                        _appendUserMsg(msg, true);
                        lastUserMessageId = Math.max(lastUserMessageId, msg.id);
                    }
                });
                if (userChatOpen) markUserAsRead();
            }
        })
        .catch(() => {});
}

function _refreshUserUnreadCount() {
    const badge = document.getElementById('userUnreadBadge');
    if (!badge) return;
    fetch('{{ route('chat.unread-count') }}')
        .then(res => res.json())
        .then(data => {
            if (data && data.unread > 0) {
                if (prevUserUnreadCount !== null && data.unread > prevUserUnreadCount) {
                    try {
                        const a = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                        a.volume = 0.4;
                        a.play().catch(() => {});
                    } catch(e) {}
                }
                prevUserUnreadCount = data.unread;
                badge.textContent = data.unread;
                badge.style.display = 'flex';
            } else {
                prevUserUnreadCount = 0;
                badge.style.display = 'none';
            }
        })
        .catch(() => {});
}

function markUserAsRead() {
    const badge = document.getElementById('userUnreadBadge');
    if (badge) badge.style.display = 'none';
    prevUserUnreadCount = 0;
    fetch('{{ route('chat.mark-read') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }).catch(() => {});
}

function previewUserImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('userImagePreview').src = e.target.result;
            document.getElementById('userImagePreviewContainer').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearUserImagePreview() {
    const input = document.getElementById('userChatImage');
    if (input) input.value = '';
    const container = document.getElementById('userImagePreviewContainer');
    if (container) container.style.display = 'none';
}

// Textarea setup, auto-grow, and Enter submission
document.addEventListener('DOMContentLoaded', function() {
    const userInput = document.getElementById('userChatInput');
    if (userInput) {
        userInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('userChatForm')?.requestSubmit();
            }
        });
        userInput.addEventListener('input', function() {
            autoGrowTextarea(this);
        });
    }

    const affiliateInput = document.getElementById('affiliateChatInput');
    if (affiliateInput) {
        affiliateInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('affiliateChatForm')?.requestSubmit();
            }
        });
        affiliateInput.addEventListener('input', function() {
            autoGrowTextarea(this);
        });
    }
});
</script>
