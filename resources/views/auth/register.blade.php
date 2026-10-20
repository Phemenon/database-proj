<x-layouts.app title="Register – Fantasy Shop">
    <div class="mx-auto max-w-md rounded-2xl border border-line bg-white p-8">
        <h1 class="mb-6 text-center font-heading text-2xl text-primary">Create Account</h1>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <x-input name="name" label="Name" required autocomplete="name" />
            <x-input name="email" type="email" label="Email" required autocomplete="email" />
            <x-input name="password" type="password" label="Password" required autocomplete="new-password" />
            <x-input name="password_confirmation" type="password" label="Confirm Password" required autocomplete="new-password" />
            <x-input name="phone" type="tel" label="Phone Number" required autocomplete="tel" />
            <x-input name="address" label="Address" required autocomplete="street-address" />

            <button type="submit" class="w-full rounded-lg bg-primary py-2 text-white hover:bg-primary-hover">Create Account</button>
        </form>

        <p class="mt-6 text-center text-sm text-muted">
            Already have an account? <a href="{{ route('login') }}" class="text-primary underline">Sign in</a>
        </p>
    </div>
</x-layouts.app>