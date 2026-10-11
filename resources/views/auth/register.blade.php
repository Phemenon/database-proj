<h1>Register</h1>
@if ($errors->any())
    <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
@endif
<form method="POST" action="{{ route('register') }}">
    @csrf
    <input name="name" value="{{ old('name') }}" placeholder="Name">
    <input name="email" type="email" value="{{ old('email') }}" placeholder="Email">
    <input name="phone" value="{{ old('phone') }}" placeholder="Phone">
    <input name="address" value="{{ old('address') }}" placeholder="Address">
    <input name="password" type="password" placeholder="Password">
    <input name="password_confirmation" type="password" placeholder="Confirm password">
    <button>Register</button>
    <p>มีบัญชีอยู่แล้ว? <a href="{{ route('login') }}">เข้าสู่ระบบ</a></p>
</form>