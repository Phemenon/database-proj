@props(['cartCount' => 0])

<header class="border-b border-line bg-white">
    <div class="mx-auto flex max-w-7xl items-center gap-6 px-6 py-4">
        <a href="{{ route('home') }}" class="font-heading text-2xl text-primary">Fantasy Shop</a>

        <form action="{{ route('products.index') }}" method="GET" class="hidden flex-1 md:block">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search for items..."
                   class="w-full rounded-lg border border-line bg-cream px-4 py-2 text-sm focus:border-primary focus:outline-none">
        </form>

        <div class="ml-auto flex items-center gap-4">
            {{-- ไอคอน wishlist --}}
            <a href="#" aria-label="Wishlist" class="hover:text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.099 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                </svg>
            </a>

            {{-- ไอคอนตะกร้า + badge จำนวนสินค้า --}}
            <a href="#" aria-label="Cart" class="relative hover:text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                </svg>
                @if ($cartCount > 0)
                    <span class="absolute -right-2 -top-2 flex size-4 items-center justify-center rounded-full bg-red-600 text-[10px] text-white">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            @guest
                <a href="{{ route('login') }}" class="text-sm hover:text-primary">Login</a>
                <a href="{{ route('register') }}" class="text-sm hover:text-primary">Register</a>
            @endguest

            @auth
                <details class="relative">
                    <summary class="flex cursor-pointer list-none items-center gap-2 text-sm [&::-webkit-details-marker]:hidden">
                        <span class="flex size-8 items-center justify-center rounded-full bg-primary text-white">
                            <!-- mb_substr(...) แทน substr เพราะชื่อภาษาไทยหนึ่งตัวอักษรใช้หลาย byte substr อาจตัดครึ่งตัวแล้วขึ้นเป็นเครื่องหมาย � -->
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </span>
                        {{ auth()->user()->name }}
                    </summary>
                    <div class="absolute right-0 mt-2 w-44 rounded-lg border border-line bg-white py-1 shadow-lg">
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-cream">Profile</a>
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-cream">My Orders</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm hover:bg-cream">Sign out</button>
                        </form>
                    </div>
                </details>
            @endauth
        </div>
    </div>

    <nav class="border-t border-line">
        <div class="mx-auto flex max-w-7xl gap-6 px-6 py-2 text-sm text-muted">
            <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
            @foreach ($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="hover:text-primary">{{ $category->name }}</a>
            @endforeach
        </div>
    </nav>
</header>