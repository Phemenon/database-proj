<h1>Member Info</h1>
<p>{{ $user->name }} | {{ $user->email }}</p>
<p>{{ $user->phone }} | {{ $user->address }}</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button>Logout</button>
</form>