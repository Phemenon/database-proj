<x-layouts.app title="Fantasy Shop – user"> 
    <h1 class="mb-6 font-heading text-3xl text-primary">Your Profile</h1>
    <div class="gap-2 w-full h-full flex">

        {{-- User Info --}}
        <div class="flex flex-col
                rounded-lg border b order-line 
                p-4 w-full bg-white ">

            <div class="flex w-full items-center justify-between ">
                <h1 class="text-2xl font-bold">Your Profile</h1>
                <x-button type="submit" href="#">Edit</x-button>
            </div>

            <div class="w-full flex flex-col"> 
                <hr class="my-2 border-line"> {{-- Horizontai line --}}

                <div><span class="font-bold">Name</span> : {{ $user->name }}</div>
                <div><span class="font-bold">Email </span>: {{ $user->email  }}</div>
                <div><span class="font-bold">Phone </span>: {{ $user->phone }}</div>
                <div><span class="font-bold">Address </span>: {{ $user->address }}</div>
            </div>
        </div>
    </div>

</x-layouts.app>    

