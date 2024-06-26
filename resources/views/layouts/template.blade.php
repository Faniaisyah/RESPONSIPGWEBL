<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .navbar-blue {
            background-color: #244f7c !important;
        }
        .navbar-blue .navbar-brand,
        .navbar-blue .nav-link,
        .navbar-blue .dropdown-item {
            color: white !important;
        }
        .navbar-blue .nav-link:hover,
        .navbar-blue .dropdown-item:hover {
            color: #d1ecf1 !important;
        }
        .navbar-blue .nav-link.nav-link-login {
            color: rgb(244, 10, 166) !important;
        }
        .navbar-blue .nav-link.nav-link-logout {
            color: rgb(214, 61, 26) !important;
        }
        .navbar-blue .nav-link.nav-link-dashboard {
            color: white !important;
        }

        /* Styling for dropdown items */
        .dropdown-item {
            color: black !important;
            font-weight: normal !important;
            background-color: #506881 !important; /* Menambahkan latar belakang putih */
        }
        .dropdown-item:hover {
            background-color: #506881 !important;
            color: #506881 !important;
        }
        .dropdown-item i {
            margin-right: 8px;
        }

        html,
        body,
        #map {
            height: 100%;
            width: 100%;
            margin: 0;
        }

        #map {
            height: calc(100vh - 56px);
            width: 100%;
            margin: 0;
        }

        .dropdown-menu {
            background-color: #506881 !important;
            border: 2px solid #18515b !important;
        }
        .dropdown-item {
            color: black !important;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa !important;
            color: #506881 !important;
        }
    </style>

    @yield('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-blue">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fa-solid fa-person-military-rifle"></i> {{ $title }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('index') }}"><i class="fa-solid fa-house-fire"></i> Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-hourglass-end"></i> Output </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('output-point') }}"><i class="fa-solid fa-location-dot"></i> Point</a></li>
                            <li><a class="dropdown-item" href="{{ route('output-polyline') }}"><i class="fa-solid fa-grip-lines-vertical"></i> Polyline</a></li>
                            <li><a class="dropdown-item" href="{{ route('output-polygon') }}"><i class="fa-solid fa-vector-square"></i> Polygon</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#infoModal"><i class="fa-solid fa-info"></i> Info</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-table"></i> Table </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('table-point') }}"><i class="fa-solid fa-table"></i> Table Point</a></li>
                            <li><a class="dropdown-item" href="{{ route('table-polyline') }}"><i class="fa-solid fa-table"></i> Table Polyline</a></li>
                            <li><a class="dropdown-item" href="{{ route('table-polygon') }}"><i class="fa-solid fa-table"></i> Table Polygon</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#infoModal"><i class="fa-solid fa-info"></i> Info</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#infoModal"><i class="fa-solid fa-info"></i> Info</a>
                    </li>

                    @if (Auth::check())
                    <li class="nav-item">
                        <a class="nav-link nav-link-dashboard" href="{{ route('dashboard') }}"><i class="fa-solid fa-user"></i> Dashboard</a>
                    </li>

                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button class="nav-link nav-link-logout" type="submit"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                    </form>
                    @else
                    <li class="nav-item">
                        <a class="nav-link nav-link-login" href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">INFO</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Fani Aisyah - 22/493988/SV/20769
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @yield('content')

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    {{-- jQuery JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    @include('components.toast')

    @yield('script')
</body>

</html>
