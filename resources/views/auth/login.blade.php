<h1>Login</h1>
@if ($errors->any())
    <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
@endif
<form method="POST" action="{{ route('login') }}">
    @csrf
    <input name="email" type="email" value="{{ old('email') }}" placeholder="Email">
    <input name="password" type="password" placeholder="Password">
    <label><input type="checkbox" name="remember"> Remember me</label>
    <button>Login</button>
    <p>ยังไม่มีบัญชี? <a href="{{ route('register') }}">สมัครสมาชิก</a></p>
</form>