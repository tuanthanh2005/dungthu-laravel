@extends('layouts.app')

@section('title', 'Quản lý Proxy - Admin')

@push('styles')
<style>
    .admin-wrapper {
        padding: 40px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin-top: 70px;
    }

    .admin-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">
                    <i class="fas fa-network-wired text-primary me-2"></i>Quản lý Proxy
                </h4>
                <a href="{{ route('admin.proxies.create') }}" class="btn btn-primary rounded-pill">
                    <i class="fas fa-plus me-2"></i>Thêm Proxy
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tên gói</th>
                            <th>IP:Port</th>
                            <th>Giao thức</th>
                            <th>Quốc gia</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proxies as $proxy)
                            <tr>
                                <td><span class="fw-bold">{{ $proxy->name }}</span></td>
                                <td>{{ $proxy->ip }}:{{ $proxy->port }}</td>
                                <td><span class="badge bg-secondary">{{ $proxy->protocol }}</span></td>
                                <td>{{ $proxy->location }}</td>
                                <td>{{ number_format($proxy->price) }}đ</td>
                                <td>
                                    @if($proxy->is_active)
                                        <span class="badge bg-success">Hoạt động</span>
                                    @else
                                        <span class="badge bg-danger">Ngừng bán</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.proxies.edit', $proxy) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.proxies.destroy', $proxy) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Chưa có proxy nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $proxies->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
