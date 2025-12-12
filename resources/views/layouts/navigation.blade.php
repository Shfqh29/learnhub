<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- LEFT SIDE -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('module3.index') }}" class="text-xl font-bold text-gray-800 dark:text-gray-200">
                        LearnHub
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('module1.index') }}"
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium
                       {{ request()->routeIs('module1.index') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-300' }}">
                        Module 1
                    </a>

                    <a href="{{ route('module2.index') }}"
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium
                       {{ request()->routeIs('module2.*') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-300' }}">
                        Module 2
                    </a>

                    <a href="{{ route('module3.index') }}"
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium
                       {{ request()->routeIs('module3.index') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-300' }}">
                        Module 3
                    </a>

                    <a href="{{ route('module4.index') }}"
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium
                       {{ request()->routeIs('module4.index') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-300' }}">
                        Module 4
                    </a>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="flex items-center">
                @auth
                    <span class="text-sm text-gray-600 dark:text-gray-300 mr-4">
                        {{ Auth::user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm text-red-600 hover:text-red-800">
                            Logout
                        </button>
                    </form>
                @else
                    <span class="text-sm text-gray-500 dark:text-gray-300">
                        Guest
                    </span>
                @endauth
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md
                        text-gray-400 hover:text-gray-500 hover:bg-gray-100
                        dark:text-gray-500 dark:hover:bg-gray-900">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                              class="inline-flex"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('module1.index') }}" class="block pl-3 pr-4 py-2 text-base text-gray-700">
                Module 1
            </a>
            <a href="{{ route('module2.index') }}" class="block pl-3 pr-4 py-2 text-base text-gray-700">
                Module 2
            </a>
            <a href="{{ route('module3.index') }}" class="block pl-3 pr-4 py-2 text-base text-gray-700">
                Module 3
            </a>
            <a href="{{ route('module4.index') }}" class="block pl-3 pr-4 py-2 text-base text-gray-700">
                Module 4
            </a>
        </div>
    </div>
</nav>
