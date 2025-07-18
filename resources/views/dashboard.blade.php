@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Tailwind CSS & Alpine.js -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="//unpkg.com/alpinejs" defer></script>

<div x-data="{ 
        darkMode: false, 
        showBookings: false, 
        loading: false,
        sidebarOpen: false 
    }" 
    :class="darkMode ? 'dark' : ''" 
    class="min-h-screen flex bg-gradient-to-tr from-pink-200 via-green-200 to-blue-200 dark:from-pink-700 dark:via-green-700 dark:to-blue-700"
>

    <!-- Sidebar -->
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
        class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-pink-400 via-green-400 to-blue-500 dark:from-pink-800 dark:via-green-800 dark:to-blue-800 text-white shadow-lg transform transition-transform duration-300 ease-in-out z-30 md:relative md:translate-x-0 flex flex-col"
    >
        <div class="flex items-center justify-center h-16 border-b border-white/20">
            <h1 class="text-2xl font-bold tracking-wider">Dashboard</h1>
        </div>
        <nav class="flex-grow px-4 py-6 space-y-4">
            <a href="{{ route('dashboard') }}" class="block py-2 px-3 rounded hover:bg-white/20 transition">
                Home
            </a>
            <a href="{{ route('users.index') }}" class="block py-2 px-3 rounded hover:bg-white/20 transition">
                Users
            </a>
            <button 
                @click="showBookings = !showBookings" 
                class="w-full text-left py-2 px-3 rounded hover:bg-white/20 transition focus:outline-none"
            >
                Bookings
            </button>
            <template x-if="showBookings">
                <div class="pl-4 mt-2 space-y-1">
                    @foreach ($bookings as $booking)
                    <div class="text-sm px-2 py-1 rounded bg-white/20 truncate" title="{{ $booking->title }}">
                        {{ $booking->title }}
                    </div>
                    @endforeach
                    @if($bookings->isEmpty())
                    <div class="text-xs italic px-2 py-1 text-white/70">No bookings</div>
                    @endif
                </div>
            </template>
        </nav>
        <div class="p-4 border-t border-white/20">
            <button 
                @click="darkMode = !darkMode" 
                class="w-full py-2 rounded bg-white/20 hover:bg-white/30 transition"
                aria-label="Toggle dark mode"
            >
                <template x-if="!darkMode">
                    <span>🌙 Dark Mode</span>
                </template>
                <template x-if="darkMode">
                    <span>☀️ Light Mode</span>
                </template>
            </button>
        </div>
    </aside>

    <!-- Main content wrapper -->
    <div class="flex flex-col flex-1 min-h-screen md:ml-64">

        <!-- Topbar -->
        <header class="flex items-center justify-between bg-white dark:bg-gray-800 shadow px-4 py-3 md:hidden">
            <button @click="sidebarOpen = true" aria-label="Open sidebar" class="text-pink-600 dark:text-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500 rounded">
                <!-- Hamburger icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Dashboard</h1>
            <div></div> <!-- Empty placeholder for flex space -->
        </header>

        <!-- Overlay for sidebar on mobile -->
        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-50"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-50"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black opacity-50 z-20 md:hidden"
            style="display:none"
        ></div>

        <!-- Page Content -->
        <main class="p-6 sm:px-8 overflow-y-auto">
            <!-- ✅ Success Message -->
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 p-3 rounded-lg border border-green-300 dark:border-green-600 text-center animate-fade-in mb-6">
                    <strong>✔</strong> {{ session('success') }}
                </div>
            @endif

            <!-- 👋 Welcome -->
            <div class="bg-gradient-to-r from-pink-300 via-green-300 to-blue-200 dark:from-pink-700 dark:via-green-700 dark:to-blue-700 shadow-md rounded-xl p-6 border border-pink-300 dark:border-green-700">
                <h3 class="text-2xl font-bold text-purple-700 dark:text-gray-200">Welcome, {{ Auth::user()->name }}!</h3>
                <p class="text-purple-600 dark:text-gray-400 mt-2">Here's your dashboard summary:</p>
            </div>

            <!-- 📊 Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">

                <!-- Bookings Card -->
                <div 
                    @click="loading = true; showBookings = !showBookings; setTimeout(() => loading = false, 300)" 
                    class="bg-white dark:bg-gray-700 border-l-4 border-pink-400 dark:border-green-500 shadow-md p-5 rounded-lg cursor-pointer hover:bg-pink-50 dark:hover:bg-gray-600 transition-all duration-300 transform hover:scale-[1.02] active:scale-95"
                    title="Toggle your bookings"
                    role="button" tabindex="0"
                    @keydown.enter.prevent="loading = true; showBookings = !showBookings; setTimeout(() => loading = false, 300)"
                    @keydown.space.prevent="loading = true; showBookings = !showBookings; setTimeout(() => loading = false, 300)"
                >
                    <div class="flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-purple-700 dark:text-gray-200">Total Bookings</h4>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-400 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 7h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex items-center mt-2">
                        <p class="text-2xl font-bold text-pink-500 dark:text-green-400">{{ $totalBookings }}</p>
                        <template x-if="loading">
                            <svg class="animate-spin h-5 w-5 ml-2 text-gray-500 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                        </template>
                    </div>
                </div>

                <!-- Users Card -->
                <a href="{{ route('users.index') }}" class="block group">
                    <div class="bg-white dark:bg-gray-700 border-l-4 border-purple-400 dark:border-blue-500 shadow-md p-5 rounded-lg hover:bg-purple-50 dark:hover:bg-gray-600 transition transform group-hover:scale-[1.02]">
                        <h4 class="text-lg font-semibold text-purple-700 dark:text-gray-200 group-hover:text-purple-900 dark:group-hover:text-gray-100">Total Users</h4>
                        <p class="text-2xl font-bold text-purple-500 dark:text-blue-400 group-hover:text-purple-700 dark:group-hover:text-blue-300">{{ $totalUsers }}</p>
                    </div>
                </a>
            </div>

            <!-- 📅 Bookings List -->
            <div
                x-show="showBookings"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 transform -translate-y-3 scale-95"
                x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 transform -translate-y-3 scale-95"
                class="bg-gradient-to-r from-pink-100 via-green-100 to-blue-100 dark:from-pink-800 dark:via-green-800 dark:to-blue-800 shadow-md rounded-xl p-6 border border-purple-200 dark:border-gray-600 mt-6"
            >
                <h3 class="text-xl font-semibold text-purple-600 dark:text-gray-200 mb-5">Your Bookings</h3>

                @if ($bookings->count())
                    <div class="space-y-4">
                        @foreach ($bookings as $booking)
                            <div class="border border-purple-300 dark:border-gray-600 rounded-md p-4 bg-white dark:bg-gray-700 hover:shadow-lg transition duration-300">
                                <h4 class="text-lg font-bold text-purple-700 dark:text-gray-200">{{ $booking->title }}</h4>
                                <p class="text-purple-600 dark:text-gray-300 text-sm mt-1">{{ $booking->description }}</p>
                                <p class="text-xs text-purple-500 dark:text-gray-400 mt-2">
                                    📅 {{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y h:i A') }}
                                </p>

                                <div class="mt-3 flex flex-wrap gap-4">
                                    <a href="{{ route('bookings.edit', $booking->id) }}" 
                                       class="text-blue-600 dark:text-blue-300 hover:text-blue-800 dark:hover:text-blue-100 font-semibold underline"
                                    >Edit</a>

                                    <form method="POST" action="{{ route('bookings.destroy', $booking->id) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this booking?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 font-semibold underline">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-sm text-purple-500 dark:text-gray-400 italic">You don’t have any bookings yet.</p>
                @endif
            </div>
        </main>
    </div>
</div>

<!-- 🔧 Optional Animation Style -->
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}
</style>
@endsection
