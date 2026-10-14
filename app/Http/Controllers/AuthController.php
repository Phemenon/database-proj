<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone'    => ['required', 'string', 'max:20'],
            'address'  => ['required', 'string', 'max:500'],
        ], [
            'name.required'      => 'please enter your name',
            'email.required'     => 'please enter your email',
            'email.email'        => 'please enter a valid email',
            'email.unique'       => 'this email is already taken',
            'password.required'  => 'please enter your password',
            'password.min'       => 'password must be at least 8 characters',
            'password.confirmed' => 'password confirmation does not match',
            'phone.required'     => 'please enter your phone number',
            'address.required'   => 'please enter your address',
        ]);

        // password ถูก hash อัตโนมัติจาก cast ใน User model ห้าม Hash::make ซ้ำ
        $user = User::create($data);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Sign up successful!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // $credentials คือตัวแปรที่เก็บข้อมูลที่ผ่านการตรวจสอบแล้ว
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'please enter your email',
            'email.email'       => 'please enter a valid email',
            'password.required' => 'please enter your password',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'please enter a valid email and password'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // ถ้าผู้ใช้ถูกเด้งมา login เพราะพยายามเข้าหน้าที่ต้อง login (เช่น ตะกร้า) จะพากลับหน้านั้น ถ้าไม่มีหน้าที่จำไว้ก็ไปหน้าแรก
        return redirect()->intended(route('home'))->with('success', 'Sign in successful!');
    }

    public function logout(Request $request)
    {
        // 1. ออกจากระบบของ Laravel
        Auth::logout();
        // 2. ยกเลิก Session ปัจจุบัน ล้างข้อมูลใน Session และเปลี่ยน Session ID ใหม่ Session ใช้เก็บข้อมูลที่เกี่ยวข้องกับการใช้งานระหว่าง Request เช่น ข้อมูลชั่วคราวหรือสถานะบางอย่างของผู้ใช้
        $request->session()->invalidate();
        // 3. สร้าง CSRF Token ใหม่
        // CSRF Token คือ เป็น Token ที่ Laravel ใช้ช่วยตรวจสอบว่า Request ที่เปลี่ยนแปลงข้อมูล เช่น POST, PUT และ DELETE ผ่านการป้องกัน CSRF หรือไม่
        // เมื่อเรา Invalidate Session แล้ว จึงสร้าง Token ใหม่ให้ Session ที่กำลังใช้งานต่อไป เพื่อให้ฟอร์มและ Request ถัดไปใช้ Token ชุดใหม่
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}