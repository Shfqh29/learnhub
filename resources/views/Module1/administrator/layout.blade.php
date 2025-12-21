<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnHub</title>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen"> 
   {{-- SIDEBAR --}}
<aside class="w-[250px] h-screen sticky top-0 overflow-y-auto bg-[#115E59] text-gray-300 flex flex-col py-8 px-6">

  {{-- BRAND --}}
<div class="mb-16 px-3 flex items-center space-x-2">
    {{-- Logo topi graduation putih --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" /> <!-- topi segitiga -->
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v7" /> <!-- tali gantung -->
      <path stroke-linecap="round" stroke-linejoin="round" d="M18 17l-6 4-6-4" /> <!-- pinggiran topi bawah -->
    </svg>

    {{-- System Name --}}
    <span class="text-white font-extrabold text-2xl">LearnHub</span>
</div>


    {{-- Dashboard --}}
    <a href="{{ route('home') }}"
        class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#334155] transition
        {{ request()->routeIs('module1.*') ? 'bg-[#2563EB] text-white font-bold' : '' }}">
        <span class="text-lg">🏠</span>
        <span>Home</span>
    </a>

    {{-- Manage Teachers --}}
<a href="{{ route('administrator.teacherslist') }}"
    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#334155] transition
    {{ request()->routeIs('administrator.teacherslist') ? 'bg-[#2DD4BF] text-white font-bold' : '' }}">
    <span class="text-lg">👨‍🏫</span>
    <span>Manage Teachers</span>
</a>

    {{-- Manage Students --}}
    <a href="#"
        class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#334155] transition
        {{ request()->routeIs('module3.*') ? 'bg-[#2DD4BF] text-white font-bold' : '' }}">
        <span class="text-lg">🎓</span>
        <span>Manage Students</span>
    </a>

   {{-- Add Teacher --}}
<a href="{{ route('administrator.addteacher') }}"
    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#334155] transition
    {{ request()->routeIs('administrator.addteacher') ? 'bg-[#2DD4BF] text-white font-bold' : '' }}">
    <span class="text-lg">📝</span>
    <span>Add Teacher</span>
</a>

{{-- Manage Courses --}}
<a href="{{ route('module2.indexAdmin') }}"
    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#334155] transition
    {{ request()->routeIs('module2.indexAdmin') ? 'bg-[#2DD4BF] text-white font-bold' : '' }}">
    <span class="text-lg">📚</span>
    <span>Manage Courses</span>
</a>

        {{-- Content --}}
    {{--   <a href="{{ route('module3.index') }}"
   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#334155] transition
   {{ request()->routeIs('module3.*') ? 'bg-[#2DD4BF] text-white font-bold' : '' }}">
    <span class="text-lg">📄</span>
    <span>Content</span>
</a>--}}


     {{-- Assessment --}}
{{-- <a href="{{ route('module4.index') }}"
   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#334155] transition
   {{ request()->routeIs('module4.*') ? 'bg-[#2DD4BF] text-white font-bold' : '' }}">
    <span class="text-lg">📝</span>
    <span>Assessment</span>
</a>--}}


    {{-- Settings --}}
    <a href="{{ url('/settings') }}"
        class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#334155] transition
        {{ request()->is('settings') ? 'bg-[#2DD4BF] text-white font-bold' : '' }}">
        <span class="text-lg">⚙️</span>
        <span>Settings</span>
    </a>

    {{-- Logout --}}
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"
            class="flex items-center space-x-3 px-4 py-3 mt-4 rounded-lg text-gray-300 hover:bg-red-600 transition w-full text-left">
        <span class="text-lg">🚪</span>
            <span>Logout</span>
        </button>
    </form>
    </nav>
</aside>


    {{-- MAIN CONTENT WRAPPER --}}
    <div class="flex-1 flex flex-col">

        {{-- TOP BAR --}}
       <header class="bg-[#0D9488] px-10 py-4 flex justify-between items-center">
    {{-- Greeting --}}
    <span class="text-white font-bold text-lg">Welcome Admin !</span>

    {{-- Profile --}}
    <div class="flex items-center space-x-3 text-white font-bold">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M5.121 17.804A9 9 0 1112 21a9 9 0 01-6.879-3.196z" />
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 12a4 4 0 100-8 4 4 0 000 8z" />
        </svg>
        <span>Profile</span>
    </div>
</header>

    {{-- PAGE CONTENT --}}
    <main 
        style="
            background-image: url('{{ asset('images/bg.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        "
        class="flex-1 p-10 overflow-auto"
    >
    <div class="backdrop-blur-sm bg-white/70 rounded-xl p-8 shadow-lg max-w-4xl mx-auto">
            @yield('content')
        </div>
    </main>


    </div>
</div>

</body>
</html>
