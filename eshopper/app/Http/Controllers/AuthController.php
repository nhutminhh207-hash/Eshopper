<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth,Hash};
use Illuminate\Validation\Rules\Password;
class AuthController extends Controller {
    public function loginForm() { return view('auth.login'); }
    public function registerForm() { return view('auth.register'); }
    public function register(Request $request) {
        $data=$request->validate(['name'=>['required','string','max:255'],'email'=>['required','email','max:255','unique:users,email'],'password'=>['required','confirmed',Password::min(8)]]);
        $user=User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>Hash::make($data['password']),'role'=>'user']);
        Auth::login($user); $request->session()->regenerate();
        return redirect()->route('shop.index')->with('success','Đăng ký thành công.');
    }
    public function login(Request $request) {
        $data=$request->validate(['email'=>['required','email'],'password'=>['required','string']]);
        if (!Auth::attempt($data)) return back()->withErrors(['email'=>'Email hoặc mật khẩu không đúng.'])->onlyInput('email');
        $request->session()->regenerate();
        return redirect()->route($request->user()->role==='admin'?'admin.dashboard':'shop.index');
    }
    public function logout(Request $request) {
        Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
        return redirect()->route('home')->with('success','Đã đăng xuất.');
    }
}
