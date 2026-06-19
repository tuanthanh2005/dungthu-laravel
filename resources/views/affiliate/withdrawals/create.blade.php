@extends('layouts.app')

@section('title', __('Tạo yêu cầu rút tiền') . ' | DungThu')

@push('styles')
    <style>
        .aff-wrapper {
            background-color: #f8fafc;
            min-height: 100vh;
            padding-top: 100px;
            padding-bottom: 50px;
        }

        .content-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-label {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            color: #1e293b;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            border-color: #6366f1;
        }

        .balance-summary {
            background: #fef2f2;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #fee2e2;
            margin-bottom: 25px;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <div class="aff-wrapper">
        <div class="container">
            <div class="content-card">
                <h4 class="fw-bold mb-4 text-center">{{ __('Yêu cầu rút tiền') }}</h4>

                <div class="balance-summary">
                    <div class="text-danger small fw-bold text-uppercase mb-1">{{ __('Số dư hiện tại') }}</div>
                    <h3 class="fw-bold text-danger mb-0">{{ app()->getLocale() === 'en' ? '$' . number_format($affiliate->balance / \App\Models\SiteSetting::getValue('usd_exchange_rate', 25000), 2) : number_format($affiliate->balance, 0, ',', '.') . 'đ' }}</h3>
                </div>

                <form action="{{ route('affiliate.withdrawals.store') }}" method="POST">
                    @csrf
                    
                    @if($errors->any())
                        <div class="alert alert-danger mb-4" style="border-radius: 12px;">
                            <ul class="mb-0" style="padding-left: 15px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label">{{ __('Số tiền muốn rút') }} ({{ app()->getLocale() === 'en' ? 'USD' : 'VNĐ' }})</label>
                        <input type="number" name="amount" class="form-control" placeholder="{{ app()->getLocale() === 'en' ? 'Min $2.00' : 'Tối thiểu 50.000đ' }}" required
                            min="50000" max="{{ $affiliate->balance }}" value="{{ old('amount') }}">
                        <div class="form-text small">{{ __('Số tiền rút phải nhỏ hơn hoặc bằng số dư hiện tại.') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-4">
                            <label class="form-label">{{ __('Tên ngân hàng') }}</label>
                            <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $affiliate->bank_name) }}"
                                placeholder="{{ __('Ví dụ: Vietcombank') }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">{{ __('Số tài khoản') }}</label>
                            <input type="text" name="bank_account_number" class="form-control"
                                value="{{ old('bank_account_number', $affiliate->bank_account_number) }}" placeholder="{{ __('Nhập số tài khoản...') }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">{{ __('Họ tên chủ tài khoản') }}</label>
                            <input type="text" name="bank_account_name" class="form-control"
                                value="{{ old('bank_account_name', $affiliate->bank_account_name) }}" placeholder="{{ __('Ví dụ: NGUYEN VAN A') }}" required
                                style="text-transform: uppercase;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">{{ __('Ghi chú (nếu có)') }}</label>
                        <textarea name="note" class="form-control" rows="2"
                            placeholder="{{ __('Thông nhắn nhủ Admin...') }}">{{ old('note') }}</textarea>
                    </div>

                    <div class="alert alert-info py-2" style="border-radius: 10px; font-size: 13px;">
                        <i class="fas fa-info-circle me-1"></i> {{ __('Admin sẽ Verify & Chuyển tiền trong vòng 24h làm việc hoặc liên hệ trực tiếp.') }}
                    </div>

                    <button type="submit" class="btn btn-danger w-100 py-3 fw-bold"
                        style="border-radius: 12px; background: #ef4444;">
                        {{ __('Gửi yêu cầu rút tiền') }}
                    </button>

                    <a href="{{ route('affiliate.withdrawals') }}"
                        class="btn btn-link w-100 mt-2 text-muted text-decoration-none">
                        {{ __('Hủy thao tác') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection