@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng - Admin')

@push('styles')
<style>
    .container-admin {
        padding: 2rem;
        max-width: 1000px;
        margin: 0 auto;
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
        margin: 0;
    }

    .order-code-main {
        font-family: monospace;
        font-size: 1.2rem;
        color: #6c5ce7;
        font-weight: 700;
    }

    .content-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .box {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem;
    }

    .box-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3436;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e0e0e0;
    }

    .info-item {
        display: grid;
        grid-template-columns: 150px 1fr;
        margin-bottom: 1rem;
        gap: 1rem;
    }

    .info-label {
        font-weight: 600;
        color: #2d3436;
    }

    .info-value {
        color: #636e72;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 0.75rem;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-paid {
        background: #cfe2ff;
        color: #084298;
    }

    .status-processing {
        background: #e7d4f5;
        color: #6c5ce7;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .price-highlight {
        font-weight: 700;
        color: #00b894;
        font-size: 1.1rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2d3436;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #6c5ce7;
        box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1);
        outline: none;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
        font-family: 'Courier New', monospace;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .form-group.has-error .form-control {
        border-color: #dc3545;
    }

    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: #6c5ce7;
        color: white;
        flex: 1;
    }

    .btn-primary:hover {
        background: #5f4ec7;
    }

    .btn-secondary {
        background: #e0e0e0;
        color: #2d3436;
        flex: 1;
    }

    .btn-secondary:hover {
        background: #d0d0d0;
    }

    .timeline {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .timeline-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .timeline-dot {
        width: 30px;
        height: 30px;
        background: #6c5ce7;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        flex-shrink: 0;
        margin-top: 0.25rem;
    }

    .timeline-dot.inactive {
        background: #ddd;
        color: #999;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-title {
        font-weight: 600;
        color: #2d3436;
    }

    .timeline-date {
        font-size: 0.85rem;
        color: #636e72;
    }

    .section-full {
        grid-column: 1 / -1;
    }

    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 1rem 1.5rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #28a745;
    }

    @media (max-width: 768px) {
        .container-admin {
            padding: 1rem;
        }

        .content-layout {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .info-item {
            grid-template-columns: 1fr;
        }

        .button-group {
            flex-direction: column;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="container-admin">
    <div class="page-header">
        <div>
            <h1>📝 Chi Tiết Đơn Hàng</h1>
            <div class="order-code-main">#{{ $buffOrder->order_code }}</div>
        </div>
        <a href="{{ route('admin.buff.orders.index') }}" class="btn btn-secondary" style="width: auto; flex: 0;">
            ← Quay Lại
        </a>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <div class="content-layout">
        <!-- Thông tin đơn hàng -->
        <div class="box">
            <div class="box-title">📋 Thông Tin Đơn Hàng</div>

            <div class="info-item">
                <div class="info-label">Mã Đơn:</div>
                <div class="info-value" style="font-family: monospace; font-weight: 600;">#{{ $buffOrder->order_code }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Trạng Thái:</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $buffOrder->status }}">
                        @switch($buffOrder->status)
                            @case('pending')
                                ⏳ Chờ Thanh Toán
                                @break
                            @case('paid')
                                ✓ Đã Thanh Toán
                                @break
                            @case('processing')
                                🔄 Đang Xử Lý
                                @break
                            @case('completed')
                                ✓ Hoàn Thành
                                @break
                            @case('cancelled')
                                ✗ Hủy
                                @break
                        @endswitch
                    </span>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Ngày Tạo:</div>
                <div class="info-value">{{ $buffOrder->created_at->format('d/m/Y H:i:s') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Ngày Thanh Toán:</div>
                <div class="info-value">{{ $buffOrder->paid_at ? $buffOrder->paid_at->format('d/m/Y H:i:s') : '---' }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Ngày Bắt Đầu:</div>
                <div class="info-value">{{ $buffOrder->started_at ? $buffOrder->started_at->format('d/m/Y H:i:s') : '---' }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Ngày Hoàn Thành:</div>
                <div class="info-value">{{ $buffOrder->completed_at ? $buffOrder->completed_at->format('d/m/Y H:i:s') : '---' }}</div>
            </div>
        </div>

        <!-- Thông tin dịch vụ -->
        <div class="box">
            <div class="box-title">🎯 Thông Tin Dịch Vụ</div>

            <div class="info-item">
                <div class="info-label">Dịch Vụ:</div>
                <div class="info-value">{{ $buffOrder->buffService->name }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Nền Tảng:</div>
                <div class="info-value">{{ ucfirst($buffOrder->buffService->platform) }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Loại:</div>
                <div class="info-value">{{ ucfirst($buffOrder->buffService->service_type) }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Server:</div>
                <div class="info-value">{{ $buffOrder->buffServer->name }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Số Lượng:</div>
                <div class="info-value">{{ $buffOrder->quantity }}</div>
            </div>

            @if($buffOrder->emotion_type)
                <div class="info-item">
                    <div class="info-label">Cảm Xúc:</div>
                    <div class="info-value" style="font-size: 1.5rem;">{{ $buffOrder->emotion_type }}</div>
                </div>
            @endif

            <div class="info-item">
                <div class="info-label">Link:</div>
                <div class="info-value">
                    <a href="{{ $buffOrder->social_link }}" target="_blank" style="color: #6c5ce7; word-break: break-all;">
                        {{ $buffOrder->social_link }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="box">
            <div class="box-title">👤 Thông Tin Khách Hàng</div>

            <div class="info-item">
                <div class="info-label">Tên:</div>
                <div class="info-value">{{ $buffOrder->user->name }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $buffOrder->user->email }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Tham Gia:</div>
                <div class="info-value">{{ $buffOrder->user->created_at->format('d/m/Y') }}</div>
            </div>
        </div>

        <!-- Giá tiền -->
        <div class="box">
            <div class="box-title">💰 Thông Tin Giá</div>

            <div class="info-item">
                <div class="info-label">Giá Cơ Bản:</div>
                <div class="info-value">{{ number_format($buffOrder->base_price) }} đ</div>
            </div>

            <div class="info-item">
                <div class="info-label">Giá/Đơn Vị:</div>
                <div class="info-value">{{ number_format($buffOrder->unit_price) }} đ</div>
            </div>

            <div class="info-item">
                <div class="info-label">Phí Dịch Vụ:</div>
                <div class="info-value">{{ $buffOrder->fee_amount ? number_format($buffOrder->fee_amount) : 0 }} đ</div>
            </div>

            <hr style="margin: 1rem 0; border: none; border-top: 1px solid #e0e0e0;">

            <div class="info-item">
                <div class="info-label">Tổng Tiền:</div>
                <div class="price-highlight">{{ number_format($buffOrder->total_price) }} đ</div>
            </div>
        </div>

        <!-- Biểu đồ tiến độ -->
        <div class="box section-full">
            <div class="box-title">📈 Tiến Độ Đơn Hàng</div>

            <ul class="timeline">
                <li class="timeline-item">
                    <div class="timeline-dot">1</div>
                    <div class="timeline-content">
                        <div class="timeline-title">Tạo Đơn Hàng</div>
                        <div class="timeline-date">{{ $buffOrder->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                </li>

                <li class="timeline-item">
                    <div class="timeline-dot @if(!$buffOrder->paid_at) inactive @endif">2</div>
                    <div class="timeline-content">
                        <div class="timeline-title">Thanh Toán</div>
                        <div class="timeline-date">
                            @if($buffOrder->paid_at)
                                {{ $buffOrder->paid_at->format('d/m/Y H:i:s') }}
                            @else
                                ---
                            @endif
                        </div>
                    </div>
                </li>

                <li class="timeline-item">
                    <div class="timeline-dot @if(!$buffOrder->started_at) inactive @endif">3</div>
                    <div class="timeline-content">
                        <div class="timeline-title">Bắt Đầu Xử Lý</div>
                        <div class="timeline-date">
                            @if($buffOrder->started_at)
                                {{ $buffOrder->started_at->format('d/m/Y H:i:s') }}
                            @else
                                ---
                            @endif
                        </div>
                    </div>
                </li>

                <li class="timeline-item">
                    <div class="timeline-dot @if(!$buffOrder->completed_at) inactive @endif">4</div>
                    <div class="timeline-content">
                        <div class="timeline-title">Hoàn Thành</div>
                        <div class="timeline-date">
                            @if($buffOrder->completed_at)
                                {{ $buffOrder->completed_at->format('d/m/Y H:i:s') }}
                            @else
                                ---
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Form cập nhật -->
        <div class="box section-full">
            <div class="box-title">✏️ Cập Nhật Đơn Hàng</div>

            <form method="POST" action="{{ route('admin.buff.orders.update', $buffOrder) }}">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div>
                        <div class="form-group @error('status') has-error @enderror">
                            <label class="form-label">Trạng Thái</label>
                            <select name="status" class="form-control" required>
                                <option value="pending" @selected($buffOrder->status === 'pending')>⏳ Chờ Thanh Toán</option>
                                <option value="paid" @selected($buffOrder->status === 'paid')>✓ Đã Thanh Toán</option>
                                <option value="processing" @selected($buffOrder->status === 'processing')>🔄 Đang Xử Lý</option>
                                <option value="completed" @selected($buffOrder->status === 'completed')>✓ Hoàn Thành</option>
                                <option value="cancelled" @selected($buffOrder->status === 'cancelled')>✗ Hủy</option>
                            </select>
                            @error('status')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group @error('actual_price') has-error @enderror">
                            <label class="form-label">Giá Thực Tế (đ)</label>
                            <input type="number" name="actual_price" class="form-control"
                                step="1" min="0"
                                placeholder="Để trống để giữ nguyên"
                                value="{{ $buffOrder->actual_price }}" />
                            @error('actual_price')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label class="form-label">Ngày Bắt Đầu</label>
                            <input type="datetime-local" name="started_at" class="form-control"
                                value="{{ $buffOrder->started_at ? $buffOrder->started_at->format('Y-m-d\TH:i') : '' }}" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ngày Hoàn Thành</label>
                            <input type="datetime-local" name="completed_at" class="form-control"
                                value="{{ $buffOrder->completed_at ? $buffOrder->completed_at->format('Y-m-d\TH:i') : '' }}" />
                        </div>
                    </div>
                </div>

                <div class="form-group @error('admin_notes') has-error @enderror">
                    <label class="form-label">Ghi Chú của Admin</label>
                    <textarea name="admin_notes" class="form-control"
                        placeholder="Ghi chú về đơn hàng...">{{ $buffOrder->admin_notes }}</textarea>
                    @error('admin_notes')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group @error('admin_pin') has-error @enderror">
                    <label class="form-label">Mã Xác Nhận <span style="color: #dc3545;">*</span></label>
                    <input type="text" name="admin_pin" class="form-control"
                        placeholder="Nhập 3 chữ số xác nhận"
                        maxlength="3" pattern="\d{3}" required
                        style="font-size: 1.2rem; letter-spacing: 0.5rem; text-align: center;">
                    @error('admin_pin')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        ✓ Cập Nhật
                    </button>
                    <a href="{{ route('admin.buff.orders.index') }}" class="btn btn-secondary">
                        ← Quay Lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
