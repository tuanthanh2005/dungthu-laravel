<div class="ai-generator-card mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <div class="ai-badge-icon">
                <i class="fas fa-magic"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark">
                    Trợ Lý AI Viết Blog Bán Hàng (Google Gemini)
                </h5>
                <small class="text-muted">Tự động viết nội dung chốt sales, mô tả ngắn & chọn danh mục từ Tiêu đề</small>
            </div>
        </div>
        
        <div class="d-flex align-items-center gap-2">
            @php
                $hasApiKey = !empty(\App\Services\GeminiBlogService::getApiKey());
                $defaultModel = \App\Models\SiteSetting::getValue('gemini_default_model', 'gemini-2.0-flash');
            @endphp
            
            <span id="aiKeyStatusBadge">
                @if($hasApiKey)
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                        <i class="fas fa-check-circle me-1"></i>Đã có API Key
                    </span>
                @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2">
                        <i class="fas fa-exclamation-circle me-1"></i>Chưa cài API Key
                    </span>
                @endif
            </span>

            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#aiKeyQuickConfig">
                <i class="fas fa-cog me-1"></i>Đổi Key
            </button>
            <a href="{{ route('admin.menu-settings') }}" target="_blank" class="btn btn-link btn-sm text-decoration-none text-muted" title="Trang cài đặt chính">
                <i class="fas fa-external-link-alt"></i> Cài Đặt
            </a>
        </div>
    </div>

    <!-- Quick API Key Settings Collapsible -->
    <div class="collapse mb-3" id="aiKeyQuickConfig">
        <div class="p-3 bg-light rounded-3 border">
            <label class="form-label fw-bold text-dark small mb-1">
                <i class="fas fa-key me-1 text-warning"></i> Cấu hình nhanh Gemini API Key:
            </label>
            <div class="input-group">
                <input type="password" id="quick_gemini_api_key" class="form-control form-control-sm" 
                       placeholder="Nhập API Key mới (AIzaSy...)" 
                       value="{{ \App\Models\SiteSetting::getValue('gemini_api_key', '') }}">
                <button type="button" class="btn btn-primary btn-sm px-3" onclick="saveQuickGeminiKey()">
                    <i class="fas fa-save me-1"></i> Lưu Key
                </button>
            </div>
            <small class="text-muted d-block mt-1">
                Key này sẽ được lưu vào hệ thống để dùng cho tất cả các bài viết sau.
            </small>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <!-- Tùy chọn Model Gemini -->
        <div class="col-md-6">
            <label for="ai_model_select" class="form-label fw-bold small text-secondary">
                <i class="fas fa-microchip me-1 text-primary"></i>Chọn Model Gemini
            </label>
            <select id="ai_model_select" class="form-select border-2">
                <option value="gemini-2.0-flash" {{ $defaultModel == 'gemini-2.0-flash' ? 'selected' : '' }}>Gemini 2.0 Flash (Nhanh & Mới nhất - Nhanh ⚡)</option>
                <option value="gemini-1.5-flash" {{ $defaultModel == 'gemini-1.5-flash' ? 'selected' : '' }}>Gemini 1.5 Flash (Tốc độ cao 🚀)</option>
                <option value="gemini-1.5-pro" {{ $defaultModel == 'gemini-1.5-pro' ? 'selected' : '' }}>Gemini 1.5 Pro (Sâu sắc & Chi tiết 💎)</option>
                <option value="gemini-2.0-flash-lite" {{ $defaultModel == 'gemini-2.0-flash-lite' ? 'selected' : '' }}>Gemini 2.0 Flash Lite</option>
                <option value="gemini-3.1-flash-lite" {{ $defaultModel == 'gemini-3.1-flash-lite' ? 'selected' : '' }}>Gemini 3.1 Flash-Lite</option>
                <option value="gemini-3.5-flash" {{ $defaultModel == 'gemini-3.5-flash' ? 'selected' : '' }}>Gemini 3.5 Flash</option>
            </select>
        </div>

        <!-- Tùy chọn Giọng văn Bán hàng -->
        <div class="col-md-6">
            <label for="ai_tone_select" class="form-label fw-bold small text-secondary">
                <i class="fas fa-bullhorn me-1 text-danger"></i>Mục Tiêu & Giọng Văn
            </label>
            <select id="ai_tone_select" class="form-select border-2">
                <option value="sales" selected>🛒 Bán Hàng & Chốt Đơn (Mặc định - Kéo khách hàng)</option>
                <option value="consulting">👨‍💼 Tư Vấn & Thuyết Phục (Xây dựng uy tín)</option>
                <option value="sharing">💡 Chia Sẻ Kinh Nghiệm (Trao giá trị khéo léo)</option>
                <option value="review">⭐ Review Sản Phẩm (Đánh giá ưu điểm)</option>
            </select>
        </div>
    </div>

    <!-- Nút bấm Kích hoạt AI -->
    <button type="button" id="btnGenerateBlogAI" class="btn btn-ai-generate w-100 py-3 rounded-3 fw-bold text-white shadow-sm" onclick="triggerBlogAIGeneration()">
        <i class="fas fa-bolt me-2"></i> TỰ ĐỘNG VIẾT BÀI BÁN HÀNG BẰNG AI GEMINI
    </button>

    <!-- Trạng thái Loading -->
    <div id="aiGeneratingProgress" class="d-none mt-3 p-3 bg-white rounded-3 border text-center">
        <div class="spinner-border text-primary me-2" role="status" style="width: 1.8rem; height: 1.8rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span id="aiProgressText" class="fw-bold text-primary fs-6">
            🤖 AI đang phân tích tiêu đề và soạn bài viết bán hàng...
        </span>
        <div class="progress mt-2" style="height: 6px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary w-100"></div>
        </div>
        <small class="text-muted d-block mt-2">Quá trình có thể mất từ 5 - 15 giây tùy độ dài bài viết.</small>
    </div>
</div>

<style>
.ai-generator-card {
    background: linear-gradient(135deg, #f5f3ff 0%, #eff6ff 100%);
    border: 2px solid #ddd6fe;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(124, 58, 237, 0.08);
}

.ai-badge-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #7c3aed 0%, #2563eb 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

.btn-ai-generate {
    background: linear-gradient(135deg, #7c3aed 0%, #2563eb 100%);
    border: none;
    font-size: 16px;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-ai-generate:hover {
    background: linear-gradient(135deg, #6d28d9 0%, #1d4ed8 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(124, 58, 237, 0.35) !important;
}

.btn-ai-generate:disabled {
    opacity: 0.7;
    transform: none;
}
</style>

<script>
function promptAdminPin(callback) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Xác nhận thao tác Admin',
            text: 'Vui lòng nhập mã PIN 8 số để xác thực:',
            input: 'password',
            inputAttributes: { maxlength: 8, pattern: '[0-9]{8}', inputmode: 'numeric' },
            showCancelButton: true,
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#7c3aed',
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const pin = result.value.trim();
                if (!/^\d{8}$/.test(pin)) {
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Mã PIN phải đúng 8 số.' });
                    return;
                }
                callback(pin);
            }
        });
    } else {
        const pin = prompt('Vui lòng nhập mã PIN 8 số để xác thực:');
        if (pin && /^\d{8}$/.test(pin.trim())) {
            callback(pin.trim());
        } else if (pin) {
            alert('Mã PIN phải đúng 8 số.');
        }
    }
}

function triggerBlogAIGeneration(adminPin = null) {
    const titleInput = document.getElementById('title');
    const titleValue = titleInput ? titleInput.value.trim() : '';

    if (!titleValue) {
        alert('⚠️ Vui lòng nhập "Tiêu đề bài viết" ở ô phía trên để AI có thể phân tích và viết bài!');
        if (titleInput) titleInput.focus();
        return;
    }

    const model = document.getElementById('ai_model_select').value;
    const tone = document.getElementById('ai_tone_select').value;
    const btn = document.getElementById('btnGenerateBlogAI');
    const progress = document.getElementById('aiGeneratingProgress');
    const progressText = document.getElementById('aiProgressText');

    btn.disabled = true;
    progress.classList.remove('d-none');

    const steps = [
        '🤖 AI đang phân tích tiêu đề và định hình cấu trúc bán hàng...',
        '✍️ Đang sáng tạo nội dung chốt sales & lập dàn ý...',
        '💎 Đang viết nội dung chi tiết và định dạng HTML...',
        '✨ Đang tạo mô tả ngắn chuẩn SEO và lựa chọn danh mục...'
    ];
    let stepIdx = 0;
    const stepInterval = setInterval(() => {
        stepIdx = (stepIdx + 1) % steps.length;
        progressText.textContent = steps[stepIdx];
    }, 2500);

    const payload = {
        title: titleValue,
        model: model,
        tone: tone
    };
    if (adminPin) {
        payload.admin_pin = adminPin;
    }

    fetch('{{ route("admin.blogs.generate_ai") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Đã có lỗi xảy ra khi tạo bài viết!');
        }
        return data;
    })
    .then(res => {
        clearInterval(stepInterval);
        progress.classList.add('d-none');
        btn.disabled = false;

        if (res.success && res.data) {
            const result = res.data;

            // 1. Cập nhật Mô tả ngắn (Excerpt)
            const excerptEl = document.getElementById('excerpt');
            if (excerptEl && result.excerpt) {
                excerptEl.value = result.excerpt;
                if (typeof updateExcerptCounter === 'function') {
                    updateExcerptCounter();
                }
            }

            // 2. Cập nhật Nội dung vào TinyMCE Editor
            if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                tinymce.get('content').setContent(result.content || '');
            } else {
                const contentEl = document.getElementById('content');
                if (contentEl) contentEl.value = result.content || '';
            }

            // 3. Cập nhật Danh mục
            const catEl = document.getElementById('category');
            if (catEl && result.category) {
                catEl.value = result.category;
            }

            // Scroll nhẹ đến khu vực trình soạn thảo
            const contentArea = document.getElementById('content');
            if (contentArea) {
                contentArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            alert('🎉 THÀNH CÔNG! AI đã viết xong bài blog bán hàng chuẩn SEO. Bạn có thể chỉnh sửa thêm trước khi lưu!');
        } else {
            alert('❌ ' + (res.message || 'Không thể tạo bài viết'));
        }
    })
    .catch(err => {
        clearInterval(stepInterval);
        progress.classList.add('d-none');
        btn.disabled = false;

        if (err.message && (err.message.includes('8 số') || err.message.includes('xác nhận') || err.message.includes('PIN'))) {
            promptAdminPin((pin) => {
                triggerBlogAIGeneration(pin);
            });
            return;
        }

        alert('❌ Lỗi: ' + err.message);
    });
}

function saveQuickGeminiKey(adminPin = null) {
    const keyInput = document.getElementById('quick_gemini_api_key');
    const key = keyInput ? keyInput.value.trim() : '';

    if (!key) {
        alert('Vui lòng nhập Gemini API Key!');
        return;
    }

    const payload = { api_key: key };
    if (adminPin) {
        payload.admin_pin = adminPin;
    }

    fetch('{{ route("admin.blogs.save_gemini_key") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Đã có lỗi xảy ra khi lưu Key!');
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            alert('✅ Đã lưu Gemini API Key thành công!');
            const badge = document.getElementById('aiKeyStatusBadge');
            if (badge) {
                badge.innerHTML = `<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2"><i class="fas fa-check-circle me-1"></i>Đã có API Key</span>`;
            }
            // Đóng collapse
            const collapseEl = document.getElementById('aiKeyQuickConfig');
            if (collapseEl && typeof bootstrap !== 'undefined') {
                const bsCollapse = bootstrap.Collapse.getInstance(collapseEl) || new bootstrap.Collapse(collapseEl);
                bsCollapse.hide();
            }
        } else {
            alert('❌ Lỗi: ' + data.message);
        }
    })
    .catch(err => {
        if (err.message && (err.message.includes('8 số') || err.message.includes('xác nhận') || err.message.includes('PIN'))) {
            promptAdminPin((pin) => {
                saveQuickGeminiKey(pin);
            });
            return;
        }
        alert('❌ Lỗi lưu key: ' + err.message);
    });
}
</script>
