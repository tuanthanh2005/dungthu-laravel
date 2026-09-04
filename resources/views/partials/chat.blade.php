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

/* Hide pill button & popover when chat widget is active */
.chat-widget.active ~ .contact-us-pill-wrapper,
.chat-widget.active ~ .support-menu-popover {
    opacity: 0 !important;
    pointer-events: none !important;
    transform: translateY(15px) !important;
}

/* ============================================
   CONTACT US PILL BUTTON & POPOVER (NEW DESIGN)
   ============================================ */
.contact-us-pill-wrapper {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 100000;
    transition: all 0.3s ease;
}

.contact-us-pill-btn {
    display: inline-flex;
    align-items: center;
    padding: 5px 18px 5px 5px;
    background: #ffffff;
    border-radius: 50px;
    position: relative;
    cursor: pointer;
    text-decoration: none !important;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    transition: all 0.45s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2.5px solid transparent;
    background-image: linear-gradient(#ffffff, #ffffff), linear-gradient(135deg, #ff7e5f 0%, #feb47b 30%, #4682b4 70%, #00d2ff 100%);
    background-origin: border-box;
    background-clip: padding-box, border-box;
    user-select: none;
    outline: none;
}

.contact-us-pill-btn .pill-icon-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #2d2926;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 18px;
    flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(0,0,0,0.25);
}

.contact-us-pill-btn .pill-text {
    font-size: 14.5px;
    font-weight: 700;
    color: #1f2937;
    margin-left: 10px;
    white-space: nowrap;
    letter-spacing: -0.2px;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    max-width: 160px;
    opacity: 1;
    overflow: hidden;
    display: inline-block;
    vertical-align: middle;
    transition: max-width 0.45s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.35s ease, margin 0.35s ease;
}

/* 5s Auto Collapse State */
.contact-us-pill-btn.is-collapsed {
    padding-right: 5px !important;
}

.contact-us-pill-btn.is-collapsed .pill-text {
    max-width: 0 !important;
    opacity: 0 !important;
    margin-left: 0 !important;
}

/* Hover: Always expand full text */
.contact-us-pill-btn:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
    padding-right: 18px !important;
}

.contact-us-pill-btn:hover .pill-text {
    max-width: 160px !important;
    opacity: 1 !important;
    margin-left: 10px !important;
}

.contact-us-pill-btn .pill-red-dot {
    position: absolute;
    top: 2px;
    right: 4px;
    width: 14px;
    height: 14px;
    background-color: #ff0000;
    border-radius: 50%;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 6px rgba(255, 0, 0, 0.5);
    animation: pillDotPulse 2s infinite ease-in-out;
}

@keyframes pillDotPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.25); }
}

/* POPOVER MENU */
.support-menu-popover {
    position: fixed;
    bottom: 80px;
    right: 24px;
    width: 290px;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(0, 0, 0, 0.08);
    z-index: 100001;
    display: none;
    overflow: hidden;
    animation: supportPopoverAnim 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    transition: opacity 0.3s ease, transform 0.3s ease;
}

@keyframes supportPopoverAnim {
    from { opacity: 0; transform: translateY(12px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.support-menu-popover.active {
    display: block;
}

.support-popover-header {
    padding: 12px 16px;
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.support-popover-body {
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.support-popover-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 14px;
    text-decoration: none !important;
    color: #1e293b;
    transition: all 0.2s ease;
    background: #ffffff;
    cursor: pointer;
}

.support-popover-item:hover {
    background: #f1f5f9;
    transform: translateX(-2px);
}

.popover-item-icon {
    width: 36px;
    height: 36px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

.popover-item-info {
    flex: 1;
    min-width: 0;
}

.popover-item-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.2;
}

.popover-item-sub {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.popover-arrow {
    font-size: 11px;
    color: #cbd5e1;
    transition: transform 0.2s ease;
}

.support-popover-item:hover .popover-arrow {
    color: #475569;
    transform: translateX(2px);
}

@media (max-width: 991px) {
    .contact-us-pill-wrapper {
        bottom: 72px;
        right: 12px;
    }
    .support-menu-popover {
        bottom: 124px;
        right: 12px;
        width: 275px;
    }
}

@media (max-width: 576px) {
    .contact-us-pill-wrapper {
        bottom: 70px;
        right: 10px;
    }
    .support-menu-popover {
        bottom: 120px;
        right: 10px;
        width: 270px;
    }
    .contact-us-pill-btn {
        padding: 4px 14px 4px 4px;
    }
    .contact-us-pill-btn .pill-icon-circle {
        width: 32px;
        height: 32px;
        font-size: 15px;
    }
    .contact-us-pill-btn .pill-text {
        font-size: 12.5px;
        margin-left: 8px;
    }
    .contact-us-pill-btn .pill-red-dot {
        top: 1px;
        right: 3px;
        width: 12px;
        height: 12px;
    }
}
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

@keyframes giftBoxWiggleContinuous {
    0%, 100% { transform: rotate(0deg) scale(1); }
    10% { transform: rotate(14deg) scale(1.08); }
    20% { transform: rotate(-16deg) scale(1.08); }
    30% { transform: rotate(14deg) scale(1.08); }
    40% { transform: rotate(-12deg) scale(1.08); }
    50% { transform: rotate(8deg) scale(1.05); }
    60% { transform: rotate(-5deg) scale(1.02); }
    70% { transform: rotate(0deg) scale(1); }
}

.gift-box-fab.is-wiggling {
    animation: giftBoxWiggleContinuous 1.8s infinite ease-in-out;
    box-shadow: 0 0 18px rgba(255, 65, 108, 0.7) !important;
}

.gift-box-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid #ffffff;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
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
    white-space: pre-wrap;
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
    padding: 0;
    width: 44px;
    min-width: 44px;
    height: 44px;
    border-radius: 50%;
    border: none;
    background: transparent;
    color: var(--widget-color, #6366f1);
    font-size: 21px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: none;
    flex-shrink: 0;
}

.chat-send-btn:hover:not(:disabled) {
    transform: scale(1.08);
    background: rgba(99, 102, 241, 0.1);
    box-shadow: none;
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
        bottom: 55px;
        right: 8px;
        gap: 6px;
    }

    .chat-fab {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }

    .chat-fab.gift-box-fab i {
        font-size: 16px !important;
    }

    .gift-box-badge {
        width: 16px;
        height: 16px;
        font-size: 9px;
        top: -3px;
        right: -3px;
        border-width: 1px;
    }

    .chat-fab .unread-badge {
        min-width: 18px;
        height: 18px;
        font-size: 9px;
        top: -3px;
        right: -3px;
        border-width: 1.5px;
        padding: 0 4px;
    }

    /* Scaling Zalo custom icon elements for mobile */
    .chat-fab .position-relative {
        width: 24px !important;
        height: 24px !important;
    }

    .chat-fab .position-relative i.fa-comment {
        font-size: 22px !important;
    }

    .chat-fab .position-relative span {
        font-size: 11px !important;
    }

    .chat-fab .fab-tooltip {
        display: none;
    }

    /* Toggle Button for Mobile Collapsible Menu */
    .chat-fab-toggle-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(4px);
        color: #ffffff;
        border: 1.5px solid rgba(255, 255, 255, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.25);
        transition: all 0.25s ease;
        margin-bottom: 2px;
        align-self: flex-end;
    }

    .chat-fab-toggle-btn:hover, .chat-fab-toggle-btn:active {
        background: rgba(15, 23, 42, 0.95);
        transform: scale(1.1);
    }

    /* When Collapsed on Mobile: hide secondary icons, show main chat button + toggle */
    .chat-fab-container.is-collapsed .chat-fab:not(.user-support):not(.admin-chat) {
        display: none !important;
    }

    .chat-fab-container.is-collapsed #chatFabToggleIcon {
        transform: rotate(180deg);
    }

    .chat-widget {
        bottom: 110px; 
        right: 8px;
        left: auto;
        width: 300px; 
        height: 480px; 
        max-height: calc(100vh - 160px);
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
        padding: 0 14px;
        height: 40px;
        font-size: 13px;
        border-radius: 20px;
    }
}

@media (max-width: 480px) {
    .chat-widget {
        bottom: 110px;
        right: 8px;
        left: 8px;
        width: auto;
        height: 420px;
        border-radius: 16px;
    }

    .chat-fab-container {
        bottom: 50px;
        right: 8px;
    }

    .chat-footer {
        padding: 10px 8px;
    }

    .chat-input-wrapper {
        gap: 6px;
        width: 100%;
        min-width: 0;
    }

    .chat-tool-btn {
        width: 36px;
        height: 36px;
        min-width: 36px;
    }

    .chat-tool-btn i {
        font-size: 15px;
    }

    .chat-input-container {
        min-width: 0;
    }

    .chat-input {
        width: 100%;
        min-width: 0;
        height: 40px;
        padding: 9px 12px;
    }

    .chat-send-btn {
        width: 40px;
        min-width: 40px;
        height: 40px;
        padding: 0;
        flex-shrink: 0;
    }
}

/* Chat Tools & Image Preview */
.chat-tool-btn {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--widget-color, #6366f1);
    background: #f3f4f6;
    border: 1.5px dashed var(--widget-color, #6366f1);
    cursor: pointer;
    transition: all 0.25s ease;
    border-radius: 50%;
    flex-shrink: 0;
}
.chat-tool-btn i {
    font-size: 18px;
}
.chat-tool-btn:hover {
    background: var(--widget-color, #6366f1);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    border-style: solid;
}

/* Emoji Picker CSS */
.emoji-picker-container {
    position: relative;
    display: inline-block;
}
.emoji-popover {
    position: absolute;
    bottom: 56px;
    left: -10px;
    width: 280px;
    height: 220px;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0,0,0,0.08);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    border-radius: 16px;
    display: none;
    z-index: 100000;
    flex-direction: column;
    overflow: hidden;
    animation: popEmoji 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes popEmoji {
    from { opacity: 0; transform: translateY(10px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
.emoji-popover-header {
    padding: 8px 12px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    background: #f8fafc;
}
.emoji-list {
    flex: 1;
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
    padding: 8px;
    overflow-y: auto;
}
.emoji-item {
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.15s ease;
    user-select: none;
    width: 32px;
    height: 32px;
}
.emoji-item:hover {
    background: #f1f5f9;
    transform: scale(1.15);
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
                <h3>{{ __('Chat với Admin') }}</h3>
                <p class="chat-status-indicator">
                    <span class="chat-status-dot"></span>
                    {{ __('Hỗ trợ CTV') }}
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
            <h4>{{ __('Chào') }} {{ Auth::guard('affiliate')->user()->name }}! 👋</h4>
            <p>{{ __('Admin sẽ phản hồi ngay khi có thể') }}<br>{{ __('Vui lòng để lại tin nhắn hoặc ảnh bill') }}</p>
        </div>
    </div>

    <div class="chat-footer">
        <form id="affiliateChatForm" onsubmit="sendAffiliateMessage(event)">
            <div class="chat-input-wrapper">
                <label for="affiliateChatImage" class="chat-tool-btn">
                    <i class="fas fa-image"></i>
                    <input type="file" id="affiliateChatImage" hidden accept="image/*" onchange="previewAffiliateImage(this)">
                </label>
                <div class="emoji-picker-container">
                    <button type="button" class="chat-tool-btn" id="affiliateEmojiToggleBtn" title="{{ __('Chọn emoji') }}">
                        <i class="far fa-smile" style="font-size: 18px;"></i>
                    </button>
                    <div class="emoji-popover" id="affiliateEmojiPopover">
                        <div class="emoji-popover-header">
                            <span>{{ __('Biểu tượng cảm xúc') }}</span>
                        </div>
                        <div class="emoji-list" id="affiliateEmojiList"></div>
                    </div>
                </div>
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
                        placeholder="{{ __('Nhập tin nhắn...') }}"
                        autocomplete="off"
                        maxlength="1000"
                        rows="1"
                        style="resize: none; overflow-y: hidden;"
                    ></textarea>
                </div>
                <button class="chat-send-btn" type="submit" id="affiliateChatSendBtn" aria-label="{{ __('Gửi') }}" title="{{ __('Gửi') }}">
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
                <h3>{{ __('Admin Hỗ trợ') }}</h3>
                <p class="chat-status-indicator">
                    <span class="chat-status-dot"></span>
                    {{ __('Admin sẽ rep tin của bạn sớm nhất') }}
                </p>
            </div>
        </div>
        <button class="chat-close-btn" onclick="closeUserChat()" aria-label="{{ __('Đóng') }}">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="chat-body" id="userChatBody">
        <div class="chat-welcome" id="userChatWelcome">
            <i class="fas fa-comments"></i>
            <h4>{{ __('Xin chào') }}, {{ Auth::user()->name ?? __('bạn') }}! 👋</h4>
            <p>{{ __('Chúng tôi luôn ở đây để hỗ trợ bạn.') }}<br>{{ __('Hãy gửi tin nhắn bên dưới nhé!') }}</p>
        </div>
    </div>

    <div class="chat-footer">
        <form id="userChatForm" onsubmit="sendUserMessage(event)" autocomplete="off">
            <div class="chat-input-wrapper">
                <label for="userChatImage" class="chat-tool-btn" title="{{ __('Gửi ảnh') }}">
                    <i class="fas fa-image"></i>
                    <input type="file" id="userChatImage" hidden accept="image/*" onchange="previewUserImage(this)">
                </label>
                <div class="emoji-picker-container">
                    <button type="button" class="chat-tool-btn" id="userEmojiToggleBtn" title="{{ __('Chọn emoji') }}">
                        <i class="far fa-smile" style="font-size: 18px;"></i>
                    </button>
                    <div class="emoji-popover" id="userEmojiPopover">
                        <div class="emoji-popover-header">
                            <span>{{ __('Biểu tượng cảm xúc') }}</span>
                        </div>
                        <div class="emoji-list" id="userEmojiList"></div>
                    </div>
                </div>
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
                        placeholder="{{ __('Nhập tin nhắn...') }}"
                        autocomplete="off"
                        maxlength="1000"
                        rows="1"
                        style="resize: none; overflow-y: hidden;"
                    ></textarea>
                </div>
                <button class="chat-send-btn" type="submit" id="userChatSendBtn" aria-label="{{ __('Gửi') }}" title="{{ __('Gửi') }}">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endauth


@php
    $userVouchers = collect();
    $userVoucherCount = 0;
    if (Auth::check()) {
        $userVouchers = \App\Models\Coupon::where('user_id', Auth::id())
            ->where('is_used', false)
            ->latest()
            ->get();
        $userVoucherCount = $userVouchers->count();
    }
@endphp

<!-- Support Menu Popover (Xuất hiện khi click "Liên hệ chúng tôi") -->
<div class="support-menu-popover" id="supportMenuPopover">
    <div class="support-popover-header">
        <div class="fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 13.5px;">
            <i class="fas fa-headset text-danger"></i> {{ __('Liên Hệ & Hỗ Trợ') }}
        </div>
        <button type="button" class="btn-close btn-sm ms-auto" onclick="closeSupportMenuPopover()" aria-label="Close"></button>
    </div>
    <div class="support-popover-body">
        {{-- User Chat Hỗ Trợ --}}
        <a href="javascript:void(0)" onclick="openUserChatFromPopover(event)" class="support-popover-item">
            <div class="popover-item-icon bg-primary text-white">
                <i class="fas fa-comments"></i>
            </div>
            <div class="popover-item-info">
                <div class="popover-item-title">{{ __('Chat Hỗ Trợ Admin 24/7') }}</div>
                <div class="popover-item-sub">{{ __('Chat trực tiếp hệ thống') }}</div>
            </div>
            <i class="fas fa-chevron-right popover-arrow"></i>
        </a>

        {{-- Affiliate Chat --}}
        @if(Auth::guard('affiliate')->check() && Auth::guard('affiliate')->user()->status === 'approved')
        <a href="javascript:void(0)" onclick="openAffiliateChatFromPopover(event)" class="support-popover-item">
            <div class="popover-item-icon bg-danger text-white">
                <i class="fas fa-headset"></i>
            </div>
            <div class="popover-item-info">
                <div class="popover-item-title">{{ __('Chat Hỗ Trợ CTV') }}</div>
                <div class="popover-item-sub">{{ __('Trao đổi với Admin') }}</div>
            </div>
            <i class="fas fa-chevron-right popover-arrow"></i>
        </a>
        @endif

        {{-- Zalo Admin --}}
        <a href="{{ \App\Helpers\SupportHelper::getZaloLink() }}" target="_blank" onclick="closeSupportMenuPopover()" class="support-popover-item">
            <div class="popover-item-icon" style="background: #0068ff; color: #fff;">
                <i class="fas fa-comment-dots"></i>
            </div>
            <div class="popover-item-info">
                <div class="popover-item-title">Zalo Admin</div>
                <div class="popover-item-sub">{{ __('Cấp tài khoản & hỗ trợ nhanh') }}</div>
            </div>
            <i class="fas fa-chevron-right popover-arrow"></i>
        </a>

        {{-- Telegram Admin --}}
        <a href="{{ \App\Helpers\SupportHelper::getTelegramLink() }}" target="_blank" onclick="closeSupportMenuPopover()" class="support-popover-item">
            <div class="popover-item-icon" style="background: #0088cc; color: #fff;">
                <i class="fab fa-telegram-plane"></i>
            </div>
            <div class="popover-item-info">
                <div class="popover-item-title">Telegram Admin</div>
                <div class="popover-item-sub">{{ __('Hỗ trợ Telegram 24/7') }}</div>
            </div>
            <i class="fas fa-chevron-right popover-arrow"></i>
        </a>

        {{-- Kho Voucher --}}
        @auth
        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#userVouchersModal" onclick="closeSupportMenuPopover()" class="support-popover-item">
            <div class="popover-item-icon" style="background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); color: #fff;">
                <i class="fas fa-gift"></i>
            </div>
            <div class="popover-item-info">
                <div class="popover-item-title d-flex align-items-center gap-1.5">
                    {{ __('Kho Voucher Khuyến Mãi') }}
                    @if($userVoucherCount > 0)
                        <span class="badge bg-danger rounded-pill" style="font-size: 10px;">{{ $userVoucherCount }}</span>
                    @endif
                </div>
                <div class="popover-item-sub">{{ __('Xem mã giảm giá của bạn') }}</div>
            </div>
            <i class="fas fa-chevron-right popover-arrow"></i>
        </a>
        @endauth
    </div>
</div>

<!-- Contact Us Pill Floating Button (Theo đúng thiết kế nút Bo Tròn Gradient + Red Dot) -->
<div class="contact-us-pill-wrapper">
    <button type="button" class="contact-us-pill-btn" onclick="toggleSupportMenuPopover(event)" aria-label="{{ __('Liên hệ chúng tôi') }}">
        <div class="pill-icon-circle">
            <i class="fas fa-comment-dots"></i>
        </div>
        <span class="pill-text">{{ __('Liên hệ chúng tôi') }}</span>
        <span class="pill-red-dot"></span>
    </button>
</div>

<!-- Modal Kho Voucher Của Bạn -->
<div class="modal fade" id="userVouchersModal" tabindex="-1" aria-labelledby="userVouchersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: #ffffff;">
            
            {{-- Header --}}
            <div class="modal-header border-0 text-white px-4 py-3 position-relative d-flex align-items-center justify-content-between" 
                 style="background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);">
                <div>
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2 mb-1" id="userVouchersModalLabel" style="font-size: 17px;">
                        <i class="fas fa-gift text-warning"></i>
                        {{ __('KHO VOUCHER CỦA BẠN') }}
                    </h5>
                    <div class="d-flex align-items-center gap-1.5 text-white-50" style="font-size: 11.5px;">
                        <span class="text-white fw-medium">{{ __('Danh sách mã giảm giá ưu đãi dành riêng cho bạn') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.9; filter: invert(1) brightness(2);"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-3.5 p-sm-4" style="background-color: #f8fafc; max-height: 440px; overflow-y: auto;">
                @auth
                    @forelse($userVouchers as $v)
                        <div class="p-3 bg-white rounded-3 shadow-sm border border-danger border-opacity-25 mb-2.5 d-flex align-items-center justify-content-between gap-2" style="border-left: 4.5px solid #ff416c !important;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; background: #fff1f2; color: #ff416c;">
                                    <i class="fas fa-ticket-alt fs-5"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-dark font-monospace fs-6 text-uppercase">{{ $v->code }}</span>
                                        <button class="btn btn-xs btn-outline-secondary px-1.5 py-0.5" onclick="navigator.clipboard.writeText('{{ $v->code }}'); alert('Đã sao chép: {{ $v->code }}');" title="Sao chép mã">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                    <div class="text-danger fw-extrabold" style="font-size: 13.5px;">
                                        Giảm {{ number_format($v->value, 0, ',', '.') }}đ
                                    </div>
                                    <div class="text-muted" style="font-size: 11px;">Mã riêng khả dụng cho tài khoản của bạn</div>
                                </div>
                            </div>
                            <a href="{{ route('shop') }}" class="btn btn-sm text-white fw-bold px-3 py-1.5 rounded-pill shadow-sm flex-shrink-0" style="background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); font-size: 11.5px; border: none;">
                                {{ __('Dùng ngay') }}
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted px-3">
                            <i class="fas fa-box-open fa-3x mb-2 text-secondary opacity-50"></i>
                            <h6 class="fw-bold text-dark mb-1">Bạn chưa có voucher riêng nào</h6>
                            <p class="small text-muted mb-0" style="line-height: 1.5;">
                                Bạn hãy hoàn thành 1 đơn hàng và dùng vé xoay may mắn nhận voucher.<br>
                                Voucher Admin sẽ tặng ngẫu nhiên cho khách hàng đặt đơn nhiều!
                            </p>
                        </div>
                    @endforelse
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-user-lock fa-3x mb-3 text-warning opacity-75"></i>
                        <h6 class="fw-bold text-dark mb-1">Vui lòng đăng nhập</h6>
                        <p class="small text-muted mb-3">Đăng nhập tài khoản để kiểm tra kho quà và mã giảm giá riêng của bạn.</p>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary fw-bold rounded-pill px-4">
                            <i class="fas fa-sign-in-alt me-1"></i> Đăng Nhập Ngay
                        </a>
                    </div>
                @endauth
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0 p-3 bg-white d-flex align-items-center justify-content-end">
                <button type="button" class="btn btn-sm btn-light border text-secondary px-4 rounded-pill" data-bs-dismiss="modal">
                    {{ __('Đóng') }}
                </button>
            </div>
        </div>
    </div>
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
    const poll = () => {
        if (affiliateChatOpen) checkNewAffiliateMessages();
        else refreshAffiliateUnreadCount();
        affiliatePollingInterval = setTimeout(poll, affiliateChatOpen ? 5000 : 30000);
    };
    poll();
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
    // Emoji list initialization
    const emojis = [
        '😀','😃','😄','😁','😆','😅','😂','🤣','😊','😇','🙂','🙃','😉','😌','😍','🥰','😘','😗','😙','😚','😋','😛','😝','😜','🤪','🤨','🧐','🤓','😎','🤩','🥳','😏','😒','😞','😔','😟','😕','🙁','☹️','😣','😖','😫','😩','🥺','😢','😭','😤','😠','😡','🤬','🤯','😳','🥵','🥶','😱','😨','😰','😥','😓','🤗','🤔','🤭','🤫','🤥','😶','😐','😑','😬','🙄','😯','😦','😧','😮','😲','🥱','😴','🤤','😪','😵','🤐','🥴','🤢','🤮','🤧','😷','🤒','🤕','🤑','🤠','😈','👿','👹','👺','🤡','💩','👻','💀','☠️','👽','👾','🤖','🎃','😺','😸','😹','😻','😼','😽','🙀','😿','😾','❤️','🧡','💛','💚','💙','💜','🖤','🤍','🤎','💔','❣️','💕','💞','💓','💗','💖','💘','💝','💟','🌟','⭐','✨','⚡','💥','🔥','🌈','☀️','🌤️','⛅','🌥️','☁️','🌦️','🌧️','⛈️','🌩️','❄️','💨','🌪️','🌫️','🌊','🎈','🎉','🎊','🎁','🐱','🐶','🦊','🐰','🐻','🐼','🐨','🦁','🐯','🐮','🐷','🐸','🐵','🐔','🐧','🐦','🐤','🐣','🐥','🦆','🦅','🦉','🦇','🐺','🐗','🐴','🐝','🐛','🦋','🐌','🐞','🐜','🕷️','🕸️','🐢','🐍','🦎','🐙','🦑','🦐','🦞','🦀','🐡','🐠','🐟','🐬','🐳','🐋','🦈','🐊','🐆','🐅','🦍','🦧','🚀','🛸','🎮','🕹️','🧩','🔮','🧸'
    ];

    function setupEmojiPicker(toggleBtnId, popoverId, listId, inputId) {
        const toggleBtn = document.getElementById(toggleBtnId);
        const popover = document.getElementById(popoverId);
        const list = document.getElementById(listId);
        const input = document.getElementById(inputId);

        if (!toggleBtn || !popover || !list || !input) return;

        emojis.forEach(emoji => {
            const span = document.createElement('span');
            span.className = 'emoji-item';
            span.innerText = emoji;
            span.addEventListener('click', function (e) {
                e.stopPropagation();
                const startPos = input.selectionStart;
                const endPos = input.selectionEnd;
                const textVal = input.value;
                input.value = textVal.substring(0, startPos) + emoji + textVal.substring(endPos, textVal.length);
                input.focus();
                input.selectionStart = startPos + emoji.length;
                input.selectionEnd = startPos + emoji.length;
            });
            list.appendChild(span);
        });

        toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (popover.style.display === 'flex') {
                popover.style.display = 'none';
            } else {
                popover.style.display = 'flex';
            }
        });

        document.addEventListener('click', function (e) {
            if (!popover.contains(e.target) && e.target !== toggleBtn && !toggleBtn.contains(e.target)) {
                popover.style.display = 'none';
            }
        });
    }

    setupEmojiPicker('affiliateEmojiToggleBtn', 'affiliateEmojiPopover', 'affiliateEmojiList', 'affiliateChatInput');
    setupEmojiPicker('userEmojiToggleBtn', 'userEmojiPopover', 'userEmojiList', 'userChatInput');

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
        const popover = document.getElementById('supportMenuPopover');
        const pillBtn = document.querySelector('.contact-us-pill-btn');
        if (popover?.contains(event.target) || pillBtn?.contains(event.target)) {
            return;
        }

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
        if (!res.ok) {
            return res.json().then(data => {
                throw new Error(data.error || 'Server error ' + res.status);
            });
        }
        return res.json();
    })
    .then(data => {
        if (data && data.id) {
            input.value = '';
            autoGrowTextarea(input);
            clearUserImagePreview();
            _appendUserMsg(data, false);
            lastUserMessageId = Math.max(lastUserMessageId, data.id);
            
            // Bắt đầu đếm ngược thời gian chờ 10 giây chống spam
            let cooldown = 10;
            sendBtn.disabled = true;
            input.disabled = true;
            const originalPlaceholder = input.placeholder;
            
            sendBtn.innerHTML = `<span style="font-size: 11px; font-weight: bold;">${cooldown}s</span>`;
            input.placeholder = `Vui lòng đợi ${cooldown} giây...`;
            
            let interval = setInterval(() => {
                cooldown--;
                if (cooldown <= 0) {
                    clearInterval(interval);
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
                    input.disabled = false;
                    input.placeholder = originalPlaceholder;
                    input.focus();
                } else {
                    sendBtn.innerHTML = `<span style="font-size: 11px; font-weight: bold;">${cooldown}s</span>`;
                    input.placeholder = `Vui lòng đợi ${cooldown} giây...`;
                }
            }, 1000);
        }
    })
    .catch(err => {
        console.error('Send user message error:', err);
        alert(err.message || 'Không thể gửi tin nhắn, vui lòng thử lại.');
        
        // Kích hoạt lại nút nếu bị lỗi để người dùng sửa tin nhắn
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
    const poll = () => {
        if (userChatOpen) {
            _checkNewUserMessages();
        } else {
            _refreshUserUnreadCount();
        }
        userPollingInterval = setTimeout(poll, userChatOpen ? 5000 : 30000);
    };
    poll();
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

            [document.getElementById('navChatBadge'), document.getElementById('mobileChatBadge')].forEach(navBadge => {
                if (!navBadge) return;
                navBadge.textContent = data.unread || 0;
                navBadge.style.display = data.unread > 0 ? 'inline-block' : 'none';
            });
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

    // Hiệu ứng tự động thu gọn (chỉ còn icon) <-> dãn ra (hiển thị đầy đủ chữ) mỗi 10 giây
    setInterval(function() {
        const pillBtn = document.querySelector('.contact-us-pill-btn');
        const popover = document.getElementById('supportMenuPopover');
        if (pillBtn && (!popover || !popover.classList.contains('active'))) {
            pillBtn.classList.toggle('is-collapsed');
        }
    }, 10000);

    // Close Support Popover on outside click
    document.addEventListener('click', function(e) {
        const popover = document.getElementById('supportMenuPopover');
        const pillBtn = document.querySelector('.contact-us-pill-btn');
        if (popover && popover.classList.contains('active')) {
            if (!popover.contains(e.target) && (!pillBtn || !pillBtn.contains(e.target))) {
                closeSupportMenuPopover();
            }
        }
    });
});

function toggleSupportMenuPopover(e) {
    if (e) e.stopPropagation();
    const popover = document.getElementById('supportMenuPopover');
    if (popover) {
        popover.classList.toggle('active');
    }
}

function closeSupportMenuPopover() {
    const popover = document.getElementById('supportMenuPopover');
    if (popover) {
        popover.classList.remove('active');
    }
}

function openUserChatFromPopover(e) {
    if (e) e.stopPropagation();
    closeSupportMenuPopover();
    const widget = document.getElementById('userChatWidget');
    if (widget) {
        if (!userChatOpen) {
            toggleUserChat();
        }
    } else {
        @auth
            const affWidget = document.getElementById('affiliateChatWidget');
            if (affWidget) {
                if (!affiliateChatOpen) toggleAffiliateChat();
                return;
            }
        @endauth
        window.location.href = "{{ route('login') }}";
    }
}

function openAffiliateChatFromPopover(e) {
    if (e) e.stopPropagation();
    closeSupportMenuPopover();
    const affWidget = document.getElementById('affiliateChatWidget');
    if (affWidget && !affiliateChatOpen) {
        toggleAffiliateChat();
    }
}
</script>
