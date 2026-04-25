@extends('layouts.app')

@section('title', 'AI Chatbot Support - DungThu')

@section('content')
    <div class="container-fluid py-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">💬 AI Customer Support Chatbot</h4>
                        <small>Tư vấn sản phẩm & dịch vụ 24/7</small>
                    </div>

                    <div class="card-body">
                        <div id="chatbox"
                            style="height: 500px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; background-color: #f9f9f9; border-radius: 8px; margin-bottom: 20px;">
                            <!-- Chat messages will be loaded here -->
                            <div class="text-center text-muted my-5">
                                <p>👋 Chào bạn! Mình là AI tư vấn viên của DungThu.</p>
                                <p>Bạn có thể hỏi tôi về:</p>
                                <ul class="list-unstyled">
                                    <li>💻 Digital Products</li>
                                    <li>📱 Social Media Services (TikTok, Facebook, Instagram)</li>
                                    <li>🎁 TikTok Deals</li>
                                    <li>🔄 Card Exchange</li>
                                </ul>
                                <p>Bắt đầu bằng cách nhập câu hỏi bên dưới! 😊</p>
                            </div>
                        </div>

                        <div class="input-group">
                            <input type="text" id="messageInput" class="form-control"
                                placeholder="Hỏi tôi gì đó... (VD: Có sản phẩm nào về WordPress?)" />
                            <button class="btn btn-primary" id="sendBtn" type="button">
                                <span id="sendBtnText">Gửi</span>
                                <span id="sendBtnSpinner" class="spinner-border spinner-border-sm ms-2"
                                    style="display: none;"></span>
                            </button>
                        </div>

                        <small class="text-muted d-block mt-3">
                            ⚠️ Chatbot có thể không chính xác 100%. Vui lòng liên hệ support nếu cần hỗ trợ:
                            <br>📧 tranthanhtuanfix@gmail.com | ☎️ 0772698113 | 💬 Zalo: 0708910952
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .message-bubble {
            padding: 12px 16px;
            margin-bottom: 12px;
            border-radius: 8px;
            word-wrap: break-word;
            max-width: 80%;
            animation: slideIn 0.3s ease;
            line-height: 1.6;
        }

        .message-bubble.user {
            background-color: #007bff;
            color: white;
            margin-left: auto;
            text-align: right;
        }

        .message-bubble.bot {
            background-color: #e9ecef;
            color: #333;
            margin-right: auto;
        }

        /* Markdown formatting styles */
        .message-bubble strong {
            font-weight: 600;
        }

        .message-bubble em {
            font-style: italic;
        }

        .message-bubble code {
            background-color: rgba(0, 0, 0, 0.08);
            padding: 2px 8px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            display: inline-block;
        }

        .message-bubble.user code {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .message-bubble ul,
        .message-bubble ol {
            margin: 8px 0;
            padding-left: 20px;
            text-align: left;
        }

        .message-bubble li {
            margin: 4px 0;
        }

        .message-bubble hr {
            border: none;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            margin: 8px 0;
        }

        .message-bubble.user hr {
            border-top-color: rgba(255, 255, 255, 0.2);
        }

        .message-bubble blockquote {
            border-left: 3px solid #007bff;
            padding-left: 12px;
            margin: 6px 0;
            opacity: 0.85;
            font-style: italic;
        }

        .message-bubble.user blockquote {
            border-left-color: rgba(255, 255, 255, 0.5);
        }

        .message-time {
            font-size: 0.85em;
            color: #999;
            margin-top: 3px;
        }

        .feedback-buttons {
            display: flex;
            gap: 8px;
            margin-top: 8px;
            font-size: 0.85em;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .feedback-buttons button {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 4px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85em;
            color: inherit;
            transition: all 0.2s;
        }

        .feedback-buttons button:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(0, 0, 0, 0.2);
        }

        .feedback-buttons button:active {
            transform: scale(0.95);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #chatbox {
            display: flex;
            flex-direction: column;
        }

        #chatbox .message-group {
            margin-bottom: 15px;
        }
    </style>

    <script>
        const API_ENDPOINT = '/api/chatbot';
        let sessionId = null;
        let messageCount = 0;

        // Initialize chat session
        async function initializeSession() {
            try {
                const response = await fetch(`${API_ENDPOINT}/session`, { method: 'POST' });
                const data = await response.json();
                sessionId = data.session_id;
                console.log('Session initialized:', sessionId);
                loadChatHistory();
            } catch (error) {
                console.error('Error initializing session:', error);
                showError('Failed to initialize chat session');
            }
        }

        // Load previous chat history
        async function loadChatHistory() {
            if (!sessionId) return;

            try {
                const response = await fetch(`${API_ENDPOINT}/history?session_id=${sessionId}`);
                const data = await response.json();

                if (data.success && data.messages.length > 0) {
                    const chatbox = document.getElementById('chatbox');
                    chatbox.innerHTML = ''; // Clear initial message

                    data.messages.forEach(msg => {
                        addMessage(msg.message, 'user', msg.created_at, msg.id);
                        addMessage(msg.response, 'bot', msg.created_at, msg.id);
                    });

                    scrollToBottom();
                }
            } catch (error) {
                console.error('Error loading history:', error);
            }
        }

        // Add message to chatbox
        function addMessage(text, sender, time = null, messageId = null) {
            const chatbox = document.getElementById('chatbox');

            // If this is the first message, clear default content
            if (chatbox.children.length === 0 || chatbox.querySelector('.text-center')) {
                chatbox.innerHTML = '';
            }

            const messageGroup = document.createElement('div');
            messageGroup.className = 'message-group';
            messageGroup.dataset.messageId = messageId;

            const messageBubble = document.createElement('div');
            messageBubble.className = `message-bubble ${sender}`;

            // For bot messages, format with markdown
            if (sender === 'bot') {
                messageBubble.innerHTML = formatBotMessage(text);
            } else {
                messageBubble.textContent = text;
            }

            const timeEl = document.createElement('div');
            timeEl.className = 'message-time';
            if (time) {
                timeEl.textContent = time;
                messageBubble.appendChild(timeEl);
            }

            // Add feedback buttons for bot messages
            if (sender === 'bot' && messageId) {
                const feedbackDiv = document.createElement('div');
                feedbackDiv.className = 'feedback-buttons mt-2';
                feedbackDiv.innerHTML = `
                        <button class="feedback-btn-like" data-message-id="${messageId}">👍 Hữu ích</button>
                        <button class="feedback-btn-dislike" data-message-id="${messageId}">👎 Không</button>
                    `;
                messageBubble.appendChild(feedbackDiv);

                // Add event listeners
                const likeBtn = feedbackDiv.querySelector('.feedback-btn-like');
                const dislikeBtn = feedbackDiv.querySelector('.feedback-btn-dislike');

                if (likeBtn) {
                    likeBtn.addEventListener('click', () => submitFeedback(messageId, true));
                }
                if (dislikeBtn) {
                    dislikeBtn.addEventListener('click', () => submitFeedback(messageId, false));
                }
            }

            messageGroup.appendChild(messageBubble);
            chatbox.appendChild(messageGroup);
            scrollToBottom();
        }

        // Format bot message with markdown support
        function formatBotMessage(text) {
            // Escape HTML first
            let html = document.createElement('div');
            html.textContent = text;
            html = html.innerHTML;

            // Convert markdown-style formatting
            // Bold: **text** -> <strong>text</strong>
            html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

            // Italic: *text* -> <em>text</em>
            html = html.replace(/\*(.*?)\*/g, '<em>$1</em>');

            // Code: `text` -> <code>text</code>
            html = html.replace(/`(.*?)`/g, '<code>$1</code>');

            // Horizontal lines: --- -> <hr>
            html = html.replace(/^---+$/gm, '<hr>');

            // Process line breaks and lists
            const lines = html.split('\n');
            let processed = [];
            let inList = false;

            lines.forEach(line => {
                // List items: • item
                if (line.trim().startsWith('•')) {
                    if (!inList) {
                        processed.push('<ul>');
                        inList = true;
                    }
                    processed.push('<li>' + line.trim().substring(1).trim() + '</li>');
                }
                // Numbered lists: 1. item, 2. item
                else if (/^\d+\.\s/.test(line.trim())) {
                    if (inList && !processed[processed.length - 1].includes('<ol>')) {
                        processed.pop(); // Remove <ul>
                        processed.push('<ol>');
                    } else if (!inList) {
                        processed.push('<ol>');
                        inList = true;
                    }
                    processed.push('<li>' + line.trim().replace(/^\d+\.\s/, '') + '</li>');
                }
                else {
                    if (inList) {
                        processed.push(processed[processed.length - 1].includes('<ol>') ? '</ol>' : '</ul>');
                        inList = false;
                    }
                    if (line.trim() !== '') {
                        processed.push('<p>' + line + '</p>');
                    }
                }
            });

            if (inList) {
                processed.push(processed[processed.length - 1].includes('<ol>') ? '</ol>' : '</ul>');
            }

            return processed.join('');
        }

        // Send message to chatbot
        async function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();

            if (!message || !sessionId) return;

            // Clear input
            input.value = '';

            // Add user message to chatbox
            addMessage(message, 'user');

            // Show loading state
            const sendBtn = document.getElementById('sendBtn');
            const sendBtnText = document.getElementById('sendBtnText');
            const sendBtnSpinner = document.getElementById('sendBtnSpinner');

            sendBtn.disabled = true;
            sendBtnText.style.display = 'none';
            sendBtnSpinner.style.display = 'inline-block';

            try {
                const response = await fetch(`${API_ENDPOINT}/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        message: message,
                        session_id: sessionId,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    // Add bot response
                    const now = new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
                    // Note: We get messageId from the last added message to database
                    addMessage(data.message, 'bot', now);
                    messageCount++;
                } else {
                    addMessage(data.message || 'Xin lỗi, có lỗi xảy ra!', 'bot');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                addMessage('❌ Lỗi kết nối. Vui lòng thử lại!', 'bot');
            } finally {
                sendBtn.disabled = false;
                sendBtnText.style.display = 'inline';
                sendBtnSpinner.style.display = 'none';
                document.getElementById('messageInput').focus();
            }
        }

        // Submit feedback
        async function submitFeedback(messageId, isHelpful) {
            try {
                const response = await fetch(`${API_ENDPOINT}/feedback`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        message_id: messageId,
                        is_helpful: isHelpful,
                    }),
                });

                if (response.ok) {
                    alert('Cảm ơn feedback của bạn! 🙏');
                }
            } catch (error) {
                console.error('Error submitting feedback:', error);
            }
        }

        // Utility functions
        function scrollToBottom() {
            const chatbox = document.getElementById('chatbox');
            chatbox.scrollTop = chatbox.scrollHeight;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function showError(message) {
            const chatbox = document.getElementById('chatbox');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger';
            errorDiv.textContent = message;
            chatbox.appendChild(errorDiv);
        }

        // Event listeners
        document.getElementById('sendBtn').addEventListener('click', sendMessage);
        document.getElementById('messageInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', initializeSession);
    </script>
@endsection