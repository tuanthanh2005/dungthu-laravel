@extends('layouts.admin')

@section('title', 'Quản Lý Servers - Admin')

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
        color: white;
    }

    .servers-table {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
    }

    .table {
        margin: 0;
        width: 100%;
    }

    .table thead {
        background: #f8f9fa;
    }

    .table th {
        padding: 1rem;
        font-weight: 700;
        color: #666;
        border-bottom: 2px solid #e0e0e0;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #e0e0e0;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f8f9fa;
    }

    .badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-success {
        background: #d1e7dd;
        color: #0f5132;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
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

    .pagination {
        margin-top: 2rem;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="container-admin">
    <div class="page-header">
        <h1>🖥️ Quản Lý Servers</h1>
        <a href="{{ route('admin.buff.servers.create') }}" class="btn-primary">
            + Thêm Server
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($servers->count() > 0)
        <div class="servers-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tên Server</th>
                        <th>Mô Tả</th>
                        <th>Trạng Thái</th>
                        <th>Bảo Trì</th>
                        <th style="width: 150px;">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servers as $server)
                        <tr>
                            <td><strong>{{ $server->name }}</strong></td>
                            <td>{{ $server->description ?? '-' }}</td>
                            <td>
                                @if($server->is_active)
                                    <span class="badge badge-success">✓ Hoạt động</span>
                                @else
                                    <span class="badge badge-danger">✗ Không hoạt động</span>
                                @endif
                            </td>
                            <td>
                                @if($server->is_maintenance)
                                    <span class="badge badge-warning">🔧 Bảo trì</span>
                                @else
                                    <span class="badge badge-success">✓ Sẵn sàng</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.buff.servers.edit', $server) }}" class="btn-sm btn-edit">
                                        ✎ Sửa
                                    </a>
                                    <form method="POST" 
                                        action="{{ route('admin.buff.servers.destroy', $server) }}"
                                        style="display: inline;"
                                        onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-delete">
                                            🗑 Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($servers->hasPages())
            <div class="d-flex justify-content-center">
                {{ $servers->links('pagination::bootstrap-4') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <h3>Chưa có server nào</h3>
            <p>Hãy thêm server đầu tiên để bắt đầu!</p>
            <a href="{{ route('admin.buff.servers.create') }}" class="btn-primary" style="margin-top: 1rem;">
                + Tạo Server
            </a>
        </div>
    @endif
</div>
@endsection
