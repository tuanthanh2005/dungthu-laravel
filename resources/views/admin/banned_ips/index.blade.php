@extends('layouts.admin')

@section('title', 'Quản lý Khóa IP')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">
                <i class="fas fa-user-slash text-danger me-2"></i>Quản lý Khóa IP
            </h1>
            <p class="text-muted small mb-0">Thêm, xem và mở khóa các địa chỉ IP bị ngắt kết nối khỏi website.</p>
        </div>
        <div>
            <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill">
                <i class="fas fa-ban me-1"></i> Tổng IP bị khóa: {{ $bannedIps->total() }}
            </span>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Form Thêm IP Khóa -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="card-title mb-0 fw-bold fs-6">
                        <i class="fas fa-shield-alt me-2"></i>Thêm IP Vào Danh Sách Cấm
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.banned-ips.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="ip_address" class="form-label fw-bold text-dark">Địa chỉ IP <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-network-wired"></i></span>
                                <input type="text" 
                                       class="form-field @error('ip_address') is-invalid @enderror" 
                                       id="ip_address" 
                                       name="ip_address" 
                                       placeholder="Ví dụ: 116.96.77.80" 
                                       value="{{ old('ip_address') }}" 
                                       required>
                            </div>
                            @error('ip_address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="duration" class="form-label fw-bold text-dark">Thời hạn khóa</label>
                            <select class="form-field" id="duration" name="duration">
                                <option value="permanent" {{ old('duration') == 'permanent' ? 'selected' : '' }}>Khóa vĩnh viễn</option>
                                <option value="1_day" {{ old('duration') == '1_day' ? 'selected' : '' }}>Khóa 24 Giờ (1 Ngày)</option>
                                <option value="7_days" {{ old('duration') == '7_days' ? 'selected' : '' }}>Khóa 7 Ngày</option>
                                <option value="30_days" {{ old('duration') == '30_days' ? 'selected' : '' }}>Khóa 30 Ngày</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label fw-bold text-dark">Lý do khóa</label>
                            <textarea class="form-field" 
                                      id="reason" 
                                      name="reason" 
                                      rows="3" 
                                      placeholder="Mô tả lý do (Ví dụ: Quét SQL Injection, spam request...)">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-danger w-100 py-2 fw-bold shadow-sm">
                            <i class="fas fa-lock me-1"></i> Khóa IP Ngay Tức Thì
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Danh sách IP bị khóa -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold fs-6 text-dark">
                        <i class="fas fa-list text-primary me-2"></i>Danh Sách IP Đang Bị Khóa
                    </h5>

                    <!-- Search form -->
                    <form action="{{ route('admin.banned-ips.index') }}" method="GET" class="d-flex gap-2">
                        <div class="input-group input-group-sm" style="width: 240px;">
                            <input type="text" 
                                   name="search" 
                                   class="form-field form-field-sm" 
                                   placeholder="Tìm kiếm IP, lý do..." 
                                   value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        @if(request('search'))
                            <a href="{{ route('admin.banned-ips.index') }}" class="btn btn-sm btn-light border" title="Xóa lọc">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 50px;">#</th>
                                    <th>Địa chỉ IP</th>
                                    <th>Lý do khóa</th>
                                    <th>Thời hạn</th>
                                    <th>Người tạo</th>
                                    <th>Ngày tạo</th>
                                    <th class="text-end pe-3">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bannedIps as $index => $item)
                                    <tr>
                                        <td class="ps-3 text-muted">{{ $bannedIps->firstItem() + $index }}</td>
                                        <td>
                                            <span class="font-monospace fw-bold text-danger fs-6">
                                                <i class="fas fa-ban text-danger me-1 small"></i>{{ $item->ip_address }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted small" title="{{ $item->reason }}">
                                                {{ Str::limit($item->reason ?: 'Chặn thủ công', 35) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(is_null($item->banned_until))
                                                <span class="badge bg-danger">Vĩnh viễn</span>
                                            @elseif($item->banned_until->isPast())
                                                <span class="badge bg-secondary">Đã hết hạn</span>
                                            @else
                                                <span class="badge bg-warning text-dark" title="{{ $item->banned_until->format('H:i d/m/Y') }}">
                                                    Hết hạn {{ $item->banned_until->diffForHumans() }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">{{ $item->banned_by ?: 'System' }}</td>
                                        <td class="small text-muted">{{ $item->created_at->format('H:i d/m/Y') }}</td>
                                        <td class="text-end pe-3">
                                            <form action="{{ route('admin.banned-ips.destroy', $item->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn bỏ khóa cho IP {{ $item->ip_address }} không?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-success px-2 py-1" title="Bỏ khóa IP">
                                                    <i class="fas fa-unlock me-1"></i> Bỏ khóa
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="fas fa-shield-alt fa-2x mb-2 d-block text-secondary"></i>
                                            Chưa có địa chỉ IP nào trong danh sách bị khóa.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($bannedIps->hasPages())
                    <div class="card-footer bg-white py-3">
                        {{ $bannedIps->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
