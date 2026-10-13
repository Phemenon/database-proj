@props(['title' => 'Fantasy Shop'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&family=Noto+Sans+Thai:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<!-- antialiased ทำให้ตัวอักษรเรียบขึ้นบนบางเบราว์เซอร์ -->
<body class="flex min-h-screen flex-col bg-cream text-ink antialiased">
    <x-navbar />

    <!-- amx-auto max-w-7xl px-6 แทน .container เดิม: max-w-7xl จำกัดความกว้างที่ 1280px, mx-auto จัดกึ่งกลาง, px-6 เว้นขอบซ้ายขวา -->
    <main class="mx-auto w-full max-w-7xl flex-1 px-6 py-8">
        <x-flash />
        {{ $slot }}
    </main>

    <x-footer />
</body>
</html>
