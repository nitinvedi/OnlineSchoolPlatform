<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('admin-head')
    <style>
        [data-admin-shell] {
            color: #F0EDE6;
        }

        [data-admin-shell] .admin-card {
            background: #111111;
            border: 1px solid #1E1E1E;
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.28);
        }

        [data-admin-shell] .admin-panel {
            background: #0D0D0D;
            border: 1px solid #1E1E1E;
        }

        [data-admin-shell] .admin-rule {
            border-color: #1E1E1E;
        }

        [data-admin-shell] .admin-input,
        [data-admin-shell] .admin-select,
        [data-admin-shell] .admin-textarea {
            background: #0D0D0D;
            border: 1px solid #1E1E1E;
            color: #F0EDE6;
        }

        [data-admin-shell] .admin-input:focus,
        [data-admin-shell] .admin-select:focus,
        [data-admin-shell] .admin-textarea:focus {
            border-color: #2255FF;
            box-shadow: 0 0 0 1px #2255FF;
            outline: none;
        }

        [data-admin-shell] .admin-chip {
            border: 1px solid #1E1E1E;
            background: #111111;
        }

        [data-admin-shell] .admin-table thead th {
            color: #555555;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            font-size: 10px;
            font-family: 'IBM Plex Mono', monospace;
        }

        [data-admin-shell] .admin-table tbody tr:hover {
            background: #0F0F0F;
        }

        [data-admin-shell] .admin-checkbox {
            appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 0;
            border: 1px solid #555555;
            background: #0D0D0D;
            position: relative;
        }

        [data-admin-shell] .admin-checkbox:checked {
            background: #2255FF;
            border-color: #2255FF;
        }

        [data-admin-shell] .admin-checkbox:checked::after {
            content: '✓';
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            color: #ffffff;
            font-size: 11px;
            line-height: 1;
            font-weight: 700;
        }

        [data-admin-shell] .admin-toggle {
            appearance: none;
            width: 54px;
            height: 28px;
            border-radius: 0.35rem;
            background: #1E1E1E;
            position: relative;
            transition: background-color 150ms ease;
        }

        [data-admin-shell] .admin-toggle::after {
            content: '';
            width: 20px;
            height: 20px;
            border-radius: 0.2rem;
            background: #F0EDE6;
            position: absolute;
            top: 4px;
            left: 4px;
            transition: transform 150ms ease;
        }

        [data-admin-shell] .admin-toggle:checked {
            background: #2255FF;
        }

        [data-admin-shell] .admin-toggle:checked::after {
            transform: translateX(26px);
        }

        [data-admin-shell] .admin-mono {
            font-family: 'IBM Plex Mono', monospace;
        }

        [data-admin-shell] .admin-display {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 0.06em;
        }
    </style>
</head>
<body class="bg-[#080808] font-sans antialiased">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="flex items-center justify-center h-16 px-4 bg-gray-900">
                <span class="text-white font-bold text-xl">Admin Panel</span>
            </div>
            <nav class="mt-8">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path></svg>
                    Overview
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                    Users
                </a>
                <a href="{{ route('admin.courses.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.courses.*') ? 'bg-gray-100 border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Courses
                </a>
                <a href="{{ route('admin.revenue.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.revenue.*') ? 'bg-gray-100 border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                    Revenue
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.reviews.*') ? 'bg-gray-100 border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    Reviews
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.reports.*') ? 'bg-gray-100 border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                    Reports
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-100 border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Settings
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm px-6 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-semibold text-gray-900">{{ $title ?? 'Admin Dashboard' }}</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Welcome, {{ auth()->user()->name }}</span>
                        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">Back to Site</a>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>