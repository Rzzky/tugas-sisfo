<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Sarana Prasarana</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .login-container::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url('https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&q=80&w=2070');
            background-size: cover;
            background-position: center;
            filter: blur(4px);
            z-index: -1;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="min-h-screen flex items-center justify-center login-container relative">
        <div class="bg-white/70 backdrop-blur-xl p-8 sm:p-10 rounded-2xl shadow-2xl w-full max-w-md">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center bg-indigo-600 rounded-full p-4 mb-4 shadow-lg">
                    <i class="fas fa-boxes-stacked text-white text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-slate-800">SISFO SARPRAS</h2>
                <p class="text-slate-500 mt-1">Silakan login untuk melanjutkan</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert"><p>{{ session('success') }}</p></div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert"><p>{{ session('error') }}</p></div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-envelope text-slate-400"></i></div>
                        <input type="email" name="email" id="email" class="bg-white/50 block w-full pl-10 pr-3 py-2.5 border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="user@example.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-lock text-slate-400"></i></div>
                        <input type="password" name="password" id="password" class="bg-white/50 block w-full pl-10 pr-3 py-2.5 border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Password" required>
                    </div>
                </div>
                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg transition-transform transform hover:scale-105">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3"><i class="fas fa-sign-in-alt text-indigo-300"></i></span>
                        Login
                    </button>
                </div>
            </form>
            <div class="mt-6 text-center">
                <p class="text-sm text-slate-600">Belum punya akun? <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Daftar sekarang</a></p>
            </div>
        </div>
    </div>
</body>
</html>