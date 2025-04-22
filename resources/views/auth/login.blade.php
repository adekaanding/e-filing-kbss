<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'e-Filing KBSS') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-primary">e-Filing KBSS</h1>
                    <p class="text-gray-600 mt-2">Login to access the file management system</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-6">
                        <label for="name" class="form-label">Username</label>
                        <input id="name" type="text" class="form-input" name="name" required autofocus />
                    </div>

                    <div class="mb-6">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-input" name="password" required />
                    </div>

                    <div>
                        <button type="submit" class="w-full btn btn-primary">
                            Log in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>