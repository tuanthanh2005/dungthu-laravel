<!--
FLOATING CHATBOT WIDGET
Include this in your layout.blade.php or any page to add floating chatbot
Usage: @include('chatbot.widget')
-->

<div id="chatbot-widget" class="chatbot-widget">
    <!-- Toggle Button -->
    <button id="chatbot-toggle" class="chatbot-toggle" title="Open Chatbot">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
    </button>

    <!-- Chat Window -->
    <div id="chatbot-window" class="chatbot-window hidden">
        <div class="chatbot-header">
            <h5>💬 DungThu AI Support</h5>
            <button id="chatbot-close" class="chatbot-close">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div id="chatbot-messages" class="chatbot-messages">
            <div class="chatbot-welcome">
                <p>👋 Chào bạn!</p>
                <p>Mình có thể giúp bạn tìm sản phẩm hoặc tư vấn dịch vụ.</p>
                <p style="font-size: 0.85em; color: #999;">Nhập câu hỏi bên dưới nha!</p>
            </div>
        </div>

        <div class="chatbot-input-area">
            <input type="text" id="chatbot-msg-input" class="chatbot-input" placeholder="Hỏi gì đó...">
            <button id="chatbot-send-btn" class="chatbot-send-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M16.6915026,12.4744748 L3.50612381,13.2599618 C3.19218622,13.2599618 3.03521743,13.4170592 3.03521743,13.5741566 L1.15159189,20.0151496 C0.8376543,20.8006365 0.99,21.89 1.77946707,22.52 C2.41,22.99 3.50612381,23.1 4.13399899,22.8429026 L21.714504,14.0454487 C22.6563168,13.5741566 23.1272231,12.6315722 22.9702544,11.6889879 C22.9702544,11.6889879 22.9702544,11.5318905 22.9702544,11.4748031 L4.13399899,1.16346278 C3.34915502,0.9 2.40734225,1.00636533 1.77946707,1.4776575 C0.994623095,2.10604706 0.837654326,3.0486314 1.15159189,3.98721575 L3.03521743,10.4282088 C3.03521743,10.5853061 3.34915502,10.7424035 3.50612381,10.7424035 L16.6915026,11.5318905 C16.6915026,11.5318905 17.1624089,11.5318905 17.1624089,11.0606983 L17.1624089,12.4744748 C17.1624089,12.4744748 17.1624089,12.4744748 16.6915026,12.4744748 Z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<style>
    /* Chatbot Widget Styles */
    :root {
        --chatbot-primary: #007bff;
        --chatbot-secondary: #e9ecef;
        --chatbot-text: #333;
        --chatbot-text-light: #666;
        --chatbot-border: #ddd;
    }

    .chatbot-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        z-index: 9999;
    }

    .chatbot-toggle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background-color: var(--chatbot-primary);
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        transition: all 0.3s ease;
    }

    .chatbot-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(0, 123, 255, 0.4);
    }

    .chatbot-toggle:active {
        transform: scale(0.95);
    }

    .chatbot-toggle.hidden {
        display: none;
    }

    .chatbot-window {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 380px;
        height: 500px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 40px rgba(0, 0, 0, 0.16);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        animation: slideUp 0.3s ease;
    }

    .chatbot-window.hidden {
        display: none;
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

    .chatbot-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chatbot-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .chatbot-close {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.2s;
    }

    .chatbot-close:hover {
        opacity: 0.8;
    }

    .chatbot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        background-color: #f9f9f9;
    }

    .chatbot-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chatbot-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .chatbot-messages::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .chatbot-messages::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .chatbot-welcome {
        text-align: center;
        color: var(--chatbot-text-light);
        padding: 20px 10px;
    }

    .chatbot-welcome p {
        margin: 8px 0;
        font-size: 14px;
        line-height: 1.4;
    }

    .chatbot-message {
        display: flex;
        margin-bottom: 12px;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .chatbot-message.user {
        justify-content: flex-end;
    }

    .chatbot-message.bot {
        justify-content: flex-start;
    }

    .chatbot-message-content {
        background-color: var(--chatbot-secondary);
        color: var(--chatbot-text);
        padding: 10px 14px;
        border-radius: 8px;
        word-wrap: break-word;
        max-width: 70%;
        font-size: 13px;
        line-height: 1.6;
    }

    .chatbot-message.user .chatbot-message-content {
        background-color: var(--chatbot-primary);
        color: white;
    }

    /* Markdown-style formatting */
    .chatbot-message-content strong {
        font-weight: 600;
        color: inherit;
    }

    .chatbot-message-content em {
        font-style: italic;
    }

    .chatbot-message-content code {
        background-color: rgba(0, 0, 0, 0.1);
        padding: 2px 6px;
        border-radius: 3px;
        font-family: 'Courier New', monospace;
        font-size: 0.9em;
    }

    .chatbot-message.user .chatbot-message-content code {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .chatbot-message-content ul,
    .chatbot-message-content ol {
        margin: 6px 0;
        padding-left: 20px;
    }

    .chatbot-message-content li {
        margin: 3px 0;
    }

    .chatbot-message-content hr {
        border: none;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        margin: 8px 0;
    }

    .chatbot-message.user .chatbot-message-content hr {
        border-top-color: rgba(255, 255, 255, 0.2);
    }

    .chatbot-message-content blockquote {
        border-left: 3px solid var(--chatbot-primary);
        padding-left: 10px;
        margin-left: 0;
        margin: 6px 0;
        opacity: 0.8;
    }

    .chatbot-input-area {
        display: flex;
        padding: 12px;
        border-top: 1px solid var(--chatbot-border);
        background: white;
    }

    .chatbot-input {
        flex: 1;
        border: 1px solid var(--chatbot-border);
        border-radius: 20px;
        padding: 10px 14px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s;
    }

    .chatbot-input:focus {
        border-color: var(--chatbot-primary);
    }

    .chatbot-send-btn {
        background-color: var(--chatbot-primary);
        color: white;
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        margin-left: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s;
    }

    .chatbot-send-btn:hover {
        background-color: #0056b3;
    }

    .chatbot-send-btn:active {
        transform: scale(0.95);
    }

    /* Responsive */
    @media (max-width: 480px) {
        .chatbot-window {
            width: 100%;
            height: 100%;
            bottom: 0;
            right: 0;
            border-radius: 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('chatbot-toggle');
        const closeBtn = document.getElementById('chatbot-close');
        const chatWindow = document.getElementById('chatbot-window');
        const sendBtn = document.getElementById('chatbot-send-btn');
        const input = document.getElementById('chatbot-msg-input');
        const messagesDiv = document.getElementById('chatbot-messages');

        let sessionId = null;

        // Initialize session
        async function initSession() {
            try {
                const response = await fetch('/api/chatbot/session', { method: 'POST' });
                const data = await response.json();
                sessionId = data.session_id;
            } catch (error) {
                console.error('Error initializing session:', error);
            }
        }

        // Send message
        async function sendMessage() {
            const message = input.value.trim();
            if (!message || !sessionId) return;

            // Clear input
            input.value = '';

            // Add user message
            addMessageToWidget(message, 'user');

            try {
                const response = await fetch('/api/chatbot/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        message: message,
                        session_id: sessionId,
                    }),
                });

                const data = await response.json();
                if (data.success) {
                    addMessageToWidget(data.message, 'bot');
                } else {
                    addMessageToWidget('❌ Có lỗi xảy ra. Vui lòng thử lại!', 'bot');
                }
            } catch (error) {
                console.error('Error:', error);
                addMessageToWidget('❌ Lỗi kết nối. Vui lòng thử lại!', 'bot');
            }
        }

        function addMessageToWidget(text, sender) {
            const messageEl = document.createElement('div');
            messageEl.className = `chatbot-message ${sender}`;

            const contentEl = document.createElement('div');
            contentEl.className = 'chatbot-message-content';
            
            // Parse and format markdown/text
            if (sender === 'bot') {
                contentEl.innerHTML = formatBotResponse(text);
            } else {
                contentEl.textContent = text;
            }

            messageEl.appendChild(contentEl);
            messagesDiv.appendChild(messageEl);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function formatBotResponse(text) {
            // Escape HTML
            let html = text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');

            // Convert markdown-style formatting
            // Bold: **text** -> <strong>text</strong>
            html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            
            // Italic: *text* or _text_ -> <em>text</em>
            html = html.replace(/\*(.*?)\*/g, '<em>$1</em>');
            html = html.replace(/_(.*?)_/g, '<em>$1</em>');
            
            // Code: `text` -> <code>text</code>
            html = html.replace(/`(.*?)`/g, '<code>$1</code>');
            
            // Horizontal lines: --- -> <hr>
            html = html.replace(/^---+$/gm, '<hr>');
            
            // Line breaks
            html = html.replace(/\n\n+/g, '</p><p>');
            html = '<p>' + html + '</p>';
            
            // Lists: • item -> <li>
            html = html.replace(/^• (.*?)$/gm, (match) => {
                if (!html.includes('<ul>')) {
                    return '<ul><li>' + match.substring(2) + '</li>';
                }
                return '<li>' + match.substring(2) + '</li>';
            });
            html = html.replace(/((<li>.*?<\/li>)+)/s, '<ul>$1</ul>');
            
            // Remove extra <p> tags around lists
            html = html.replace(/<p>(<ul>.*?<\/ul>)<\/p>/s, '$1');
            
            return html;
        }

        // Event listeners
        toggleBtn.addEventListener('click', () => {
            chatWindow.classList.toggle('hidden');
            if (!chatWindow.classList.contains('hidden') && !sessionId) {
                initSession();
            }
        });

        closeBtn.addEventListener('click', () => chatWindow.classList.add('hidden'));

        sendBtn.addEventListener('click', sendMessage);

        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });

        // Initialize on widget open
        initSession();
    });
</script>
