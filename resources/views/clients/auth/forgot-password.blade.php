@include('clients.blocks.header')

<div class="forgot-template">
    <div class="forgot-container">
        <div class="forgot-box">
            <h2>Quên mật khẩu</h2>
            <p>Nhập email để nhận liên kết đặt lại mật khẩu</p>

            {{-- Thông báo thành công --}}
            @if (session('status'))
                <div class="alert-success">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Thông báo lỗi --}}
            @if ($errors->any())
                <div class="alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('password.mail') }}" method="POST">
                @csrf

                <div class="form-groupp">
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    <label>Email</label>
                </div>

                <button type="submit">Gửi liên kết đặt lại mật khẩu</button>
            </form>

            <div class="back-login">
                <a href="{{ route('login') }}">← Quay lại đăng nhập</a>
            </div>
        </div>
    </div>
</div>

@include('clients.blocks.footer')