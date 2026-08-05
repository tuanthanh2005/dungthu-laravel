<div class="position-fixed d-flex flex-column gap-2" style="right:16px;bottom:80px;z-index:9999">
    <a href="{{ \App\Helpers\SupportHelper::getZaloLink() }}" target="_blank" rel="noopener noreferrer"
       class="rounded-circle d-flex align-items-center justify-content-center text-white shadow"
       style="width:48px;height:48px;background:#0068ff;text-decoration:none" aria-label="{{ __('Liên hệ Zalo') }}">
        <i class="fa-solid fa-comment"></i>
    </a>
    <a href="{{ \App\Helpers\SupportHelper::getTelegramLink() }}" target="_blank" rel="noopener noreferrer"
       class="rounded-circle d-flex align-items-center justify-content-center text-white shadow"
       style="width:48px;height:48px;background:#0088cc;text-decoration:none" aria-label="{{ __('Telegram Admin') }}">
        <i class="fab fa-telegram-plane"></i>
    </a>
</div>
