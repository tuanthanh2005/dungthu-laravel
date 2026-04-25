@extends('layouts.admin')

@section('title', 'Quản Lý Dịch Vụ - Admin')

@push('styles')
<style>
    .container-admin {
        padding: 2rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h1 {
        font-size: 1.8rem;
        font-weight: 800;
        color: #2d3436;
    }

    .btn-primary {
        background: #6c5ce7;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: #5f4ec7;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .service-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem;
        transition: all 0.2s ease;
    }

    .service-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #6c5ce7;
    }

    .service-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .service-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3436;
        margin-bottom: 0.5rem;
    }

    .service-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        color: #666;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
    }

    .badge {
        display: inline-block;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .badge-success {
        background: #d1e7dd;
        color: #0f5132;
    }

    .badge-danger {
        background: #f8d7da;
        color: #842029;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        border-radius: 4px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        flex: 1;
        text-align: center;
    }

    .btn-edit {
        background: #0dcaf0;
        color: white;
    }

    .btn-edit:hover {
        background: #0ac8e8;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 8px;
        border: 1px dashed #e0e0e0;
    }
</style>
@endpush

@section('content')
<div class="container-admin">
    <div class="page-header">
        <h1>🎯 Quản Lý Dịch Vụ</h1>
        <a href="{{ route('admin.buff.services.create') }}" class="btn-primary">
            + Thêm Dịch Vụ
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($services->count() > 0)
        <div class="services-grid">
            @foreach($services as $service)
                <div class="service-card">
                    <div class="service-icon">
                        <i class="{{ $service->getIcon() }}"></i>
                    </div>
                    <div class="service-name">{{ $service->name }}</div>
                    <div class="service-info">
                        <div class="info-row">
                            <span>Nền tảng:</span>
                            <strong>{{ ucfirst($service->platform) }}</strong>
                        </div>
                        <div class="info-row">
                            <span>Loại:</span>
                            <strong>{{ ucfirst($service->service_type) }}</strong>
                        </div>
                        <div class="info-row">
                            <span>Giá/đơn vị:</span>
                            <strong>{{ number_format($service->price_per_unit, 0, ',', '.') }}đ</strong>
                        </div>
                        <div class="info-row">
                            <span>Min-Max:</span>
                            <strong>{{ number_format($service->min_amount) }} - {{ number_format($service->max_amount) }}</strong>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        @if($service->is_active)
                            <span class="badge badge-success">✓ Hoạt động</span>
                        @else
                            <span class="badge badge-danger">✗ Không hoạt động</span>
                        @endif
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('admin.buff.services.edit', $service) }}" class="btn-sm btn-edit">
                            ✎ Sửa
                        </a>
                        <form method="POST" 
                            action="{{ route('admin.buff.services.destroy', $service) }}"
                            style="flex: 1;"
                            onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm btn-delete" style="width: 100%; margin: 0;">
                                🗑 Xóa
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if($services->hasPages())
            <div class="d-flex justify-content-center">
                {{ $services->links('pagination::bootstrap-4') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <h3>Chưa có dịch vụ nào</h3>
            <p>Hãy thêm dịch vụ đầu tiên để bắt đầu!</p>
            <a href="{{ route('admin.buff.services.create') }}" class="btn-primary" style="margin-top: 1rem;">
                + Tạo Dịch Vụ
            </a>
        </div>
    @endif
</div>
@endsection
