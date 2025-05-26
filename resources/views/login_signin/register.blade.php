<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <title>Đăng Ký</title>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <h1>HUCE STORAGE</h1>
            <div class="image-area">
                <img src="https://diginet.com.vn/wp-content/uploads/2020/10/5-cach-quan-ly-kho-hang-hieu-qua-cho-doanh-nghiep-vua-va-lon-1-min.jpg" alt="Jars and coins illustration">
            </div>
        </div>
        <div class="right-panel">
            <div class="box-login">
                <h1>Đăng ký</h1>
                <form class="login-form" action="{{ route('register') }}" method="POST">
                    @csrf
                    @if (session('success'))
                    <div class="alert alert-success">
                    {{ session('success') }}
                    </div>
                    @endif
                    <label for="username">Tên</label>
                    <input type="username" id="username" name="username" placeholder="Nhập tên của bạn" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Nhập địa chỉ email" required>
                
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                
                    <label for="password_confirmation">Nhập Lại Mật khẩu</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu" required>
                
                
                    <label for="phone">Số điện thoại</label>
                    <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại của bạn" required>  
                
                    <button type="submit">Đăng ký</button>
                    <span style="margin:10px auto;">Bạn đã có tài khoản? <a href="/login">Đăng nhập ngay</a></span>
                </form>
            </div>
        </div>
    </div>
</body>
</html>