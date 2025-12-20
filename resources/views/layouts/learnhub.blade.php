@php
    // Dummy user sementara utk dev/testing
    if (!isset($user)) {
        if (request()->is('admin/*')) {
            $user = (object)[
                'type' => 'admin',
                'name' => 'Dummy Admin'
            ];
        } else {
            $user = (object)[
                'type' => 'teacher',
                'name' => 'Dummy Teacher'
            ];
        }
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnHub</title>

    <!-- ✅ BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS + Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

<style>
body.modal-open {
    overflow: auto !important; /* pastikan body boleh scroll */
    padding-right: 0 !important; /* hilangkan padding tambahan Bootstrap */
}
</style>


</head>

<body class="bg-gray-100 font-sans">
<div class="flex min-h-screen">

 {{-- Sidebar --}}
<aside class="w-[250px] h-screen 
    {{ $user && strtolower($user->type) === 'admin' ? 'bg-teal-900' 
       : (strtolower($user->type) === 'teacher' ? 'bg-[#1E293B]' 
       : 'bg-[#2E2B5F]') }} 
    text-gray-300 flex flex-col py-8 px-6 fixed top-0 left-0">


        {{-- BRAND --}}
        <div class="mb-16 px-3 flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v7" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 17l-6 4-6-4" />
            </svg>
            <span class="text-white font-extrabold text-2xl">LearnHub</span>
        </div>

        {{-- NAV --}}
        <nav class="flex flex-col space-y-2">

            {{-- Dashboard --}}
            <a href="{{ route('module1.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
               {{ request()->routeIs('module1.*')
                    ? (strtolower($user->type) === 'admin' ? 'bg-teal-600 text-white font-bold' : 'bg-[#2563EB] text-white font-bold')
                    : 'hover:bg-[#334155]' }}">
                <span class="text-lg">🔑</span>
                <span>Dashboard</span>
            </a>

            {{-- Manage Courses --}}
            <a href="{{ strtolower($user->type) === 'admin' ? route('module2.admin') : route('module2.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
               {{ request()->routeIs('module2.*')
                    ? (strtolower($user->type) === 'admin' ? 'bg-teal-600 text-white font-bold' : 'bg-[#2563EB] text-white font-bold')
                    : 'hover:bg-[#334155]' }}">
                <span class="text-lg">📚</span>
                <span>Manage Courses</span>
            </a>

            {{-- Content --}}
            <a href="{{ route('module3.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
               {{ request()->routeIs('module3.*')
                    ? (strtolower($user->type) === 'admin' ? 'bg-teal-600 text-white font-bold' : 'bg-[#2563EB] text-white font-bold')
                    : 'hover:bg-[#334155]' }}">
                <span class="text-lg">📄</span>
                <span>Content</span>
            </a>

            {{-- Assessment --}}
            <a href="{{ route('module4.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
               {{ request()->routeIs('module4.*')
                    ? (strtolower($user->type) === 'admin' ? 'bg-teal-600 text-white font-bold' : 'bg-[#2563EB] text-white font-bold')
                    : 'hover:bg-[#334155]' }}">
                <span class="text-lg">📝</span>
                <span>Assessment</span>
            </a>

            {{-- Settings --}}
            <a href="{{ url('/settings') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition hover:bg-[#334155]">
                <span class="text-lg">⚙️</span>
                <span>Settings</span>
            </a>

            {{-- Logout --}}
            <a href="#" class="flex items-center space-x-3 px-4 py-3 mt-8 rounded-lg hover:bg-red-600 transition">
                <span class="text-lg">➡️</span>
                <span>Logout</span>
            </a>

        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col ml-[250px]">

     {{-- Topbar --}}
<header class="{{ $user && strtolower($user->type) === 'admin' ? 'bg-teal-700' 
    : (strtolower($user->type) === 'teacher' ? 'bg-[#2563EB]' 
    : 'bg-[#4B0082]') }} px-10 py-4 flex justify-between items-center">
            <span class="text-white font-bold text-lg">
                Welcome {{ ucfirst($user->type) }}!
            </span>
            <div class="flex items-center space-x-3 text-white font-bold">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1112 21a9 9 0 01-6.879-3.196z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
                <span>Profile</span>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 p-10 bg-cover bg-center" style="background-image: url('{{ asset('images/bg.jpg') }}')">
            <div class="backdrop-blur-sm bg-white/70 rounded-xl p-8 shadow-lg max-w-5xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
</body>
</html>
