@extends('layouts.admin')

@section('title', 'Quản Lý Giá - Admin')

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
    }

    .page-header h1 {
        font-size: 1.8rem;
        font-weight: 800;
        color: #2d3436;
        margin: 0;
    }

    .btn-create {
        background: #6c5ce7;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s ease;
    }

    .btn-create:hover {
        background: #5f4ec7;
    }

    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 1rem 1.5rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #28a745;
    }

    .success-message button {
        float: right;
        background: none;
        border: none;
        color: #155724;
        cursor: pointer;
        font-size: 1.5rem;
        line-height: 1;
    }

    .table-responsive {
        overflow-x: auto;
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    thead {
        background: #f5f5f5;
    }

    th {
        padding: 1rem;
        text-align: left;
        font-weight: 700;
        color: #2d3436;
        border-bottom: 2px solid #e0e0e0;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #e0e0e0;
    }

    tbody tr:hover {
        background: #fafafa;
    }

    .service-name {
        font-weight: 600;
        color: #2d3436;
    }

    .server-name {
        color: #636e72;
    }

    .price-value {
        font-weight: 700;
        color: #00b894;
        font-size: 1.1rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-edit {
        background: #a29bfe;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .btn-edit:hover {
        background: #6c5ce7;
    }

    .btn-delete {
        background: #ff7675;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .btn-delete:hover {
        background: #d63031;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #636e72;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .empty-state p {
        margin: 0.5rem 0;
        font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .container-admin {
            padding: 1rem;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .btn-create {
            width: 100%;
            text-align: center;
        }

        th, td {
            padding: 0.75rem;
            font-size: 0.9rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-edit, .btn-delete {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-admin">
    <div class="page-header">
        <h1>💰 Quản Lý Giá</h1>
        <a href="{{ route('admin.buff.prices.create') }}" class="btn-create">
            + Thêm Giá Mới
        </a>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
            <button onclick="this.parentElement.style.display='none';">×</button>
        </div>
    @endif

    @if($prices->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Dịch Vụ</th>
                        <th>Server</th>
                        <th>Giá (đ)</th>
                        <th>Trạng Thái</th>
                        <th style="width: 150px;">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prices as $price)
                        <tr>
                            <td>
                                <div class="service-name">{{ $price->buffService->name }}</div>
                                <div style="font-size: 0.85rem; color: #636e72;">
                                    {{ $price->buffService->platform }} • {{ $price->buffService->service_type }}
                                </div>
                            </td>
                            <td>
                                <div class="server-name">{{ $price->buffServer->name }}</div>
                            </td>
                            <td>
                                <div class="price-value">{{ number_format($price->price) }}</div>
                            </td>
                            <td>
                                @if($price->buffService->is_active && $price->buffServer->is_active)
                                    <span style="background: #d4edda; color: #155724; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem;">
                                        ✓ Hoạt động
                                    </span>
                                @else
                                    <span style="background: #f8d7da; color: #721c24; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem;">
                                        ✗ Bị vô hiệu
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.buff.prices.edit', $price) }}" class="btn-edit">
                                        ✏️ Sửa
                                    </a>
                                    <form method="POST" action="{{ route('admin.buff.prices.destroy', $price) }}"
                                        style="display: inline;"
                                        onsubmit="return confirm('Xóa giá này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">
                                            🗑️ Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($prices->hasPages())
            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                {{ $prices->links() }}
            </div>
        @endif
    @else
        <div style="background: white; border: 1px solid #e0e0e0; border-radius: 8px;">
            <div class="empty-state">
                <div class="empty-state-icon">💸</div>
                <p style="font-weight: 600; color: #2d3436;">Chưa có giá nào</p>
                <p>Thêm giá mới cho dịch vụ của bạn</p>
                <a href="{{ route('admin.buff.prices.create') }}" class="btn-create" style="margin-top: 1rem;">
                    + Thêm Giá Mới
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
