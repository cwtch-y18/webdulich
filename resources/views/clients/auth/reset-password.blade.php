@include('clients.blocks.header')

<div class="forgot-template">
    <div class="forgot-container">
        <div class="forgot-box">
            <h2>Đặt lại mật khẩu</h2>
            <p>Vui lòng nhập mật khẩu mới cho tài khoản của bạn</p>

            {{-- Thông báo thành công --}}
            @if (session('status'))
                <div class="alert-success">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Thông báo lỗi từ server --}}
            @if ($errors->any())
                <div class="alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" id="reset_password">
                @csrf

                {{-- Token --}}
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email --}}
                <div class="form-groupp">
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ $email ?? old('email') }}"
                           required>
                    <label>Email</label>
                    <small class="error-text" id="error-email"></small>
                </div>

                {{-- Password --}}
                <div class="form-groupp">
                    <input type="password"
                           id="password"
                           name="password"
                           required>
                    <label>Mật khẩu mới</label>
                    <small class="error-text" id="error-password"></small>
                </div>

                {{-- Confirm password --}}
                <div class="form-groupp">
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           required>
                    <label>Xác nhận mật khẩu</label>
                    <small class="error-text" id="error-password-confirm"></small>
                </div>

                <button type="submit">Cập nhật mật khẩu</button>
            </form>

            <div class="back-login">
                <a href="{{ route('login') }}">← Quay lại đăng nhập</a>
            </div>
        </div>
    </div>
</div>
@include('clients.blocks.footer')