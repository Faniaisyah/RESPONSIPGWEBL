<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }
        #map {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: left;
            z-index: 1;
            position: relative;
        }

        .form-container img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
            max-width: 100px;
            height: auto;
            border-radius: 10px 10px 0 0;
            object-fit: cover;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container .input-group {
            margin-bottom: 15px;
        }
        .form-container .input-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-container .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container .input-group input:focus {
            border-color: #5b9bd5;
            outline: none;
        }
        .form-container .remember-me {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .form-container .remember-me input {
            margin-right: 10px;
        }
        .form-container .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }
        .form-container .forgot-password a {
            color: #007bff;
            text-decoration: none;
        }
        .form-container .forgot-password a:hover {
            text-decoration: underline;
        }
        .form-container .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container .submit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div id="map"></div>
    <div class="form-container">
        <img src="{{ asset('storage/images/mmm2.jpg') }}" alt="Login Image">
        <h2>Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="input-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="input-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="remember-me">
                <label for="remember_me">
                    <input id="remember_me" type="checkbox" name="remember">
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="submit-btn">Log in</button>
        </form>
    </div>

    <!-- Leaflet Map Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').setView([51.505, -0.09], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
        });
    </script>
</body>
</html>
