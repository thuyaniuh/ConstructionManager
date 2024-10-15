<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title','Admin')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{asset('style.css')}}" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <header class="container-fluid p-3 bg-primary text-white text-center">
        <div class="row align-items-center">
            <div class="col-md-1 text-left">
                <!-- Nút để mở navbar -->
                <button class="btn btn-light" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="col-md-9 text-left">
                <h3>ADMIN DASHBOARD</h3>
            </div>
            <div class="col-md-2 text-right">
                <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">Tài khoản</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Chỉnh sửa thông tin</a></li>
                    <li><a class="dropdown-item" href="#" onclick="logout()">Đăng xuất</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Offcanvas Sidebar (Navbar) -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Quản lí</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <!-- Navbar được include ở đây -->
            @include('admin.layout.navbar')
        </div>
    </div>

    <!-- Main content -->
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-12">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>