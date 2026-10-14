<x-layouts.app title="Sign in – Fantasy Shop">
    <div class="mx-auto max-w-md rounded-2xl border border-line bg-white p-8">
        <h1 class="mb-6 text-center font-heading text-2xl text-primary">Welcome back!</h1>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <x-input name="email" type="email" label="Email" required autofocus autocomplete="email" />
            <x-input name="password" type="password" label="Password" required autocomplete="current-password" />

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" value="1"> Remember me
            </label>

            <button type="submit" class="w-full rounded-lg bg-primary py-2 text-white hover:bg-primary-hover">Sign in</button>
        </form>

        <p class="mt-6 text-center text-sm text-muted">
            Don't have an account? <a href="{{ route('register') }}" class="text-primary underline">Register</a>
        </p>
    </div>
</x-layouts.app>