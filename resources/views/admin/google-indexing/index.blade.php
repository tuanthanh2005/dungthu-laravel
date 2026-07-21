@extends('layouts.admin')

@section('title', 'Cấu hình & Lập chỉ mục Google Indexing')

@section('page_title', 'Google Indexing')

@push('styles')
<style>

    .admin-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    .admin-nav .nav-link {
        color: #4a5568;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 10px;
        transition: all 0.3s ease;
        margin: 0 5px;
    }

    .admin-nav .nav-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .admin-nav .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .status-badge {
        font-size: 0.85rem;
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 600;
    }

    .console-card {
        border-radius: 15px;
        border: 1px solid #e2e8f0;
        padding: 20px;
        background: #f8fafc;
        transition: all 0.3s ease;
        min-height: 280px;
        height: 100%;
    }

    .console-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-color: #cbd5e1;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
        <!-- Google Indexing Panel -->
        <div class="row">
            <!-- Configuration Status -->
            <div class="col-lg-5 mb-4" data-aos="fade-right">
                <div class="admin-card h-100 mb-0">
                    <h4 class="fw-bold mb-4">
                        <i class="fab fa-google text-primary me-2"></i>Trạng thái kết nối Google
                    </h4>

                    <table class="table align-middle">
                        <tbody>
                            <tr>
                                <td><strong>API Indexing:</strong></td>
                                <td>
                                    @if($enabled)
                                        <span class="status-badge bg-success text-white"><i class="fas fa-check-circle me-1"></i>Đang bật</span>
                                    @else
                                        <span class="status-badge bg-secondary text-white"><i class="fas fa-times-circle me-1"></i>Đang tắt</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Site URL Google:</strong></td>
                                <td><code class="text-primary">{{ $siteUrl }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Tên File Key:</strong></td>
                                <td><code class="text-secondary">{{ basename($keyFile) ?: 'Chưa cấu hình' }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Trạng thái File Key:</strong></td>
                                <td>
                                    @if($keyFileExists)
                                        <span class="text-success"><i class="fas fa-check me-1"></i>Đã tìm thấy file</span>
                                    @else
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Không tìm thấy</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Sản phẩm hệ thống:</strong></td>
                                <td><span class="badge bg-info px-3 py-2 fs-6">{{ $totalProducts }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Bài viết (Blogs):</strong></td>
                                <td><span class="badge bg-success px-3 py-2 fs-6">{{ $totalBlogs }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Từ khóa SEO:</strong></td>
                                <td><span class="badge bg-warning text-dark px-3 py-2 fs-6">{{ $totalSeoKeywords }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Submit Arbitrary URL -->
            <div class="col-lg-7 mb-4" data-aos="fade-left">
                <div class="admin-card h-100 mb-0">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-paper-plane text-success me-2"></i>Gửi Index URL Tùy Ý
                    </h4>
                    <p class="text-muted small">Nhập bất kỳ đường dẫn nào của website (ví dụ: <code>{{ $siteUrl }}/tim-kiem/gi-do</code>) để gửi trực tiếp lên Google API.</p>

                    <form id="form-submit-single-url">
                        <div class="mb-3">
                            <label for="input-url" class="form-label fw-bold">URL cần gửi index:</label>
                            <input type="url" id="input-url" class="form-control px-3 py-2 rounded-3" placeholder="https://..." required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="select-type" class="form-label fw-bold">Hành động:</label>
                                <select id="select-type" class="form-select px-3 py-2 rounded-3">
                                    <option value="URL_UPDATED">Thêm / Cập nhật URL (Updated)</option>
                                    <option value="URL_DELETED">Gỡ bỏ URL (Deleted)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="input-pin" class="form-label fw-bold">Mã xác nhận (PIN Admin):</label>
                                <input type="password" id="input-pin" class="form-control px-3 py-2 rounded-3" placeholder="Mã PIN 8 số" required maxlength="8">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm w-100 mt-2 btn-submit-url">
                            <i class="fas fa-cloud-upload-alt me-2"></i>Gửi yêu cầu lập chỉ mục
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bulk submission section -->
            <div class="col-12 mb-4" data-aos="fade-up">
                <div class="admin-card">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-layer-group text-warning me-2"></i>Bảng điều khiển Index hàng loạt
                    </h4>

                    <div class="row g-4">
                        <!-- Bulk Products -->
                        <div class="col-md-6 col-lg-3">
                            <div class="console-card text-center d-flex flex-column justify-content-between">
                                <div>
                                    <i class="fas fa-box fs-1 text-primary mb-3"></i>
                                    <h5>Index Tất Cả Sản Phẩm</h5>
                                    <p class="text-muted small">Gửi toàn bộ sản phẩm hiện có trên website lên Google Indexing API.</p>
                                </div>
                                <button type="button" class="btn btn-primary rounded-pill w-100 btn-bulk-index mt-3" data-url="{{ route('admin.google-indexing.submit-all-products', [], false) }}" data-type="Sản phẩm">
                                    <i class="fab fa-google me-2"></i>Bắt đầu gửi
                                </button>
                            </div>
                        </div>

                        <!-- Bulk Categories -->
                        <div class="col-md-6 col-lg-3">
                            <div class="console-card text-center d-flex flex-column justify-content-between">
                                <div>
                                    <i class="fas fa-list fs-1 text-success mb-3"></i>
                                    <h5>Index Tất Cả Danh Mục</h5>
                                    <p class="text-muted small">Gửi tất cả các danh mục lọc sản phẩm hoạt động lên Google Indexing API.</p>
                                </div>
                                <button type="button" class="btn btn-success text-white rounded-pill w-100 btn-bulk-index mt-3" data-url="{{ route('admin.google-indexing.submit-all-categories', [], false) }}" data-type="Danh mục">
                                    <i class="fab fa-google me-2"></i>Bắt đầu gửi
                                </button>
                            </div>
                        </div>

                        <!-- Bulk SEO Keywords -->
                        <div class="col-md-6 col-lg-3">
                            <div class="console-card text-center d-flex flex-column justify-content-between">
                                <div>
                                    <i class="fas fa-search fs-1 text-warning mb-3"></i>
                                    <h5>Index Từ Khóa SEO</h5>
                                    <p class="text-muted small">Gửi tất cả các trang đích (landing pages) từ khóa SEO đang hoạt động lên Google.</p>
                                </div>
                                <button type="button" class="btn btn-warning text-dark rounded-pill w-100 btn-bulk-index mt-3" data-url="{{ route('admin.seo-keywords.submit-all', [], false) }}" data-type="Từ khóa SEO">
                                    <i class="fab fa-google me-2"></i>Bắt đầu gửi
                                </button>
                            </div>
                        </div>
                        
                        <!-- Bulk Blogs (130 Latest) -->
                        <div class="col-md-6 col-lg-3">
                            <div class="console-card text-center d-flex flex-column justify-content-between">
                                <div>
                                    <i class="fas fa-blog fs-1 text-danger mb-3"></i>
                                    <h5>Index 130 Blog Mới Nhất</h5>
                                    <p class="text-muted small">Gửi 130 bài viết gần đây nhất (từ bài 1 đến 130) lên Google Indexing API.</p>
                                </div>
                                <button type="button" class="btn btn-danger text-white rounded-pill w-100 btn-bulk-index-custom mt-3" data-url="{{ route('admin.google-indexing.submit-all', [], false) }}?latest=true&limit=130&offset=0" data-label="130 bài Blog mới nhất (1-130)">
                                    <i class="fab fa-google me-2"></i>Bắt đầu gửi (1-130)
                                </button>
                            </div>
                        </div>

                        <!-- Bulk Blogs (130 Previous) -->
                        <div class="col-md-6 col-lg-3">
                            <div class="console-card text-center d-flex flex-column justify-content-between">
                                <div>
                                    <i class="fas fa-history fs-1 text-secondary mb-3"></i>
                                    <h5>Index 130 Blog Trước Đó</h5>
                                    <p class="text-muted small">Gửi 130 bài viết tiếp theo (từ bài 131 đến 260 - dành cho các bài cũ bị mất chỉ mục).</p>
                                </div>
                                <button type="button" class="btn btn-secondary text-white rounded-pill w-100 btn-bulk-index-custom mt-3" data-url="{{ route('admin.google-indexing.submit-all', [], false) }}?latest=true&limit=130&offset=130" data-label="130 bài Blog trước đó (131-260)">
                                    <i class="fab fa-google me-2"></i>Bắt đầu gửi (131-260)
                                </button>
                            </div>
                        </div>
                        
                        <!-- Bulk Proxy & VPN -->
                        <div class="col-md-6 col-lg-3">
                            <div class="console-card text-center d-flex flex-column justify-content-between">
                                <div>
                                    <i class="fas fa-network-wired fs-1 text-info mb-3"></i>
                                    <h5>Index Trang Proxy & VPN</h5>
                                    <p class="text-muted small">Gửi các trang đích danh sách Proxy và VPN lên Google Indexing API.</p>
                                </div>
                                <button type="button" class="btn btn-info text-white rounded-pill w-100 btn-bulk-index mt-3" data-url="{{ route('admin.google-indexing.submit-all-proxies', [], false) }}" data-type="Proxy & VPN">
                                    <i class="fab fa-google me-2"></i>Bắt đầu gửi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent logs -->
            <div class="col-12" data-aos="fade-up">
                <div class="admin-card">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <h4 class="fw-bold mb-0">
                            <i class="fas fa-history text-secondary me-2"></i>Nhật ký gửi Index (Trong 24h qua)
                        </h4>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 btn-refresh-logs">
                            <i class="fas fa-sync-alt me-1"></i>Làm mới nhật ký
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="table-logs">
                            <thead class="table-light">
                                <tr>
                                    <th>URL</th>
                                    <th>Kiểu</th>
                                    <th>Nguồn</th>
                                    <th>Trạng thái</th>
                                    <th>Thời gian gửi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Đang tải lịch sử nhật ký...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="pagination-container"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });

    function unlockEverything() {
        sessionStorage.setItem('admin_unlocked', 'true');
    }

    // Protection for other links
    document.querySelectorAll('.protected-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            
            if (sessionStorage.getItem('admin_unlocked') === 'true') {
                window.location.href = url;
            } else {
                const pass = prompt('Đây là khu vực bảo mật. Vui lòng nhập mật khẩu để tiếp tục:');
                if (pass === '113') {
                    unlockEverything();
                    window.location.href = url;
                } else if (pass !== null) {
                    alert('Mật khẩu không chính xác!');
                }
            }
        });
    });

    function adminLockManual() {
        if (confirm('Bạn có muốn khóa lại các khu vực bảo mật không?')) {
            sessionStorage.removeItem('admin_unlocked');
            sessionStorage.removeItem('revenue_unlocked');
            window.location.href = "{{ route('admin.lock') }}";
        }
    }

    let allLogs = [];
    let currentPage = 1;
    const itemsPerPage = 10;

    // Fetch and render logs
    function fetchLogs() {
        const tableBody = document.querySelector('#table-logs tbody');
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Đang tải...</td></tr>';
        document.getElementById('pagination-container').innerHTML = '';

        fetch('{{ route("admin.google-indexing.recent", [], false) }}?minutes=1440&limit=500', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                allLogs = data.data;
                renderLogsPage(1);
            } else {
                allLogs = [];
                renderLogsPage(1);
            }
        })
        .catch(err => {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-danger">Không thể tải nhật ký. Đã xảy ra lỗi kết nối.</td></tr>';
        });
    }

    function renderLogsPage(page) {
        const tableBody = document.querySelector('#table-logs tbody');
        if (allLogs.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted">Không có nhật ký gửi index nào trong 24h qua.</td></tr>';
            document.getElementById('pagination-container').innerHTML = '';
            return;
        }

        const totalPages = Math.ceil(allLogs.length / itemsPerPage);
        if (page < 1) page = 1;
        if (page > totalPages) page = totalPages;
        currentPage = page;

        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedLogs = allLogs.slice(start, end);

        let html = '';
        paginatedLogs.forEach(log => {
            let typeBadge = log.type === 'URL_UPDATED' 
                ? '<span class="badge bg-success">UPDATED</span>' 
                : '<span class="badge bg-danger">DELETED</span>';
            
            let statusBadge = '';
            if (log.status === 'success') {
                statusBadge = '<span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i>Thành công</span>';
            } else if (log.status === 'failed') {
                statusBadge = `<span class="text-danger fw-bold" title="${log.message || ''}"><i class="fas fa-exclamation-circle me-1"></i>Thất bại</span>`;
            } else {
                statusBadge = `<span class="text-muted fw-bold"><i class="fas fa-arrow-alt-circle-right me-1"></i>Bỏ qua (${log.status})</span>`;
            }

            let localTime = new Date(log.submitted_at).toLocaleString('vi-VN');

            html += `
                <tr>
                    <td><code class="text-primary small">${log.url}</code></td>
                    <td>${typeBadge}</td>
                    <td><span class="text-secondary small">${log.source}</span></td>
                    <td>${statusBadge}</td>
                    <td><small class="text-muted">${localTime}</small></td>
                </tr>
            `;
        });
        tableBody.innerHTML = html;
        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        if (totalPages <= 1) {
            document.getElementById('pagination-container').innerHTML = '';
            return;
        }

        let paginationHtml = '<ul class="pagination justify-content-center mb-0 mt-3">';
        
        // Prev button
        paginationHtml += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="event.preventDefault(); renderLogsPage(${currentPage - 1})">Trước</a>
        </li>`;

        // Pages
        for (let i = 1; i <= totalPages; i++) {
            if (totalPages > 7) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    paginationHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="event.preventDefault(); renderLogsPage(${i})">${i}</a>
                    </li>`;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            } else {
                paginationHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="event.preventDefault(); renderLogsPage(${i})">${i}</a>
                </li>`;
            }
        }

        // Next button
        paginationHtml += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="event.preventDefault(); renderLogsPage(${currentPage + 1})">Sau</a>
        </li>`;

        paginationHtml += '</ul>';
        document.getElementById('pagination-container').innerHTML = paginationHtml;
    }

    // Single URL submission
    document.getElementById('form-submit-single-url').addEventListener('submit', function(e) {
        e.preventDefault();

        const urlInput = document.getElementById('input-url').value;
        const typeSelect = document.getElementById('select-type').value;
        const pinInput = document.getElementById('input-pin').value;
        const submitBtn = document.querySelector('.btn-submit-url');
        const originalHtml = submitBtn.innerHTML;

        if (!/^\d{8}$/.test(pinInput)) {
            alert('Mã xác nhận phải đúng 8 số.');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...';

        fetch('{{ route("admin.google-indexing.submit-url", [], false) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                url: urlInput,
                type: typeSelect,
                admin_pin: pinInput
            })
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            if (status === 200 && body.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Gửi URL lên Google Indexing thành công!',
                });
                document.getElementById('input-url').value = '';
                document.getElementById('input-pin').value = '';
                fetchLogs();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Thất bại!',
                    text: body.message || 'Lỗi xảy ra từ hệ thống hoặc API đã hết hạn mức hôm nay.'
                });
            }
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi kết nối!',
                text: 'Không thể kết nối đến server.'
            });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
        });
    });

    // Bulk submission handlers
    document.querySelectorAll('.btn-bulk-index-custom').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const label = this.getAttribute('data-label');
            const button = this;
            const originalHtml = button.innerHTML;
            executeBulkIndex(url, label, button, originalHtml);
        });
    });

    document.querySelectorAll('.btn-bulk-index').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const type = this.getAttribute('data-type');
            const button = this;
            const originalHtml = button.innerHTML;
            executeBulkIndex(url, 'tất cả ' + type, button, originalHtml);
        });
    });

    function executeBulkIndex(url, typeText, button, originalHtml) {
        const pin = window.prompt(`Nhập mã xác nhận (PIN admin 8 số) để gửi INDEX HÀNG LOẠT ${typeText} lên Google:`);
        if (pin === null) return;
        if (!/^\d{8}$/.test(pin)) {
            alert('Mã xác nhận phải đúng 8 số.');
            return;
        }

        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                admin_pin: pin
            })
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            if (status === 200 && body.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: body.message || `Gửi Index hàng loạt ${typeText} thành công!`,
                    confirmButtonText: 'Đồng ý'
                });
                fetchLogs();
            } else {
                let failedDetails = '';
                if (body.failed && body.failed.length > 0) {
                    failedDetails = '\n\nChi tiết lỗi:\n' + body.failed.slice(0, 10).map(f => `- ${f.slug || f.name || f.product_id || f.blog_id}: ${f.message}`).join('\n');
                    if (body.failed.length > 10) {
                        failedDetails += `\n... và ${body.failed.length - 10} lỗi khác.`;
                    }
                }
                Swal.fire({
                    icon: body.submitted > 0 ? 'warning' : 'error',
                    title: body.submitted > 0 ? 'Hoàn thành một phần!' : 'Thất bại!',
                    text: (body.message || 'Lỗi xảy ra từ hệ thống hoặc API đã hết hạn mức hôm nay.') + failedDetails
                });
                fetchLogs();
            }
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi kết nối!',
                text: 'Không thể kết nối đến server.'
            });
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalHtml;
        });
    }

    document.querySelector('.btn-refresh-logs').addEventListener('click', fetchLogs);

    // Initial load
    document.addEventListener('DOMContentLoaded', fetchLogs);
</script>
@endpush
