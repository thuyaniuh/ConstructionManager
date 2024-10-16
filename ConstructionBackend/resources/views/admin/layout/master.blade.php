<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    @include('admin.layout.styles')
</head>

<body>
    <!-- Sidebar -->
    <div id="sidebar" class="bg-dark position-fixed h-100" style="width: 250px;">
        <div class="text-white p-3">
            <h3>AdminLTE</h3>
        </div>
        @include('admin.layout.navbar')
    </div>
    <!-- Main Content -->
    <div id="main-content" class="transition">
        <nav class="navbar navbar-light bg-dark px-3">
            <button class="btn btn-dark" id="toggleSidebar"><i class="fas fa-bars"></i></button>
            <a href="#" class="text-white me-3 nav-link">Home</a>
            <div class="ms-auto">
                <div class="dropdown d-inline">
                    <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Account
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#">Change Password</a></li>
                        <li><a class="dropdown-item" href="#">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-5">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('hide');
            document.getElementById('main-content').classList.toggle('expanded');
        });
    </script>
</body>

</html>